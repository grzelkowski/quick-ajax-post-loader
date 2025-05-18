<?php 
if (!defined('ABSPATH')) {
    exit;
}
class QAPL_Shortcode_Params_Handler {
    public static function get_params($params) {
        $defaults = array(
            'id' => '',
            'excluded_post_ids' => '',
            'post_type' => '',
            'posts_per_page' => '',
            'order' => '',
            'orderby' => '',
            'sort_options' => '',
            'quick_ajax_css_style' => '',
            'grid_num_columns' => '',
            'post_item_template' => '',
            'taxonomy_filter_class' => '',
            'container_class' => '',
            'load_more_posts' => '',
            'loader_icon' => '',
            'quick_ajax_id' => '',
            'quick_ajax_taxonomy' => '',
            //'manual_selected_terms' => '',
            'ignore_sticky_posts' => '',
            'ajax_initial_load' => '',
            'infinite_scroll' => '',
            'show_end_message' => '',
        );
        //retain only the keys that match the defaults
        $params = array_intersect_key($params, $defaults);
        //merge provided parameters with defaults
        $params = shortcode_atts($defaults, $params, 'quick-ajax');        

        //sanitize and cast numeric and boolean parameters
        $params['id'] = intval($params['id']);
        $params['ignore_sticky_posts'] = filter_var($params['ignore_sticky_posts'], FILTER_VALIDATE_BOOLEAN);
        $params['ajax_initial_load'] = filter_var($params['ajax_initial_load'], FILTER_VALIDATE_BOOLEAN);
        $params['infinite_scroll'] = filter_var($params['infinite_scroll'], FILTER_VALIDATE_BOOLEAN);
        $params['show_end_message'] = filter_var($params['show_end_message'], FILTER_VALIDATE_BOOLEAN);
        $params['excluded_post_ids'] = array_filter(array_map('intval', explode(',', $params['excluded_post_ids'])));
        $params['posts_per_page'] = intval($params['posts_per_page']);
        $params['quick_ajax_css_style'] = intval($params['quick_ajax_css_style']);
        $params['grid_num_columns'] = intval($params['grid_num_columns']);
        $params['load_more_posts'] = intval($params['load_more_posts']);
        $params['quick_ajax_id'] = intval($params['quick_ajax_id']);

        //sanitize text parameters
        $params['post_type'] = sanitize_text_field($params['post_type']);
        $params['order'] = sanitize_text_field($params['order']);
        $params['orderby'] = sanitize_text_field($params['orderby']);
        //$params['sort_options'] = sanitize_text_field($params['sort_options']);
        $params['post_item_template'] = sanitize_text_field($params['post_item_template']);
        $params['taxonomy_filter_class'] = sanitize_html_class($params['taxonomy_filter_class']);
        $params['container_class'] = sanitize_html_class($params['container_class']);
        $params['loader_icon'] = sanitize_text_field($params['loader_icon']);
        $params['quick_ajax_taxonomy'] = sanitize_text_field($params['quick_ajax_taxonomy']);
        //$params['manual_selected_terms'] = (!empty($params['quick_ajax_taxonomy'])) ? array_filter(array_map('intval', explode(',', $params['manual_selected_terms']))) : '';

        //return sanitized data
        return $params;
    }
}
class QAPL_Shortcode_Post_Meta_Handler {
    public static function load_and_sanitize($id) {
        $serialized_data = get_post_meta($id, QAPL_Quick_Ajax_Helper::quick_ajax_shortcode_settings(), true);
        if (!$serialized_data) {
            return array();
        }
        $form_data = maybe_unserialize($serialized_data);
        
        if (empty($form_data) || !is_array($form_data)) {
            return array();
        }
        return self::sanitize($form_data);
    }
    private static function sanitize($meta_data) {
        $sanitized = array();
        foreach ($meta_data as $key => $value) {
            if (is_array($value)) {
                $sanitized_array = [];    
                foreach ($value as $item) {
                    if (is_numeric($item)) {
                        $sanitized_array[] = absint($item); // sanitize as int
                    } else {
                        $sanitized_array[] = sanitize_text_field($item); // sanitize as string
                    }
                }    
                $sanitized[$key] = $sanitized_array;
            } elseif (is_numeric($value)) {
                $sanitized[$key] = intval($value);
            } elseif (is_string($value)) {
                $sanitized[$key] = sanitize_text_field($value);
            }
        }
        return $sanitized;
    }
}
class QAPL_Shortcode_Query_Args_Provider {
    private $shortcode_params;
    private $shortcode_postmeta;

