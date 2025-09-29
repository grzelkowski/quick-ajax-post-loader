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

    public static function check_name_conflict(string $type, string $name) {
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
}