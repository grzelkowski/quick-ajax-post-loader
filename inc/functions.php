<?php 
if (!defined('ABSPATH')) {
    exit;
}
//add get qapl_render_post_container - echo qapl_render_post_container()
//add get qapl_render_taxonomy_filter
function qapl_render_post_container($args, $attributes = null, $render_context = null, $meta_query = null) {
    if (!class_exists('QAPL_Quick_Ajax_Handler') || !method_exists('QAPL_Quick_Ajax_Handler', 'get_instance')) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            //error_log('Quick Ajax Post Loader: QAPL_Quick_Ajax_Handler class or method get_instance not found');
        }
        return;
    }
    $ajax_class = QAPL_Quick_Ajax_Handler::get_instance();
    $attributes = $attributes ?? [];
    $ajax_class->wp_query_args($args, $attributes);
    $ajax_class->layout_customization($attributes);
    $output = '';
    $filter_wrapper_start = $filter_wrapper_end = '';
    if(isset($render_context['controls_container']) && $render_context['controls_container'] == 1){
        $filter_wrapper_start = '<div class="quick-ajax-controls-container">';
         $filter_wrapper_end = '</div>';
    }
    $output .= $filter_wrapper_start;
    if (isset($render_context['sort_options']) && !empty($render_context['sort_options']) && is_array($render_context['sort_options'])) {
        $output .= $ajax_class->render_sort_options($render_context['sort_options']);
    }
    if (isset($render_context['taxonomy']) && !empty($render_context['taxonomy']) && is_string($render_context['taxonomy'])) {
        $output .= $ajax_class->render_taxonomy_terms_filter($render_context['taxonomy']);
    }
    $output .= $filter_wrapper_end;
    $output .= $ajax_class->wp_query();
    echo $output;
   
}
//alias for backward compatibility
function qapl_quick_ajax_post_grid($args, $attributes) {
    return qapl_render_post_container($args, $attributes);
}

function qapl_render_taxonomy_filter($args, $attributes, $taxonomy = null){
    if (!class_exists('QAPL_Quick_Ajax_Handler') || !method_exists('QAPL_Quick_Ajax_Handler', 'get_instance')) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            //error_log('Quick Ajax Post Loader: QAPL_Quick_Ajax_Handler class or method get_instance not found');
        }
        return;
    }
    $ajax_class = QAPL_Quick_Ajax_Handler::get_instance();
    $attributes = $attributes ?? [];
    $ajax_class->wp_query_args($args, $attributes);
    $ajax_class->layout_customization($attributes);
    $selected_taxonomy = null;

    if (!empty($taxonomy) && is_string($taxonomy)) {
        $selected_taxonomy = $taxonomy;
    } elseif (!empty($args['selected_taxonomy']) && is_string($args['selected_taxonomy'])) {
        $selected_taxonomy = $args['selected_taxonomy'];
    }
    if(!empty($selected_taxonomy) && is_string($selected_taxonomy)) {
        echo $ajax_class->render_taxonomy_terms_filter($selected_taxonomy);
    }
}
//alias for backward compatibility
function qapl_quick_ajax_term_filter($args, $attributes, $taxonomy) {
    return qapl_render_taxonomy_filter($args, $attributes, $taxonomy);
}

function qapl_render_sort_controls($args, $attributes, $sort_options){
    if (!class_exists('QAPL_Quick_Ajax_Handler') || !method_exists('QAPL_Quick_Ajax_Handler', 'get_instance')) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            //error_log('Quick Ajax Post Loader: QAPL_Quick_Ajax_Handler class or method get_instance not found');
        }
        return;
    }
    $ajax_class = QAPL_Quick_Ajax_Handler::get_instance();
    $attributes = $attributes ?? [];
    $ajax_class->wp_query_args($args, $attributes);
    $ajax_class->layout_customization($attributes);
    if(!empty($sort_options) && is_array($sort_options)) {
        echo $ajax_class->render_sort_options($sort_options);
    }
}

function qapl_quick_ajax_get_quick_ajax_id(){
    if (!class_exists('QAPL_Quick_Ajax_Handler') || !method_exists('QAPL_Quick_Ajax_Handler', 'get_instance')) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            //error_log('Quick Ajax Post Loader: QAPL_Quick_Ajax_Handler class or method get_instance not found');
        }
        return '';
    }
    $instance = QAPL_Quick_Ajax_Handler::get_instance();
    return sanitize_text_field($instance->get_quick_ajax_id());
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
