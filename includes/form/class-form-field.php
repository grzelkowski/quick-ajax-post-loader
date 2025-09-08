<?php 
if (!defined('ABSPATH')) {
    exit;
}
//this class holds all field data,
//it is the final field object
class QAPL_Quick_Ajax_Form_Field implements QAPL_Quick_Ajax_Form_Field_Interface {
    private string $name;
    private string $label;
    private string $type;
    private array $options;
    private $default;
    private string $description;
    private string $placeholder;

    //this constructor sets all properties for the field
    public function __construct(string $name, string $label, string $type, array $options, $default, string $description, string $placeholder) {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
        $this->options = $options;
        $this->default = $default;
        $this->description = $description;
        $this->placeholder = $placeholder;
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
    public function get_default() {
        return $this->default;
    }
    public function get_description(): string {
        return $this->description;
    }
    public function get_placeholder(): string {
        return $this->placeholder;
    }
    //returns all field data in array format
    public function get_field(): array {
        return [
            'name' => $this->name,
            'label' => $this->label,
            'type' => $this->type,
            'options' => $this->options,
            'default' => $this->default,
            'description' => $this->description,
            'placeholder' => $this->placeholder,
        ];
    }
}
