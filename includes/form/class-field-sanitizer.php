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
                $value = is_numeric($value) ? $value + 0 : $field->get_default();
                $min = $field->get_min();
                $max = $field->get_max();
                if ($min !== null && $value < $min) return $field->get_default();
                if ($max !== null && $value > $max) return $field->get_default();
                return $value;
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