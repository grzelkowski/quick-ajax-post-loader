<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Creator_Post_Type {
    public static function init() {
        add_action('init', [__CLASS__, 'register']);
    }

    public static function register() {
        $labels = [
            'name'               => __('Quick Ajax Shortcodes', 'quick-ajax-post-loader'),
            'singular_name'      => __('Quick Ajax Shortcode', 'quick-ajax-post-loader'),
            'add_new'            => __('Add New', 'quick-ajax-post-loader'),
            'add_new_item'       => __('Add New Quick Ajax', 'quick-ajax-post-loader'),
            'edit_item'          => __('Edit Quick Ajax', 'quick-ajax-post-loader'),
            'new_item'           => __('New Quick Ajax', 'quick-ajax-post-loader'),
            'view_item'          => __('View Quick Ajax', 'quick-ajax-post-loader'),
            'search_items'       => __('Search Quick Ajax', 'quick-ajax-post-loader'),
            'not_found'          => __('No Items found', 'quick-ajax-post-loader'),
            'not_found_in_trash' => __('No Items found in trash', 'quick-ajax-post-loader'),
            'menu_name'          => __('Shortcodes', 'quick-ajax-post-loader'),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => QAPL_Constants::PLUGIN_MENU_SLUG,
            'query_var'          => true,
            'rewrite'            => ['slug' => QAPL_Constants::CPT_SHORTCODE_SLUG],
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 25,
            'supports'           => ['title'],
            'menu_icon'          => 'dashicons-editor-code',
        ];

        register_post_type(QAPL_Constants::CPT_SHORTCODE_SLUG, $args);
    }
}

QAPL_Creator_Post_Type::init();