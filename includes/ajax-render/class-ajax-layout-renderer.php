<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Ajax_Layout_Renderer{
    private $file_manager;
    private $load_more_renderer;
    private $helper;

    public function __construct(QAPL_File_Manager $file_manager, QAPL_Ajax_Load_More_Renderer $load_more_renderer, QAPL_Ajax_Helper $helper) {
        $this->file_manager         = $file_manager;
        $this->load_more_renderer   = $load_more_renderer;
        $this->helper               = $helper;
    }

    public function render_layout($query_args, $source_args, $layout, $attributes, $ajax_initial_load, $quick_ajax_id) {
        if (empty($query_args)) {
            return '';
        }
        $query_args['selected_taxonomy'] = sanitize_text_field($source_args['selected_taxonomy'] ?? '');
        $query_args['selected_terms'] = array_map('absint', $source_args['selected_terms'] ?? []);

        $query = new WP_Query($query_args);
        //$this->attributes[QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID] = $this->quick_ajax_id;
        //$layout_quick_ajax_id = esc_attr($this->attributes[QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID]);
        $class_container = '';
        $class_inner_container = '';
        if (isset($layout[QAPL_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE]) && $layout[QAPL_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE] != 0) {
            $class_container .= 'quick-ajax-theme';
        }
        if(isset($layout[QAPL_Constants::ATTRIBUTE_GRID_NUM_COLUMNS]) && (!empty($layout[QAPL_Constants::ATTRIBUTE_GRID_NUM_COLUMNS]))){
            $class_inner_container .= 'col-qty-'.$layout[QAPL_Constants::ATTRIBUTE_GRID_NUM_COLUMNS];   
        }
        if(isset($layout[QAPL_Constants::ATTRIBUTE_CONTAINER_CLASS]) && !empty(trim($layout[QAPL_Constants::ATTRIBUTE_CONTAINER_CLASS]))){
            $class_inner_container .= ' '.$layout[QAPL_Constants::ATTRIBUTE_CONTAINER_CLASS];
        }
        $container_class = $this->helper->extract_classes_from_string($class_container);
        $container_inner_class = $this->helper->extract_classes_from_string($class_inner_container);
        ob_start();
        // Start output buffering
        do_action(QAPL_Constants::HOOK_POSTS_CONTAINER_BEFORE, $quick_ajax_id);
        echo '<div id="quick-ajax-'.esc_attr($quick_ajax_id).'" class="quick-ajax-posts-container '.esc_attr($container_class).'">';
        do_action(QAPL_Constants::HOOK_POSTS_CONTAINER_START, $quick_ajax_id);
        echo '<div class="quick-ajax-posts-wrapper '.esc_attr($container_inner_class).'">';
        

        if ($query->have_posts()) {
            $container_settings = [
                'quick_ajax_id' => $quick_ajax_id,
                'template_name' => $attributes[QAPL_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE],
            ];
            $qapl_post_template = QAPL_Post_Template_Factory::get_template($container_settings);
            QAPL_Post_Template_Context::set_template($qapl_post_template);
            if ($ajax_initial_load) {
                if (empty($attributes[QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID])) {
                    $attributes[QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID] = $quick_ajax_id;
                }
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '<div class="qapl-initial-loader" data-button="'.QAPL_Constants::TERM_FILTER_BUTTON_DATA_BUTTON.'" style="display:none;" data-action="' . esc_attr(wp_json_encode($source_args)) . '" data-attributes="' . esc_attr(wp_json_encode($attributes)) . '"></div>';
            } else {
                while ($query->have_posts()) {
                    $query->the_post();                        
                    include($layout[QAPL_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE]);
                }
            }
            QAPL_Post_Template_Context::clear_template(); 
        } else {
            // No posts found
            $container_settings = [
                'quick_ajax_id' => $quick_ajax_id,
                'template_name' => 'no-post-message',
            ];
            $qapl_no_post_template = QAPL_Post_Template_Factory::get_template($container_settings);
            QAPL_Post_Template_Context::set_template($qapl_no_post_template);
            include($this->file_manager->get_no_posts_template());
            QAPL_Post_Template_Context::clear_template(); 
        }
                    
        echo '</div>';
    
        do_action(QAPL_Constants::HOOK_LOADER_BEFORE, $quick_ajax_id);
        $this->render_loader($layout);
        do_action(QAPL_Constants::HOOK_LOADER_AFTER, $quick_ajax_id);

        if (!$ajax_initial_load) {
            $this->render_load_more($attributes, $source_args, $query, $quick_ajax_id);
        }

        do_action(QAPL_Constants::HOOK_POSTS_CONTAINER_END, $quick_ajax_id);
        echo '</div>';
        do_action(QAPL_Constants::HOOK_POSTS_CONTAINER_AFTER, $quick_ajax_id);
        
        wp_reset_postdata();
        // Get the buffered content into a variable
        $output = ob_get_clean(); 
        
        //$output = $this->replace_placeholders($output); // not in use after removing placeholders
        return $output; // Return the content
    }
    private function render_loader($layout) {
        $layout_path = $layout[QAPL_Constants::ATTRIBUTE_LOADER_ICON] ?? null;
        if (!$layout_path || !file_exists($layout_path)) {
            return;
        }
        echo '<div class="quick-ajax-loader-container">';
        include $layout_path;
        echo '</div>';
    }
    private function render_load_more($attributes, $source_args, $query, $quick_ajax_id) {
        $infinite_scroll = isset($attributes[QAPL_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL]) ? intval($attributes[QAPL_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL]) : QAPL_Constants::QUERY_SETTING_AJAX_ON_INITIAL_LOAD_DEFAULT;
        $query_data = [
            'paged'           => intval($query->get('paged')),
            'max_num_pages'   => intval($query->max_num_pages),
            'found_posts'     => intval($query->found_posts),
            'post_count'      => intval($query->post_count),
            'infinite_scroll' => $infinite_scroll,
        ];
        //echo wp_kses_post($this->load_more_button(intval($query->get('paged')), intval($query->max_num_pages), intval($query->found_posts), intval($query->post_count), $infinite_scroll));
        //echo wp_kses_post($this->load_more_button($attributes, $source_args, $query_data, $quick_ajax_id));
        $load_more_data = $this->load_more_renderer->build_load_more_button($attributes, $source_args, $query_data, $quick_ajax_id);
        if (!$load_more_data) {
            return;
        }
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $this->load_more_renderer->render_load_more_button($load_more_data);
    }
}