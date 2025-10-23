<?php
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Ajax_Controller {
    private $file_manager;
    private $query_builder;
    private $layout_renderer;
    private $ui_renderer;
    private $helper;
    private $global_options;

    public function __construct() {
        $this->global_options  = get_option(QAPL_Quick_Ajax_Constants::GLOBAL_OPTIONS_NAME, []);
        $this->file_manager    = new QAPL_Quick_Ajax_File_Manager();
        $this->query_builder   = new QAPL_Ajax_Query_Builder();
        $this->helper          = new QAPL_Ajax_Helper();
        $this->ui_renderer     = new QAPL_Ajax_UI_Renderer($this->file_manager, $this->global_options);
        $this->layout_renderer = new QAPL_Ajax_Layout_Renderer($this->file_manager, $this->ui_renderer);
    }

    public function render_post_container($args, $attributes = [], $render_context = [], $meta_query = null) {
        $input_args = $args; 
        $query_args = $this->query_builder->wp_query_args($args, $attributes);
        if (!$query_args) {
            return '';
        }
        $layout_data = $this->layout_renderer->layout_customization($attributes, $this->global_options);
        $layout = $layout_data['layout'];
        $attrs = $layout_data['attributes'];
        $ajax_initial_load = $layout_data['ajax_initial_load'];
        $quick_ajax_id = $this->query_builder->get_quick_ajax_id();
        $action_args = $input_args;

        ob_start();
        // optional filter + sort wrappers
        if (!empty($render_context['controls_container'])) {
            echo '<div class="quick-ajax-controls-container">';
        }
        if (!empty($render_context['sort_options'])) {
            echo $this->ui_renderer->render_sort_options($render_context['sort_options'], $layout, $query_args, $attrs, $action_args, $quick_ajax_id);
        }
        if (!empty($render_context['show_taxonomy_filter']) && !empty($args['selected_taxonomy'])) {
            echo $this->ui_renderer->render_taxonomy_terms_filter($args['selected_taxonomy'], $query_args, $input_args, $layout, $attrs, $action_args, $quick_ajax_id);
        }
        if (!empty($render_context['controls_container'])) {
            echo '</div>';
        }
        echo $this->layout_renderer->wp_query($query_args, $input_args, $layout, $attrs, $ajax_initial_load, $action_args, $quick_ajax_id);
        return ob_get_clean();
    }

    public function render_taxonomy_filter($args, $attributes, $taxonomy = null) {
        $selected_taxonomy = $taxonomy;
        if (!$selected_taxonomy && isset($args['selected_taxonomy'])) {
            $selected_taxonomy = sanitize_text_field($args['selected_taxonomy']);
        }
        if (!$selected_taxonomy) {
            return '';
        }
        $input_args = $args;
        $query_args = $this->query_builder->wp_query_args($args, $attributes);
        $layout_data = $this->layout_renderer->layout_customization($attributes, $this->global_options);
        $layout = $layout_data['layout'];
        $attrs = $layout_data['attributes'];
        $quick_ajax_id = $this->query_builder->get_quick_ajax_id();
        $action_args = $input_args;
        return $this->ui_renderer->render_taxonomy_terms_filter($selected_taxonomy, $query_args, $input_args, $layout, $attrs, $action_args, $quick_ajax_id);
    }

    public function render_sort_controls($args, $attributes, $sort_options) {
        $input_args = $args;
        $query_args = $this->query_builder->wp_query_args($args, $attributes);
        $layout_data = $this->layout_renderer->layout_customization($attributes, $this->global_options);
        $layout = $layout_data['layout'];
        $attrs = $layout_data['attributes'];
        $quick_ajax_id = $this->query_builder->get_quick_ajax_id();
        $action_args = $input_args;

        return $this->ui_renderer->render_sort_options($sort_options, $layout, $query_args, $attrs, $action_args, $quick_ajax_id);
    }
}