    public function __construct(array $shortcode_params, array $postmeta) {
        $this->shortcode_params = $shortcode_params;
        $this->shortcode_postmeta = $postmeta;
    }
    // return shortcode param if set else get value from postmeta
    public function get_arg_value($shortcode_key, $meta_key = null) {
        // check if param exists in shortcode
        if (!empty($this->shortcode_params[$shortcode_key])) {
            return $this->shortcode_params[$shortcode_key];
        }
        // fallback to meta key if not provided
        if (!$meta_key) {
            $meta_key = $shortcode_key;
        }
        // check if param exists in postmeta
        if (isset($this->shortcode_postmeta[$meta_key])) {
            return $this->shortcode_postmeta[$meta_key];
        }        
        return '';
    }
}
class QAPL_Shortcode_Ajax_Attributes_Provider {
    private $shortcode_params = [];
    private $shortcode_postmeta = [];

    public function __construct(array $shortcode_params, array $shortcode_postmeta) {
        $this->shortcode_params = $shortcode_params;
        $this->shortcode_postmeta = $shortcode_postmeta;
    }
    public function get_attributes() {
        return $this->create_shortcode_attributes();
    }

    private function create_shortcode_attributes() {
        $attributes = array();        
        if (!empty($this->shortcode_params['id'])) {
            $attributes['shortcode'] = true;
            $attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_id()] = absint($this->shortcode_params['id']);
        } else {
            $attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_id()] = $this->get_sanitized_attribute([
                'shortcode_key' => QAPL_Quick_Ajax_Helper::layout_quick_ajax_id(),
                'type' => 'number',
            ]);
        }
        $attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_css_style()] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Helper::layout_quick_ajax_css_style(),
            'postmeta_key' => QAPL_Quick_Ajax_Helper::shortcode_page_layout_quick_ajax_css_style(),
            'type' => 'string',
        ]);
        $attributes[QAPL_Quick_Ajax_Helper::layout_container_num_columns()] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Helper::layout_container_num_columns(),
            'postmeta_key' => QAPL_Quick_Ajax_Helper::shortcode_page_layout_select_columns_qty(),
            'type' => 'number',
        ]);
        $attributes[QAPL_Quick_Ajax_Helper::layout_post_item_template()] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Helper::layout_post_item_template(),
            'postmeta_key' => QAPL_Quick_Ajax_Helper::shortcode_page_layout_post_item_template(),
            'type' => 'string',
        ]);
        $attributes[QAPL_Quick_Ajax_Helper::layout_taxonomy_filter_class()] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Helper::layout_taxonomy_filter_class(),
            'postmeta_key' => QAPL_Quick_Ajax_Helper::shortcode_page_layout_taxonomy_filter_class(),
            'type' => 'html_class',
        ]);
        $attributes[QAPL_Quick_Ajax_Helper::layout_container_class()] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Helper::layout_container_class(),
            'postmeta_key' => QAPL_Quick_Ajax_Helper::shortcode_page_layout_container_class(),
            'type' => 'html_class',
        ]);
        $attributes[QAPL_Quick_Ajax_Helper::layout_load_more_posts()] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Helper::layout_load_more_posts(),
            'postmeta_key' => QAPL_Quick_Ajax_Helper::shortcode_page_select_custom_load_more_post_quantity(),
            'only_if_meta_key_true' => QAPL_Quick_Ajax_Helper::shortcode_page_show_custom_load_more_post_quantity(),
            'type' => 'number',
        ]);
        $attributes[QAPL_Quick_Ajax_Helper::layout_select_loader_icon()] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Helper::layout_select_loader_icon(),
            'postmeta_key' => QAPL_Quick_Ajax_Helper::shortcode_page_select_loader_icon(),
            'only_if_meta_key_true' => QAPL_Quick_Ajax_Helper::shortcode_page_override_global_loader_icon(),
            'type' => 'string',
        ]);
        $attributes[QAPL_Quick_Ajax_Helper::query_settings_ajax_on_initial_load()] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Helper::query_settings_ajax_on_initial_load(),
            'postmeta_key' => QAPL_Quick_Ajax_Helper::shortcode_page_ajax_on_initial_load(),
            'type' => 'bool',
        ]);
        $attributes[QAPL_Quick_Ajax_Helper::layout_ajax_infinite_scroll()] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Helper::layout_ajax_infinite_scroll(),
            'postmeta_key' => QAPL_Quick_Ajax_Helper::shortcode_page_ajax_infinite_scroll(),
            'type' => 'bool',
        ]);
        $attributes[QAPL_Quick_Ajax_Helper::layout_show_end_message()] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Helper::layout_show_end_message(),
            'postmeta_key' => QAPL_Quick_Ajax_Helper::shortcode_page_show_end_message(),
            'type' => 'bool',
        ]);  
        return !empty($attributes) ? $attributes : false;
    }

    private function get_sanitized_attribute(array $config) {
        /**
         * - try to get value from shortcode args first
         * - if not found, try to get value from shortcode postmeta settings
         * - if 'only_if_meta_key_true' is set, check its value before using postmeta value
         * - if value is still not found, return empty value (0 or empty string)
         * - sanitize the value based on given type:
         *   - 'number' = return as integer
         *   - 'bool' = return as 1 or 0
         *   - 'html_class' = sanitize as safe css class
         *   - 'string' = sanitize as safe text
         */
        $shortcode_key = $config['shortcode_key'] ?? null;
        $meta_key = $config['postmeta_key'] ?? null;
        $type = $config['type'] ?? 'string';
        $only_if_meta_key_true = $config['only_if_meta_key_true'] ?? null;
        $value = null;
        
        // try to get value from shortcode args
        if (!empty($this->shortcode_params[$shortcode_key])) {
            $value = $this->shortcode_params[$shortcode_key];
        // if not found try to get value from shortcode settings
        } elseif (!empty($meta_key) && isset($this->shortcode_postmeta[$meta_key])) {
            // check if additional meta key condition is required
            if (!empty($only_if_meta_key_true)) {
                if (!empty($this->shortcode_postmeta[$only_if_meta_key_true])) {
                    $value = $this->shortcode_postmeta[$meta_key];
                } else {
                    $value = null;
                }
            } else {
                $value = $this->shortcode_postmeta[$meta_key];
            }
        }
        // return default empty value if value is still null
        if ($value === null) {
            return ($type === 'number' || $type === 'bool') ? 0 : '';
        }
        // sanitize value based on type
        switch ($type) {
            case 'number':
                return intval($value);
            case 'bool':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            case 'html_class':
                    $classes = preg_split('/[\s,]+/', $value, -1, PREG_SPLIT_NO_EMPTY);
                    $sanitized_classes = array_map('sanitize_html_class', $classes);
                    return implode(' ', $sanitized_classes);
            case 'string':
            default:
                return sanitize_text_field($value);
        }
    }
}

