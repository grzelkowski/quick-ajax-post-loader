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
    public function args_json(array $args) {
        return wp_json_encode($args);
    }
    public function extract_classes_from_string($string){
        // Split the input string into an array using whitespace or comma as separators
        $class_container_array = preg_split('/[\s,]+/', $string);
        $class_container_array = array_map('sanitize_html_class', $class_container_array);

        // Iterate over the array and remove elements that start with a digit
        foreach ($class_container_array as $key => $item) {
            if (preg_match('/^\d/', $item)) {
                // Use unset to remove the item from the array if it starts with a digit
                unset($class_container_array[$key]);
            }
        }
        $container_class = implode(' ', $class_container_array);
        return $container_class;
    }
}
