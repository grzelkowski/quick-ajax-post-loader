<?php 
if (!defined('ABSPATH')) {
    exit;
}
function wpg_quick_ajax_post_grid($args, $attributes = null, $taxonomy = null, $meta_query = null) {
    $ajax_class = WPG_Quick_Ajax_Handler::get_instance();
    $ajax_class->quick_ajax_wp_query_args($args, $attributes);
    $ajax_class->quick_ajax_layout_customization($attributes);
    if(isset($taxonomy)){
       echo $ajax_class->quick_ajax_term_filter($taxonomy);
    }
    echo $ajax_class->quick_ajax_wp_query();
}

function wpg_quick_ajax_term_filter($args, $attributes, $taxonomy = null, $quick_ajax_id = null){
    $ajax_class = WPG_Quick_Ajax_Handler::get_instance();
    $ajax_class->quick_ajax_wp_query_args($args, $attributes);
    $ajax_class->quick_ajax_layout_customization($attributes);
    if(isset($taxonomy)){
       echo $ajax_class->quick_ajax_term_filter($taxonomy, $quick_ajax_id);
    }
}

function wpg_get_quick_ajax_id() {
    $instance = WPG_Quick_Ajax_Handler::get_instance();
    return $instance->get_quick_ajax_id();
}

function wpg_quick_ajax_check_page_type($values){
    $screen = get_current_screen();
    $page_type = false;
    if (isset($_GET['page'])) {
        $page_type = sanitize_text_field($_GET['page']); 
    } elseif (isset($_GET['post_type'])) {
        $page_type = sanitize_text_field($_GET['post_type']); 
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