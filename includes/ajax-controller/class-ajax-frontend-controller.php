<?php 
if (!defined('ABSPATH')) {
    exit;
}
// fresh instance is correct here: one render per AJAX request, quick_ajax_id comes from request attributes

// phpcs:disable WordPress.Security.NonceVerification.Missing -- public read-only endpoint, nonce intentionally not required (cache-safe)
final class QAPL_Ajax_Frontend_Controller {
    use QAPL_Ajax_Request_Verifier;
    
    public static function register(): void {
        // load posts
        add_action('wp_ajax_qapl_action_load_posts', [self::class, 'load_posts']);
        add_action('wp_ajax_nopriv_qapl_action_load_posts', [self::class, 'load_posts']);
    }
    public static function load_posts(): void {
        // no nonce here on purpose - public read-only data, and nonce breaks with page cache
        self::verify_ajax_context();
        if (empty($_POST['args'])) {
            wp_send_json_error(['message' => 'Quick Ajax Post Loader: Invalid request, Missing arguments.']);
        }
        $helper = new QAPL_Ajax_Helper();
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized in sanitize_json_to_array()
        $post_args = $helper->sanitize_json_to_array(wp_unslash($_POST['args']));
        $post_attributes = [];
        if (isset($_POST['attributes'])) {
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized in sanitize_json_to_array()
            $post_attributes = $helper->sanitize_json_to_array(wp_unslash($_POST['attributes']));
        }
        $render = new QAPL_Ajax_Frontend_Render();
        $result = $render->render_ajax_response($post_args, $post_attributes);
        if (!$result) {
            wp_send_json_error(['message' => 'Quick Ajax Post Loader: Invalid query arguments.']);
        }
        wp_send_json_success($result);
    }
}
// phpcs:enable WordPress.Security.NonceVerification.Missing
QAPL_Ajax_Frontend_Controller::register();