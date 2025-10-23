<?php 
if (!defined('ABSPATH')) {
    exit;
}
class QAPL_Shortcode_Params_Handler {
    public static function get_params($params) {
        $defaults = array(
            'id' => '',
            'excluded_post_ids' => '',
            'post_type' => '',
            'posts_per_page' => '',
            'order' => '',
            'orderby' => '',
            'sort_options' => '',
            'quick_ajax_css_style' => '',
            'grid_num_columns' => '',
            'post_item_template' => '',
            'taxonomy_filter_class' => '',
            'container_class' => '',
            'load_more_posts' => '',
            'loader_icon' => '',
            'quick_ajax_id' => '',
            'quick_ajax_taxonomy' => '',
            //'manual_selected_terms' => '',
            'ignore_sticky_posts' => '',
            'ajax_initial_load' => '',
            'infinite_scroll' => '',
            'show_end_message' => '',
        );
        //retain only the keys that match the defaults
        $params = array_intersect_key($params, $defaults);
        //merge provided parameters with defaults
        $params = shortcode_atts($defaults, $params, 'quick-ajax');        

        //sanitize and cast numeric and boolean parameters
        $params['id'] = intval($params['id']);
        $params['ignore_sticky_posts'] = filter_var($params['ignore_sticky_posts'], FILTER_VALIDATE_BOOLEAN);
        $params['ajax_initial_load'] = filter_var($params['ajax_initial_load'], FILTER_VALIDATE_BOOLEAN);
        $params['infinite_scroll'] = filter_var($params['infinite_scroll'], FILTER_VALIDATE_BOOLEAN);
        $params['show_end_message'] = filter_var($params['show_end_message'], FILTER_VALIDATE_BOOLEAN);
        $params['excluded_post_ids'] = array_filter(array_map('intval', explode(',', $params['excluded_post_ids'])));
        $params['posts_per_page'] = intval($params['posts_per_page']);
        $params['quick_ajax_css_style'] = intval($params['quick_ajax_css_style']);
        $params['grid_num_columns'] = intval($params['grid_num_columns']);
        $params['load_more_posts'] = intval($params['load_more_posts']);
        $params['quick_ajax_id'] = intval($params['quick_ajax_id']);

        //sanitize text parameters
        $params['post_type'] = sanitize_text_field($params['post_type']);
        $params['order'] = sanitize_text_field($params['order']);
        $params['orderby'] = sanitize_text_field($params['orderby']);
        //$params['sort_options'] = sanitize_text_field($params['sort_options']);
        $params['post_item_template'] = sanitize_text_field($params['post_item_template']);
        $params['taxonomy_filter_class'] = sanitize_html_class($params['taxonomy_filter_class']);
        $params['container_class'] = sanitize_html_class($params['container_class']);
        $params['loader_icon'] = sanitize_text_field($params['loader_icon']);
        $params['quick_ajax_taxonomy'] = sanitize_text_field($params['quick_ajax_taxonomy']);
        //$params['manual_selected_terms'] = (!empty($params['quick_ajax_taxonomy'])) ? array_filter(array_map('intval', explode(',', $params['manual_selected_terms']))) : '';

        //return sanitized data
        return $params;
    }
}
