<?php 
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('QAPL_Admin_Menu')) {
    class QAPL_Admin_Menu {
        private ?QAPL_Settings_Page $settings_page = null;

        public function __construct() {
            add_action('admin_menu', array($this, 'add_menu'));
            add_action('admin_menu', array($this, 'add_settings_page'));
            add_action('admin_init', array($this, 'register_settings'));
        }

        private function get_settings_page(): ?QAPL_Settings_Page {
            if ($this->settings_page === null && class_exists('QAPL_Settings_Page')) {
                $this->settings_page = new QAPL_Settings_Page(
                    QAPL_Constants::ADMIN_PAGE_SETTINGS_GROUP,
                    QAPL_Constants::GLOBAL_OPTIONS_NAME
                );
                $this->settings_page->init_option_page_fields();
            }
            return $this->settings_page;
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
            if (!current_user_can('manage_options')) {
                wp_die(esc_html(__('You do not have sufficient permissions to access this page.', 'quick-ajax-post-loader')));
            }
            $form = $this->get_settings_page();
            if ($form) {
                $form->init_option_page_content();
                $form->render_quick_ajax_page();
            }
        }
        public function register_settings() {
            register_setting(
                QAPL_Constants::ADMIN_PAGE_SETTINGS_GROUP,
                QAPL_Constants::GLOBAL_OPTIONS_NAME,
                array($this, 'quick_ajax_sanitize_callback')
            );
        }
        
        public function quick_ajax_sanitize_callback($values) {
            if (!is_array($values)) {
                return sanitize_text_field($values);
            }
            $settings = $this->get_settings_page();
            $sanitizer = new QAPL_Field_Sanitizer();
            $sanitized = [];
            foreach ($values as $key => $value) {
                $field_name = QAPL_Constants::GLOBAL_OPTIONS_NAME . '[' . $key . ']';
                $field = $settings ? $settings->get_field($field_name) : null;
                if ($field) {
                    $sanitized[$key] = $sanitizer->sanitize_field($field, $value);
                } else {
                    $sanitized[$key] = is_array($value)
                        ? array_map('sanitize_text_field', $value)
                        : sanitize_text_field($value);
                }
            }
            return $sanitized;
        }
    }
    new QAPL_Admin_Menu();
}
