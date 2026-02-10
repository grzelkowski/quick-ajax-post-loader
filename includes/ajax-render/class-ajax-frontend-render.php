<?php
if (!defined('ABSPATH')) {
    exit;
}
final class QAPL_Ajax_Frontend_Render {
    private $global_options;
    private $file_manager;
    private $helper;
    private $query_builder;
    private $ui_renderer;
    private $layout_renderer;
    private $load_more_renderer;
    private $layout_builder;


    public function __construct() {
        $this->global_options       = get_option(QAPL_Constants::GLOBAL_OPTIONS_NAME, []);
        $this->file_manager         = new QAPL_File_Manager();
        $this->helper               = new QAPL_Ajax_Helper();
        $this->query_builder        = new QAPL_Ajax_Query_Builder();
        $this->ui_renderer          = new QAPL_Ajax_Filter_Menu_Renderer($this->file_manager, $this->helper, $this->global_options);
        $this->layout_builder       = new QAPL_Ajax_Layout_Builder($this->file_manager, $this->helper);
        $this->load_more_renderer   = new QAPL_Ajax_Load_More_Renderer($this->file_manager, $this->ui_renderer, $this->helper);
        $this->layout_renderer      = new QAPL_Ajax_Layout_Renderer($this->file_manager, $this->load_more_renderer, $this->helper);
    }
    private function build_render_context($source_args, $attributes) {
        // build query args
        $query_args = $this->query_builder->wp_query_args($source_args, $attributes);
        if (!$query_args) {
            return false;
        }
        // build layout
        $layout_data = $this->layout_builder->layout_customization($attributes, $this->global_options);
        $context = [
            'query_args'    => $query_args,
            'layout'        => $layout_data['layout'],
            'attrs'         => $layout_data['attributes'],
            'ajax_initial'  => $layout_data['ajax_initial_load'] ?? null,
            'quick_ajax_id' => $this->query_builder->get_quick_ajax_id(),
        ];
        return $context;
    }

    public function render_post_container($source_args, $attributes = [], $render_context = [], $meta_query = null) {
        $context = $this->build_render_context($source_args, $attributes);
        if (!$context) {
            return '';
        }
        $query_args         = $context['query_args'];
        $layout             = $context['layout'];
        $attrs              = $context['attrs'];
        $ajax_initial_load  = $context['ajax_initial'];
        $quick_ajax_id      = $context['quick_ajax_id'];


        ob_start();
        // optional filter + sort wrappers
        if (!empty($render_context['controls_container'])) {
            echo '<div class="quick-ajax-controls-container">';
        }
        if (!empty($render_context['sort_options'])) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $this->ui_renderer->render_sort_options($render_context['sort_options'], $layout, $query_args, $attrs, $source_args, $quick_ajax_id);
        }
        if (!empty($render_context['show_taxonomy_filter']) && !empty($source_args['selected_taxonomy'])) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $this->ui_renderer->render_taxonomy_terms_filter($source_args['selected_taxonomy'], $query_args, $source_args, $layout, $attrs, $quick_ajax_id);
        }
        if (!empty($render_context['controls_container'])) {
            echo '</div>';
        }
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $this->layout_renderer->render_layout($query_args, $source_args, $layout, $attrs, $ajax_initial_load, $quick_ajax_id);
        return ob_get_clean();
    }

    public function render_taxonomy_filter($source_args, $attributes, $taxonomy = null) {
        $selected_taxonomy = $taxonomy;
        if (!$selected_taxonomy && isset($source_args['selected_taxonomy'])) {
            $selected_taxonomy = sanitize_text_field($source_args['selected_taxonomy']);
        }
        if (!$selected_taxonomy) {
            return '';
        }
        $context = $this->build_render_context($source_args, $attributes);
        if (!$context) {
            return '';
        }
        $query_args         = $context['query_args'];
        $layout             = $context['layout'];
        $attrs              = $context['attrs'];
        $quick_ajax_id      = $context['quick_ajax_id'];

        return $this->ui_renderer->render_taxonomy_terms_filter($selected_taxonomy, $query_args, $source_args, $layout, $attrs, $quick_ajax_id);
    }

    public function render_sort_controls($source_args, $attributes, $sort_options) {
        $context = $this->build_render_context($source_args, $attributes);
        if (!$context) {
            return '';
        }
        $query_args         = $context['query_args'];
        $layout             = $context['layout'];
        $attrs              = $context['attrs'];
        $quick_ajax_id      = $context['quick_ajax_id'];
        return $this->ui_renderer->render_sort_options($sort_options, $layout, $query_args, $attrs, $source_args, $quick_ajax_id);
    }
}