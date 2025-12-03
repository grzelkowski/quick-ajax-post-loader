<?php 
if (!defined('ABSPATH')) {
    exit;
}
//this class helps build the field object step by step
//it is useful if you don't want to pass all parameters in constructor
class QAPL_Form_Field_Builder {
    private string $name = '';
    private string $label = '';
    private string $type = 'text';
    private array $options = [];
    private $default = '';
    private string $description = '';
    private string $placeholder = '';

    public function set_name(string $name): self {
        $this->name = $name;
        return $this;
    }
    public function set_label(string $label): self {
        $this->label = $label;
        return $this;
    }
    public function set_type(string $type): self {
        $this->type = $type;
        return $this;
    }
    public function set_options(array $options): self {
        $this->options = $options;
        return $this;
    }
    public function set_default($default): self {
        $this->default = $default;
        return $this;
    }
    public function set_description(string $description): self {
        $this->description = $description;
        return $this;
    }
    public function set_placeholder(string $placeholder): self {
        $this->placeholder = $placeholder;
        return $this;
    }

    //finally this method creates the final field object
    //using all the properties you set before
    public function build(): QAPL_Form_Field_Interface {
        return new QAPL_Form_Field(
            $this->name,
            $this->label,
            $this->type,
            $this->options,
            $this->default,
            $this->description,
            $this->placeholder
        );
    }
}