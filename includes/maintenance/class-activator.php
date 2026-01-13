<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('QAPL_Quick_Ajax_Activator')) {
    class QAPL_Quick_Ajax_Activator {
        public static function activate() {
            require_once dirname(__DIR__) . '/resources/class-utilities.php';
            $default_value = [
                'loader_icon' => QAPL_Constants::LAYOUT_SETTING_SELECT_LOADER_ICON_DEFAULT,
            ];
            self::add_or_update_option_autoload(QAPL_Constants::GLOBAL_OPTIONS_NAME, $default_value, 'off');
        }
        private static function add_or_update_option_autoload(string $option_name, $default_value = '', string $autoload = 'auto'): void {
            global $wpdb;
            
            // Check if the option exists
            $existing_option = get_option($option_name, false);
        
            if ($existing_option !== false) {
                // Update autoload value if the option exists            
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- required to update autoload which wp api does not support
                $updated = $wpdb->update(
                    $wpdb->options,
                    ['autoload' => $autoload], // Update autoload field
                    ['option_name' => $option_name],
                    ['%s'],
                    ['%s']
                );
        
                //clear cache after updating
                if ($updated !== false) {
                    wp_cache_delete($option_name, 'options');
                }
            } else {
                // Add the option with specified autoload value
                add_option($option_name, $default_value, '', $autoload);    
                //clear cache after adding
                wp_cache_delete($option_name, 'options');
            }
        }
    }
}