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
    private $end_posts_renderer;

    public function __construct() {
        $this->global_options = get_option(QAPL_Constants::GLOBAL_OPTIONS_NAME);
        if (!is_array($this->global_options)) {
            $this->global_options = [];
        }
        $this->file_manager         = new QAPL_File_Manager();
        $this->helper               = new QAPL_Ajax_Helper();
        $this->query_builder        = new QAPL_Ajax_Query_Builder();
        $this->ui_renderer          = new QAPL_Ajax_Filter_Menu_Renderer($this->file_manager, $this->helper, $this->global_options);
        $this->layout_builder       = new QAPL_Ajax_Layout_Builder($this->file_manager, $this->helper);
        $this->load_more_renderer   = new QAPL_Ajax_Load_More_Renderer($this->file_manager, $this->ui_renderer, $this->helper);
        $this->layout_renderer      = new QAPL_Ajax_Layout_Renderer($this->file_manager, $this->load_more_renderer, $this->helper);
        $this->end_posts_renderer   = new QAPL_Ajax_End_Message_Renderer($this->file_manager);
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
        // build the search field once - its position decides where it is printed
        $search_position = !empty($render_context['search_position'])
            ? $render_context['search_position']
            : QAPL_Constants::QUERY_SETTING_SEARCH_FIELD_POSITION_DEFAULT;
        $search_field = '';
        if (!empty($render_context['show_search'])) {
            $search_field = $this->ui_renderer->render_search_field($layout, $attrs, $source_args, $quick_ajax_id, $search_position);
        }
        // each control can share the controls row or sit on its own line
        $search_inline = !isset($render_context['search_inline']) || !empty($render_context['search_inline']);
        $sort_inline   = !isset($render_context['sort_inline']) || !empty($render_context['sort_inline']);
        $search_before = ($search_position === QAPL_Constants::QUERY_SETTING_SEARCH_FIELD_POSITION_BEFORE_FILTERS);
        $inline_search_field = $search_inline ? $search_field : '';
        $standalone_search_field = $search_inline ? '' : $search_field;

        $sort_options_field = '';
        if (!empty($render_context['sort_options'])) {
            $sort_options_field = $this->ui_renderer->render_sort_options($render_context['sort_options'], $layout, $query_args, $attrs, $source_args, $quick_ajax_id);
        }
        if (!$sort_inline) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $sort_options_field;
        }
        if (!$search_inline && $search_before) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $standalone_search_field;
        }
        // optional filter + sort wrappers
        if (!empty($render_context['controls_container'])) {
            echo '<div class="quick-ajax-controls-container">';
        }
        if ($sort_inline) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $sort_options_field;
        }
        if ($search_before) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $inline_search_field;
        }
        if (!empty($render_context['show_taxonomy_filter']) && !empty($source_args['selected_taxonomy'])) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $this->ui_renderer->render_taxonomy_terms_filter($source_args['selected_taxonomy'], $query_args, $source_args, $layout, $attrs, $quick_ajax_id);
        }
        if (!$search_before) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $inline_search_field;
        }
        if (!empty($render_context['controls_container'])) {
            echo '</div>';
        }
        if (!$search_inline && !$search_before) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $standalone_search_field;
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
    public function render_search_controls($source_args, $attributes, $position = QAPL_Constants::QUERY_SETTING_SEARCH_FIELD_POSITION_DEFAULT) {
        $context = $this->build_render_context($source_args, $attributes);
        if (!$context) {
            return '';
        }
        $layout             = $context['layout'];
        $attrs              = $context['attrs'];
        $quick_ajax_id      = $context['quick_ajax_id'];
        return $this->ui_renderer->render_search_field($layout, $attrs, $source_args, $quick_ajax_id, $position);
    }
    public function render_ajax_response(array $post_args, array $post_attributes) {
        $query_args = $this->query_builder->wp_query_args($post_args, $post_attributes);
        if (!$query_args) {
            return false;
        }
        $layout_data   = $this->layout_builder->layout_customization($post_attributes, $this->global_options);
        $layout        = $layout_data['layout'];
        $attrs         = $layout_data['attributes'];
        $quick_ajax_id = $this->query_builder->get_quick_ajax_id();

        $query = new WP_Query($query_args);
        ob_start();
        if ($query->have_posts()) {
            $container_settings = [
                'quick_ajax_id' => $quick_ajax_id,
                'template_name' => $attrs[QAPL_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE],
                'global_options' => $this->global_options,
            ];
            $qapl_post_template = QAPL_Post_Template_Factory::get_template($container_settings);
            QAPL_Post_Template_Context::set_template($qapl_post_template);
            while ($query->have_posts()) {
                $query->the_post();
                $template_path = $layout[QAPL_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE];
                if (!$template_path || !file_exists($template_path)) {
                    QAPL_Post_Template_Context::clear_template();
                    wp_reset_postdata();
                    ob_end_clean();
                    wp_send_json_error(['message' => 'Quick Ajax Post Loader: Template file not found']);
                }
                include $template_path;
            }
            QAPL_Post_Template_Context::clear_template();
        } else {
            $container_settings = [
                'quick_ajax_id' => $quick_ajax_id,
                'template_name' => 'no-post-message',
                'global_options' => $this->global_options,
            ];
            $qapl_no_post_template = QAPL_Post_Template_Factory::get_template($container_settings);
            QAPL_Post_Template_Context::set_template($qapl_no_post_template);
            include $this->file_manager->get_no_posts_template();
            QAPL_Post_Template_Context::clear_template();
        }
        wp_reset_postdata();
        $output = ob_get_clean();

        $query_data = [
            'paged'           => intval($query->get('paged')),
            'max_num_pages'   => intval($query->max_num_pages),
            'found_posts'     => intval($query->found_posts),
            'post_count'      => intval($query->post_count),
            'infinite_scroll' => intval($attrs[QAPL_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL] ?? 0),
        ];
        $load_more_data   = $this->load_more_renderer->build_load_more_button($attrs, $post_args, $query_data, $quick_ajax_id);
        $load_more        = $load_more_data ? $this->load_more_renderer->render_load_more_button($load_more_data) : false;
        $show_end_message = $this->end_posts_renderer->build_end_of_posts_message(
            $load_more,
            intval($query->max_num_pages),
            $quick_ajax_id,
            intval($attrs[QAPL_Constants::ATTRIBUTE_SHOW_END_MESSAGE] ?? 0)
        );
        return [
            'output'           => $output,
            'args'             => $query_args,
            'load_more'        => $load_more,
            'show_end_message' => $show_end_message,
        ];
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
}