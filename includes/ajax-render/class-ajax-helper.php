<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Ajax_Helper{
    public function sanitize_json_to_array($data) {
        // Check if input is a JSON string
        if (is_string($data)) {
            $data = json_decode($data, true);
            // Check if JSON decoding was successful
            if (json_last_error() !== JSON_ERROR_NONE) {
                return []; //Return an empty array if JSON decoding failed
            }
        }
        // Ensure input is an array
        if (!is_array($data)) {
            return [];
        }
        // Sanitize array values
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->sanitize_json_to_array($value);
            } elseif (is_numeric($value)) {
                $data[$key] = absint($value);
            } else {
                $data[$key] = sanitize_text_field($value);
            }
        }
        return $data;
    }
    public function args_json(array $args){
        $json_data = wp_json_encode($args);
        return $json_data;
    }
}
