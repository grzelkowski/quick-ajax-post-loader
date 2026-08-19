<?php
if (!defined('ABSPATH')) {
    exit;
}

interface QAPL_Template_Locator_Interface {
    public function get_templates_dir_path(string $file): string;
    public function get_templates_file_path($template_name, $default_name, $base_path): string;
    public function get_templates_items_array($template_file_location, $template_name, $default_file = false);
    public function get_post_item_template(string $template_name = ''): string;
    public function get_loader_icon_template(string $template_name = ''): string;
    public function get_no_posts_template(): string;
    public function get_end_posts_template(): string;
    public function get_taxonomy_filter_button_template(): string;
    public function get_load_more_button_template(): string;
    public function get_search_button_template(): string;
}