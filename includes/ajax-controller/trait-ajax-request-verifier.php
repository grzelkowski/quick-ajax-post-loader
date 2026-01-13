<?php 
if (!defined('ABSPATH')) {
    exit;
}

trait QAPL_Ajax_Request_Verifier {
    protected static function verify_request(): void {
        if (!defined('DOING_AJAX') || !DOING_AJAX) {
            wp_send_json_error(['message' => 'Quick Ajax Post Loader: Not an AJAX request']);
        }

        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), QAPL_Constants::NONCE_FORM_QUICK_AJAX_ACTION)) {
            wp_send_json_error(['message' => 'Quick Ajax Post Loader: Unauthorized request']);
        }
    }
}