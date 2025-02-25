<?php 
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('QAPL_Quick_Ajax_Handler')) {
    class QAPL_Quick_Ajax_Handler{
        private static $instance = null;
        private $helper;
        public $args = array();
        public $attributes = array();
        public $layout = array();
        private $ajax_initial_load;
        private $quick_ajax_id;
        private $quick_ajax_block_id;
        private $global_options;
        //private $placeholder_replacer;
        
        public function __construct(){
            $this->helper = QAPL_Quick_Ajax_Helper::get_instance();
            $this->quick_ajax_id = 0;
            $this->quick_ajax_block_id = '';
            $this->global_options = get_option($this->helper->admin_page_global_options_name(), []);
            //$this->placeholder_replacer = new QAPL_Placeholder_Replacer(); // not in use after removing placeholders
            // Filter hooks for filter wrapper
            /*
            add_action(QAPL_Hooks::HOOK_FILTER_CONTAINER_BEFORE, array($this, 'action_filter_wrapper_pre'));         
            add_action(QAPL_Hooks::HOOK_FILTER_CONTAINER_START, array($this, 'action_filter_wrapper_open'));
            add_action(QAPL_Hooks::HOOK_FILTER_CONTAINER_END, array($this, 'action_filter_wrapper_close'));
            add_action(QAPL_Hooks::HOOK_FILTER_CONTAINER_AFTER, array($this, 'action_filter_wrapper_complete'));

            // Filter hooks for posts wrapper
            add_action(QAPL_Hooks::HOOK_POSTS_CONTAINER_BEFORE, array($this, 'action_posts_wrapper_pre'));     
            add_action(QAPL_Hooks::HOOK_POSTS_CONTAINER_START, array($this, 'action_posts_wrapper_open'));
            add_action(QAPL_Hooks::HOOK_POSTS_CONTAINER_END, array($this, 'action_posts_wrapper_close'));     
            add_action(QAPL_Hooks::HOOK_POSTS_CONTAINER_AFTER, array($this, 'action_posts_wrapper_complete'));

            // Filter hooks for load more button
            add_action(QAPL_Hooks::HOOK_LOAD_MORE_BEFORE, array($this, 'action_load_more_button_pre'));     
            add_action(QAPL_Hooks::HOOK_LOAD_MORE_AFTER, array($this, 'action_load_more_button_complete')); 

            // Filter hooks for loader icon
            add_action(QAPL_Hooks::HOOK_LOADER_BEFORE, array($this, 'action_loader_icon_pre'));     
            add_action(QAPL_Hooks::HOOK_LOADER_AFTER, array($this, 'action_loader_icon_complete'));
*/
            // Filters with arguments (query and taxonomy buttons)
            //add_filter(QAPL_Hooks::HOOK_MODIFY_POSTS_QUERY_ARGS, array($this, 'filter_modify_query_args'), 10, 2); 
            //add_filter(QAPL_Hooks::HOOK_MODIFY_TAXONOMY_FILTER_BUTTONS, array($this, 'filter_modify_taxonomy_filter_buttons'), 10, 2);
        }
        
        public static function get_instance() {
            if (null === self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }        

        public function get_quick_ajax_id() {
            return $this->quick_ajax_id;
        }

        private function create_post_not_in($excluded_post_ids){
            $post__not_in = [];
            $array_ids = is_array($excluded_post_ids) ? $excluded_post_ids : preg_split('/[,\s]+/', $excluded_post_ids);
            
            foreach ($array_ids as $post_id) {
                $int_post_id = intval($post_id);
                //Check if the value is greater than 0 and not already in the array
                if ($int_post_id > 0 && !in_array($int_post_id, $post__not_in, true)) {
                    $post__not_in[] = $int_post_id;
                }
            }
            return $post__not_in;                     
        }
        /*
        public function filter_modify_query_args($args, $quick_ajax_id) {
            if($quick_ajax_id == $this->quick_ajax_id){
                return $args;
            }
            return $args;
        }        
        
        public function filter_modify_taxonomy_filter_buttons($buttons, $quick_ajax_id) {
            if($quick_ajax_id == $this->quick_ajax_id){
                return $buttons;
            }
            return $buttons;
        }
        */

        private function generate_block_id($attributes = false) {
            if (!is_array($attributes)) {
                $attributes = [$this->helper->layout_quick_ajax_id() => sanitize_text_field($attributes)];
            }
            if (isset($attributes[$this->helper->layout_quick_ajax_id()])) {
                // Prefix 'p' for 'shortcode' equal to true, otherwise 'c'
                $prefix = (isset($attributes['shortcode']) && $attributes['shortcode'] === true) ? 'p' : 'c';              
                $this->quick_ajax_id = esc_attr($prefix . $attributes[$this->helper->layout_quick_ajax_id()]);
                $this->quick_ajax_block_id = 'quick-ajax-' . esc_attr($prefix . $attributes[$this->helper->layout_quick_ajax_id()]);
                

            } else {
                // Increment qapl_id if 'quick_ajax_id' is not set
                $this->quick_ajax_id++;
            }
        }
        /*
        public function modify_posts_where($where, $wp_query){
            global $wpdb;
            $exclude_ids = isset($this->args['post__not_in']) ? array_map('intval', $this->args['post__not_in']) : array();
            if (!empty($exclude_ids)) {
                $exclude_ids_string = implode(',', array_map('intval', $exclude_ids));
                $where .= $wpdb->prepare(" AND {$wpdb->posts}.ID NOT IN (%s)", $exclude_ids_string);
            }
            
            return $where;
        }
        */
        public function wp_query_args($args, $attributes = false){
            $this->args = [];
            $this->generate_block_id($attributes);
            $quick_ajax_args = $this->initialize_query_args($args);
            $this->args['post_status'] = $this->helper->shortcode_page_select_post_status_default_value();
            if (isset($quick_ajax_args['post_type']) && !empty($quick_ajax_args['post_type'])) {
                foreach ($quick_ajax_args as $key => $value) {
                    if (!empty($value)) {
                        $this->args[$key] = $value;
                    }
                }
            }
            if(isset($args['tax_query']) && !empty($args['tax_query'])){
                $this->args['tax_query'] = $args['tax_query'];
            }
            $this->args = apply_filters(QAPL_Hooks::HOOK_MODIFY_POSTS_QUERY_ARGS, $this->args, $this->quick_ajax_id);

            if (empty($this->args)) {
                return false;
            }else{
                return $this->args; 
            }
        }
        private function initialize_query_args($args) {
            // Set default query arguments
            $query_args = [
                'post_type' => isset($args['post_type']) ? sanitize_text_field($args['post_type']) : null,
                'posts_per_page' => isset($args['posts_per_page']) ? intval($args['posts_per_page']) : $this->helper->shortcode_page_select_posts_per_page_default_value(),
                'orderby' => isset($args['orderby']) ? sanitize_text_field($args['orderby']) : $this->helper->shortcode_page_select_orderby_default_value(),
                'order' => isset($args['order']) ? sanitize_text_field($args['order']) : $this->helper->shortcode_page_select_order_default_value(),
                'post__not_in' => isset($args['post__not_in']) ? array_map('absint', $this->create_post_not_in($args['post__not_in'])) : '',
                'ignore_sticky_posts' => isset($args['ignore_sticky_posts']) ? intval($args['ignore_sticky_posts']) : $this->helper->shortcode_page_ignore_sticky_posts_default_value(),
                'paged' => isset($args['paged']) ? intval($args['paged']) : 1
            ];
            // Check if 'offset' is provided and use it instead of 'paged'
            if (isset($args['offset']) && !is_null($args['offset'])) {
                // Set the offset value and remove 'paged' from the query
                $query_args['offset'] = intval($args['offset']);
                unset($query_args['paged']);
            }

            return $query_args;    
        }
        public function sanitize_json_to_array($data) {
            // Check if input is a JSON string
            if (is_string($data)) {
                $data = json_decode($data, true);
                // Check if JSON decoding was successful
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return []; //Return an empty array if JSON decoding failed
                }
            }
            // Ensure input is an array
            if (!is_array($data)) {
                return [];
            }
            // Sanitize array values
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = $this->sanitize_json_to_array($value);
                } elseif (is_numeric($value)) {
                    $data[$key] = absint($value);
                } else {
                    $data[$key] = sanitize_text_field($value);
                }
            }
            return $data;
        }
        
        
        private function get_post_assigned_to_the_term($term, $post_type, $excluded_post_ids){
            $args = array(
                'posts_per_page' => 1,
                'post_type' => $post_type,
                'tax_query' => array(
                    array(
                        'taxonomy' => $term->taxonomy,
                        'field'    => 'term_id',
                        'terms'    => $term->term_id,
                    ),
                ),
                'post__not_in' => $excluded_post_ids,
            );
            $posts = get_posts($args);

            if (!empty($posts)) {
                return true;
            }
            return false;      
        }
        public function render_taxonomy_terms_filter($taxonomy){
            if(!$this->args){
                return false;
            }
            $terms = get_terms( array(
                'taxonomy' => $taxonomy,
                'object_type' => array($this->args['post_type']),
                'hide_empty' => true,                
            ) );
            $block_id = 'quick-ajax-filter-'.$this->quick_ajax_id;
            $class_container = 'quick-ajax-filter-container';
            if (isset($this->layout[$this->helper->layout_quick_ajax_css_style()]) && $this->layout[$this->helper->layout_quick_ajax_css_style()] != 0) {
                $class_container .= ' quick-ajax-theme';
            }
            if(isset($this->layout[$this->helper->layout_taxonomy_filter_class()]) && !empty(trim($this->layout[$this->helper->layout_taxonomy_filter_class()]))){
                $class_container .= ' '.$this->layout[$this->helper->layout_taxonomy_filter_class()];
            }            
            $container_class = $this->extract_classes_from_string($class_container);

            ob_start(); // Start output buffering

            do_action(QAPL_Hooks::HOOK_FILTER_CONTAINER_BEFORE, $this->quick_ajax_id);
            echo '<div id="'.esc_attr($block_id).'" class="'.esc_attr($container_class).'">';
            do_action(QAPL_Hooks::HOOK_FILTER_CONTAINER_START, $this->quick_ajax_id);
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $this->attributes[$this->helper->layout_quick_ajax_id()] = $this->quick_ajax_id;
                
                $navigation_buttons = [];                
                $button_base = [
                    'data-button' => $this->helper->taxonomy_filter_button_data_button(),
                    'template' => $this->helper->plugin_templates_taxonomy_filter_button(),
                    'data-attributes' => $this->attributes,
                ];
                $show_all_label = $this->global_options['show_all_label'] ?? __('Show All', 'quick-ajax-post-loader');    
                $show_all_button = [                    
                    'term_id' => 'none',
                    'taxonomy' => $taxonomy,
                    'template' => $button_base['template'],
                    'button_label' => $show_all_label,
                    'data-button' => $button_base['data-button'],
                    'data-action' => $this->args,
                    'data-attributes' => $button_base['data-attributes'],
                ];
                $navigation_buttons[] = $show_all_button;
                $exclude_ids = (isset($this->args['post__not_in'])) ? $this->args['post__not_in'] : '';
                foreach ( $terms as $term ) { 
                    $not_empty = $this->get_post_assigned_to_the_term($term, $this->args['post_type'], $exclude_ids);
                    if($not_empty == true){
                        $term_button_data = [                        
                            'term_id' => $term->term_id,
                            'taxonomy' => $term->taxonomy,
                            'template' => $button_base['template'],
                            'button_label' => $term->name,
                            'data-button' => $button_base['data-button'],
                            'data-action' => $this->generate_tax_query($taxonomy, $term->slug),
                            'data-attributes' => $button_base['data-attributes'],
                        ];
                        $navigation_buttons[] = $term_button_data;
                    }
                }
                
                $navigation_buttons = apply_filters(QAPL_Hooks::HOOK_MODIFY_TAXONOMY_FILTER_BUTTONS, $navigation_buttons, $this->quick_ajax_id);
                $filter_buttons='';
                foreach ( $navigation_buttons as $button ) {
                    $filter_buttons .= $this->update_button_template($button);
                }
                echo $filter_buttons;
            }
            do_action(QAPL_Hooks::HOOK_FILTER_CONTAINER_END, $this->quick_ajax_id);
            echo '</div>';
            do_action(QAPL_Hooks::HOOK_FILTER_CONTAINER_AFTER, $this->quick_ajax_id);

            $output = ob_get_clean(); // Get the buffered content into a variable
            //$output = $this->replace_placeholders($output); // not in use after removing placeholders
            return $output; // Return the content
        }
        public function render_sort_options($sort_options){
            $block_id = 'quick-ajax-sort-options-'.$this->quick_ajax_id;
            $class_container = 'quick-ajax-sort-options-container';
            if (isset($this->layout[$this->helper->layout_quick_ajax_css_style()]) && $this->layout[$this->helper->layout_quick_ajax_css_style()] != 0) {
                $class_container .= ' quick-ajax-theme';
            }
            //if(isset($this->layout[$this->helper->layout_taxonomy_filter_class()]) && !empty(trim($this->layout[$this->helper->layout_taxonomy_filter_class()]))){
            //    $class_container .= ' '.$this->layout[$this->helper->layout_taxonomy_filter_class()];
            //}            
            $container_class = $this->extract_classes_from_string($class_container);
            $sort_buttons ='';
            ob_start(); // Start output buffering

            //do_action(QAPL_Hooks::HOOK_FILTER_CONTAINER_BEFORE, $this->quick_ajax_id);
            echo '<div id="'.esc_attr($block_id).'" class="'.esc_attr($container_class).'">';
            //do_action(QAPL_Hooks::HOOK_FILTER_CONTAINER_START, $this->quick_ajax_id);
            if(isset($sort_options) && is_array($sort_options)){
                $default_sort_options = $this->helper->shortcode_page_select_sort_button_options_values();
                $label_map = [];
                foreach ($default_sort_options as $option) {
                    $label_map[$option['value']] = $option['label'];
                }

                $sorted_options = [];
                foreach ($sort_options as $value) {
                    $parts = explode('-', $value); 
                    $orderby = $parts[0];
                    $order = strtoupper($parts[1] ?? 'DESC');
                    $label = $label_map[$value] ?? ucfirst($orderby) . ' (' . ucfirst(strtolower($order)) . ')';

                    $sorted_options[] = [
                        'orderby' => $orderby,
                        'order'   => $order,
                        'label'   => $label,
                    ];
                }
                $sorted_options = apply_filters(QAPL_Hooks::HOOK_MODIFY_SORTING_OPTIONS_VARIANTS, $sorted_options, $this->quick_ajax_id);
                $filtered_orderby_options = [];
                foreach ($sorted_options as $option) {
                    $filtered_orderby_options[] = [
                        'value' => strtolower($option['orderby']) . '-' . strtolower($option['order']),
                        'label' => $option['label'],
                    ];
                }

                $button_option = [
                    'label'   => __('Sort by', 'quick-ajax-post-loader'),
                    'id' => 'quick_ajax_sort_option',
                    'name' => 'quick_ajax_sort_option',
                    'options' => $filtered_orderby_options
                ];
                $sort_buttons .= $this->create_sort_button($button_option);
            }
            echo $sort_buttons;
            //do_action(QAPL_Hooks::HOOK_FILTER_CONTAINER_END, $this->quick_ajax_id);
            echo '</div>';
            //do_action(QAPL_Hooks::HOOK_FILTER_CONTAINER_AFTER, $this->quick_ajax_id);
            $output = ob_get_clean(); // Get the buffered content into a variable
            //$output = $this->replace_placeholders($output); // not in use after removing placeholders
            return $output; // Return the content
        }
        private function create_sort_button($button_data) {         
            $this->attributes[$this->helper->layout_quick_ajax_id()] = $this->quick_ajax_id;
            $sort_option = '<div class="quick-ajax-sort-option-wrapper">';
            $default_option = strtolower($this->args['orderby']).'-'.strtolower($this->args['order']);
            $sort_option .= '<select id="'.esc_attr($button_data['id']).'" name="'.esc_attr($button_data['name']).'" aria-label="'.$button_data['label'].'">';
            foreach ($button_data['options'] as $option) {
                $value = esc_attr($option['value']);
                $label = esc_html($option['label']);
                $selected = ($default_option == $option['value']) ? ' selected' : '';
                $sort_option .= '<option value="' . $value . '"'.$selected.'>' . $label . '</option>';
            }
            $sort_option .= '</select>';
            $sort_option .= '<span class="quick-ajax-settings" data-button="'.$this->helper->sort_option_button_data_button().'" data-attributes="' . esc_attr(wp_json_encode($this->attributes)) . '" data-action="' . esc_attr(wp_json_encode($this->args)) . '"></span>';
            $sort_option .= '</div>';                      
            return $sort_option;
        }

        private function update_button_template($button_data) {
            $button_label = isset($button_data['button_label']) ? esc_html($button_data['button_label']) : '';
            if (empty($button_label)){
                return '';
            }
            if($button_data['data-button'] == $this->helper->load_more_button_data_button()){
                $load_more_settings = [
                    'quick_ajax_id' => $this->quick_ajax_id,
                    'template_name' => 'load-more-button',
                ];
                $qapl_load_more_template = QAPL_Post_Template_Factory::get_template($load_more_settings);
                QAPL_Post_Template_Context::set_template($qapl_load_more_template);
            }
            ob_start();
            include($button_data['template']);
            $content = ob_get_clean();
            $modified_content = $this->add_button_data($content, $button_data);
            QAPL_Post_Template_Context::clear_template();
            return $modified_content;
        }

        private function add_button_data($content, $button_data) {
            $button_data_attributes = htmlspecialchars(wp_json_encode($button_data['data-attributes']), ENT_QUOTES, 'UTF-8');
            $button_data_action = htmlspecialchars(wp_json_encode($button_data['data-action']), ENT_QUOTES, 'UTF-8');            
            $button_type = htmlspecialchars($button_data['data-button']);
            $regex = '/<([^>]+)data-button="'.$button_type.'"([^>]*)>/';
            
            $modified_content = preg_replace_callback(
                $regex,
                function ($matches) use ($button_type, $button_data_action, $button_data_attributes) {
                    //$matches[0] = <div class="qapl-filter-button" data-button="quick-ajax-filter-button" id="id">
                    //$matches[1] = div class="qapl-filter-button"
                    //$matches[2] = id="id"
                    
                    $full_match = $matches[0];
                    //update or add 'data-action'
                    if (preg_match('/data-action="[^"]*"/', $full_match)) {
                        //if 'data-action' exists, replace it
                        $full_match = preg_replace('/data-action="[^"]*"/', 'data-action="' . $button_data_action . '"', $full_match);
                    } else {
                        // if 'data-action' does not exist, add it
                        $full_match = preg_replace('/(data-button="'.$button_type.'")/', '$1 data-action="' . $button_data_action . '"', $full_match);
                    }
                    //update or add 'data-attributes'
                    if (preg_match('/data-attributes="[^"]*"/', $full_match)) {
                        //if 'data-attributes' exists, replace it
                        $full_match = preg_replace('/data-attributes="[^"]*"/', 'data-attributes="' . $button_data_attributes . '"', $full_match);
                    } else {
                        //if 'data-attributes' does not exist, add it
                        $full_match = preg_replace('/(data-button="'.$button_type.'")/', '$1 data-attributes="' . $button_data_attributes . '"', $full_match);
                    }
                    return $full_match;
                },
                $content
            );
            //Add button_label if button element contains THE_LABEL
            $button_label = htmlspecialchars($button_data['button_label']);
            $label_regex = sprintf('/<(\w+)\s[^>]*data-button="%s"[^>]*>QUICK_AJAX_LABEL<\/\\1>/s', preg_quote($button_type, '/'));
            $modified_content = preg_replace_callback(
                $label_regex,
                function ($matches) use ($button_label) {
                    $full_match = $matches[0];
                    $label_replaced = str_replace('QUICK_AJAX_LABEL', $button_label, $full_match);
                    return $label_replaced;
                },
                $modified_content
            );
            return $modified_content;
        }

        private function generate_tax_query($taxonomy, $term_slug){
            $term_args = $this->args;
            unset($term_args['paged']);
            unset($term_args['offset']);
            $term_args['tax_query'] = array(
                array(
                    'taxonomy' => $taxonomy, 
                    'field' => 'slug',
                    'terms' => $term_slug,
                ),
            );
            return $term_args;
        }

        public function meta_query($field_name, $field_value, $compare = '='){
            $meta_args = $this->args;
            unset($meta_args['paged']);
            unset($meta_args['offset']);
            $meta_args['meta_query'] = array(
                array(
                    'key' => $field_name,
                    'value' => $field_value,
                    'compare' => $compare,
                ),
            );
            return $meta_args;
        }

        private function extract_classes_from_string($string){
            // Split the input string into an array using whitespace or comma as separators
            $class_container_array = preg_split('/[\s,]+/', $string);
            $class_container_array = array_map('sanitize_html_class', $class_container_array);

            // Iterate over the array and remove elements that start with a digit
            foreach ($class_container_array as $key => $item) {
                if (preg_match('/^\d/', $item)) {
                    // Use unset to remove the item from the array if it starts with a digit
                    unset($class_container_array[$key]);
                }
            }
            $container_class = implode(' ', $class_container_array);
            return $container_class;
        }

        public function layout_customization($attributes){
            $this->attributes = [];
            $this->layout = [];
            //Apply quick AJAX CSS Style
            $this->layout[$this->helper->layout_quick_ajax_css_style()] = (isset($attributes[$this->helper->layout_quick_ajax_css_style()])) ? esc_attr($attributes[$this->helper->layout_quick_ajax_css_style()]) : 0;
            //Number of columns
            $this->layout[$this->helper->layout_container_num_columns()] = (isset($attributes[$this->helper->layout_container_num_columns()])) ? esc_attr($attributes[$this->helper->layout_container_num_columns()]) : 0;
            //add custom class for taxonomy filter
            if(isset($attributes[$this->helper->layout_taxonomy_filter_class()])){
                $this->layout[$this->helper->layout_taxonomy_filter_class()] = $this->extract_classes_from_string($attributes[$this->helper->layout_taxonomy_filter_class()]);
            }
            //Add class to post container
            if(isset($attributes[$this->helper->layout_container_class()])){
                $this->layout[$this->helper->layout_container_class()] = $this->extract_classes_from_string($attributes[$this->helper->layout_container_class()]);
            }
            //Post Item Template
            $post_item_template = isset($attributes[$this->helper->layout_post_item_template()]) ? $attributes[$this->helper->layout_post_item_template()] : false;
            $this->layout[$this->helper->layout_post_item_template()] = $this->helper->plugin_templates_post_item_template($post_item_template);
            $this->attributes[$this->helper->layout_post_item_template()] = $post_item_template;
            //Custom Load More Post Quantity            
            if(isset($attributes[$this->helper->layout_load_more_posts()])){
                $this->attributes[$this->helper->layout_load_more_posts()] = intval($attributes[$this->helper->layout_load_more_posts()]);
            }            
            //Select Loader Icon
            if (isset($attributes[$this->helper->layout_select_loader_icon()]) && !empty($attributes[$this->helper->layout_select_loader_icon()])) {
                $loader_icon = $attributes[$this->helper->layout_select_loader_icon()];
            } elseif (isset($this->global_options['loader_icon']) && !empty($this->global_options['loader_icon'])) {
                // fallback to global option if attributes value is invalid
                $loader_icon = $this->global_options['loader_icon'];
            } else {
                // final fallback to default value
                $loader_icon = $this->helper->shortcode_page_select_loader_icon_default_value();
            }
            $this->layout[$this->helper->layout_select_loader_icon()] = $this->helper->plugin_templates_loader_icon_template($loader_icon);
            $this->attributes[$this->helper->layout_select_loader_icon()] = $loader_icon;
            $this->ajax_initial_load = isset($attributes[$this->helper->query_settings_ajax_on_initial_load()]) ? intval($attributes[$this->helper->query_settings_ajax_on_initial_load()]) : $this->helper->shortcode_page_ajax_on_initial_load_default_value();
        }

        public function wp_query(){
            if(!$this->args){
                return false;
            }
            $args = $this->args;
            
            $query = new WP_Query($args);
            $this->attributes[$this->helper->layout_quick_ajax_id()] = $this->quick_ajax_id;
            $layout_quick_ajax_id = esc_attr($this->attributes[$this->helper->layout_quick_ajax_id()]);
            $class_container = $class_inner_container = '';
            if (isset($this->layout[$this->helper->layout_quick_ajax_css_style()]) && $this->layout[$this->helper->layout_quick_ajax_css_style()] != 0) {
                $class_container .= 'quick-ajax-theme';
            }
            if(isset($this->layout[$this->helper->layout_container_num_columns()]) && (!empty($this->layout[$this->helper->layout_container_num_columns()]))){
                $class_inner_container .= 'col-qty-'.$this->layout[$this->helper->layout_container_num_columns()];   
            }
            if(isset($this->layout[$this->helper->layout_container_class()]) && !empty(trim($this->layout[$this->helper->layout_container_class()]))){
                $class_inner_container .= ' '.$this->layout[$this->helper->layout_container_class()];
            }
            $container_class = $this->extract_classes_from_string($class_container);
            $container_inner_class = $this->extract_classes_from_string($class_inner_container);
            ob_start();
            // Start output buffering
            do_action(QAPL_Hooks::HOOK_POSTS_CONTAINER_BEFORE, $this->quick_ajax_id);
            echo '<div id="quick-ajax-'.esc_attr($layout_quick_ajax_id).'" class="quick-ajax-posts-container '.esc_attr($container_class).'">';
            do_action(QAPL_Hooks::HOOK_POSTS_CONTAINER_START, $this->quick_ajax_id);
            echo '<div class="quick-ajax-posts-wrapper '.esc_attr($container_inner_class).'">';
            
            $container_settings = [
                'quick_ajax_id' => $this->quick_ajax_id,
                'template_name' => $this->attributes['post_item_template'],
            ];
            $qapl_post_template = QAPL_Post_Template_Factory::get_template($container_settings);
            QAPL_Post_Template_Context::set_template($qapl_post_template);
            if ($query->have_posts()) {
                if ($this->ajax_initial_load) {
                    echo '<div class="qapl-initial-loader" data-button="quick-ajax-filter-button" style="display:none;" data-action="' . esc_attr(wp_json_encode($this->args)) . '" data-attributes="' . esc_attr(wp_json_encode($this->attributes)) . '"></div>';
                } else {
                    while ($query->have_posts()) {
                        $query->the_post();                        
                        include($this->layout[$this->helper->layout_post_item_template()]);
                    }
                }
            } else {
                include($this->helper->plugin_templates_no_posts());
            }
            QAPL_Post_Template_Context::clear_template();
            $this->load_more_button($query->get('paged'), $query->max_num_pages, $query->found_posts);
            echo '</div>';
            do_action(QAPL_Hooks::HOOK_LOADER_BEFORE, $this->quick_ajax_id);
            echo '<div class="qapl-loader-container">'; 
            include($this->layout[$this->helper->layout_select_loader_icon()]);
            echo '</div>';
            do_action(QAPL_Hooks::HOOK_LOADER_AFTER, $this->quick_ajax_id);
            do_action(QAPL_Hooks::HOOK_POSTS_CONTAINER_END, $this->quick_ajax_id);
            echo '</div>';
            do_action(QAPL_Hooks::HOOK_POSTS_CONTAINER_AFTER, $this->quick_ajax_id);
            wp_reset_postdata();
            // Get the buffered content into a variable
            $output = ob_get_clean(); 
            
            //$output = $this->replace_placeholders($output); // not in use after removing placeholders
            return $output; // Return the content
        }
        public function load_more_button($paged, $max_num_pages, $found_posts) {
            if(!$this->args){
                return false;
            }
            //echo 'paged:'.$paged.'<br />$max_num_pages:'.$max_num_pages.'<br />$found_posts:'.$found_posts.'<br />';
            //print_r($this->args);

            if (isset($this->attributes[$this->helper->layout_load_more_posts()]) && !empty($this->attributes[$this->helper->layout_load_more_posts()])) {
            // Check if load_more_posts attribute is set
            // if we want to add a different number of posts than displayed at the start
            // use 'offset' not 'paged'
                $load_more_posts = intval($this->attributes[$this->helper->layout_load_more_posts()]);
                $offset = isset($this->args['offset']) ? $this->args['offset'] + $load_more_posts : + $load_more_posts;
               
                if (($found_posts <= $offset) || ($found_posts <= intval($this->args['posts_per_page']))) {
                    return false;
                }
                 // Update offset
                $this->args['offset'] = isset($this->args['offset']) ? intval($this->args['offset']) + $load_more_posts : intval($this->args['posts_per_page']);
                $this->args['posts_per_page'] = $load_more_posts;
            } else {
                // Check if there are no more pages to load
                if ($max_num_pages <= $paged) {
                    return false;
                }                
                $this->args['paged'] += 1;
            }
            
            //do_action(QAPL_Hooks::HOOK_LOAD_MORE_BEFORE);
            $button_data['template'] = $this->helper->plugin_templates_load_more_button();
            $button_data['button_label'] = __('Load More', 'quick-ajax-post-loader');
            $button_data['data-button'] = $this->helper->load_more_button_data_button();
            $button_data['data-action'] = $this->args;
            $button_data['data-attributes'] = $this->attributes;
            echo $this->update_button_template($button_data);
            //do_action(QAPL_Hooks::HOOK_LOAD_MORE_AFTER);
        }
        /* not in use after removing placeholders
        public function replace_placeholders($content) {
            if (!$this->placeholder_replacer) {
                return $content; // fallback if placeholder replacer is not initialized
            }
            return $this->placeholder_replacer->replace_placeholders($content);
        }*/
        /*       
        public function print_results(){
            print_r($this->args);
        }
        */
        public function args_json(){
            $json_data = wp_json_encode($this->args);
            return $json_data;
        }
    }
}
