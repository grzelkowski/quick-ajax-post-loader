<?php
if (!defined('ABSPATH')) {
    exit;
}
class QAPL_Global_Option_Value_Provider implements QAPL_Value_Provider_Interface {
    private array $data = [];
    public function __construct(string $option_name) {
        $option = get_option($option_name);
        if (is_array($option)) {
            foreach ($option as $key => $value) {
                $full_key = $option_name . '[' . $key . ']';
                $this->data[$full_key] = $value;
            }
        }
    }
    public function get(string $field_name) {
        return $this->data[$field_name] ?? null;
    }
}