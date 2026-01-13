<?php 
if (!defined('ABSPATH')) {
    exit;
}

abstract class QAPL_Admin_Options_Page_Form extends QAPL_Form_Content_Builder {
    protected $option_group;
    protected $option_name;
    protected $tabs = [];
    
    public function __construct($option_group, $option_name) {
        $this->option_group = $option_group;
        $this->option_name = $option_name;
    }
    public function init() {
        $this->unserialize_data();
        $this->init_option_page_fields();
        $this->init_option_page_content();
    }
    abstract public function render_quick_ajax_page_heading();
    abstract public function init_option_page_fields();
    abstract public function init_option_page_content();

    public function add_quick_ajax_page_content($id, $title, $content) {
        $this->tabs[$id] = ['title' => $title, 'content' => $content];
    }
    private function unserialize_data(){ 
        $data = get_option($this->option_name);
        if (is_array($data)) {
            foreach ($data as $field_name => $field_value) {
                $field_key = $this->option_name . '[' . $field_name . ']';
                $this->existing_values[$field_key]=array(
                    'name' => $field_key,
                    'value' => $field_value
                );
            }
        }
    }
    public function register_field($field) {
        $this->create_field($field);
    }
    public function render_field($field_name, $options = [], $required = false) {
        return $this->add_field($field_name, $options, $required);
    }
    public function get_field($field_name) {
        return $this->fields[$field_name] ?? null;
    }
    public function update_field_options($field_name, array $options) {
        if (!isset($this->fields[$field_name])) {
            return false;
        }
        $this->fields[$field_name]['options'] = $options;
        return true;
    }
    public function get_option_group() {
        return $this->option_group;
    }
    public function field_options_wrapper(array $args = []) {
        return $this->field_options($args);
    }
    public function get_taxonomy_options() {
        return $this->get_taxonomy_options_for_post_type();
    }
    public function get_term_options() {
        return $this->get_term_options_for_taxonomy();
    }
    public function create_accordion_block_wrapper($title, $content, ?string $id = null) {
        return $this->create_accordion_block($title, $content, $id);
    }



    public function render_quick_ajax_page(){ 
        echo '<div class="wrap">';
        echo '<div class="quick-ajax-heading">';
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output already escaped
        echo $this->render_quick_ajax_page_heading();
        echo '</div>';
        echo '<div class="quick-ajax-form-wrap ' . esc_attr($this->get_quick_ajax_form_class()) . '" id="form-' . esc_attr($this->option_group) . '">';
        //echo '<form method="post" action="options.php">';
        //settings_fields($this->option_group); // Output security fields for the registered settings
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output already escaped
        echo $this->render_quick_ajax_tabs_navigation();
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output already escaped
        echo $this->render_quick_ajax_tabs_content();
        //echo '</form>';
        echo '</div>';
    }

    private function render_quick_ajax_tabs_navigation() {
        // create navigation only if there is more than 1 tab
        if (count($this->tabs) <= 1) {
            return '';
        }
        $html = '<div class="quick-ajax-tabs" role="tablist">';
        $firstTab = true;    
        foreach ($this->tabs as $id => $tab) {
            $class = $firstTab ? ' active' : '';
            $aria_class = $firstTab ? 'true' : 'false';
            $html .= '<button type="button" class="quick-ajax-tab-button' . $class . '" role="tab" aria-selected="'.$aria_class.'" aria-controls="quick-ajax-tab-' . esc_attr($id) . '" data-tab="quick-ajax-tab-' . esc_attr($id) . '">' . esc_html($tab['title']) . '</button>';
            $firstTab = false;
        }    
        $html .= '</div>';
        return $html;
    }
    private function render_quick_ajax_tabs_content() {
        $html = '';
        $firstTab = true;
        foreach ($this->tabs as $id => $tab) {
            $class = $firstTab ? 'quick-ajax-tab-content active' : 'quick-ajax-tab-content';
            $aria_class = $firstTab ? '' : 'hidden';
            $html .= '<div id="quick-ajax-tab-' . esc_attr($id) . '" class="' . $class . '" role="tabpanel" tabindex="0" '.$aria_class.'>';
            $html .= $tab['content'];            
            $html .= '</div>';
            $firstTab = false;
        }
        return $html;
    }
}