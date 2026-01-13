<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Ajax_UI_Renderer{
    private $file_manager;
    private $helper;
    private $global_options;

    public function __construct(QAPL_File_Manager $file_manager, QAPL_Ajax_Helper $helper, array $global_options = []) {
        $this->file_manager     = $file_manager;
        $this->helper           = $helper;
        $this->global_options   = $global_options;
    }
    private function get_post_assigned_to_the_term($term, $post_type, $excluded_post_ids){
        // phpcs:disable WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in -- intentional exclusion of rendered posts
        // phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- taxonomy filtering is required
        $query_args = array(
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
        // phpcs:enable WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
        // phpcs:enable WordPress.DB.SlowDBQuery.slow_db_query_tax_query
        $posts = get_posts($query_args);

        if (!empty($posts)) {
            return true;
        }
        return false;
    }
    /**
     * Render taxonomy terms filter if conditions are met.
     *
     * This method allows passing a taxonomy directly, but if it's not provided,
     * it tries to use the 'selected_taxonomy' value from $source_args.
     * The 'selected_taxonomy' exists only if a taxonomy has been chosen.
     * If a taxonomy has been chosen, the filter will be rendered.
     * In the future, there might be an option to select a taxonomy but not display the filter itself,
     * so this method should also accommodate such a scenario if implemented.
     */
    public function render_taxonomy_terms_filter($taxonomy, $query_args, $source_args, $layout, $attributes, $quick_ajax_id){
        if (empty($query_args)) {
            return false;
        }
        if(!$taxonomy){
            $taxonomy = $source_args['selected_taxonomy'];
        }

        $terms_args = array(
            'taxonomy'     => $taxonomy,
            'object_type'  => array($query_args['post_type']),
            'hide_empty'   => true,
        );            
        // only include specific terms if selected_terms is not empty
        if (!empty($source_args['selected_terms']) && is_array($source_args['selected_terms'])) {
            $terms_args['include'] = $source_args['selected_terms'];
        }            
        $terms = get_terms($terms_args);            

        $block_id = 'quick-ajax-filter-'.$quick_ajax_id;
        $class_container = 'quick-ajax-filter-container';
        if (!empty($layout[QAPL_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE])) {
            $class_container .= ' quick-ajax-theme';
        }
        if (!empty(trim($layout[QAPL_Constants::ATTRIBUTE_TAXONOMY_FILTER_CLASS] ?? ''))) {
            $class_container .= ' ' . $layout[QAPL_Constants::ATTRIBUTE_TAXONOMY_FILTER_CLASS];
        }           
        $container_class = $this->helper->extract_classes_from_string($class_container);

        ob_start(); // Start output buffering

        do_action(QAPL_Constants::HOOK_FILTER_CONTAINER_BEFORE, $quick_ajax_id);
        echo '<div id="'.esc_attr($block_id).'" class="'.esc_attr($container_class).'">';
        do_action(QAPL_Constants::HOOK_FILTER_CONTAINER_START, $quick_ajax_id);
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            $attributes[QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID] = $quick_ajax_id;
            
            $navigation_buttons = [];                
            $button_base = [
                'data-button' => QAPL_Constants::TERM_FILTER_BUTTON_DATA_BUTTON,
                'template' => $this->file_manager->get_taxonomy_filter_button_template(),
                'data-attributes' => $attributes,
            ];
            $show_all_label = $this->global_options['show_all_label'] ?? __('Show All', 'quick-ajax-post-loader');    
            $show_all_button = [                    
                'term_id' => 'none',
                'taxonomy' => $taxonomy,
                'template' => $button_base['template'],
                'button_label' => $show_all_label,
                'data-button' => $button_base['data-button'],
                'data-action' => $source_args,
                'data-attributes' => $button_base['data-attributes'],
            ];
            $show_all_button['is_active'] = true;
            $navigation_buttons[] = $show_all_button;
            $exclude_ids = (isset($query_args['post__not_in'])) ? $query_args['post__not_in'] : '';
            foreach ( $terms as $term ) { 
                $not_empty = $this->get_post_assigned_to_the_term($term, $query_args['post_type'], $exclude_ids);
                if($not_empty == true){
                    $data_action = $source_args;
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
            
            $navigation_buttons = apply_filters(QAPL_Constants::HOOK_MODIFY_TAXONOMY_FILTER_BUTTONS, $navigation_buttons, $quick_ajax_id);
            $filter_buttons='';
            foreach ( $navigation_buttons as $button ) {
                $filter_buttons .= $this->update_button_template($button);
            }
            echo wp_kses_post($filter_buttons);
        }
        do_action(QAPL_Constants::HOOK_FILTER_CONTAINER_END, $quick_ajax_id);
        echo '</div>';
        do_action(QAPL_Constants::HOOK_FILTER_CONTAINER_AFTER, $quick_ajax_id);

        $output = ob_get_clean(); // Get the buffered content into a variable
        //$output = $this->replace_placeholders($output); // not in use after removing placeholders
        return $output; // Return the content
    }

    public function render_sort_options($sort_options, $layout, $query_args, $attributes, $source_args, $quick_ajax_id) {
        $block_id = 'quick-ajax-sort-options-'.$quick_ajax_id;
        $class_container = 'quick-ajax-sort-options-container';
        if (!empty($layout[QAPL_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE])) {
            $class_container .= ' quick-ajax-theme';
        }       
        $container_class = $this->helper->extract_classes_from_string($class_container);
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
            $field = QAPL_Form_Field_Factory::build_select_sort_button_options_field();
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
            $sorted_options = apply_filters(QAPL_Constants::HOOK_MODIFY_SORTING_OPTIONS_VARIANTS, $sorted_options, $quick_ajax_id);
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
            $sort_buttons .= $this->create_sort_button($button_option, $query_args, $attributes, $source_args, $quick_ajax_id);            
        }
        echo wp_kses($sort_buttons, $allowed_button_html);
        
        echo '</div>';
        
        $output = ob_get_clean(); // Get the buffered content into a variable
        return $output; // Return the content
    }
    private function create_sort_button($button_data, $query_args, $attributes, $source_args, $quick_ajax_id) {         
        $attributes[QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID] = $quick_ajax_id;
        $sort_option = '<div class="quick-ajax-sort-option-wrapper">';
        $default_option = strtolower($query_args['orderby']).'-'.strtolower($query_args['order']);
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
        $sort_option .= '<span class="quick-ajax-settings" data-button="'.QAPL_Constants::SORT_OPTION_BUTTON_DATA_BUTTON.'" data-attributes="' . esc_attr(wp_json_encode($attributes)) . '" data-action="' . esc_attr(wp_json_encode($source_args)) . '"></span>';
        $sort_option .= '</div>';                      
        return $sort_option;
    }
    public function update_button_template($button_data) {
        $button_label = isset($button_data['button_label']) ? esc_html($button_data['button_label']) : '';
        if (empty($button_label)){
            return '';
        }
        if (empty($button_data['template']) || !file_exists($button_data['template'])) {
        //skip rendering if template is missing or invalid
            return '';
        }
        if($button_data['data-button'] == QAPL_Constants::LOAD_MORE_BUTTON_DATA_BUTTON){
            $quick_ajax_id = $button_data['data-attributes'][QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID] ?? '';
            $load_more_settings = [
                'quick_ajax_id' => $quick_ajax_id,
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
        //encode json safely
        $button_data_attributes = htmlspecialchars(wp_json_encode($button_data['data-attributes']), ENT_QUOTES, 'UTF-8');
        $button_data_action = htmlspecialchars(wp_json_encode($button_data['data-action']), ENT_QUOTES, 'UTF-8');        
        //escape for regex    
        $button_type = htmlspecialchars($button_data['data-button']);
        $button_type_escaped = preg_quote($button_type, '/');
        $regex = '/<([^>]+)data-button="'.$button_type_escaped.'"([^>]*)>/';
        
        $modified_content = preg_replace_callback(
            $regex,
            function ($matches) use ($button_type_escaped, $button_data_action, $button_data_attributes) {
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
                    $full_match = preg_replace('/(data-button="'.$button_type_escaped.'")/', '$1 data-action="' . $button_data_action . '"', $full_match);
                }
                //update or add 'data-attributes'
                if (preg_match('/data-attributes="[^"]*"/', $full_match)) {
                    //if 'data-attributes' exists, replace it
                    $full_match = preg_replace('/data-attributes="[^"]*"/', 'data-attributes="' . $button_data_attributes . '"', $full_match);
                } else {
                    //if 'data-attributes' does not exist, add it
                    $full_match = preg_replace('/(data-button="'.$button_type_escaped.'")/', '$1 data-attributes="' . $button_data_attributes . '"', $full_match);
                }
                return $full_match;
            },
            $content
        );
        //set active for button - Show All as default
        if (!empty($button_data['is_active'])) {
            $modified_content = preg_replace(
                '/(<[^>]*class=")([^"]*)"([^>]*data-button="' . $button_type_escaped . '"[^>]*>)/',
                '$1$2 active"$3',
                $modified_content,
                1
            );
        }
        
        //Add button_label if button element contains THE_LABEL
        $button_label = htmlspecialchars($button_data['button_label']);
        $label_regex = sprintf('/<(\w+)\s[^>]*data-button="%s"[^>]*>QUICK_AJAX_LABEL<\/\\1>/s', $button_type_escaped);
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
}