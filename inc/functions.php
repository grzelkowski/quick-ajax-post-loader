<?php 
if (!defined('ABSPATH')) {
    exit;
}

function wpg_quick_ajax_post_grid($args, $attributes = null, $taxonomy = null, $meta_query = null) {
    $ajax_class = WPG_Quick_Ajax_Handler::get_instance();
    $ajax_class->quick_ajax_wp_query_args($args, $attributes);
    $ajax_class->quick_ajax_layout_customization($attributes);
    $output = '';
    if (isset($taxonomy)) {
        $output .= $ajax_class->quick_ajax_term_filter($taxonomy);
    }
    $output .= $ajax_class->quick_ajax_wp_query();
    echo wp_kses_post($output);
}

function wpg_quick_ajax_term_filter($args, $attributes, $taxonomy = null, $quick_ajax_id = null){
    $ajax_class = WPG_Quick_Ajax_Handler::get_instance();
    $ajax_class->quick_ajax_wp_query_args($args, $attributes);
    $ajax_class->quick_ajax_layout_customization($attributes);
    if(isset($taxonomy)){
        echo wp_kses_post($ajax_class->quick_ajax_term_filter($taxonomy, $quick_ajax_id));
    }
}

function wpg_get_quick_ajax_id(){
    $instance = WPG_Quick_Ajax_Handler::get_instance();
    return $instance->get_quick_ajax_id();
}

function wpg_quick_ajax_check_page_type($values){
    $screen = get_current_screen();
    $page_type = false;
    $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $post_type = filter_input(INPUT_GET, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if ($page) {
        $page_type = $page;
    } elseif ($post_type) {
        $page_type = $post_type;
    } elseif (isset($screen->post_type)) {
        $page_type = sanitize_text_field($screen->post_type);
    }
    if ($page_type) {
        if (is_array($values)) {
            return in_array($page_type, $values);
        } else {
            return $page_type === $values;
        }
    }
    return false;
}
?>