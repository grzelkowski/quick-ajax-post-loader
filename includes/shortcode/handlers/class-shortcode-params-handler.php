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
            'display_show_all_button' => '',
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
        //sanitize and cast numeric and boolean parameters only if they exist
        if (isset($params['id'])) {
            $params['id'] = intval($params['id']);
        }
        if (isset($params['ignore_sticky_posts'])) {
            $params['ignore_sticky_posts'] = filter_var($params['ignore_sticky_posts'], FILTER_VALIDATE_BOOLEAN);
        }
        if (isset($params['ajax_initial_load'])) {
            $params['ajax_initial_load'] = filter_var($params['ajax_initial_load'], FILTER_VALIDATE_BOOLEAN);
        }
        if (isset($params['infinite_scroll'])) {
            $params['infinite_scroll'] = filter_var($params['infinite_scroll'], FILTER_VALIDATE_BOOLEAN);
        }
        if (isset($params['show_end_message'])) {
            $params['show_end_message'] = filter_var($params['show_end_message'], FILTER_VALIDATE_BOOLEAN);
        }
        if (isset($params['excluded_post_ids'])) {
            $params['excluded_post_ids'] = array_filter(
                array_map('intval', explode(',', $params['excluded_post_ids']))
            );
        }
        if (isset($params['posts_per_page'])) {
            $params['posts_per_page'] = intval($params['posts_per_page']);
        }
        if (isset($params['display_show_all_button'])) {
            $params['display_show_all_button'] = filter_var($params['display_show_all_button'], FILTER_VALIDATE_BOOLEAN);
        }
        if (isset($params['quick_ajax_css_style'])) {
            $params['quick_ajax_css_style'] = intval($params['quick_ajax_css_style']);
        }
        if (isset($params['grid_num_columns'])) {
            $params['grid_num_columns'] = intval($params['grid_num_columns']);
        }
        if (isset($params['load_more_posts'])) {
            $params['load_more_posts'] = intval($params['load_more_posts']);
        }
        if (isset($params['quick_ajax_id'])) {
            $params['quick_ajax_id'] = intval($params['quick_ajax_id']);
        }
        //sanitize text parameters only if they exist
        if (isset($params['post_type'])) {
            $params['post_type'] = sanitize_text_field($params['post_type']);
        }
        if (isset($params['order'])) {
            $params['order'] = sanitize_text_field($params['order']);
        }
        if (isset($params['orderby'])) {
            $params['orderby'] = sanitize_text_field($params['orderby']);
        }
        //if (isset($params['sort_options'])) {
        //    $params['sort_options'] = sanitize_text_field($params['sort_options']);
        //}
        if (isset($params['post_item_template'])) {
            $params['post_item_template'] = sanitize_text_field($params['post_item_template']);
        }
        if (isset($params['taxonomy_filter_class'])) {
            $params['taxonomy_filter_class'] = sanitize_html_class($params['taxonomy_filter_class']);
        }
        if (isset($params['container_class'])) {
            $params['container_class'] = sanitize_html_class($params['container_class']);
        }
        if (isset($params['loader_icon'])) {
            $params['loader_icon'] = sanitize_text_field($params['loader_icon']);
        }
        if (isset($params['quick_ajax_taxonomy'])) {
            $params['quick_ajax_taxonomy'] = sanitize_text_field($params['quick_ajax_taxonomy']);
        }
        //if (isset($params['manual_selected_terms'])) {
        //    $terms = array_map(
        //        'intval',
        //        explode(',', $params['manual_selected_terms'])
        //    );
        //    $params['manual_selected_terms'] = array_values(
        //        array_filter($terms)
        //    );
        //}
        //return sanitized data
        return $params;
    }
}