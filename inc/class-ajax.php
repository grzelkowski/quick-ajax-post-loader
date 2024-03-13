<?php 
if (!defined('ABSPATH')) {
    exit;
}

if (WPG_Quick_Ajax_Helper::quick_ajax_element_exists('class','WPG_Quick_Ajax_Handler')) {
    class WPG_Quick_Ajax_Handler{
        private static $instance = null;
        public $args = array();
        public $attributes = array();
        public $layout = array();
        private $quick_ajax_id;
        private $quick_ajax_block_id;

        public function __construct(){
            add_action('before_quick_ajax_filter_wrapper', function() {});            
            add_action('quick_ajax_filter_wrapper_start', function() {});
            add_action('quick_ajax_filter_wrapper_end', function() {});
            add_action('after_quick_ajax_filter_wrapper', function() {});

            add_action('before_quick_ajax_posts_wrapper', function() {});     
            add_action('quick_ajax_posts_wrapper_start', function() {});                 
            add_action('before_quick_ajax_load_more_button', function() {});     
            add_action('after_quick_ajax_load_more_button', function() {});                
            add_action('before_quick_ajax_loader_icon', function() {});     
            add_action('after_quick_ajax_loader_icon', function() {});                
            add_action('quick_ajax_posts_wrapper_end', function() {});     
            add_action('after_quick_ajax_posts_wrapper', function() {});     

            add_filter('quick_ajax_modify_query', array($this, 'quick_ajax_action_modify_query_args'), 10, 2);      
            add_filter('quick_ajax_modify_term_buttons', array($this, 'quick_ajax_action_modify_term_button_data'), 10, 2);
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
        public function quick_ajax_action_modify_query_args($args, $quick_ajax_id) {
            if($quick_ajax_id == $this->quick_ajax_id){
            return $args;
            }
        }
        
        public function quick_ajax_action_modify_term_button_data($buttons, $quick_ajax_id) {
            if($quick_ajax_id == $this->quick_ajax_id){
                return $buttons;
            }            
        }
        private function quick_ajax_generate_block_id($attributes = false) {
            if (!is_array($attributes)) {
                $attributes = [WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_id() => $attributes];
            }
            if (isset($attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_id()])) {
                // Prefix 'p' for 'shortcode' equal to true, otherwise 'c'
                $prefix = (isset($attributes['shortcode']) && $attributes['shortcode'] === true) ? 'p' : 'c';
                $this->quick_ajax_id = $prefix . $attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_id()];
                $this->quick_ajax_block_id = 'quick-ajax-'.$prefix . $attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_id()];
            } else {
                // Increment quick_ajax_id if 'quick_ajax_id' is not set
                $this->quick_ajax_id++;
            }
        }
        public function quick_ajax_wp_query_args($args, $attributes = false){
            $this->args = [];
            $this->quick_ajax_generate_block_id($attributes);
            $quick_ajax_args['post_type'] = (isset($args['post_type'])) ? sanitize_text_field($args['post_type']) : null;
            $quick_ajax_args['posts_per_page'] = (isset($args['posts_per_page'])) ? intval($args['posts_per_page']) : WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_posts_per_page_default_value();
            $quick_ajax_args['post_status'] = (isset($args['post_status'])) ? sanitize_text_field($args['post_status']) : WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_post_status_default_value();
            $quick_ajax_args['orderby'] = (isset($args['orderby'])) ? sanitize_text_field($args['orderby']) : WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_orderby_default_value();
            $quick_ajax_args['order'] = (isset($args['order'])) ? sanitize_text_field($args['order']) : WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_order_default_value();
            $quick_ajax_args['post__not_in'] = (isset($args['post__not_in'])) ? $this->create_post_not_in($args['post__not_in']) : '';
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
            $this->args = apply_filters('quick_ajax_modify_query', $this->args, $this->quick_ajax_id);

            if (empty($this->args)) {
                return false;
            }
        }

        public function quick_ajax_term_filter($taxonomy){
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
            if(isset($this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_css_style()]) && ($this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_css_style()] != 0)){
                $class_container .= ' quick-ajax-style';
            }
            if(isset($this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_taxonomy_filter_class()]) && !empty(trim($this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_taxonomy_filter_class()]))){
                $class_container .= ' '.$this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_taxonomy_filter_class()];
            }            
            $container_class = $this->extract_classes_from_string($class_container);

            ob_start(); // Start output buffering

            do_action('before_quick_ajax_filter_wrapper');
            echo '<div id="'.$block_id.'" class="'.$container_class.'">';
            do_action('quick_ajax_filter_wrapper_start');
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $this->attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_id()] = $this->quick_ajax_block_id;
                
                $navigation_buttons = [];                
                $button_base = [
                    'data-button' => WPG_Quick_Ajax_Helper::quick_ajax_term_filter_button_data_button(),
                    'template' => WPG_Quick_Ajax_Helper::quick_ajax_plugin_templates_term_filter_button(),
                    'data-attributes' => $this->attributes,
                ];
                $show_all_button = [                    
                    'term_id' => 'none',
                    'taxonomy' => $taxonomy,
                    'template' => $button_base['template'],
                    'button_label' => __('Show All', WPG_Quick_Ajax_Helper::quick_ajax_text_domain()),
                    'data-button' => $button_base['data-button'],
                    'data-action' => $this->args,
                    'data-attributes' => $button_base['data-attributes'],
                ];
                $navigation_buttons[] = $show_all_button;
                foreach ( $terms as $term ) {
                    $term_button_data = [                        
                        'term_id' => $term->term_id,
                        'taxonomy' => $term->taxonomy,
                        'template' => $button_base['template'],
                        'button_label' => $term->name,
                        'data-button' => $button_base['data-button'],
                        'data-action' => $this->quick_ajax_tax_query($taxonomy, $term->slug),
                        'data-attributes' => $button_base['data-attributes'],
                    ];
                    $navigation_buttons[] = $term_button_data;
                }
                $navigation_buttons = apply_filters('quick_ajax_modify_term_buttons', $navigation_buttons, $this->quick_ajax_id);
                foreach ( $navigation_buttons as $button ) {
                    echo $this->quick_ajax_update_button_template($button);
                }
            }
            do_action('quick_ajax_filter_wrapper_end');
            echo '</div>';
            do_action('after_quick_ajax_filter_wrapper');

            $output = ob_get_clean(); // Get the buffered content into a variable
            return $output; // Return the content
        }
        private function quick_ajax_update_button_template($button_data) {
            $button_label = isset($button_data['button_label']) ? htmlspecialchars($button_data['button_label']) : '';
            if (empty($button_label)){
                return '';
            }
            ob_start();
            include($button_data['template']);
            $content = ob_get_clean();
            $modified_content = $this->quick_ajax_add_button_data($content, $button_data);
            return $modified_content;
        }
        private function quick_ajax_add_button_data($content, $button_data) {
            $button_data_attributes = htmlspecialchars(json_encode($button_data['data-attributes']), ENT_QUOTES, 'UTF-8');
            $button_data_action = htmlspecialchars(json_encode($button_data['data-action']), ENT_QUOTES, 'UTF-8');
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
        private function quick_ajax_tax_query($taxonomy, $term_slug){
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
        public function quick_ajax_meta_query($field_name, $field_value, $compare = '='){
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
            $globalOptions = get_option(WPG_Quick_Ajax_Helper::quick_ajax_admin_page_global_options_name());
            return $globalOptions;
        }
        private function extract_classes_from_string($string){
            // Split the input string into an array using whitespace or comma as separators
            $class_container_array = preg_split('/[\s,]+/', esc_attr($string));
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
        public function quick_ajax_layout_customization($attributes){
            $this->attributes = [];
            $this->layout = [];
            //Apply quick AJAX CSS Style
            $this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_css_style()] = (isset($attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_css_style()])) ? esc_attr($attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_css_style()]) : 0;
            //Number of columns
            $this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_grid_num_columns()] = (isset($attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_grid_num_columns()])) ? esc_attr($attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_grid_num_columns()]) : 0;
            //add custom class for taxonomy filter
            if(isset($attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_taxonomy_filter_class()])){
                $this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_taxonomy_filter_class()] = $this->extract_classes_from_string($attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_taxonomy_filter_class()]);
            }
            //Add class to post container
            if(isset($attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_container_class()])){
                $this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_container_class()] = $this->extract_classes_from_string($attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_container_class()]);
            }
            //Post Item Template
            $post_item_template = isset($attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_post_item_template()]) ? $attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_post_item_template()] : false;
            $this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_post_item_template()] = WPG_Quick_Ajax_Helper::quick_ajax_plugin_templates_post_item_template($post_item_template);
            $this->attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_post_item_template()] = $post_item_template;
            //Custom Load More Post Quantity
            if(isset($attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_load_more_posts()])){
                $this->attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_load_more_posts()] = intval($attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_load_more_posts()]);
            }
            //Select Loader Icon
            $globalOptions = $this->get_global_options();
            $loader_icon = isset($attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_select_loader_icon()]) ? $attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_select_loader_icon()] : $globalOptions['loader_icon'];
            $this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_select_loader_icon()] = WPG_Quick_Ajax_Helper::quick_ajax_plugin_templates_loader_icon_template($loader_icon);
            $this->attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_select_loader_icon()] = $loader_icon;
        }

        public function quick_ajax_wp_query(){
            if(!$this->args){
                return false;
            }
            $args = $this->args;
            $query = new WP_Query($args);
            $this->attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_id()] = $this->quick_ajax_block_id;
            $class_container = $class_inner_container = '';
            if(isset($this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_grid_num_columns()]) && (!empty($this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_grid_num_columns()]))){
                $class_container .= 'quick-ajax-style';   
            }
            if(isset($this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_grid_num_columns()]) && (!empty($this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_grid_num_columns()]))){
                $class_inner_container .= 'col-qty-'.$this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_grid_num_columns()];   
            }
            if(isset($this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_container_class()]) && !empty(trim($this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_container_class()]))){
                $class_inner_container .= ' '.$this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_container_class()];
            }
            $container_class = $this->extract_classes_from_string($class_container);
            $container_inner_class = $this->extract_classes_from_string($class_inner_container);
            
            ob_start(); 
            // Start output buffering
            do_action('before_quick_ajax_posts_wrapper');
            echo '<div id="'.$this->attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_id()].'" class="quick-ajax-posts-wrapper '.$container_class.'">';
            do_action('quick_ajax_posts_wrapper_start');
            echo '<div class="quick-ajax-posts-inner-wrapper '.$container_inner_class.'">';
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    include($this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_post_item_template()]);
                }
            } else {
                include(WPG_Quick_Ajax_Helper::quick_ajax_plugin_templates_no_posts());
            }
            
            $this->load_more_button($query->get('paged'), $query->max_num_pages, $query->found_posts);
            echo '</div>';
            do_action('before_quick_ajax_loader_icon');
            echo '<div class="quick-ajax-loader-icon-wrapper">';
            include($this->layout[WPG_Quick_Ajax_Helper::quick_ajax_layout_select_loader_icon()]);
            echo '</div>';
            do_action('after_quick_ajax_loader_icon');
            do_action('quick_ajax_posts_wrapper_end');
            echo '</div>';
            do_action('after_quick_ajax_posts_wrapper');
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

            if (isset($this->attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_load_more_posts()]) && !empty($this->attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_load_more_posts()])) {
            // Check if load_more_posts attribute is set
            // if we want to add a different number of posts than displayed at the start
            // use 'offset' not 'paged'
                $load_more_posts = intval($this->attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_load_more_posts()]);
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
        

            do_action('before_quick_ajax_load_more_button');
            $button_data['template'] = WPG_Quick_Ajax_Helper::quick_ajax_plugin_templates_load_more_button();
            $button_data['button_label'] = __('Load More', WPG_Quick_Ajax_Helper::quick_ajax_text_domain());
            $button_data['data-button'] = WPG_Quick_Ajax_Helper::quick_ajax_load_more_button_data_button();
            $button_data['data-action'] = $this->args;
            $button_data['data-attributes'] = $this->attributes;
            echo $this->quick_ajax_update_button_template($button_data);
            do_action('after_quick_ajax_load_more_button');
        }
        

        public function print_results(){
            print_r($this->args);
        }
        public function args_json(){
            $json_data = wp_json_encode($this->args);
            return $json_data;
        }

    }
}
?>