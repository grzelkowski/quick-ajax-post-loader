<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Quick_Ajax_Handler{
    private $file_manager;
    private $input_args = [];
    private $action_args = [];
    public $args = [];
    public $attributes = [];
    public $layout = [];
    private $ajax_initial_load;
    private $quick_ajax_id;
    private $global_options;
    //private $placeholder_replacer;
    
    public function __construct(QAPL_Quick_Ajax_File_Manager $file_manager){
        $this->quick_ajax_id = 0;
        $this->global_options = get_option(QAPL_Quick_Ajax_Constants::GLOBAL_OPTIONS_NAME, []);
        $this->file_manager = $file_manager;
        //$this->placeholder_replacer = new QAPL_Placeholder_Replacer(); // not in use after removing placeholders
        // Filter hooks for filter wrapper
        /*
        add_action(QAPL_Quick_Ajax_Constants::HOOK_FILTER_CONTAINER_BEFORE, array($this, 'action_filter_wrapper_pre'));         
        add_action(QAPL_Quick_Ajax_Constants::HOOK_FILTER_CONTAINER_START, array($this, 'action_filter_wrapper_open'));
        add_action(QAPL_Quick_Ajax_Constants::HOOK_FILTER_CONTAINER_END, array($this, 'action_filter_wrapper_close'));
        add_action(QAPL_Quick_Ajax_Constants::HOOK_FILTER_CONTAINER_AFTER, array($this, 'action_filter_wrapper_complete'));

        // Filter hooks for posts wrapper
        add_action(QAPL_Quick_Ajax_Constants::HOOK_POSTS_CONTAINER_BEFORE, array($this, 'action_posts_wrapper_pre'));     
        add_action(QAPL_Quick_Ajax_Constants::HOOK_POSTS_CONTAINER_START, array($this, 'action_posts_wrapper_open'));
        add_action(QAPL_Quick_Ajax_Constants::HOOK_POSTS_CONTAINER_END, array($this, 'action_posts_wrapper_close'));     
        add_action(QAPL_Quick_Ajax_Constants::HOOK_POSTS_CONTAINER_AFTER, array($this, 'action_posts_wrapper_complete'));

        // Filter hooks for load more button
        add_action(QAPL_Quick_Ajax_Constants::HOOK_LOAD_MORE_BEFORE, array($this, 'action_load_more_button_pre'));     
        add_action(QAPL_Quick_Ajax_Constants::HOOK_LOAD_MORE_AFTER, array($this, 'action_load_more_button_complete')); 

        // Filter hooks for loader icon
        add_action(QAPL_Quick_Ajax_Constants::HOOK_LOADER_BEFORE, array($this, 'action_loader_icon_pre'));     
        add_action(QAPL_Quick_Ajax_Constants::HOOK_LOADER_AFTER, array($this, 'action_loader_icon_complete'));
        */
        // Filters with arguments (query and taxonomy buttons)
        //add_filter(QAPL_Quick_Ajax_Constants::HOOK_MODIFY_POSTS_QUERY_ARGS, array($this, 'filter_modify_query_args'), 10, 2); 
        //add_filter(QAPL_Quick_Ajax_Constants::HOOK_MODIFY_TAXONOMY_FILTER_BUTTONS, array($this, 'filter_modify_taxonomy_filter_buttons'), 10, 2);
    }
        

    public function get_quick_ajax_id() {
        return $this->quick_ajax_id;
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
            $attributes = [QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID => sanitize_text_field($attributes)];
        }
        if (isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID])) {
            // Prefix 'p' for 'shortcode' equal to true, otherwise 'c'
            $prefix = (isset($attributes['shortcode']) && $attributes['shortcode'] === true) ? 'p' : 'c';              
            $this->quick_ajax_id = esc_attr($prefix . $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID]);
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
    private function sanitize_to_int_array($value) {
        // if it's a string (e.g. "1,2,3"), split it by comma or whitespace
        if (!is_array($value)) {
            $value = preg_split('/[,\s]+/', $value);
        }        
        // normalize and sanitize all values
        $value = array_map('absint', $value);        
        // remove empty values (0s, nulls, etc.)
        $value = array_filter($value, function($id) {
            return $id > 0;
        });        
        // remove duplicates
        $int_array = array_values(array_unique($value));        
        return $int_array;
    }
    private function normalize_args($args) {
        // convert comma-separated string to array of integers
        if (isset($args['post__not_in'])) {
            $args['post__not_in'] = $this->sanitize_to_int_array($args['post__not_in']);
        }        
        if (isset($args['selected_terms'])) {
            $args['selected_terms'] = $this->sanitize_to_int_array($args['selected_terms']);
        } 
        return $args;
    }
    public function wp_query_args($args, $attributes = false){
        $this->args = [];
        $this->generate_block_id($attributes);

            //normalize input args (sanitize selected_terms, post__not_in, etc.)
        $this->input_args = $this->normalize_args($args);
        $this->action_args = $this->input_args;
        
        // generate query args (post_type, tax_query, etc.)
        $quick_ajax_args = $this->initialize_query_args($this->input_args);

        $this->args['post_status'] = QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_POST_STATUS_DEFAULT;

        if (isset($quick_ajax_args['post_type']) && !empty($quick_ajax_args['post_type'])) {
            foreach ($quick_ajax_args as $key => $value) {
                if (!empty($value)) {
                    $this->args[$key] = $value;
                }
            }
        }
        /* not in use yet
        if(isset($args['tax_query']) && !empty($args['tax_query'])){
            $this->args['tax_query'] = $args['tax_query'];
        }
        */
        
        $this->args = apply_filters(QAPL_Quick_Ajax_Constants::HOOK_MODIFY_POSTS_QUERY_ARGS, $this->args, $this->quick_ajax_id);

        if (empty($this->args)) {
            return false;
        }else{
            return $this->args; 
        }
    }
    private function initialize_query_args($args) {
        // Set default query arguments
        $query_args = $this->query_args_base_query_args($args);
        $query_args = $this->query_args_add_tax_query($query_args, $args);            
        $query_args = $this->query_args_apply_offset_or_paged($query_args, $args);
        return $query_args;    
    }
    private function query_args_base_query_args($args) {
        return [
            'post_type' => isset($args['post_type']) ? sanitize_text_field($args['post_type']) : null,
            'posts_per_page' => isset($args['posts_per_page']) ? intval($args['posts_per_page']) : QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_POSTS_PER_PAGE_DEFAULT,
            'orderby' => isset($args['orderby']) ? sanitize_text_field($args['orderby']) : QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_ORDERBY_DEFAULT,
            'order' => isset($args['order']) ? sanitize_text_field($args['order']) : QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_ORDER_DEFAULT,
            'post__not_in' => $args['post__not_in'] ?? [],
            'ignore_sticky_posts' => isset($args['ignore_sticky_posts']) ? intval($args['ignore_sticky_posts']) : QAPL_Quick_Ajax_Constants::QUERY_SETTING_IGNORE_STICKY_POSTS_DEFAULT,
            'paged' => isset($args['paged']) ? intval($args['paged']) : 1,
        ];
    }        
    private function query_args_apply_offset_or_paged($query_args, $args) {
        // Check if 'offset' is provided and use it instead of 'paged'
        if (isset($args['offset']) && !is_null($args['offset'])) {
            // Set the offset value and remove 'paged' from the query
            $query_args['offset'] = intval($args['offset']);
            unset($query_args['paged']);
        }
        return $query_args;
    }
    private function query_args_add_tax_query($query_args, $args) {
        $taxonomy = isset($args['selected_taxonomy']) ? sanitize_text_field($args['selected_taxonomy']) : '';
        $terms = isset($args['selected_terms']) ? $args['selected_terms'] : [];
    
        if ($taxonomy && !empty($terms)) {
            $query_args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $terms,
                'operator' => 'IN',
            ];
        } elseif ($taxonomy) {
            $query_args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'operator' => 'EXISTS',
            ];
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
                    'operator' => 'IN',
                ),
            ),
            'post__not_in' => $excluded_post_ids,
            'fields' => 'ids',
        );
        $posts = get_posts($args);

        if (!empty($posts)) {
            return true;
        }
        return false;
    }
    /**
     * Render taxonomy terms filter if conditions are met.
     *
     * This method allows passing a taxonomy directly, but if it's not provided,
     * it tries to use the 'selected_taxonomy' value from $input_args.
     * The 'selected_taxonomy' exists only if a taxonomy has been chosen.
     * If a taxonomy has been chosen, the filter will be rendered.
     * In the future, there might be an option to select a taxonomy but not display the filter itself,
     * so this method should also accommodate such a scenario if implemented.
     */
    public function render_taxonomy_terms_filter($taxonomy = false){
        if(!$this->args){
            return false;
        }
        if(!$taxonomy){
            $taxonomy = $this->input_args['selected_taxonomy'];
        }

        $terms_args = array(
            'taxonomy'     => $taxonomy,
            'object_type'  => array($this->args['post_type']),
            'hide_empty'   => true,
        );            
        // only include specific terms if selected_terms is not empty
        if (!empty($this->input_args['selected_terms']) && is_array($this->input_args['selected_terms'])) {
            $terms_args['include'] = $this->input_args['selected_terms'];
        }            
        $terms = get_terms($terms_args);            

        $block_id = 'quick-ajax-filter-'.$this->quick_ajax_id;
        $class_container = 'quick-ajax-filter-container';
        if (isset($this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE]) && $this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE] != 0) {
            $class_container .= ' quick-ajax-theme';
        }
        if(isset($this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_TAXONOMY_FILTER_CLASS]) && !empty(trim($this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_TAXONOMY_FILTER_CLASS]))){
            $class_container .= ' '.$this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_TAXONOMY_FILTER_CLASS];
        }            
        $container_class = $this->extract_classes_from_string($class_container);

        ob_start(); // Start output buffering

        do_action(QAPL_Quick_Ajax_Constants::HOOK_FILTER_CONTAINER_BEFORE, $this->quick_ajax_id);
        echo '<div id="'.esc_attr($block_id).'" class="'.esc_attr($container_class).'">';
        do_action(QAPL_Quick_Ajax_Constants::HOOK_FILTER_CONTAINER_START, $this->quick_ajax_id);
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            $this->attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID] = $this->quick_ajax_id;
            
            $navigation_buttons = [];                
            $button_base = [
                'data-button' => QAPL_Quick_Ajax_Constants::TERM_FILTER_BUTTON_DATA_BUTTON,
                'template' => $this->file_manager->get_taxonomy_filter_button_template(),
                'data-attributes' => $this->attributes,
            ];
            $show_all_label = $this->global_options['show_all_label'] ?? __('Show All', 'quick-ajax-post-loader');    
            $show_all_button = [                    
                'term_id' => 'none',
                'taxonomy' => $taxonomy,
                'template' => $button_base['template'],
                'button_label' => $show_all_label,
                'data-button' => $button_base['data-button'],
                'data-action' => $this->action_args,
                'data-attributes' => $button_base['data-attributes'],
            ];
            $navigation_buttons[] = $show_all_button;
            $exclude_ids = (isset($this->args['post__not_in'])) ? $this->args['post__not_in'] : '';
            foreach ( $terms as $term ) { 
                $not_empty = $this->get_post_assigned_to_the_term($term, $this->args['post_type'], $exclude_ids);
                if($not_empty == true){
                    $data_action = $this->action_args;
                    $data_action['selected_terms'] = [$term->term_id];
                    $term_button_data = [                        
                        'term_id' => $term->term_id,
                        'taxonomy' => $term->taxonomy,
                        'template' => $button_base['template'],
                        'button_label' => $term->name,
                        'data-button' => $button_base['data-button'],
                        'data-action' => $data_action,
                        'data-attributes' => $button_base['data-attributes'],
                    ];
                    $navigation_buttons[] = $term_button_data;
                }
            }
            
            $navigation_buttons = apply_filters(QAPL_Quick_Ajax_Constants::HOOK_MODIFY_TAXONOMY_FILTER_BUTTONS, $navigation_buttons, $this->quick_ajax_id);
            $filter_buttons='';
            foreach ( $navigation_buttons as $button ) {
                $filter_buttons .= $this->update_button_template($button);
            }
            echo wp_kses_post($filter_buttons);
        }
        do_action(QAPL_Quick_Ajax_Constants::HOOK_FILTER_CONTAINER_END, $this->quick_ajax_id);
        echo '</div>';
        do_action(QAPL_Quick_Ajax_Constants::HOOK_FILTER_CONTAINER_AFTER, $this->quick_ajax_id);

        $output = ob_get_clean(); // Get the buffered content into a variable
        //$output = $this->replace_placeholders($output); // not in use after removing placeholders
        return $output; // Return the content
    }
    public function render_sort_options($sort_options){
        $block_id = 'quick-ajax-sort-options-'.$this->quick_ajax_id;
        $class_container = 'quick-ajax-sort-options-container';
        if (isset($this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE]) && $this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE] != 0) {
            $class_container .= ' quick-ajax-theme';
        }          
        $container_class = $this->extract_classes_from_string($class_container);
        $sort_buttons ='';
        $allowed_button_html = [
            'div' => [
                'id' => [],
                'class' => [],
            ],
            'select' => [
                'id' => [],
                'class' => [],
                'name' => [],
                'aria-label' => [],
            ],
            'option' => [
                'id' => [],
                'class' => [],
                'value' => [],
                'selected' => [],
            ],
            'span' => [
                'id' => [],
                'class' => [],
                'data-button' => [],
                'data-attributes' => [],
                'data-action' => [],
            ],
            'p' => [
                'id' => [],
                'class' => [],
            ]
        ];

        ob_start(); // Start output buffering

        echo '<div id="'.esc_attr($block_id).'" class="'.esc_attr($container_class).'">';
        if(isset($sort_options) && is_array($sort_options)){
            $field = QAPL_Quick_Ajax_Form_Field_Factory::build_select_sort_button_options_field();
            $default_sort_options = $field->get_options();
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
            $sorted_options = apply_filters(QAPL_Quick_Ajax_Constants::HOOK_MODIFY_SORTING_OPTIONS_VARIANTS, $sorted_options, $this->quick_ajax_id);
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
        echo wp_kses($sort_buttons, $allowed_button_html);
        
        echo '</div>';
        
        $output = ob_get_clean(); // Get the buffered content into a variable
        return $output; // Return the content
    }
    private function create_sort_button($button_data) {         
        $this->attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID] = $this->quick_ajax_id;
        $sort_option = '<div class="quick-ajax-sort-option-wrapper">';
        $default_option = strtolower($this->args['orderby']).'-'.strtolower($this->args['order']);
        // escape the aria-label
        $aria_label = isset($button_data['label']) ? esc_attr($button_data['label']) : '';
        $sort_option .= '<select id="'.esc_attr($button_data['id']).'" name="'.esc_attr($button_data['name']).'" aria-label="'.$aria_label.'">';
        foreach ($button_data['options'] as $option) {
            $value = esc_attr($option['value']);
            $label = esc_html($option['label']);
            $selected = ($default_option == $option['value']) ? ' selected' : '';
            $sort_option .= '<option value="' . $value . '"'.$selected.'>' . $label . '</option>';
        }
        $sort_option .= '</select>';
        $sort_option .= '<span class="quick-ajax-settings" data-button="'.QAPL_Quick_Ajax_Constants::SORT_OPTION_BUTTON_DATA_BUTTON.'" data-attributes="' . esc_attr(wp_json_encode($this->attributes)) . '" data-action="' . esc_attr(wp_json_encode($this->action_args)) . '"></span>';
        $sort_option .= '</div>';                      
        return $sort_option;
    }

    private function update_button_template($button_data) {
        $button_label = isset($button_data['button_label']) ? esc_html($button_data['button_label']) : '';
        if (empty($button_label)){
            return '';
        }
        if($button_data['data-button'] == QAPL_Quick_Ajax_Constants::LOAD_MORE_BUTTON_DATA_BUTTON){
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

    private function generate_tax_query($taxonomy, $term_id){
        $term_args = $this->args;
        unset($term_args['paged']);
        unset($term_args['offset']);
        $term_args['tax_query'] = array(
            array(
                'taxonomy' => $taxonomy, 
                'field' => 'term_id',
                'terms' => $term_id,
                'operator' => 'IN',
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
        $this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE] = (isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE])) ? esc_attr($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE]) : 0;
        //Number of columns
        $this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_GRID_NUM_COLUMNS] = (isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_GRID_NUM_COLUMNS])) ? esc_attr($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_GRID_NUM_COLUMNS]) : 0;
        //add custom class for taxonomy filter
        if(isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_TAXONOMY_FILTER_CLASS])){
            $this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_TAXONOMY_FILTER_CLASS] = $this->extract_classes_from_string($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_TAXONOMY_FILTER_CLASS]);
        }
        //Add class to post container
        if(isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS])){
            $this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS] = $this->extract_classes_from_string($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS]);
        }
        //Post Item Template
        $post_item_template = isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE]) ? $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE] : false;
        $this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE] = $this->file_manager->get_post_item_template($post_item_template);
        $this->attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE] = $post_item_template;
        //Custom Load More Post Quantity            
        if(isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOAD_MORE_POSTS])){
            $this->attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOAD_MORE_POSTS] = intval($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOAD_MORE_POSTS]);
        }            
        //Select Loader Icon
        if (isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOADER_ICON]) && !empty($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOADER_ICON])) {
            $loader_icon = $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOADER_ICON];
        } elseif (isset($this->global_options['loader_icon']) && !empty($this->global_options['loader_icon'])) {
            // fallback to global option if attributes value is invalid
            $loader_icon = $this->global_options['loader_icon'];
        } else {
            // final fallback to default value
            $loader_icon = QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_SELECT_LOADER_ICON_DEFAULT;
        }
        $this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOADER_ICON] = $this->file_manager->get_loader_icon_template($loader_icon);
        $this->attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOADER_ICON] = $loader_icon;
        // infinite_scroll
        if(isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL])){
            $this->attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL] = intval($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL]);
        }
        // show_end_message
        if(isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_SHOW_END_MESSAGE])){
            $this->attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_SHOW_END_MESSAGE] = intval($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_SHOW_END_MESSAGE]);
        }
        $this->ajax_initial_load = isset($attributes[QAPL_Quick_Ajax_Constants::AJAX_SETTING_AJAX_INITIAL_LOAD]) ? intval($attributes[QAPL_Quick_Ajax_Constants::AJAX_SETTING_AJAX_INITIAL_LOAD]) : QAPL_Quick_Ajax_Constants::QUERY_SETTING_AJAX_ON_INITIAL_LOAD_DEFAULT;
    }

    public function wp_query(){
        if(!$this->args){
            return false;
        }
        $args = $this->args;

        $args['selected_taxonomy'] = isset($this->input_args['selected_taxonomy']) ? sanitize_text_field($this->input_args['selected_taxonomy']) : '';
        $args['selected_terms'] = isset($this->input_args['selected_terms']) && is_array($this->input_args['selected_terms']) ? array_map('absint', $this->input_args['selected_terms']) : [];
        $query = new WP_Query($args);
        $this->attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID] = $this->quick_ajax_id;
        $layout_quick_ajax_id = esc_attr($this->attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID]);
        $class_container = $class_inner_container = '';
        if (isset($this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE]) && $this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE] != 0) {
            $class_container .= 'quick-ajax-theme';
        }
        if(isset($this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_GRID_NUM_COLUMNS]) && (!empty($this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_GRID_NUM_COLUMNS]))){
            $class_inner_container .= 'col-qty-'.$this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_GRID_NUM_COLUMNS];   
        }
        if(isset($this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS]) && !empty(trim($this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS]))){
            $class_inner_container .= ' '.$this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS];
        }
        $container_class = $this->extract_classes_from_string($class_container);
        $container_inner_class = $this->extract_classes_from_string($class_inner_container);
        ob_start();
        // Start output buffering
        do_action(QAPL_Quick_Ajax_Constants::HOOK_POSTS_CONTAINER_BEFORE, $this->quick_ajax_id);
        echo '<div id="quick-ajax-'.esc_attr($layout_quick_ajax_id).'" class="quick-ajax-posts-container '.esc_attr($container_class).'">';
        do_action(QAPL_Quick_Ajax_Constants::HOOK_POSTS_CONTAINER_START, $this->quick_ajax_id);
        echo '<div class="quick-ajax-posts-wrapper '.esc_attr($container_inner_class).'">';
        

        if ($query->have_posts()) {
            $container_settings = [
                'quick_ajax_id' => $this->quick_ajax_id,
                'template_name' => $this->attributes['post_item_template'],
            ];
            $qapl_post_template = QAPL_Post_Template_Factory::get_template($container_settings);
            QAPL_Post_Template_Context::set_template($qapl_post_template);
            if ($this->ajax_initial_load) {
                echo '<div class="qapl-initial-loader" data-button="quick-ajax-filter-button" style="display:none;" data-action="' . esc_attr(wp_json_encode($this->input_args)) . '" data-attributes="' . esc_attr(wp_json_encode($this->attributes)) . '"></div>';
            } else {
                while ($query->have_posts()) {
                    $query->the_post();                        
                    include($this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE]);
                }
            }
            QAPL_Post_Template_Context::clear_template(); 
        } else {
            // No posts found
            $container_settings = [
                'quick_ajax_id' => $this->quick_ajax_id,
                'template_name' => 'no-post-message',
            ];
            $qapl_no_post_template = QAPL_Post_Template_Factory::get_template($container_settings);
            QAPL_Post_Template_Context::set_template($qapl_no_post_template);
            include($this->file_manager->get_no_posts_template());
            QAPL_Post_Template_Context::clear_template(); 
        }
                    
        echo '</div>';
        do_action(QAPL_Quick_Ajax_Constants::HOOK_LOADER_BEFORE, $this->quick_ajax_id);
        echo '<div class="quick-ajax-loader-container">'; 
        include($this->layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOADER_ICON]);
        echo '</div>';
        do_action(QAPL_Quick_Ajax_Constants::HOOK_LOADER_AFTER, $this->quick_ajax_id);
        if (!$this->ajax_initial_load) {
            $infinite_scroll = isset($this->attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL]) ? intval($this->attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL]) : QAPL_Quick_Ajax_Constants::QUERY_SETTING_AJAX_ON_INITIAL_LOAD_DEFAULT;
            echo wp_kses_post($this->load_more_button(intval($query->get('paged')), intval($query->max_num_pages), intval($query->found_posts), $infinite_scroll));
        }
        do_action(QAPL_Quick_Ajax_Constants::HOOK_POSTS_CONTAINER_END, $this->quick_ajax_id);
        echo '</div>';
        do_action(QAPL_Quick_Ajax_Constants::HOOK_POSTS_CONTAINER_AFTER, $this->quick_ajax_id);
        
        wp_reset_postdata();
        // Get the buffered content into a variable
        $output = ob_get_clean(); 
        
        //$output = $this->replace_placeholders($output); // not in use after removing placeholders
        return $output; // Return the content
    }
    public function load_more_button($paged, $max_num_pages, $found_posts, $infinite_scroll = false) {
        if(!$this->args){
            return false;
        }
        //echo 'paged:'.$paged.'<br />$max_num_pages:'.$max_num_pages.'<br />$found_posts:'.$found_posts.'<br />';
        //print_r($this->args);
        $load_more_args = $this->action_args;
        $load_more_args['paged'] = isset($this->args['paged']) ? intval($this->args['paged']) : 1;
        if (isset($this->attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOAD_MORE_POSTS]) && !empty($this->attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOAD_MORE_POSTS])) {
        // Check if load_more_posts attribute is set
        // if we want to add a different number of posts than displayed at the start
        // use 'offset' not 'paged'
            $load_more_posts = intval($this->attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOAD_MORE_POSTS]);
            $offset = isset($load_more_args['offset']) ? $load_more_args['offset'] + $load_more_posts : + $load_more_posts;
            
            if (($found_posts <= $offset) || ($found_posts <= intval($load_more_args['posts_per_page']))) {
                return false;
            }
                // Update offset
            $load_more_args['offset'] = isset($load_more_args['offset']) ? intval($load_more_args['offset']) + $load_more_posts : intval($load_more_args['posts_per_page']);
            $load_more_args['posts_per_page'] = $load_more_posts;
        } else {
            // Check if there are no more pages to load
            if ($max_num_pages <= $paged) {
                return false;
            }                
            $load_more_args['paged'] += 1;
        }
        $class = '';
        if ($infinite_scroll) {
            $class = ' infinite-scroll';
        }
        //do_action(QAPL_Quick_Ajax_Constants::HOOK_LOAD_MORE_BEFORE);
        $button_data['template'] = $this->file_manager->get_load_more_button_template();
        $button_data['button_label'] = __('Load More', 'quick-ajax-post-loader');
        $button_data['data-button'] = QAPL_Quick_Ajax_Constants::LOAD_MORE_BUTTON_DATA_BUTTON;
        $button_data['data-action'] = $load_more_args;
        $button_data['data-attributes'] = $this->attributes;
        $load_more_button = '<div class="quick-ajax-load-more-container'.$class.'">';
        $load_more_button .=  $this->update_button_template($button_data);
        $load_more_button .= '</div>';
        return $load_more_button;
        //do_action(QAPL_Quick_Ajax_Constants::HOOK_LOAD_MORE_AFTER);
    }
    public function render_end_of_posts_message($load_more, $max_num_pages, $quick_ajax_id, $show_end_post_message = false) {
        if(!$show_end_post_message){
            return '';
        }
        if ($max_num_pages <= 1) {
            return ''; // only one page, don't show anything
        }
        if ($load_more) {
            return ''; // load more still available, don't show anything
        }
        $end_post_message_settings = [ 
            'quick_ajax_id' => $quick_ajax_id, //$this->quick_ajax_id returns'c'
            'template_name' => 'end-post-message',
        ];
        $qapl_end_post_message_template = QAPL_Post_Template_Factory::get_template($end_post_message_settings);
        QAPL_Post_Template_Context::set_template($qapl_end_post_message_template);
        
        ob_start();
        echo '<div class="quick-ajax-end-message-container">';
        include($this->file_manager->get_end_posts_template());
        echo '</div>';
        $content = ob_get_clean();
        QAPL_Post_Template_Context::clear_template();
        return $content;
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
