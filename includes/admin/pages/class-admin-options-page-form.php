<?php 
if (!defined('ABSPATH')) {
    exit;
}

abstract class QAPL_Admin_Options_Page_Form extends QAPL_Form_Content_Builder {
    protected $option_group;
    protected $option_name;
    protected $tabs = [];
    
    public function __construct($option_group, $option_name) {
        parent::__construct(); 
        $this->option_group = $option_group;
        $this->option_name = $option_name;
        $this->set_value_provider(
            new QAPL_Global_Option_Value_Provider($this->option_name)
        );
    }
    public function init() {
        $this->init_option_page_fields();
        $this->init_option_page_content();
    }
    abstract public function render_quick_ajax_page_heading();
    abstract public function init_option_page_fields();
    abstract public function init_option_page_content();

    public function add_quick_ajax_page_content($id, $title, $content) {
        $this->tabs[$id] = ['title' => $title, 'content' => $content];
    }
    public function render_field($field_name, $options = [], $required = false) {
        return $this->add_field($field_name, $options, $required);
    }
    public function get_field($field_name) {
        return $this->field_registry->get($field_name);
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
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output already escaped
        echo $this->render_quick_ajax_tabs_navigation();
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output already escaped
        echo $this->render_quick_ajax_tabs_content();
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