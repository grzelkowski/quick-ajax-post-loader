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
        $post_id = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);
        $post_type = get_post_type($post_id);

        if (empty($post_type)) {
            $post_type = filter_input(INPUT_GET, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (empty($post_type)) {
                $post_type = filter_input(INPUT_POST, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
        }

        if ($post_type === QAPL_Constants::CPT_SHORTCODE_SLUG) {
            new QAPL_CPT_Creator_Form(QAPL_Constants::SETTINGS_WRAPPER_ID, QAPL_Constants::DB_POSTMETA_SHORTCODE_SETTINGS, $post_type);
        }
    }
}
QAPL_Creator_Editor::init();
