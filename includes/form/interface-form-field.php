<?php 
if (!defined('ABSPATH')) {
    exit;
}
//interface for form field data
//it defines the methods that must exist in any field class
interface QAPL_Form_Field_Interface {
    public function get_name(): string;
    public function get_label(): string;
    public function get_type(): string;
    public function get_options(): array;
    public function get_default();
    public function get_description(): string;
    public function get_placeholder(): string;
    public function get_tooltip(): array;
    public function get_field(): array;
}