<?php 
if (!defined('ABSPATH')) {
    exit;
}

interface QAPL_File_Manager_Interface {
    public function file_exists(string $file_path);
    public function get_plugin_directory(): string;
    public function get_plugin_js_directory(): string;
    public function get_plugin_css_directory(): string;
    public function get_templates_dir_path(string $file);
}