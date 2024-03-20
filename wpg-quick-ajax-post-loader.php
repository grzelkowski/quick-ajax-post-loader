<?php
/*
* Plugin Name: Quick Ajax Post Loader
* Text Domain: wpg-quick-ajax-post-loader
* Domain Path: /languages
* Version: 1.0.1
* Description: Supercharge post loading with Quick Ajax Post Loader. Enhance user experience and optimize site performance using AJAX technology.
* Author: Pawel Grzelkowski
* License: GPLv2 or later
* Requires PHP: 5.6
* Requires at least: 5.6
*/

if (!defined('ABSPATH')) {
    exit;
}

function wpg_quick_ajax_post_loader_load_textdomain() {
    $locale = get_locale();
    $mo_file_path = WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/languages/' . $locale . '.mo';
    load_textdomain('wpg-quick-ajax-post-loader', $mo_file_path);
}
add_action('plugins_loaded', 'wpg_quick_ajax_post_loader_load_textdomain');

if (!class_exists('WPG_Quick_Ajax_Helper')) {
    require_once(plugin_dir_path( __FILE__ ).'inc/class-helper.php');    

        //style
        function wpg_quick_ajax_enqueue_styles() {
            wp_enqueue_style('quick-ajax-style', WPG_Quick_Ajax_Helper::quick_ajax_plugin_css_directory() . 'style.css', [], WPG_Quick_Ajax_Helper::quick_ajax_get_plugin_version());
        }
        add_action('wp_enqueue_scripts', 'wpg_quick_ajax_enqueue_styles');
        //admin style
        function wpg_quick_ajax_enqueue_admin_styles() {
            if (is_admin()) {
                wp_enqueue_style('quick-ajax-admin-style', WPG_Quick_Ajax_Helper::quick_ajax_plugin_css_directory() . 'admin-style.css', [], WPG_Quick_Ajax_Helper::quick_ajax_get_plugin_version());
            }
        }
        add_action('admin_enqueue_scripts', 'wpg_quick_ajax_enqueue_admin_styles');  

        // script
        function wpg_quick_ajax_enqueue_scripts() {
            wp_enqueue_script('quick-ajax-script', WPG_Quick_Ajax_Helper::quick_ajax_plugin_js_directory() . 'script.js', ['jquery'], WPG_Quick_Ajax_Helper::quick_ajax_get_plugin_version(), true);
            $quick_ajax_data = [
                'ajax_url' => admin_url('admin-ajax.php'),
                'helper' => [
                    'block_id' => WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_id(),
                    'filter_data_button' => WPG_Quick_Ajax_Helper::quick_ajax_term_filter_button_data_button(),
                    'load_more_data_button' => WPG_Quick_Ajax_Helper::quick_ajax_load_more_button_data_button(),
                ]
            ];
            wp_localize_script('quick-ajax-script', 'quick_ajax', $quick_ajax_data);
        }
        add_action('wp_enqueue_scripts', 'wpg_quick_ajax_enqueue_scripts');        
        //admin script
        function wpg_quick_ajax_enqueue_admin_scripts() {
            if (!is_admin()) {
                return;
            }
            $values = array('quick-ajax-creator','quick-ajax-settings');
            $page_type = wpg_quick_ajax_check_page_type($values);
            if ($page_type == true) {        
                wp_register_script('quick-ajax-admin-script', WPG_Quick_Ajax_Helper::quick_ajax_plugin_js_directory().'admin-script.js', ['jquery'], WPG_Quick_Ajax_Helper::quick_ajax_get_plugin_version(), true);
                wp_localize_script('quick-ajax-admin-script', 'quick_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
                wp_enqueue_script('quick-ajax-admin-script');            
                $quick_ajax_helper = array();
                //quickAjaxHandlePostTypeChange
                $quick_ajax_helper['quick_ajax_settings_wrapper'] = WPG_Quick_Ajax_Helper::quick_ajax_settings_wrapper_id();
                $quick_ajax_helper['quick_ajax_post_type'] = WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_post_type();
                $quick_ajax_helper['quick_ajax_taxonomy'] = WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_taxonomy();
                //functionGenerator
                $quick_ajax_helper['quick_ajax_css_style'] = WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_css_style();
                $quick_ajax_helper['grid_num_columns'] = WPG_Quick_Ajax_Helper::quick_ajax_layout_grid_num_columns();
                $quick_ajax_helper['post_item_template'] = WPG_Quick_Ajax_Helper::quick_ajax_layout_post_item_template();
                $quick_ajax_helper['post_item_template_default'] = WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_post_item_template_default_value();
                $quick_ajax_helper['taxonomy_filter_class'] = WPG_Quick_Ajax_Helper::quick_ajax_layout_taxonomy_filter_class();
                $quick_ajax_helper['container_class'] = WPG_Quick_Ajax_Helper::quick_ajax_layout_container_class();
                $quick_ajax_helper['load_more_posts'] = WPG_Quick_Ajax_Helper::quick_ajax_layout_load_more_posts();
                $quick_ajax_helper['loader_icon'] = WPG_Quick_Ajax_Helper::quick_ajax_layout_select_loader_icon();
                $quick_ajax_helper['loader_icon_default'] = WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_loader_icon_default_value();
                $quick_ajax_helper['quick_ajax_id'] = WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_id();
                wp_localize_script('quick-ajax-admin-script', 'quick_ajax_helper', $quick_ajax_helper);
            }
        }
        add_action('admin_enqueue_scripts', 'wpg_quick_ajax_enqueue_admin_scripts');
}else{
    function wpg_quick_ajax_admin_notice() {
        echo '<div class="error"><p><strong>Quick Ajax Post Loader</strong> is not functioning properly. Error: A class named <strong>"WPG_Quick_Ajax_Helper"</strong> already exists, which may have been declared by another plugin.</p></div>';
    }
    add_action('admin_notices', 'wpg_quick_ajax_admin_notice');
}