<?php

if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Field_Registry {    
    private array $fields = [];
    public function register(QAPL_Form_Field_Interface $field): void {
        $name = $field->get_name();

        if (empty($name)) {
            return;
        }
        $this->fields[$name] = $field;
    }
    public function get(string $field_name): ?QAPL_Form_Field_Interface {
        return $this->fields[$field_name] ?? null;
    }
    public function all(): array {
        return $this->fields;
    }
    public function has(string $field_name): bool {
        return isset($this->fields[$field_name]);
    }
    public function update_options(string $field_name, array $options): bool {
        if (!$this->has($field_name)) {
            return false;
        }
        $this->fields[$field_name]->set_options($options);
        return true;
    }
}