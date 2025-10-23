<?php 
if (!defined('ABSPATH')) {
    exit;
}
//add get qapl_render_post_container - echo qapl_render_post_container()
//add get qapl_render_taxonomy_filter
function qapl_render_post_container($args, $attributes = null, $render_context = null, $meta_query = null) {
    $controller = new QAPL_Ajax_Controller();
    echo $controller->render_post_container($args, $attributes, $render_context, $meta_query);
}
//alias for backward compatibility
function qapl_quick_ajax_post_grid($args, $attributes) {
    return qapl_render_post_container($args, $attributes);
}

function qapl_render_taxonomy_filter($args, $attributes, $taxonomy = null) {
    $controller = new QAPL_Ajax_Controller();
    echo $controller->render_taxonomy_filter($args, $attributes, $taxonomy);
}
//alias for backward compatibility
function qapl_quick_ajax_term_filter($args, $attributes, $taxonomy) {
    return qapl_render_taxonomy_filter($args, $attributes, $taxonomy);
}

function qapl_render_sort_controls($args, $attributes, $sort_options) {
    $controller = new QAPL_Ajax_Controller();
    echo $controller->render_sort_controls($args, $attributes, $sort_options);
}

function qapl_quick_ajax_check_page_type($values){
    if (function_exists('get_current_screen')) {
        $screen = get_current_screen();
    }else{
        $screen = null;
    }
    $page_type = null;

    // check if the 'page' parameter exists in the URL and sanitize it
    if ($page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        $page_type = sanitize_text_field($page);
    }
    // check if the 'post_type' parameter exists in the URL and sanitize it
    elseif ($post_type = filter_input(INPUT_GET, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        $page_type = sanitize_text_field($post_type);
    }
    // if neither 'page' nor 'post_type' is set, try to get the post type from the current screen
    elseif (isset($screen->post_type)) {
        $page_type = sanitize_text_field($screen->post_type);
    }

    if ($page_type) {
        if (is_array($values)) {
            return in_array($page_type, $values, true); // use strict comparison
        } else {
            return $page_type === $values;
        }
    }
    return false;
}
