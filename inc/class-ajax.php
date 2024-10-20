<?php 
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('QAPL_Quick_Ajax_Handler')) {
    class QAPL_Quick_Ajax_Handler{
        private static $instance = null;
        public $args = array();
        public $attributes = array();
        public $layout = array();
        private $quick_ajax_id;
        private $quick_ajax_block_id;
        
        public function __construct(){
            // Filter hooks for filter wrapper
            /*
            add_action('qapl_filter_wrapper_pre', array($this, 'action_filter_wrapper_pre'));         
            add_action('qapl_filter_wrapper_open', array($this, 'action_filter_wrapper_open'));
            add_action('qapl_filter_wrapper_close', array($this, 'action_filter_wrapper_close'));
            add_action('qapl_filter_wrapper_complete', array($this, 'action_filter_wrapper_complete'));

            // Filter hooks for posts wrapper
            add_action('qapl_posts_wrapper_pre', array($this, 'action_posts_wrapper_pre'));     
            add_action('qapl_posts_wrapper_open', array($this, 'action_posts_wrapper_open'));
            add_action('qapl_posts_wrapper_close', array($this, 'action_posts_wrapper_close'));     
            add_action('qapl_posts_wrapper_complete', array($this, 'action_posts_wrapper_complete'));

            // Filter hooks for load more button
            add_action('qapl_load_more_button_pre', array($this, 'action_load_more_button_pre'));     
            add_action('qapl_load_more_button_complete', array($this, 'action_load_more_button_complete')); 

            // Filter hooks for loader icon
            add_action('qapl_loader_icon_pre', array($this, 'action_loader_icon_pre'));     
            add_action('qapl_loader_icon_complete', array($this, 'action_loader_icon_complete'));
*/
            // Filters with arguments (query and term buttons)
            add_filter('qapl_modify_query', array($this, 'filter_modify_query_args'), 10, 2); 
            add_filter('qapl_modify_term_buttons', array($this, 'filter_modify_term_button_data'), 10, 2);
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
            $post__not_in = $array_ids = array();

            if (is_string($excluded_post_ids)) {
                $array_ids = preg_split('/[,\s]+/', $excluded_post_ids);
            }elseif(is_array($excluded_post_ids)){
                $array_ids = $excluded_post_ids;
            }
            if(is_array($array_ids)){
                foreach ($array_ids as $post_id) {
                    $int_post_id = intval($post_id);
                    // Check if the value is greater than 0 and not already in the array
                    if ($int_post_id > 0 && !in_array($int_post_id, $post__not_in, true)) {
                        $post__not_in[] = $int_post_id;
                    }
                }
            }            
            return $post__not_in;                       
        }

        public function filter_modify_query_args($args, $quick_ajax_id) {
            if($quick_ajax_id == $this->quick_ajax_id){
            return $args;
            }
        }
        
        public function filter_modify_term_button_data($buttons, $quick_ajax_id) {
            if($quick_ajax_id == $this->quick_ajax_id){
                return $buttons;
            }            
        }

        private function generate_block_id($attributes = false) {
            if (!is_array($attributes)) {
                $attributes = [QAPL_Quick_Ajax_Helper::layout_quick_ajax_id() => sanitize_text_field($attributes)];
            }
            if (isset($attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_id()])) {
                // Prefix 'p' for 'shortcode' equal to true, otherwise 'c'
                $prefix = (isset($attributes['shortcode']) && $attributes['shortcode'] === true) ? 'p' : 'c';              
                $this->quick_ajax_id = esc_attr($prefix . $attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_id()]);
                $this->quick_ajax_block_id = 'quick-ajax-' . esc_attr($prefix . $attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_id()]);
                

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
                $exclude_ids_string = implode(',', $exclude_ids);
                $where .= " AND {$wpdb->posts}.ID NOT IN ($exclude_ids_string)";
            }
            
            return $where;
        }
        */
    
        public function wp_query_args($args, $attributes = false){
            $this->args = [];
            $this->generate_block_id($attributes);
            $quick_ajax_args['post_type'] = (isset($args['post_type'])) ? sanitize_text_field($args['post_type']) : null;
            $quick_ajax_args['posts_per_page'] = (isset($args['posts_per_page'])) ? intval($args['posts_per_page']) : QAPL_Quick_Ajax_Helper::shortcode_page_select_posts_per_page_default_value();
            $quick_ajax_args['post_status'] = (isset($args['post_status'])) ? sanitize_text_field($args['post_status']) : QAPL_Quick_Ajax_Helper::shortcode_page_select_post_status_default_value();
            $quick_ajax_args['orderby'] = (isset($args['orderby'])) ? sanitize_text_field($args['orderby']) : QAPL_Quick_Ajax_Helper::shortcode_page_select_orderby_default_value();
            $quick_ajax_args['order'] = (isset($args['order'])) ? sanitize_text_field($args['order']) : QAPL_Quick_Ajax_Helper::shortcode_page_select_order_default_value();
            $quick_ajax_args['post__not_in'] = (isset($args['post__not_in'])) ? array_map('absint', $this->create_post_not_in($args['post__not_in'])) : '';
            $quick_ajax_args['ignore_sticky_posts'] = (isset($args['ignore_sticky_posts'])) ? intval($args['ignore_sticky_posts']) : QAPL_Quick_Ajax_Helper::shortcode_page_ignore_sticky_posts_default_value();
           // $quick_ajax_args['excluded_post_ids'] = (isset($args['excluded_post_ids'])) ? $this->create_post_not_in($args['excluded_post_ids']) : '';
            $quick_ajax_args['paged'] = (isset($args['paged'])) ? intval($args['paged']) : 1;
            $quick_ajax_args['offset'] = (isset($args['offset'])) ? intval($args['offset']) : null;
                    
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
            $this->args = apply_filters('qapl_modify_query', $this->args, $this->quick_ajax_id);

            if (empty($this->args)) {
                return false;
            }
        }
        public function sanitize_json_to_array($data) {
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
                'posts_per_page' => -1,
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
        public function term_filter($taxonomy){
            if(!$this->args){
                return false;
            }
            $terms = get_terms( array(
                'taxonomy' => $taxonomy,
                'object_type' => array($this->args['post_type']),
                'hide_empty' => true,                
            ) );
            $block_id = 'quick-ajax-term-filter-'.$this->quick_ajax_id;
            $class_container = 'quick-ajax-filter-wrapper';
            if(isset($this->layout[QAPL_Quick_Ajax_Helper::layout_quick_ajax_css_style()]) && ($this->layout[QAPL_Quick_Ajax_Helper::layout_quick_ajax_css_style()] != 0)){
                $class_container .= ' quick-ajax-style';
            }
            if(isset($this->layout[QAPL_Quick_Ajax_Helper::layout_taxonomy_filter_class()]) && !empty(trim($this->layout[QAPL_Quick_Ajax_Helper::layout_taxonomy_filter_class()]))){
                $class_container .= ' '.$this->layout[QAPL_Quick_Ajax_Helper::layout_taxonomy_filter_class()];
            }            
            $container_class = $this->extract_classes_from_string($class_container);

            ob_start(); // Start output buffering

            do_action('qapl_filter_wrapper_pre');
            echo '<div id="'.esc_attr($block_id).'" class="'.esc_attr($container_class).'">';
            do_action('qapl_filter_wrapper_open');
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $this->attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_id()] = $this->quick_ajax_block_id;
                
                $navigation_buttons = [];                
                $button_base = [
                    'data-button' => QAPL_Quick_Ajax_Helper::term_filter_button_data_button(),
                    'template' => QAPL_Quick_Ajax_Helper::plugin_templates_term_filter_button(),
                    'data-attributes' => $this->attributes,
                ];
                $show_all_button = [                    
                    'term_id' => 'none',
                    'taxonomy' => $taxonomy,
                    'template' => $button_base['template'],
                    'button_label' => __('Show All', 'qapl-quick-ajax-post-loader'),
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
                            'data-action' => $this->tax_query($taxonomy, $term->slug),
                            'data-attributes' => $button_base['data-attributes'],
                        ];
                        $navigation_buttons[] = $term_button_data;
                    }
                }
                
                $navigation_buttons = apply_filters('qapl_modify_term_buttons', $navigation_buttons, $this->quick_ajax_id);
                $filter_buttons='';
                foreach ( $navigation_buttons as $button ) {
                    $filter_buttons .= $this->update_button_template($button);
                }
                echo wp_kses_post($filter_buttons);
            }
            do_action('qapl_filter_wrapper_close');
            echo '</div>';
            do_action('qapl_filter_wrapper_complete');

            $output = ob_get_clean(); // Get the buffered content into a variable
            return $output; // Return the content
        }

        private function update_button_template($button_data) {
            $button_label = isset($button_data['button_label']) ? esc_html($button_data['button_label']) : '';
            if (empty($button_label)){
                return '';
            }
            ob_start();
            include($button_data['template']);
            $content = ob_get_clean();
            $modified_content = $this->add_button_data($content, $button_data);
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
                    //$matches[0] = <div class="filter-button" data-button="qa-filter-button" id="id">
                    //$matches[1] = div class="filter-button"
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

        private function tax_query($taxonomy, $term_slug){
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
        }

        private function get_global_options(){
            $global_options = get_option(QAPL_Quick_Ajax_Helper::admin_page_global_options_name());
            return $global_options;
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
            $this->layout[QAPL_Quick_Ajax_Helper::layout_quick_ajax_css_style()] = (isset($attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_css_style()])) ? esc_attr($attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_css_style()]) : 0;
            //Number of columns
            $this->layout[QAPL_Quick_Ajax_Helper::layout_grid_num_columns()] = (isset($attributes[QAPL_Quick_Ajax_Helper::layout_grid_num_columns()])) ? esc_attr($attributes[QAPL_Quick_Ajax_Helper::layout_grid_num_columns()]) : 0;
            //add custom class for taxonomy filter
            if(isset($attributes[QAPL_Quick_Ajax_Helper::layout_taxonomy_filter_class()])){
                $this->layout[QAPL_Quick_Ajax_Helper::layout_taxonomy_filter_class()] = $this->extract_classes_from_string($attributes[QAPL_Quick_Ajax_Helper::layout_taxonomy_filter_class()]);
            }
            //Add class to post container
            if(isset($attributes[QAPL_Quick_Ajax_Helper::layout_container_class()])){
                $this->layout[QAPL_Quick_Ajax_Helper::layout_container_class()] = $this->extract_classes_from_string($attributes[QAPL_Quick_Ajax_Helper::layout_container_class()]);
            }
            //Post Item Template
            $post_item_template = isset($attributes[QAPL_Quick_Ajax_Helper::layout_post_item_template()]) ? $attributes[QAPL_Quick_Ajax_Helper::layout_post_item_template()] : false;
            $this->layout[QAPL_Quick_Ajax_Helper::layout_post_item_template()] = QAPL_Quick_Ajax_Helper::plugin_templates_post_item_template($post_item_template);
            $this->attributes[QAPL_Quick_Ajax_Helper::layout_post_item_template()] = $post_item_template;
            //Custom Load More Post Quantity
            if(isset($attributes[QAPL_Quick_Ajax_Helper::layout_load_more_posts()])){
                $this->attributes[QAPL_Quick_Ajax_Helper::layout_load_more_posts()] = intval($attributes[QAPL_Quick_Ajax_Helper::layout_load_more_posts()]);
            }
            //Select Loader Icon
            $global_options = $this->get_global_options();
            $loader_icon = (isset($attributes[QAPL_Quick_Ajax_Helper::layout_select_loader_icon()])) ? $attributes[QAPL_Quick_Ajax_Helper::layout_select_loader_icon()]  : (isset($global_options['loader_icon']) ? $global_options['loader_icon'] : '');
        
            $this->layout[QAPL_Quick_Ajax_Helper::layout_select_loader_icon()] = QAPL_Quick_Ajax_Helper::plugin_templates_loader_icon_template($loader_icon);
            $this->attributes[QAPL_Quick_Ajax_Helper::layout_select_loader_icon()] = $loader_icon;
        }

        public function wp_query(){
            if(!$this->args){
                return false;
            }
            $args = $this->args;
            $query = new WP_Query($args);
            $this->attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_id()] = $this->quick_ajax_block_id;
            $class_container = $class_inner_container = '';
            if(isset($this->layout[QAPL_Quick_Ajax_Helper::layout_grid_num_columns()]) && (!empty($this->layout[QAPL_Quick_Ajax_Helper::layout_grid_num_columns()]))){
                $class_container .= 'quick-ajax-style';   
            }
            if(isset($this->layout[QAPL_Quick_Ajax_Helper::layout_grid_num_columns()]) && (!empty($this->layout[QAPL_Quick_Ajax_Helper::layout_grid_num_columns()]))){
                $class_inner_container .= 'col-qty-'.$this->layout[QAPL_Quick_Ajax_Helper::layout_grid_num_columns()];   
            }
            if(isset($this->layout[QAPL_Quick_Ajax_Helper::layout_container_class()]) && !empty(trim($this->layout[QAPL_Quick_Ajax_Helper::layout_container_class()]))){
                $class_inner_container .= ' '.$this->layout[QAPL_Quick_Ajax_Helper::layout_container_class()];
            }
            $container_class = $this->extract_classes_from_string($class_container);
            $container_inner_class = $this->extract_classes_from_string($class_inner_container);
            
            ob_start();
            // Start output buffering
            do_action('qapl_posts_wrapper_pre');
            echo '<div id="'.esc_attr($this->attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_id()]).'" class="quick-ajax-posts-wrapper '.esc_attr($container_class).'">';
            do_action('qapl_posts_wrapper_open');
            echo '<div class="quick-ajax-posts-inner-wrapper '.esc_attr($container_inner_class).'">';
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    include($this->layout[QAPL_Quick_Ajax_Helper::layout_post_item_template()]);
                }
            } else {
                include(QAPL_Quick_Ajax_Helper::plugin_templates_no_posts());
            }
            
            $this->load_more_button($query->get('paged'), $query->max_num_pages, $query->found_posts);
            echo '</div>';
            do_action('qapl_loader_icon_pre');
            echo '<div class="quick-ajax-loader-icon-wrapper">'; 
            include($this->layout[QAPL_Quick_Ajax_Helper::layout_select_loader_icon()]);
            echo '</div>';
            do_action('qapl_loader_icon_complete');
            do_action('qapl_posts_wrapper_close');
            echo '</div>';
            do_action('qapl_posts_wrapper_complete');
            wp_reset_postdata();
            // Get the buffered content into a variable
            $output = ob_get_clean(); 
            return $output; // Return the content
        }

        public function load_more_button($paged, $max_num_pages, $found_posts) {
            if(!$this->args){
                return false;
            }
            //echo 'paged:'.$paged.'<br />$max_num_pages:'.$max_num_pages.'<br />$found_posts:'.$found_posts.'<br />';
            //print_r($this->args);

            if (isset($this->attributes[QAPL_Quick_Ajax_Helper::layout_load_more_posts()]) && !empty($this->attributes[QAPL_Quick_Ajax_Helper::layout_load_more_posts()])) {
            // Check if load_more_posts attribute is set
            // if we want to add a different number of posts than displayed at the start
            // use 'offset' not 'paged'
                $load_more_posts = intval($this->attributes[QAPL_Quick_Ajax_Helper::layout_load_more_posts()]);
                $offset = isset($this->args['offset']) ? $this->args['offset'] + $load_more_posts : + $load_more_posts;
               
                if (($found_posts <= $offset) || ($found_posts <= intval($this->args['posts_per_page']))) {
                    return false;
                }
                 // Update offset
                $this->args['offset'] = isset($this->args['offset']) ? intval($this->args['offset']) + $load_more_posts : intval($this->args['posts_per_page']);
                
            } else {
                // Check if there are no more pages to load
                if ($max_num_pages <= $paged) {
                    return false;
                }                
                $this->args['paged'] += 1;
            }
        

            do_action('qapl_load_more_button_pre');
            $button_data['template'] = QAPL_Quick_Ajax_Helper::plugin_templates_load_more_button();
            $button_data['button_label'] = __('Load More', 'qapl-quick-ajax-post-loader');
            $button_data['data-button'] = QAPL_Quick_Ajax_Helper::load_more_button_data_button();
            $button_data['data-action'] = $this->args;
            $button_data['data-attributes'] = $this->attributes;
            echo wp_kses_post($this->update_button_template($button_data));
            do_action('qapl_load_more_button_complete');
        }
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
?>