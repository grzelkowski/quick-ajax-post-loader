<?php 
if (!defined('ABSPATH')) {
    exit;
}
interface QAPL_Value_Provider_Interface {
    public function get(string $field_name);
}