if (!class_exists('QAPL_Quick_Ajax_Shortcode')) {
    class QAPL_Quick_Ajax_Shortcode {
        private $shortcode_params = array();
        private $shortcode_postmeta = array();
        private $query_args = array();
        
        private function get_shortcode_params($params) {
            $this->shortcode_params = QAPL_Shortcode_Params_Handler::get_params($params);
        }    
        private function unserialize_shortcode_data($id) {
            $this->shortcode_postmeta = QAPL_Shortcode_Post_Meta_Handler::load_and_sanitize($id);
        }
        private function create_shortcode_args(){
            $data_args = new QAPL_Shortcode_Query_Args_Provider($this->shortcode_params, $this->shortcode_postmeta);
            $args = array();
            // get main query params from shortcode or postmeta
            $selected_post_type = $data_args->get_arg_value('post_type', QAPL_Quick_Ajax_Helper::shortcode_page_select_post_type());
            $post_per_page = $data_args->get_arg_value('posts_per_page', QAPL_Quick_Ajax_Helper::shortcode_page_select_posts_per_page());
            $post_order = $data_args->get_arg_value('order', QAPL_Quick_Ajax_Helper::shortcode_page_select_order());
            $post_orderby = $data_args->get_arg_value('orderby', QAPL_Quick_Ajax_Helper::shortcode_page_select_orderby());
            $post_not_in = $data_args->get_arg_value('excluded_post_ids', QAPL_Quick_Ajax_Helper::shortcode_page_set_post_not_in());
            $ignore_sticky_posts = $data_args->get_arg_value('ignore_sticky_posts', QAPL_Quick_Ajax_Helper::shortcode_page_ignore_sticky_posts());
            $show_taxonomy = $data_args->get_arg_value('show_taxonomy', QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter());
            $select_taxonomy = $data_args->get_arg_value('select_taxonomy', QAPL_Quick_Ajax_Helper::shortcode_page_select_taxonomy());
            $manual_term_selection = $data_args->get_arg_value('manual_term_selection', QAPL_Quick_Ajax_Helper::shortcode_page_manual_term_selection());
            $manual_selected_terms = $data_args->get_arg_value('manual_selected_terms', QAPL_Quick_Ajax_Helper::shortcode_page_manual_selected_terms());
            // return query args if post type is defined
            if(!empty($selected_post_type)){
                $args = array(
                    'post_type' => $selected_post_type,
                    'orderby' => $post_orderby, 
                    'order' => $post_order,
                    'posts_per_page' => $post_per_page,
                    //'post__not_in' => $post_not_in,
                    //'ignore_sticky_posts' => $ignore_sticky_posts,
                );
                if (!empty($post_not_in)) {
                    $args['post__not_in'] = $post_not_in;
                }                
                if (!empty($ignore_sticky_posts)) {
                    $args['ignore_sticky_posts'] = $ignore_sticky_posts;
                }
                if($show_taxonomy && $select_taxonomy){
                    $args['selected_taxonomy'] = $select_taxonomy;
                }
                if($select_taxonomy && $manual_term_selection && $manual_selected_terms){
                    $args['selected_terms'] = $manual_selected_terms;
                }
                /*
                if ($show_taxonomy && $manual_term_selection && !empty($manual_selected_terms)) {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => $select_taxonomy,
                            'field' => 'term_id',
                            'terms' => $manual_selected_terms,
                            'operator' => 'IN',
                        )
                    );
                }
                elseif ($show_taxonomy && !$manual_term_selection) {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => $select_taxonomy,
                            'operator' => 'EXISTS',
                        )
                    );
                }            
                */
            }
            if(!empty($args)){
                $this->query_args = $args;
            }
            return false;
        }
        /*
        // replaced by args['selected_taxonomy'] - may be needed in the future if there is an option to hide the filter
        private function create_shortcode_taxonomy(){
            if (empty($this->shortcode_params['id'])) {
                return null;
            }
            $show_taxonomies_filter = isset($this->shortcode_postmeta[QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter()]) ? $this->shortcode_postmeta[QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter()] : null;
            if($show_taxonomies_filter==1){
                $selectedTaxonomy = isset($this->shortcode_postmeta[QAPL_Quick_Ajax_Helper::shortcode_page_select_taxonomy()]) ? $this->shortcode_postmeta[QAPL_Quick_Ajax_Helper::shortcode_page_select_taxonomy()] : null;
                $selectedTaxonomy = esc_attr($selectedTaxonomy);
            }           
            if(!empty($selectedTaxonomy)){
                return $selectedTaxonomy;
            }
            return null;
        }*/
        private function create_shortcode_controls_container(){
            if(!empty($this->shortcode_params['id'])){
                $show_sort_orderby_button = isset($this->shortcode_postmeta[QAPL_Quick_Ajax_Helper::shortcode_page_show_sort_button()]) ? $this->shortcode_postmeta[QAPL_Quick_Ajax_Helper::shortcode_page_show_sort_button()] : null;
                if($show_sort_orderby_button==1){
                    $add_wrapper = isset($this->shortcode_postmeta[QAPL_Quick_Ajax_Helper::shortcode_page_show_inline_filter_sorting()]) ? $this->shortcode_postmeta[QAPL_Quick_Ajax_Helper::shortcode_page_show_inline_filter_sorting()] : null;
                    return $add_wrapper;
                }
            }
            return null;
        }
        private function create_shortcode_sort_button(){
            if(!empty($this->shortcode_params['id'])){
                $show_sort_orderby_button = isset($this->shortcode_postmeta[QAPL_Quick_Ajax_Helper::shortcode_page_show_sort_button()]) ? $this->shortcode_postmeta[QAPL_Quick_Ajax_Helper::shortcode_page_show_sort_button()] : null;
                if($show_sort_orderby_button==1){
                    $sort_orderby = isset($this->shortcode_postmeta[QAPL_Quick_Ajax_Helper::shortcode_page_select_sort_button_options()]) ? $this->shortcode_postmeta[QAPL_Quick_Ajax_Helper::shortcode_page_select_sort_button_options()] : null;
                    if (is_array($sort_orderby)) {
                        $sort_orderby = array_map('esc_attr', $sort_orderby);
                    }else{
                        $sort_orderby = esc_attr($sort_orderby);
                    }
                    return $sort_orderby;
                }
            }
            return null;
        }

        public function render_quick_ajax_shortcode($params) {
            $this->get_shortcode_params($params);
            if (!empty($this->shortcode_params['id'])) {
                $this->unserialize_shortcode_data($this->shortcode_params['id']);
            }
            $this->create_shortcode_args();
            $attribute_provider = new QAPL_Shortcode_Ajax_Attributes_Provider($this->shortcode_params, $this->shortcode_postmeta);
            $attributes = $attribute_provider->get_attributes();
            //$render_context['show_taxonomy_filter'] = $this->create_shortcode_taxonomy();
            $render_context['show_taxonomy_filter'] = !empty($this->query_args['selected_taxonomy']);
            $render_context['sort_options'] = $this->create_shortcode_sort_button();
            $render_context['controls_container'] = $this->create_shortcode_controls_container();
            ob_start();
            if (!empty($this->query_args) && function_exists('qapl_render_post_container')) {
                qapl_render_post_container($this->query_args, $attributes, $render_context);
            }
            $output = ob_get_clean();
            return $output;
        }
    }
    $quick_ajax_shortcode = new QAPL_Quick_Ajax_Shortcode();
    add_shortcode('qapl-quick-ajax', array($quick_ajax_shortcode, 'render_quick_ajax_shortcode'));
}
