<?php 
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('QAPL_Quick_Ajax_Settings_Page')) {
    class QAPL_Quick_Ajax_Settings_Page extends QAPL_Admin_Options_Page_Form {
        private $tabIndex = 0;

        public function render_quick_ajax_page_heading() {
            return '<h1>'.esc_html__('Quick AJAX settings', 'quick-ajax-post-loader').'</h1>';
        }
        private function settings_page_tabs(){
            $tabs[] = new QAPL_Settings_Tab_Options($this);
            $tabs[] = new QAPL_Settings_Tab_PHP_Snippet($this);
            $tabs[] = new QAPL_Settings_Tab_Help($this);
            $cleanup_flags = QAPL_Quick_Ajax_Constants::DB_OPTION_PLUGIN_CLEANUP_FLAGS;
            if (!empty(get_option($cleanup_flags))) {
                 $tabs[] = new QAPL_Settings_Tab_Cleanup($this);
            }           
            return $tabs;
        }
        public function init_option_page_fields() {
            foreach ($this->settings_page_tabs() as $tab) {
                if (method_exists($tab, 'register_fields')) {
                    $tab->register_fields();
                }
            }
        }
        public function init_option_page_content() {
            foreach ($this->settings_page_tabs() as $tab) {
                if (method_exists($tab, 'register_content')) {
                    $tab->register_content($this->tabIndex++);
                }
            }
        }
    }
}
