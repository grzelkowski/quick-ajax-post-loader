<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Settings_Page extends QAPL_Admin_Options_Page_Form {
    private $cached_tabs = null;

    public function render_quick_ajax_page_heading() {
        return '<h1>'.esc_html__('Quick AJAX settings', 'quick-ajax-post-loader').'</h1>';
    }
    private function settings_page_tabs(){
        if ($this->cached_tabs !== null) {
            return $this->cached_tabs;
        }
        $tabs = [];
        $tabs[] = new QAPL_Settings_Tab_Options($this);
        $tabs[] = new QAPL_Settings_Tab_PHP_Snippet($this);
        $tabs[] = new QAPL_Settings_Tab_Help($this);
        $cleanup_flags = QAPL_Constants::DB_OPTION_PLUGIN_CLEANUP_FLAGS;
        if (!empty(get_option($cleanup_flags))) {
                $tabs[] = new QAPL_Settings_Tab_Cleanup($this);
        }           
        $this->cached_tabs = $tabs;
        return $this->cached_tabs;
    }
    public function init_option_page_fields() {
        foreach ($this->settings_page_tabs() as $tab) {
            $tab->define_fields();
        }
    }
    public function init_option_page_content() {
        $tabIndex = 1;
        foreach ($this->settings_page_tabs() as $tab) {
            $tab->register_content($tabIndex++);
        }
    }
}

