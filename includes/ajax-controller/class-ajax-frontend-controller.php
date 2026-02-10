<?php 
if (!defined('ABSPATH')) {
    exit;
}
// phpcs:disable WordPress.Security.NonceVerification.Missing -- nonce verified in verify_request
final class QAPL_Ajax_Frontend_Controller {
    use QAPL_Ajax_Request_Verifier;
    
    public static function register(): void {
        // load posts
        add_action('wp_ajax_qapl_action_load_posts', [self::class, 'load_posts']);
        add_action('wp_ajax_nopriv_qapl_action_load_posts', [self::class, 'load_posts']);
    }
    public static function load_posts(): void {
        self::verify_request();
        // validate and sanitize input
        if (empty($_POST['args'])) {
            wp_send_json_error(['message' => 'Quick Ajax Post Loader: Invalid request, Missing arguments.']);
        } else {
            $global_options     = get_option(QAPL_Constants::GLOBAL_OPTIONS_NAME, []);
            $helper             = new QAPL_Ajax_Helper();
            $file_manager       = new QAPL_File_Manager();
            $ajax_builder       = new QAPL_Ajax_Query_Builder();
            $layout_builder     = new QAPL_Ajax_Layout_Builder($file_manager, $helper);
            $ui_renderer        = new QAPL_Ajax_Filter_Menu_Renderer($file_manager, $helper, $global_options);
            $load_more_renderer = new QAPL_Ajax_Load_More_Renderer($file_manager,$ui_renderer, $helper);
            $end_posts_renderer = new QAPL_Ajax_End_Message_Renderer($file_manager);
            
            // Sanitize 'args'
            $post_args = [];
            if (isset($_POST['args'])) {
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized in sanitize_json_to_array()
                $post_args = $helper->sanitize_json_to_array(wp_unslash($_POST['args'])); // Sanitize JSON to array
            }

            // Sanitize 'attributes'
            $post_attributes = [];
            if (isset($_POST['attributes'])) {
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized in sanitize_json_to_array()
                $post_attributes = $helper->sanitize_json_to_array(wp_unslash($_POST['attributes'])); // Sanitize JSON to array
            }

            $source_args = $post_args;
            $query_args = $ajax_builder->wp_query_args($source_args, $post_attributes);
            if (!$query_args) {
                wp_send_json_error(['message' => 'Quick Ajax Post Loader: Invalid query arguments.']);
            }

            // prepare layout data
            $layout_data = $layout_builder->layout_customization($post_attributes, $global_options);
            $layout = $layout_data['layout'];
            $attrs = $layout_data['attributes'];

            $quick_ajax_id = $ajax_builder->get_quick_ajax_id();

            // query + render
            $query = new WP_Query($query_args);

            ob_start();        
            if ($query->have_posts()) {
                $container_settings = [
                    'quick_ajax_id' => $quick_ajax_id,
                    'template_name' => $attrs[QAPL_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE],
                ];
                $qapl_post_template = QAPL_Post_Template_Factory::get_template($container_settings);
                QAPL_Post_Template_Context::set_template($qapl_post_template);
                while ($query->have_posts()) {
                    $query->the_post();
                    $template_path = $layout[QAPL_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE];
                    if (!$template_path || !file_exists($template_path)) {
                        wp_send_json_error(['message' => 'Quick Ajax Post Loader: Template file not found']);
                    }
                    include $template_path;
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
                include $file_manager->get_no_posts_template();
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
            $load_more_data = $load_more_renderer->build_load_more_button($attrs,$source_args,$query_data,$quick_ajax_id);            
            $load_more = $load_more_data ? $load_more_renderer->render_load_more_button($load_more_data) : false;

            $show_end_message = $end_posts_renderer->build_end_of_posts_message($load_more, intval($query->max_num_pages), $quick_ajax_id, intval($attrs[QAPL_Constants::ATTRIBUTE_SHOW_END_MESSAGE] ?? 0));
            
            //$output = $ajax_class->replace_placeholders($output);
            wp_send_json_success([
                'output' => $output,
                'args' => $query_args,
                'load_more' => $load_more,
                'show_end_message' => $show_end_message,
            ]);
        }
        wp_die();
    }
}
// phpcs:enable WordPress.Security.NonceVerification.Missing
QAPL_Ajax_Frontend_Controller::register();