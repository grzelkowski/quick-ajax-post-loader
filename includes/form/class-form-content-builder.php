<?php 
if (!defined('ABSPATH')) {
    exit;
}

abstract class QAPL_Form_Content_Builder{
    protected QAPL_Field_Registry $field_registry;
    protected ?QAPL_Value_Provider_Interface $value_provider = null;
    protected QAPL_Taxonomy_Options_Provider $taxonomy_options_provider;

    public function __construct() {
        $this->field_registry = new QAPL_Field_Registry();
        $this->taxonomy_options_provider = new QAPL_Taxonomy_Options_Provider();
    }
    public function set_value_provider(QAPL_Value_Provider_Interface $provider): void {
        $this->value_provider = $provider;
    }
    public function update_field_options(string $field_name, array $options): bool {
        return $this->field_registry->update_options($field_name, $options);
    }
    public function register_field(QAPL_Form_Field_Interface $field): void {
        $this->field_registry->register($field);
    }
    public function register_fields_batch(array $fields): void {
        foreach ($fields as $field) {
            $this->register_field($field);
        }
    }
    protected function add_field($field_name, $field_args = [], $required = false){
        $field = $this->field_registry->get($field_name);
        if (!$field) {
            return '';
        }
        switch ($field->get_type()) {
            case 'checkbox':
                return $this->add_checkbox_field($field_name, $field_args, $required);
            case 'select':
                return $this->add_select_field($field_name, $field_args);
            case 'multiselect':
                return $this->add_multiselect_field($field_name, $field_args);
            case 'number':
                return $this->add_number_field($field_name, $field_args);
            case 'text':
                return $this->add_text_input_field($field_name, $field_args);
            case 'color_picker':
                return $this->add_color_picker_field($field_name, $field_args);
            default:
                return '';
        }
    }
    protected function get_value(string $field_name) {
        $field = $this->field_registry->get($field_name);
        if (!$field) {
            return null;
        }
        $value = null;
        if ($this->value_provider) {
            $value = $this->value_provider->get($field_name);
        }
        return $field->prepare_value($value);
    }
    
