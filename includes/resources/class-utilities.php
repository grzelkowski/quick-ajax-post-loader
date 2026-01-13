<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Utilities {
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
            qapl_log($message, 'error');
            return false;
        }
        return true;
    }

    public static function check_name_conflict(string $type, string $name) {
        $exists = false;
        $type_formatted = '';
        $plugin_name = QAPL_Constants::PLUGIN_NAME;
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

    public static function check_page_type($values){
        if (function_exists('get_current_screen')) {
            $screen = get_current_screen();
        }else{
            $screen = null;
        }
        $page_type = null;

        // check if the 'page' parameter exists in the URL and sanitize it
        if ($page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
            $page_type = sanitize_text_field($page);
        }
        // check if the 'post_type' parameter exists in the URL and sanitize it
        elseif ($post_type = filter_input(INPUT_GET, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
            $page_type = sanitize_text_field($post_type);
        }
        // if neither 'page' nor 'post_type' is set, try to get the post type from the current screen
        elseif (isset($screen->post_type)) {
            $page_type = sanitize_text_field($screen->post_type);
        }
        if ($page_type) {
            if (is_array($values)) {
                return in_array($page_type, $values, true); // use strict comparison
            } else {
                return $page_type === $values;
            }
        }
        return false;
    }
}