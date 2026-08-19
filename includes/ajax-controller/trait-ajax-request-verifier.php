<?php 
if (!defined('ABSPATH')) {
    exit;
}

trait QAPL_Ajax_Request_Verifier {
    // no nonce here on purpose - public read-only data, and nonce breaks with page cache
    protected static function verify_ajax_context(): void {
        if (!defined('DOING_AJAX') || !DOING_AJAX) {
            wp_send_json_error(['message' => 'Quick Ajax Post Loader: Not an AJAX request']);
        }
    }
    // admin actions - ajax check + nonce
    protected static function verify_request(): void {
        self::verify_ajax_context();
        if (!isset($_POST['nonce']) || !wp_verify_nonce(wp_unslash($_POST['nonce']), QAPL_Constants::NONCE_FORM_QUICK_AJAX_ACTION)) {
            wp_send_json_error(['message' => 'Quick Ajax Post Loader: Unauthorized request']);
        }
    }
}