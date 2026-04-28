<?php 
if (!defined('ABSPATH')) {
    exit;
}
interface QAPL_Settings_Tab_Interface {
    public function define_fields(): void;
    public function register_content(int $index): void;
}
