<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Field_Sanitizer {
    public function sanitize_field(QAPL_Form_Field_Interface $field, $value) {
        switch ($field->get_type()) {
            case 'checkbox':
                return (int) !empty($value);
            case 'number':
                return is_numeric($value) ? (int) $value : (int) $field->get_default();

            case 'multiselect':
                return is_array($value) ? array_map('sanitize_text_field', $value) : [];

            case 'select':
                $allowed = array_column($field->get_options(), 'value');
                // dynamic select no options defined
                if (empty($allowed)) {
                    return sanitize_text_field((string) $value);
                }
                $value = (string) $value;
                return in_array($value, array_map('strval', $allowed), true) ? $value : (string) $field->get_default();
            default:
                if (is_array($value)) {
                    return array_map('sanitize_text_field', $value);
                }
                return sanitize_text_field((string) ($value ?? ''));
        }
    }
}