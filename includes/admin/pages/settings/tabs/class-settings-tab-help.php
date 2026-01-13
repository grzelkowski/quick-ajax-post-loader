<?php 
if (!defined('ABSPATH')) {
    exit;
}
class QAPL_Settings_Tab_Help{
    private $settings_page;

    public function __construct($settings_page) {
        $this->settings_page = $settings_page;
    }

    public function register_fields() {
        // help tab does not use form fields
    }

    public function register_content($tabIndex) {
        $tab_title = esc_html__('Help', 'quick-ajax-post-loader');
        $content   = $this->build_content();
        $this->settings_page->add_quick_ajax_page_content($tabIndex, $tab_title, $content);
    }

    private function build_content() {
        $content = '<h3>' . esc_html__('Help Content', 'quick-ajax-post-loader') . '</h3>';

        $base_help_dir = __DIR__ . '/help/';
        $locale = get_locale();

        // choose help file based on locale
        switch ($locale) {
            case 'pl_PL':
                $help_json_file_name = 'help_en_US.json';
                break;
            default:
                $help_json_file_name = 'help_en_US.json';
                break;
        }
        $help_json_file_path = $base_help_dir . $help_json_file_name;

        if (!file_exists($help_json_file_path)) {
            return $content . '<p>' . esc_html('Help file not found.') . '</p>';
        }

        $json_data = file_get_contents($help_json_file_path);
        $help_data = json_decode($json_data, true);

        if (!is_array($help_data) || empty($help_data)) {
            return $content . '<p>' . esc_html('Invalid help file format or empty content.') . '</p>';
        }

        foreach ($help_data as $key => $section) {
            if (empty($section['title']) || empty($section['content'])) {
                continue;
            }
            $section_id      = 'qapl_help_'.$key;
            $section_title   = esc_html(wp_strip_all_tags($section['title']));
            $section_content = wp_kses_post($section['content']);

            $accordion_content = '<div class="quick-ajax-section">';
            $accordion_content .= '<h3>' . $section_title . '</h3>';
            $accordion_content .= '<div class="quick-ajax-section-content">' . $section_content . '</div>';
            $accordion_content .= '</div>';

            $content .= $this->settings_page->create_accordion_block_wrapper($section_title, $accordion_content, $section_id);
        }

        return $content;
    }
}