<?php 
if (!defined('ABSPATH')) {
    exit;
}
//OLD class-field-field.php

//this class holds all field data,
//it is the final field object
class QAPL_Form_Field implements QAPL_Form_Field_Interface {
    private string $name;
    private string $label;
    private string $type;
    private array $options;
    private $default;
    private string $description;
    private string $placeholder;
    private array $tooltip = [];

    //this constructor sets all properties for the field
    public function __construct(string $name, string $label, string $type, array $options, $default, string $description, string $placeholder, array $tooltip = []) {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
        $this->options = $options;
        $this->default = $default;
        $this->description = $description;
        $this->placeholder = $placeholder;
        $this->tooltip = $tooltip;
    }
    //simple get methods to return each property
    public function get_name(): string {
        return $this->name;
    }
    public function get_label(): string {
        return $this->label;
    }
    public function get_type(): string {
        return $this->type;
    }
    public function get_options(): array {
        return $this->options;
    }
    public function set_options(array $options): void {
        $this->options = $options;
    }
    public function get_default() {
        return $this->default;
    }
    public function get_description(): string {
        return $this->description;
    }
    public function get_placeholder(): string {
        return $this->placeholder;
    }
    public function get_tooltip(): array {
        return $this->tooltip;
    }
    public function prepare_value($value) {
        if ($value === null) {
            return $this->default;
        }
        return $value;
    }
    private static function get_default_by_type(string $type) {
        switch ($type) {
            case 'multiselect':
                return [];
            case 'checkbox':
                return 0;
            default:
                return '';
        }
    }
    public static function create_from_definition(array $config): self {
        $name        = $config['name'] ?? '';
        $label       = $config['label'] ?? '';
        $type        = $config['type'] ?? '';
        $options     = $config['options'] ?? [];
        $default     = $config['default'] ?? self::get_default_by_type($type);
        $description = $config['description'] ?? '';
        $placeholder = $config['placeholder'] ?? '';
        $tooltip     = $config['tooltip'] ?? [];
        return new self($name, $label, $type, $options, $default, $description, $placeholder, $tooltip);
    }
}
