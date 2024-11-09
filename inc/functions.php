<?php 
if (!defined('ABSPATH')) {
    exit;
}
//add get qapl_quick_ajax_post_grid - echo qapl_quick_ajax_post_grid()
//add get qapl_quick_ajax_term_filter
function qapl_quick_ajax_post_grid($args, $attributes = null, $taxonomy = null, $meta_query = null) {
    if (!class_exists('QAPL_Quick_Ajax_Handler') || !method_exists('QAPL_Quick_Ajax_Handler', 'get_instance')) {
        error_log('Quick Ajax Post Loader: QAPL_Quick_Ajax_Handler class or method get_instance not found');
        return;
    }
    $ajax_class = QAPL_Quick_Ajax_Handler::get_instance();
    $attributes = $attributes ?? [];
    $ajax_class->wp_query_args($args, $attributes);
    $ajax_class->layout_customization($attributes);
    $output = '';
    if (!empty($taxonomy) && is_string($taxonomy)) {
        $output .= $ajax_class->term_filter($taxonomy);
    }
    $output .= $ajax_class->wp_query();
    echo wp_kses_post($output);
   
}

function qapl_quick_ajax_term_filter($args, $attributes, $taxonomy = null, $quick_ajax_id = null){
    if (!class_exists('QAPL_Quick_Ajax_Handler') || !method_exists('QAPL_Quick_Ajax_Handler', 'get_instance')) {
        error_log('Quick Ajax Post Loader: QAPL_Quick_Ajax_Handler class or method get_instance not found');
        return;
    }
    $ajax_class = QAPL_Quick_Ajax_Handler::get_instance();
    $attributes = $attributes ?? [];
    $ajax_class->wp_query_args($args, $attributes);
    $ajax_class->layout_customization($attributes);
    if(!empty($taxonomy) && is_string($taxonomy)) {
        echo wp_kses_post($ajax_class->term_filter($taxonomy, $quick_ajax_id));
    }
}

function qapl_get_quick_ajax_id(){
    if (!class_exists('QAPL_Quick_Ajax_Handler') || !method_exists('QAPL_Quick_Ajax_Handler', 'get_instance')) {
        error_log('Quick Ajax Post Loader: QAPL_Quick_Ajax_Handler class or method get_instance not found');
        return '';
    }
    $instance = QAPL_Quick_Ajax_Handler::get_instance();
    return sanitize_text_field($instance->get_quick_ajax_id());
}

function qapl_quick_ajax_check_page_type($values){
    $screen = null;
    if (function_exists('get_current_screen')) {
        $screen = get_current_screen();
    }

    $page_type = false;
    $page = sanitize_text_field(filter_input(INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $post_type = sanitize_text_field(filter_input(INPUT_GET, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

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
