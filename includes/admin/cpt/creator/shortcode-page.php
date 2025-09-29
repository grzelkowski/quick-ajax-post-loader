<?php 
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('QAPL_Quick_Ajax_Form_Creator')) {


  //  $post_id = isset($_GET['post']) ? $_GET['post'] : '';
    $post_id = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);
    $post_type = get_post_type($post_id);
    if (empty($post_type)) {
        $post_type = filter_input(INPUT_GET, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($post_type)) {
            $post_type = filter_input(INPUT_POST, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
    }
    if ($post_type === QAPL_Quick_Ajax_Constants::CPT_SHORTCODE_SLUG) {
        $form = new QAPL_Quick_Ajax_Form_Creator(QAPL_Quick_Ajax_Constants::SETTINGS_WRAPPER_ID, QAPL_Quick_Ajax_Constants::DB_POSTMETA_SHORTCODE_SETTINGS, $post_type);
    }
}