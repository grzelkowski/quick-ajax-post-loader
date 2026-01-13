<?php 
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('QAPL_Admin_Menu')) {
    class QAPL_Admin_Menu {
        public function __construct() {
            add_action('admin_menu', array($this, 'add_menu'));
            add_action('admin_menu', array($this, 'add_settings_page'));
            add_action('admin_init', array($this, 'register_settings'));
        }
        public function add_menu(){
            // Quick Ajax Menu
            add_menu_page(
                'Quick AJAX',
                'Quick AJAX',
                'manage_options',
                QAPL_Constants::PLUGIN_MENU_SLUG,
                array($this, 'options_page_content'),
                'dashicons-editor-code',
                80
            );
            // "Add New"
            add_submenu_page(
                QAPL_Constants::PLUGIN_MENU_SLUG,
                __('Add New', 'quick-ajax-post-loader'),
                __('Add New', 'quick-ajax-post-loader'),
                'edit_posts',
                'post-new.php?post_type=' . QAPL_Constants::CPT_SHORTCODE_SLUG
            );
        }
        public function add_settings_page() {
            // "settings"
            add_submenu_page(
                QAPL_Constants::PLUGIN_MENU_SLUG,
                __('Settings & Features', 'quick-ajax-post-loader'),
                __('Settings & Features', 'quick-ajax-post-loader'),
                'manage_options',
                QAPL_Constants::SETTINGS_PAGE_SLUG,
                array($this, 'render_quick_ajax_settings_page')
            );
        }
        public function render_quick_ajax_settings_page() {
            // "settings Page"
            if (!current_user_can('manage_options')) {
                wp_die(esc_html(__('You do not have sufficient permissions to access this page.', 'quick-ajax-post-loader')));
            }
            if (class_exists('QAPL_Settings_Page')) {
                $form = new QAPL_Settings_Page(QAPL_Constants::ADMIN_PAGE_SETTINGS_GROUP, QAPL_Constants::GLOBAL_OPTIONS_NAME);
                $form->init();
                $form->render_quick_ajax_page();
            }
        }
        public function register_settings() {
            // Register the settings group
            register_setting(
                QAPL_Constants::ADMIN_PAGE_SETTINGS_GROUP,
                QAPL_Constants::GLOBAL_OPTIONS_NAME,
                array($this, 'quick_ajax_sanitize_callback')
            );
        }
        
        public function quick_ajax_sanitize_callback($values){
        //error_log('quick_ajax_sanitize_callback: ' . print_r($values, true));
        $sanitized_value = is_array($values) ? array() : '';
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $sanitized_value[$key] = sanitize_text_field($value);
            }
        } else {
            $sanitized_value = sanitize_text_field($values);
        }
        //error_log('Sanitized value: ' . print_r($sanitized_value, true));
        return $sanitized_value;
        }    
    }
    new QAPL_Admin_Menu();
}