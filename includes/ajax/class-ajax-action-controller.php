<?php 
if (!defined('ABSPATH')) {
    exit;
}
final class QAPL_Quick_Ajax_Action_Controller {

    public static function register(): void {
        // load posts
        add_action('wp_ajax_qapl_quick_ajax_load_posts', [self::class, 'load_posts']);
        add_action('wp_ajax_nopriv_qapl_quick_ajax_load_posts', [self::class, 'load_posts']);

        // get taxonomies
        add_action('wp_ajax_qapl_quick_ajax_get_taxonomies_by_post_type', [self::class, 'get_taxonomies_by_post_type']);
        add_action('wp_ajax_nopriv_qapl_quick_ajax_get_taxonomies_by_post_type', [self::class, 'get_taxonomies_by_post_type']);

        // get terms
        add_action('wp_ajax_qapl_quick_ajax_get_terms_by_taxonomy', [self::class, 'get_terms_by_taxonomy']);
        add_action('wp_ajax_nopriv_qapl_quick_ajax_get_terms_by_taxonomy', [self::class, 'get_terms_by_taxonomy']);
    }

    public static function load_posts(): void {
        self::verify_request();
        // validate and sanitize input
        if (empty($_POST['args'])) {
            wp_send_json_error(['message' => 'Quick Ajax Post Loader: Invalid request, Missing arguments.']);
        } else {

            $helper = new QAPL_Ajax_Helper();

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

            // Sanitize 'button_type'
            $button_type = '';
            if (isset($_POST['button_type'])) {
                $button_type = sanitize_text_field(wp_unslash($_POST['button_type'])); // Sanitize string input
            }

            if ($button_type === 'quick-ajax-load-more-button' && isset($post_attributes['load_more_posts']) && $post_attributes['load_more_posts'] > 0) {
                //investigate, load more works good without this parameter
            //   $args['posts_per_page'] = intval($attributes['load_more_posts']);
            }
            
            $ajax_builder = new QAPL_Ajax_Query_Builder();
            $action_args = $post_args;
            $query_args = $ajax_builder->wp_query_args($action_args, $post_attributes);
            if (!$query_args) {
                wp_send_json_error(['message' => 'Quick Ajax Post Loader: Invalid query arguments.']);
            }

            // get global components
            $file_manager = new QAPL_Quick_Ajax_File_Manager();
            $ui_renderer = new QAPL_Ajax_UI_Renderer($file_manager, get_option(QAPL_Quick_Ajax_Constants::GLOBAL_OPTIONS_NAME, []));
            $layout_renderer = new QAPL_Ajax_Layout_Renderer($file_manager, $ui_renderer);

            // prepare layout data
            $layout_data = $layout_renderer->layout_customization($post_attributes, get_option(QAPL_Quick_Ajax_Constants::GLOBAL_OPTIONS_NAME, []));
            $layout = $layout_data['layout'];
            $attrs = $layout_data['attributes'];
            $ajax_initial_load = $layout_data['ajax_initial_load'];

            $quick_ajax_id = $ajax_builder->get_quick_ajax_id();

            // query + render
            $query = new WP_Query($query_args);

            ob_start();        
            if ($query->have_posts()) {
                $container_settings = [
                    'quick_ajax_id' => $quick_ajax_id,
                    'template_name' => $attrs[QAPL_Quick_Ajax_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE],
                ];
                $qapl_post_template = QAPL_Post_Template_Factory::get_template($container_settings);
                QAPL_Post_Template_Context::set_template($qapl_post_template);
                while ($query->have_posts()) {
                    $query->the_post();
                    $template_path = $layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE];
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
                'infinite_scroll' => intval($attrs[QAPL_Quick_Ajax_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL] ?? 0),
            ];
            $load_more = $layout_renderer->load_more_button($query_args, $attrs, $action_args, $query_data, $quick_ajax_id);
            $show_end_message = $layout_renderer->render_end_of_posts_message($load_more, intval($query->max_num_pages), $quick_ajax_id, intval($attrs[QAPL_Quick_Ajax_Constants::ATTRIBUTE_SHOW_END_MESSAGE] ?? 0));

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
    public static function get_taxonomies_by_post_type(): void {
        self::verify_request();
        if (empty($_POST['post_type'])) {
            wp_send_json_error(['message' => 'Quick Ajax Post Loader: Invalid request, missing post type.']);
        }
        $post_type = sanitize_text_field(wp_unslash($_POST['post_type']));
        $taxonomies = get_object_taxonomies($post_type, 'objects');

        ob_start();
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                echo '<option value="' . esc_attr($taxonomy->name) . '">' . esc_html($taxonomy->label) . '</option>';
            }
        } else {
            echo '<option value="0">' . esc_html__('No taxonomy found', 'quick-ajax-post-loader') . '</option>';
        }
        $output = ob_get_clean();

        wp_send_json_success($output);
        wp_die();
    }
    public static function get_terms_by_taxonomy(): void {
        self::verify_request();
        //return info if No taxonomy
        if (empty($_POST['taxonomy']) || $_POST['taxonomy'] === '0') {
            ob_start();
            echo '<div class="quick-ajax-multiselect-option"><span class="no-options">' . esc_html__('No taxonomy available', 'quick-ajax-post-loader') . '</span></div>';
            $output = ob_get_clean();
            wp_send_json_success($output);
            wp_die();
        }
        $taxonomy = sanitize_text_field(wp_unslash($_POST['taxonomy']));
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]);
        //get checked terms if saved in post_meta
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $saved_terms = [];
        if ($post_id > 0) {
            // get saved terms from post meta
            $post_meta = get_post_meta($post_id, QAPL_Quick_Ajax_Constants::DB_POSTMETA_SHORTCODE_SETTINGS, true);
            $post_meta_values = maybe_unserialize($post_meta);
            // if terms exist in post meta
            if (is_array($post_meta_values) && isset($post_meta_values['qapl_manual_selected_terms'])) {
                $saved_terms = array_map('intval', (array) $post_meta_values['qapl_manual_selected_terms']);
            }
        }
        ob_start();
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                ?>
                <div class="quick-ajax-multiselect-option">
                    <label>
                        <input type="checkbox" name="qapl_manual_selected_terms[]" value="<?php echo esc_attr($term->term_id); ?>" <?php checked(in_array($term->term_id, $saved_terms)); ?>>
                        <?php echo esc_html($term->name); ?>
                    </label>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="quick-ajax-multiselect-option">
                <span class="no-options"><?php echo esc_html__('No terms found', 'quick-ajax-post-loader'); ?></span>
            </div>
            <?php
        }
        $output = ob_get_clean();
        wp_send_json_success($output);
        wp_die();
    }
    private static function verify_request(): void {
        if (!defined('DOING_AJAX') || !DOING_AJAX) {
            wp_send_json_error(['message' => 'Quick Ajax Post Loader: Not an AJAX request']);
        }
        // nonce verification
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])),QAPL_Quick_Ajax_Constants::NONCE_FORM_QUICK_AJAX_ACTION)) {
            wp_send_json_error(['message' => 'Quick Ajax Post Loader: Unauthorized request']);
        }
    }
}
QAPL_Quick_Ajax_Action_Controller::register();