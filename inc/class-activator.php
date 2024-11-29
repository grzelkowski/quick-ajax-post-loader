<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('QAPL_Quick_Ajax_Activator')) {
    class QAPL_Quick_Ajax_Activator {
        public static function activate() {
            require_once plugin_dir_path(__FILE__) . 'class-helper.php';
            $qapl_helper = QAPL_Quick_Ajax_Helper::get_instance();
            $default_value = [
                'loader_icon' => $qapl_helper::shortcode_page_select_loader_icon_default_value(),
            ];
            $qapl_helper::add_or_update_option_autoload($qapl_helper::admin_page_global_options_name(), $default_value, 'off');
        }
    }
}