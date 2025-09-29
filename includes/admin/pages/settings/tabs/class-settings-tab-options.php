<?php
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Settings_Tab_Options{
    private $settings_page;

    public function __construct($settings_page) {
        $this->settings_page = $settings_page;
    }

    public function register_fields() {
        //select loader icon
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_global_select_loader_icon();
        $this->settings_page->register_field($field);
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_global_read_more_text_field();
        $this->settings_page->register_field($field);
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_global_show_all_label_field();
        $this->settings_page->register_field($field);
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_global_load_more_label_field();
        $this->settings_page->register_field($field);
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_global_no_post_message_field();
        $this->settings_page->register_field($field);
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_global_end_post_message_field();
        $this->settings_page->register_field($field);

        //Sorting Options
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_global_sort_option_date_desc_label_field();
        $this->settings_page->register_field($field);
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_global_sort_option_date_asc_label_field();
        $this->settings_page->register_field($field);
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_global_sort_option_comment_count_desc_label_field();
        $this->settings_page->register_field($field);
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_global_sort_option_title_desc_label_field();
        $this->settings_page->register_field($field);
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_global_sort_option_title_asc_label_field();
        $this->settings_page->register_field($field);
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_global_sort_option_rand_label_field();
        $this->settings_page->register_field($field);
    }

    public function register_content($tabIndex) {
        $tab_title = esc_html__('Global Options', 'quick-ajax-post-loader');
        $content = $this->build_content();
        $this->settings_page->add_quick_ajax_page_content($tabIndex, $tab_title, $content);
    }

    private function build_content() {
        ob_start();
        settings_fields($this->settings_page->get_option_group());
        $settings_fields_html = ob_get_clean();

        $content = '<div id="quick-ajax-example-code">';
        $content .= '<form method="post" action="options.php">';            
        $content .= '<h3>'.__('Global Options', 'quick-ajax-post-loader').'</h3>';
        $content .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::GLOBAL_LOADER_ICON_FIELD);
        $content .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::GLOBAL_READ_MORE_LABEL_FIELD);
        $content .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::GLOBAL_SHOW_ALL_LABEL_FIELD);
        $content .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::GLOBAL_LOAD_MORE_LABEL_FIELD);
        $content .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::GLOBAL_NO_POST_MESSAGE_FIELD);
        $content .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::GLOBAL_END_POST_MESSAGE_FIELD);
        $content .= '<h4>'.__('Sorting Option Labels', 'quick-ajax-post-loader').'</h4>';
        $content .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::GLOBAL_SORT_OPTION_DATE_DESC_LABEL_FIELD);
        $content .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::GLOBAL_SORT_OPTION_DATE_ASC_LABEL_FIELD);
        $content .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::GLOBAL_SORT_OPTION_COMMENT_COUNT_DESC_LABEL_FIELD);
        $content .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::GLOBAL_SORT_OPTION_TITLE_ASC_LABEL_FIELD);
        $content .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::GLOBAL_SORT_OPTION_TITLE_DESC_LABEL_FIELD);
        $content .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::GLOBAL_SORT_OPTION_RAND_LABEL_FIELD);
        $content .= $settings_fields_html;
        $content .= get_submit_button(esc_html__('Save Settings', 'quick-ajax-post-loader'), 'primary', 'save_settings_button', false);
        $content .= '</form>';
        $content .= '</div>';
        return $content;
    }
}