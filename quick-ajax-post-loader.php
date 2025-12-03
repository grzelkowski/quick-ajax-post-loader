<?php
/*
* Plugin Name: Quick Ajax Post Loader
* Plugin URI: https://github.com/grzelkowski/quick-ajax-post-loader/releases
* Text Domain: quick-ajax-post-loader
* Domain Path: /languages
* Version: 1.8.6
* Description: Supercharge post loading with Quick Ajax Post Loader. Enhance user experience and optimize site performance using AJAX technology.
* Author: Pawel Grzelkowski
* Author URI: https://grzelkowski.com
* License: GPLv2 or later
* Requires PHP: 7.4
* Requires at least: 5.6
* Tested up to: 6.9
*/
if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/resources/class-constants.php';

if (version_compare(PHP_VERSION, QAPL_Constants::PLUGIN_MINIMUM_PHP_VERSION, '<')) {
    add_action( 'admin_notices', static function () {
        $message = sprintf(
            // translators: %1$s is the required PHP version, %2$s is the current PHP version.
            __('This plugin requires PHP %1$s or higher. Your server is running %2$s.', 'quick-ajax-post-loader'),
            QAPL_Constants::PLUGIN_MINIMUM_PHP_VERSION,
            PHP_VERSION
        );
        echo '<div class="notice notice-error"><p><strong>Quick Ajax Post Loader:</strong> '.esc_html($message).'</p></div>';
    } );
    return;
}

global $wp_version;
if (version_compare($wp_version, QAPL_Constants::PLUGIN_MINIMUM_WP_VERSION, '<')) {
    add_action( 'admin_notices', static function () use ($wp_version) {
        $message = sprintf(
            // translators: %1$s is the minimum supported WordPress version, %2$s is the current WordPress version.
            __('This plugin was tested only with WordPress %1$s or higher. Your site is running %2$s. It may not work correctly.', 'quick-ajax-post-loader'),
            QAPL_Constants::PLUGIN_MINIMUM_WP_VERSION,
            $wp_version
        );
        echo '<div class="notice notice-warning"><p><strong>Quick Ajax Post Loader:</strong> '.esc_html($message).'</p></div>';
    } );
}

register_activation_hook(plugin_basename(__FILE__), static function () {
    // load activator only on activation
    $activator_path = plugin_dir_path(__FILE__) . 'includes/maintenance/class-activator.php';
    require_once $activator_path;
    QAPL_Quick_Ajax_Activator::activate();
});

if (class_exists('QAPL_Initializer')) {
    add_action('admin_notices', static function () {
        echo '<div class="notice notice-error"><p><strong>Quick Ajax Post Loader:</strong> Class QAPL_Initializer already exists. Conflict detected. Plugin aborted.</p></div>';
    });
    return;
}
require_once plugin_dir_path(__FILE__) . 'includes/class-initializer.php';

add_action('plugins_loaded', static function () {
    QAPL_Initializer::initialize();
});
