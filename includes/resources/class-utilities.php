<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Quick_Ajax_Utilities {
    public static function verify_classes_exist($class_names, string $context = ''): bool {
        if (is_string($class_names)) {
        $class_names = [$class_names];
        }
        $missing_classes = [];
        foreach ($class_names as $class_name) {
            if (!class_exists($class_name)) {
                $missing_classes[] = $class_name;
            }
        }
        if (!empty($missing_classes)) {
            $message = 'Missing classes: ' . implode(', ', $missing_classes);
            if (!empty($context)) {
                $message = "[" . $context . "] " . $message;
            }
            if (class_exists('QAPL_Quick_Ajax_Logger')) {
                QAPL_Quick_Ajax_Logger::log($message, 'error');
            }
            elseif (defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
                error_log($message);
            }
            return false;
        }
        return true;
    }

    public static function element_exists(string $type, string $name) {
        $exists = false;
        $type_formatted = '';
        $plugin_name = QAPL_Quick_Ajax_Constants::PLUGIN_NAME;
        if ($type === 'class' && class_exists($name)) {
            $exists = true;
            $type_formatted = 'class';
        } else if ($type === 'function' && function_exists($name)) {
            $exists = true;
            $type_formatted = 'function';
        }
        if ($exists) {
            add_action('admin_notices', function() use ($name, $type_formatted, $plugin_name) {
                echo '<div class="notice notice-error"><p><strong>' . esc_html($plugin_name) . '</strong> is not working properly. Error: A ' . esc_html($type_formatted) . ' named <strong>' . esc_html($name) . '</strong> already exists, which may have been declared by another plugin.</p></div>';
            });
            return false;
        }   
        return true;
    }
    public static function add_or_update_option_autoload(string $option_name, $default_value = '', string $autoload = 'auto'): void {
        global $wpdb;
        
        // Check if the option exists
        $existing_option = get_option($option_name, false);
    
        if ($existing_option !== false) {
            // Update autoload value if the option exists            
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