    protected function get_quick_ajax_form_class() {
        $current_user = wp_get_current_user();
        $scheme = get_user_option('admin_color', $current_user->ID);
        return $scheme . '-style';
    }
    private function add_checkbox_field($field_name, $field_args = [], $required = false) {
        $field = $this->field_registry->get($field_name);
        if (!$field) {
            return '';
        }
        $name = $field->get_name();
        $label = $field->get_label();
        $description = $field->get_description();
        $tooltip = $field->get_tooltip();

        $checked = $this->get_value($name);
        $is_required = $required ? 'required' : '';
            
        $visibility = $this->show_hide_element($field_args);
        $field_container_class = $visibility['field_container_class'];
        $field_container_data_item = $visibility['field_container_data_item'];

        $field_output = '<div class="quick-ajax-field-container quick-ajax-checkbox-field qa-inline-block' . $field_container_class . '"' . $field_container_data_item . '>';
        $field_output .= '<div class="quick-ajax-field-label"><label for="' . esc_attr($name) . '">' . esc_html($label) . '</label>'. $this->render_tooltip($tooltip) .'</div>';
        $field_output .= '<div class="quick-ajax-field">';
        $field_output .= '<div class="switch-checkbox">';
        $field_output .= '<div class="switch-wrap">';
        $field_output .= '<label for="' . esc_attr($name) . '">';
        $field_output .= '<input type="checkbox" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" value="1" ' . checked($checked, 1, false) . ' '. $is_required .' />';
        $field_output .= '<span class="switch"></span>';
        $field_output .= '</label>';
        $field_output .= '</div>';            
        $field_output .= '</div>';
        $field_output .= $this->add_field_description($description);
        $field_output .= '</div>';            
        $field_output .= '</div>';
    
        return $field_output;
    }
    private function add_select_field($field_name, $field_args = []){
        $field = $this->field_registry->get($field_name);
        if (!$field) {
            return '';
        }
        $name = $field->get_name();
        $label = $field->get_label();
        $description = $field->get_description();
        $tooltip = $field->get_tooltip();
        $options_list = $field->get_options();

        $current_value = $this->get_value($name);
        $visibility = $this->show_hide_element($field_args);
        $field_container_class = $visibility['field_container_class'];
        $field_container_data_item = $visibility['field_container_data_item']; 
        $field_output = '<div class="quick-ajax-field-container quick-ajax-select-field' . $field_container_class . '"' . $field_container_data_item . '>';
        $field_output .= '<div class="quick-ajax-field-label"><label for="' . esc_attr($name) . '">' . esc_html($label) .'</label>'. $this->render_tooltip($tooltip) .'</div>';
        $field_output .= '<div class="quick-ajax-field">';
        $field_output .= '<select name="' . esc_attr($name) . '" id="' . esc_attr($name) . '">';
        if (is_array($options_list)) {
            foreach ($options_list as $option) {
                $field_output .= '<option value="' . esc_attr($option['value']) . '"' . selected($current_value, $option['value'], false) . '>';
                $field_output .= esc_html($option['label']);
                $field_output .= '</option>';
            }
        }

        $field_output .= '</select>';
        $field_output .= $this->add_field_description($description);
        $field_output .= '</div>';
        $field_output .= '</div>';

        return $field_output;
    }
    private function add_multiselect_field($field_name, $field_args = []) {
        $field = $this->field_registry->get($field_name);
        if (!$field) {
            return '';
        }
        $name = $field->get_name();
        $label = $field->get_label();
        $description = $field->get_description();
        $tooltip = $field->get_tooltip();
        $options_list = $field->get_options();

        $current_values = $this->get_value($name);
        $visibility = $this->show_hide_element($field_args);
        $field_container_data_item = $visibility['field_container_data_item'];
        $field_container_class = $visibility['field_container_class'];
        $field_output = '<div class="quick-ajax-field-container quick-ajax-multiselect-field' . $field_container_class . '"' . $field_container_data_item . '>';
        $field_output .= '<div class="quick-ajax-field-label"><label>' . esc_html($label) . '</label>'. $this->render_tooltip($tooltip) .'</div>';
        $field_output .= '<div class="quick-ajax-field">';

        $field_output .= '<div class="quick-ajax-field-options" id="' . esc_attr($name) . '">';
        if (is_array($options_list)) {
            foreach ($options_list as $option) {
                $checked = in_array((string) $option['value'], array_map('strval', (array) $current_values), true) ? 'checked' : '';
                $field_output .= '<div class="quick-ajax-multiselect-option">';
                $field_output .= '<label class="quick-ajax-checkbox">';
                $field_output .= '<input type="checkbox" name="' . esc_attr($name) . '[]" value="' . esc_attr($option['value']) . '" ' . $checked . '>';
                $field_output .= esc_html($option['label']);
                $field_output .= '</label>';
                $field_output .= '</div>';
            }
        }
        $field_output .= '</div>';
        
        $field_output .= $this->add_field_description($description);
        $field_output .= '</div>';
        $field_output .= '</div>';
    
        return $field_output;
    }    
    private function add_number_field($field_name, $field_args = []){
        $field = $this->field_registry->get($field_name);
        if (!$field) {
            return '';
        }
        $name = $field->get_name();
        $label = $field->get_label();
        $description = $field->get_description();
        $tooltip = $field->get_tooltip();

        $current_value = $this->get_value($name);
        $visibility = $this->show_hide_element($field_args);
        $field_container_data_item = $visibility['field_container_data_item'];
        $field_container_class = $visibility['field_container_class'];
        
        $field_output = '<div class="quick-ajax-field-container quick-ajax-number-field' . $field_container_class . '"' . $field_container_data_item . '>';
        $field_output .= '<div class="quick-ajax-field-label"><label for="' . esc_attr($name) . '">' . esc_html($label) . '</label>'. $this->render_tooltip($tooltip) .'</div>';
        $field_output .= '<div class="quick-ajax-field">';
        $field_output .= '<input type="number" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" value="' . esc_attr($current_value) . '" />';
        $field_output .= $this->add_field_description($description);
        $field_output .= '</div>';
        $field_output .= '</div>';

        return $field_output;
    }
    private function add_text_input_field($field_name, $field_args = []){
        $field = $this->field_registry->get($field_name);
        if (!$field) {
            return '';
        }
        $name = $field->get_name();
        $label = $field->get_label();
        $description = $field->get_description();
        $tooltip = $field->get_tooltip();
        $placeholder = $field->get_placeholder();

        $current_value = $this->get_value($name);
        $visibility = $this->show_hide_element($field_args);
        $field_container_data_item = $visibility['field_container_data_item'];
        $field_container_class = $visibility['field_container_class'];
        $placeholder = !empty($placeholder) ? ' placeholder="' . esc_attr($placeholder) . '"' : '';
        
        $field_output = '<div class="quick-ajax-field-container quick-ajax-text-input-field' . $field_container_class . '"' . $field_container_data_item . '>';
        $field_output .= '<div class="quick-ajax-field-label"><label for="' . esc_attr($name) . '">' . esc_html($label) . '</label>'. $this->render_tooltip($tooltip) .'</div>';
        $field_output .= '<div class="quick-ajax-field">';
        $field_output .= '<input type="text" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" value="' . esc_attr($current_value) . '"' . $placeholder . '/>';
        $field_output .= $this->add_field_description($description);
        $field_output .= '</div>';
        $field_output .= '</div>';
    
        return $field_output;
    }
    private function add_color_picker_field($field_name, $field_args = []){
        $field = $this->field_registry->get($field_name);
        if (!$field) {
            return '';
        }
        $name = $field->get_name();
        $label = $field->get_label();
        $description = $field->get_description();
        $tooltip = $field->get_tooltip();

        $current_value = $this->get_value($name);
        $visibility = $this->show_hide_element($field_args);
        $field_container_data_item = $visibility['field_container_data_item'];
        $field_container_class = $visibility['field_container_class'];

        $field_output = '<div class="quick-ajax-field-container quick-ajax-text-input-field' . $field_container_class . '"' . $field_container_data_item . '>';
        $field_output .= '<div class="quick-ajax-field-label"><label for="' . esc_attr($name) . '">' . esc_html($label) . '</label>'. $this->render_tooltip($tooltip) .'</div>';
        $field_output .= '<div class="quick-ajax-field">';
        $field_output .= '<input type="text" class="color-picker-field" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" value="' . esc_attr($current_value) . '"/>';
        $field_output .= $this->add_field_description($description);
        $field_output .= '</div>';
        $field_output .= '</div>';
    
        return $field_output;
    }
    protected function field_options(array $overrides = []): array {
        $defaults = [
            'is_trigger' => false,
            'visible_if' => [],
        ];
    
        return array_merge($defaults, $overrides);
    }    
    private function show_hide_element($field_args) {
        $element_data = [
            'field_container_data_item' => '',
            'field_container_class'     => ''
        ];
    
        // check if field is trigger
        if (!empty($field_args['is_trigger']) && $field_args['is_trigger'] === true) {
            $element_data['field_container_class'] .= ' show-hide-trigger';
        }
    
        // check if field has visibility conditions
        if (!empty($field_args['visible_if']) && is_array($field_args['visible_if'])) {
           
            $conditions = $field_args['visible_if'];
            $element_data['field_container_data_item'] = ' data-conditional=\'' . esc_attr(wp_json_encode($conditions)) . '\'';
            
            foreach ($conditions as $field => $expected_value) {
                $actual_value = $this->get_value($field);
                if ((string)$actual_value !== (string)$expected_value) {
                    $element_data['field_container_class'] .= ' inactive';
                    break;
                }
            }
        }
        return $element_data;
    }    
    private function add_field_description($description) {
        if (!empty($description)) {
            return '<p class="quick-ajax-field-desc">' . esc_html($description) . '</p>';
        }
        return '';
    }
    private function render_tooltip(array $tooltip): string {
        if (empty($tooltip['content'])) {
            return '';
        }

        $title = !empty($tooltip['title']) ? esc_attr($tooltip['title']) : '';
        $content = $tooltip['content'];

        return '<span class="qapl-tooltip" tabindex="0" role="tooltip" aria-label="' . $title . '">
            <span class="qapl-tooltip-icon"><span class="qapl-tooltip-icon-inner">?</span></span>
            <span class="qapl-tooltip-content"><span class="qapl-tooltip-content-inner">' . wp_kses_post($content) . '</span></span>
        </span>';
    }
    protected function get_taxonomy_options_for_post_type(?string $post_type = null): array {
        if (empty($post_type)) {
            $post_type = $this->get_value(QAPL_Constants::QUERY_SETTING_SELECT_POST_TYPE);
            if (empty($post_type)) {
                $post_type = QAPL_Constants::QUERY_SETTING_SELECT_POST_TYPE_DEFAULT;
            }
        }
        return $this->taxonomy_options_provider->get_taxonomy_options_for_post_type($post_type);
    }
    protected function get_term_options_for_taxonomy(?string $taxonomy = null): array {
        if (empty($taxonomy)) {
            $taxonomy = $this->get_value(QAPL_Constants::QUERY_SETTING_SELECT_TAXONOMY);
        }
        if (empty($taxonomy)) {
            $post_type = $this->get_value(QAPL_Constants::QUERY_SETTING_SELECT_POST_TYPE);
            if (empty($post_type)) {
                $post_type = QAPL_Constants::QUERY_SETTING_SELECT_POST_TYPE_DEFAULT;
            }
            $taxonomy_options = $this->taxonomy_options_provider->get_taxonomy_options_for_post_type($post_type);
            if (!empty($taxonomy_options[0]['value'])) {
                $taxonomy = $taxonomy_options[0]['value'];
            }
        }
        if (empty($taxonomy)) {
            return [
                [
                    'label' => esc_html__('No taxonomy available', 'quick-ajax-post-loader'),
                    'value' => ''
                ]
            ];
        }
        return $this->taxonomy_options_provider->get_term_options_for_taxonomy($taxonomy);
    }
    protected function create_accordion_block($title, $content, $id){
        $id_attr = $id ? ' id="'.esc_attr(sanitize_key($id)).'"' : '';
        return '<div'.$id_attr.' class="quick-ajax-accordion-wrapper">
            <div class="quick-ajax-accordion-toggle" tabindex="0">
                <h3 class="accordion-title">'.esc_html($title).'</h3>
                <span class="accordion-icon">
                    <span></span>
                </span>
            </div>
            <div class="quick-ajax-accordion-content">
                '.wp_kses_post($content).'
            </div>
        </div>';
    }
}