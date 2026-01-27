<?php
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Creator_Editor {
    public static function init() {
        add_action('load-post.php', [__CLASS__, 'maybe_init_form']);
        add_action('load-post-new.php', [__CLASS__, 'maybe_init_form']);
    }

    public static function maybe_init_form() {       
        $screen = get_current_screen();
        if (!$screen || $screen->post_type !== QAPL_Constants::CPT_SHORTCODE_SLUG) {
            return;
        }
        new QAPL_CPT_Creator_Form(QAPL_Constants::SETTINGS_WRAPPER_ID, QAPL_Constants::DB_POSTMETA_SHORTCODE_SETTINGS, $screen->post_type
    );
    }
}
QAPL_Creator_Editor::init();
