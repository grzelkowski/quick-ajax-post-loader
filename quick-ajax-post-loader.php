<?php
/*
* Plugin Name: Quick Ajax Post Loader
* Plugin URI: https://github.com/grzelkowski/quick-ajax-post-loader/releases
* Text Domain: quick-ajax-post-loader
* Domain Path: /languages
* Version: 1.7.1
* Description: Supercharge post loading with Quick Ajax Post Loader. Enhance user experience and optimize site performance using AJAX technology.
* Author: Pawel Grzelkowski
* Author URI: https://grzelkowski.com
* License: GPLv2 or later
* Requires PHP: 7.4
* Requires at least: 5.6
* Tested up to: 6.8
*/
if (!defined('ABSPATH')) {
    exit;
}
require_once plugin_dir_path(__FILE__) . 'inc/class-activator.php';
register_activation_hook(__FILE__, ['QAPL_Quick_Ajax_Activator', 'activate']);

function qapl_quick_ajax_load_textdomain() {
    load_plugin_textdomain('quick-ajax-post-loader', false, dirname(plugin_basename(__FILE__)) . '/languages/');    
}
add_action('plugins_loaded', 'qapl_quick_ajax_load_textdomain');

function qapl_initialize_plugin() {
    if (class_exists('QAPL_Quick_Ajax_Helper')) {
        function qapl_quick_ajax_admin_notice() {
            echo '<div class="error"><p><strong>Quick Ajax Post Loader:</strong> A class named <strong>"QAPL_Quick_Ajax_Helper"</strong> already exists, which may have been declared by another plugin.</p></div>';
        }
        add_action('admin_notices', 'qapl_quick_ajax_admin_notice');
    }else{
        require_once(plugin_dir_path( __FILE__ ).'inc/class-helper.php');
        $qapl_helper = QAPL_Quick_Ajax_Helper::get_instance();
        // Register frontend styles and scripts
        add_action('wp_enqueue_scripts', [$qapl_helper, 'enqueue_frontend_styles_and_scripts']);    
        // Register admin styles and scripts
        add_action('admin_enqueue_scripts', [$qapl_helper, 'enqueue_admin_styles_and_scripts']);
    }
}
add_action('plugins_loaded', 'qapl_initialize_plugin');