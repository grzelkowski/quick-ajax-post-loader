<?php
if (!defined('ABSPATH')) {
    exit;
}
/*
* Plugin Name: Quick Ajax Post Loader
* Plugin URI: https://github.com/grzelkowski/quick-ajax-post-loader/releases
* Text Domain: quick-ajax-post-loader
* Domain Path: /languages
* Version: 1.2.1
* Description: Supercharge post loading with Quick Ajax Post Loader. Enhance user experience and optimize site performance using AJAX technology.
* Author: Pawel Grzelkowski
* Author URI: https://grzelkowski.com
* License: GPLv2 or later
* Requires PHP: 5.6
* Requires at least: 5.6
* Tested up to: 6.6.2
*/

function qapl_quick_ajax_load_textdomain() {
    $locale = get_locale();
    $mo_file_path = esc_url(WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/languages/' . sanitize_file_name($locale) . '.mo');
    load_textdomain('quick-ajax-post-loader', $mo_file_path);
}

add_action('plugins_loaded', 'qapl_quick_ajax_load_textdomain');

if (!class_exists('QAPL_Quick_Ajax_Helper')) {
    require_once(plugin_dir_path( __FILE__ ).'inc/class-helper.php');    

        //style
        function qapl_quick_ajax_enqueue_styles() {
            if (!is_admin()) {
                wp_enqueue_style('qapl-quick-ajax-style', QAPL_Quick_Ajax_Helper::get_plugin_css_directory() . 'style.css', [], QAPL_Quick_Ajax_Helper::get_plugin_version());
            }
        }
        add_action('wp_enqueue_scripts', 'qapl_quick_ajax_enqueue_styles');
        //admin style
        function qapl_quick_ajax_enqueue_admin_styles() {
            if (is_admin()) {
                wp_enqueue_style('qapl-quick-ajax-admin-style', QAPL_Quick_Ajax_Helper::get_plugin_css_directory() . 'admin-style.css', [], QAPL_Quick_Ajax_Helper::get_plugin_version());
            }
        }
        add_action('admin_enqueue_scripts', 'qapl_quick_ajax_enqueue_admin_styles');
        // script
        function qapl_quick_ajax_enqueue_scripts() {
            if (!is_admin()) {
                wp_enqueue_script('qapl-quick-ajax-script', QAPL_Quick_Ajax_Helper::get_plugin_js_directory() . 'script.js', ['jquery'], QAPL_Quick_Ajax_Helper::get_plugin_version(), true);
                $quick_ajax_data = [
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce(QAPL_Quick_Ajax_Helper::wp_nonce_form_quick_ajax_action()),
                    'helper' => [
                        'block_id' => QAPL_Quick_Ajax_Helper::layout_quick_ajax_id(),
                        'filter_data_button' => QAPL_Quick_Ajax_Helper::term_filter_button_data_button(),
                        'load_more_data_button' => QAPL_Quick_Ajax_Helper::load_more_button_data_button(),
                    ]
                ];
                wp_localize_script('qapl-quick-ajax-script', 'qapl_quick_ajax_helper', $quick_ajax_data);
            }
        }
        add_action('wp_enqueue_scripts', 'qapl_quick_ajax_enqueue_scripts');        
        //admin script
        function qapl_quick_ajax_enqueue_admin_scripts() {
            $values = array(QAPL_Quick_Ajax_Helper::cpt_shortcode_slug(),QAPL_Quick_Ajax_Helper::settings_page_slug());
            $page_type = qapl_quick_ajax_check_page_type($values);
            if ($page_type == true) {        
                wp_register_script('qapl-quick-ajax-admin-script', QAPL_Quick_Ajax_Helper::get_plugin_js_directory().'admin-script.js', ['jquery'], QAPL_Quick_Ajax_Helper::get_plugin_version(), true);
                $qapl_quick_ajax_data = array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce(QAPL_Quick_Ajax_Helper::wp_nonce_form_quick_ajax_action()),
                    'quick_ajax_settings_wrapper' => QAPL_Quick_Ajax_Helper::settings_wrapper_id(),
                    'quick_ajax_post_type' => QAPL_Quick_Ajax_Helper::shortcode_page_select_post_type(),
                    'quick_ajax_taxonomy' => QAPL_Quick_Ajax_Helper::shortcode_page_select_taxonomy(),
                    'quick_ajax_css_style' => QAPL_Quick_Ajax_Helper::layout_quick_ajax_css_style(),
                    'grid_num_columns' => QAPL_Quick_Ajax_Helper::layout_grid_num_columns(),
                    'post_item_template' => QAPL_Quick_Ajax_Helper::layout_post_item_template(),
                    'post_item_template_default' => QAPL_Quick_Ajax_Helper::shortcode_page_layout_post_item_template_default_value(),
                    'taxonomy_filter_class' => QAPL_Quick_Ajax_Helper::layout_taxonomy_filter_class(),
                    'container_class' => QAPL_Quick_Ajax_Helper::layout_container_class(),
                    'load_more_posts' => QAPL_Quick_Ajax_Helper::layout_load_more_posts(),
                    'loader_icon' => QAPL_Quick_Ajax_Helper::layout_select_loader_icon(),
                    'loader_icon_default' => QAPL_Quick_Ajax_Helper::shortcode_page_select_loader_icon_default_value(),
                    'quick_ajax_id' => QAPL_Quick_Ajax_Helper::layout_quick_ajax_id(),
                );
                wp_localize_script('qapl-quick-ajax-admin-script', 'qapl_quick_ajax_helper', $qapl_quick_ajax_data);                
                wp_enqueue_script('qapl-quick-ajax-admin-script');
            }
        }
        add_action('admin_enqueue_scripts', 'qapl_quick_ajax_enqueue_admin_scripts');
}else{
    function qapl_quick_ajax_admin_notice() {
        echo '<div class="error"><p><strong>Quick Ajax Post Loader:</strong> A class named <strong>"QAPL_Quick_Ajax_Helper"</strong> already exists, which may have been declared by another plugin.</p></div>';
    }
    add_action('admin_notices', 'qapl_quick_ajax_admin_notice');
}