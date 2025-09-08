<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('QAPL_Quick_Ajax_Activator')) {
    class QAPL_Quick_Ajax_Activator {
        public static function activate() {
            require_once plugin_dir_path(__FILE__) . 'class-helper.php';
            $default_value = [
                'loader_icon' => QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_SELECT_LOADER_ICON_DEFAULT,
            ];
            QAPL_Quick_Ajax_Utilities::add_or_update_option_autoload(QAPL_Quick_Ajax_Constants::GLOBAL_OPTIONS_NAME, $default_value, 'off');
        }
    }
}