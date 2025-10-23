<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Shortcode_Post_Meta_Handler {
    public static function load_and_sanitize($id) {
        $serialized_data = get_post_meta($id, QAPL_Quick_Ajax_Constants::DB_POSTMETA_SHORTCODE_SETTINGS, true);
        if (!$serialized_data) {
            return array();
        }
        $form_data = maybe_unserialize($serialized_data);
        
        if (empty($form_data) || !is_array($form_data)) {
            return array();
        }
        return self::sanitize($form_data);
    }
    private static function sanitize($meta_data) {
        $sanitized = array();
        foreach ($meta_data as $key => $value) {
            if (is_array($value)) {
                $sanitized_array = [];    
                foreach ($value as $item) {
                    if (is_numeric($item)) {
                        $sanitized_array[] = absint($item); // sanitize as int
                    } else {
                        $sanitized_array[] = sanitize_text_field($item); // sanitize as string
                    }
                }    
                $sanitized[$key] = $sanitized_array;
            } elseif (is_numeric($value)) {
                $sanitized[$key] = intval($value);
            } elseif (is_string($value)) {
                $sanitized[$key] = sanitize_text_field($value);
            }
        }
        return $sanitized;
    }
}