<?php
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Settings_Tab_Cleanup{
    private $settings_page;

    public function __construct($settings_page) {
        $this->settings_page = $settings_page;
    }

    public function register_fields() {
        // remove old data checkbox
        $field = QAPL_Form_Field_Factory::build_global_remove_old_data_field();
        $this->settings_page->register_field($field);
    }

    public function register_content($tabIndex) {
        $tab_title = esc_html__('Purge Old Data', 'quick-ajax-post-loader');
        $content = $this->build_content();
        $this->settings_page->add_quick_ajax_page_content($tabIndex, $tab_title, $content);
    }

    private function build_content() {
         $action_url = esc_url(admin_url('admin-post.php')); // use admin-post.php for admin actions
        $content = '<div id="quick-ajax-clear-data">';
        $content .= '<h3>' . esc_html__('Purge Old Data', 'quick-ajax-post-loader') . '</h3>';
        $content .= '<form method="post" action="' . $action_url . '">';
        $content .= $this->settings_page->render_field('qapl_remove_old_meta', false, true); // add additional form field
        $content .= '<input type="hidden" name="action" value="qapl_purge_unused_data" />';
        $content .= '<input type="hidden" name="qapl_purge_unused_data" value="1" />'; // set value to "1" for consistency
        $content .= wp_nonce_field('qapl_purge_unused_data', 'qapl_purge_nonce', true, false); // create nonce field for security
        $content .= get_submit_button(esc_html__('Purge Unused Data', 'quick-ajax-post-loader'), 'primary', 'purge_data_button', false); // generate submit button
        $content .= '</form>';
        $content .= '</div>';
        return $content;
    }
}
