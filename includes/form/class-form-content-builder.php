<?php 
if (!defined('ABSPATH')) {
    exit;
}

abstract class QAPL_Form_Content_Builder{
    protected $fields = array();
    protected $existing_values = array();
    protected function create_field(QAPL_Quick_Ajax_Form_Field_Interface $field) {
        $field_properties = $field->get_field();
        if (!empty($field_properties['name'])) {
            $field = [
                'name' => $field_properties['name'],
                'label' => isset($field_properties['label']) ? $field_properties['label'] : null,
                'type' => isset($field_properties['type']) ? $field_properties['type'] : null,
                'options' => isset($field_properties['options']) ? $field_properties['options'] : null,
                'default' => isset($field_properties['default']) ? $field_properties['default'] : null,
                'placeholder' => isset($field_properties['placeholder']) ? $field_properties['placeholder'] : null,
                'description' => isset($field_properties['description']) ? $field_properties['description'] : null,
            ];
            $this->fields[$field_properties['name']] = $field;
        }
    }
    protected function add_field($field_name, $show_hide_element_id = false, $required = false){
        if($this->fields[$field_name]['type'] == 'checkbox'){
            return $this->add_checkbox_field($field_name, $show_hide_element_id, $required);
        }
        elseif($this->fields[$field_name]['type'] == 'select'){
            return $this->add_select_field($field_name, $show_hide_element_id);
        }
        elseif($this->fields[$field_name]['type'] == 'multiselect'){
            return $this->add_multiselect_field($field_name, $show_hide_element_id);
        }
        elseif($this->fields[$field_name]['type'] == 'number'){
            return $this->add_number_field($field_name, $show_hide_element_id);
        }
        elseif($this->fields[$field_name]['type'] == 'text'){
            return $this->add_text_input_field($field_name, $show_hide_element_id);
        }
        elseif($this->fields[$field_name]['type'] == 'color_picker'){
            return $this->add_color_picker_field($field_name, $show_hide_element_id);
        }
        return '';        
    }
    protected function get_the_value_if_exist($field_name){
        if(isset($this->existing_values[$field_name]['value'])){
            return $this->existing_values[$field_name]['value'];
        }
        elseif(isset($this->fields[$field_name]['default'])){
            return $this->fields[$field_name]['default'];
        }else{
            return false;
        }
    }
    protected function get_quick_ajax_form_class() {
        $current_user = wp_get_current_user();
        $scheme = get_user_option('admin_color', $current_user->ID);
        return $scheme . '-style';
    }
    private function add_checkbox_field($field_name, $field_options = [], $required = false) {
        $checked = $this->get_the_value_if_exist($field_name);
        $is_required = $required ? 'required' : '';
            
        $visibility = $this->show_hide_element($field_options);
        $field_container_class = $visibility['field_container_class'];
        $field_container_data_item = $visibility['field_container_data_item']; 
        
        $field = '<div class="quick-ajax-field-container quick-ajax-select-field qa-inline-block' . $field_container_class . '"' . $field_container_data_item . '>';
        $field .= '<label for="' . $this->fields[$field_name]['name'] . '">' . $this->fields[$field_name]['label'] . '</label>';
        $field .= '<div class="quick-ajax-field">';
        $field .= '<div class="switch-checkbox">';
        $field .= '<div class="switch-wrap">';
        $field .= '<label for="' . $this->fields[$field_name]['name'] . '">';
        $field .= '<input type="checkbox" name="' . $this->fields[$field_name]['name'] . '" id="' . $this->fields[$field_name]['name'] . '" value="1" ' . checked($checked, 1, false) . ' '. $is_required .' />';
        $field .= '<span class="switch"></span>';
        $field .= '</label>';
        $field .= '</div>';            
        $field .= '</div>';
        $field .= $this->add_field_description($this->fields[$field_name]['description']);
        $field .= '</div>';            
        $field .= '</div>';
    
        return $field;
    }
    
    private function add_select_field($field_name, $field_options = []){
        $current_value = $this->get_the_value_if_exist($field_name);
        $visibility = $this->show_hide_element($field_options);
        $field_container_class = $visibility['field_container_class'];
        $field_container_data_item = $visibility['field_container_data_item']; 
        $field = '<div class="quick-ajax-field-container quick-ajax-checkbox-field' . $field_container_class . '"' . $field_container_data_item . '>';
        $field .= '<label for="' . esc_attr($this->fields[$field_name]['name']) . '">' . esc_html($this->fields[$field_name]['label']) . '</label>';
        $field .= '<div class="quick-ajax-field">';
        $field .= '<select name="' . esc_attr($this->fields[$field_name]['name']) . '" id="' . esc_attr($this->fields[$field_name]['name']) . '">';
        if(is_array($this->fields[$field_name]['options'])){
            foreach ($this->fields[$field_name]['options'] as $option) {
                $field .= '<option value="' . esc_attr($option['value']) . '"' . selected($current_value, $option['value'], false) . '>';
                $field .= esc_html($option['label']);
                $field .= '</option>';
            }
        }

        $field .= '</select>';
        $field .= $this->add_field_description($this->fields[$field_name]['description']);
        $field .= '</div>';
        $field .= '</div>';

        return $field;
    }
    private function add_multiselect_field($field_name, $field_options = []) {
        $current_values = $this->get_the_value_if_exist($field_name);
        $show_hide_element = $this->show_hide_element($field_options);
        $field_container_data_item = $show_hide_element['field_container_data_item'];
        $field_container_class = $show_hide_element['field_container_class'];
        $field = '<div class="quick-ajax-field-container quick-ajax-multiselect-field' . $field_container_class . '"' . $field_container_data_item . '>';
        $field .= '<label>' . esc_html($this->fields[$field_name]['label']) . '</label>';
        $field .= '<div class="quick-ajax-field">';

        $field .= '<div class="quick-ajax-field-options" id="' . esc_attr($this->fields[$field_name]['name']) . '">';
        if(is_array($this->fields[$field_name]['options'])){
            foreach ($this->fields[$field_name]['options'] as $option) {
                if (is_array($current_values)) {
                    $checked = checked(in_array($option['value'], $current_values), true, false);
                } else {
                    $checked = checked($option['value'], true, false);
                }
                $field .= '<div class="quick-ajax-multiselect-option">';         
                $field .= '<label class="quick-ajax-checkbox">';
                $field .= '<input type="checkbox" name="' . esc_attr($this->fields[$field_name]['name']) . '[]" value="' . esc_attr($option['value']) . '" ' . $checked . '>';
                $field .= esc_html($option['label']);
                $field .= '</label>';
                $field .= '</div>';
            }
        }elseif(is_string($this->fields[$field_name]['options'])){
            $field .= '<div class="quick-ajax-multiselect-option">'.$this->fields[$field_name]['options'].'</div>';
        }
        $field .= '</div>';
        
        $field .= $this->add_field_description($this->fields[$field_name]['description']);
        $field .= '</div>';
        $field .= '</div>';
    
        return $field;
    }
    
    private function add_number_field($field_name, $field_options = []){
        $current_value = $this->get_the_value_if_exist($field_name);
        $show_hide_element = $this->show_hide_element($field_options);
        $field_container_data_item = $show_hide_element['field_container_data_item'];
        $field_container_class = $show_hide_element['field_container_class'];
        
        $field = '<div class="quick-ajax-field-container quick-ajax-number-field' . $field_container_class . '"' . $field_container_data_item . '>';
        $field .= '<label for="' . esc_attr($this->fields[$field_name]['name']) . '">' . esc_html($this->fields[$field_name]['label']) . '</label>';
        $field .= '<div class="quick-ajax-field">';
        $field .= '<input type="number" name="' . esc_attr($this->fields[$field_name]['name']) . '" id="' . esc_attr($this->fields[$field_name]['name']) . '" value="' . esc_attr($current_value) . '" />';
        $field .= $this->add_field_description($this->fields[$field_name]['description']);
        $field .= '</div>';
        $field .= '</div>';

        return $field;
    }
    private function add_text_input_field($field_name, $field_options = []){
        $current_value = $this->get_the_value_if_exist($field_name);
        $show_hide_element = $this->show_hide_element($field_options);
        $field_container_data_item = $show_hide_element['field_container_data_item'];
        $field_container_class = $show_hide_element['field_container_class'];
        $placeholder = !empty($this->fields[$field_name]['placeholder']) ? ' placeholder="' . esc_attr($this->fields[$field_name]['placeholder']) . '"' : '';
        
        $field = '<div class="quick-ajax-field-container quick-ajax-text-input-field' . $field_container_class . '"' . $field_container_data_item . '>';
        $field .= '<label for="' . esc_attr($this->fields[$field_name]['name']) . '">' . esc_html($this->fields[$field_name]['label']) . '</label>';
        $field .= '<div class="quick-ajax-field">';
        $field .= '<input type="text" name="' . esc_attr($this->fields[$field_name]['name']) . '" id="' . esc_attr($this->fields[$field_name]['name']) . '" value="' . esc_attr($current_value) . '"' . $placeholder . '/>';
        $field .= $this->add_field_description($this->fields[$field_name]['description']);
        $field .= '</div>';
        $field .= '</div>';
    
        return $field;
    }
    private function add_color_picker_field($field_name, $field_options = []){
        ob_start();
        $current_value = $this->get_the_value_if_exist($field_name);
        $show_hide_element = $this->show_hide_element($field_options);
        $field_container_data_item = $show_hide_element['field_container_data_item'];
        $field_container_class = $show_hide_element['field_container_class'];

        $field = '<div class="quick-ajax-field-container quick-ajax-text-input-field' . $field_container_class . '"' . $field_container_data_item . '>';
        $field .= '<label for="' . esc_attr($this->fields[$field_name]['name']) . '">' . esc_html($this->fields[$field_name]['label']) . '</label>';
        $field .= '<div class="quick-ajax-field">';
        $field .= '<input type="text" class="color-picker-field" name="' . esc_attr($this->fields[$field_name]['name']) . '" id="' . esc_attr($this->fields[$field_name]['name']) . '" value="' . esc_attr($current_value) . '"/>';
        $field .= $this->add_field_description($this->fields[$field_name]['description']);
        $field .= '</div>';
        $field .= '</div>';
    
        return $field;
    }
    protected function field_options(array $overrides = []): array {
        $defaults = [
            'is_trigger' => false,
            'visible_if' => [],
        ];
    
        return array_merge($defaults, $overrides);
    }    
    private function show_hide_element($field_options) {
        $element_data = [
            'field_container_data_item' => '',
            'field_container_class'     => ''
        ];
    
        // check if field is trigger
        if (!empty($field_options['is_trigger']) && $field_options['is_trigger'] === true) {
            $element_data['field_container_class'] .= ' show-hide-trigger';
        }
    
        // check if field has visibility conditions
        if (!empty($field_options['visible_if']) && is_array($field_options['visible_if'])) {
           
            $conditions = $field_options['visible_if'];
            $element_data['field_container_data_item'] = ' data-conditional=\'' . json_encode($conditions) . '\'';
            
            foreach ($conditions as $field => $expected_value) {
                $actual_value = $this->get_the_value_if_exist($field);
                if ((string)$actual_value !== (string)$expected_value) {
                    $element_data['field_container_class'] .= ' inactive';
                    break;
                }
            }
        }
        return $element_data;
    }
    
    private function add_field_description($field_description) {
        if (!empty($field_description)) {
            return '<p class="quick-ajax-field-desc">' . esc_html($field_description) . '</p>';
        }
        return '';
    }
    protected function get_taxonomy_options_for_post_type(?string $post_type = null): array{
        if (empty($post_type)) {
            $post_type = $this->get_the_value_if_exist(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_POST_TYPE);
            if (empty($post_type)) {
                $post_type = QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_POST_TYPE_DEFAULT;
            }
        }
        $taxonomy_options = [];
        $post_type_object = get_post_type_object($post_type);
        if ($post_type_object) {
            $taxonomies = get_object_taxonomies($post_type);
            if (!empty($taxonomies)) {
                foreach ($taxonomies as $taxonomy) {
                    $taxonomy_object = get_taxonomy($taxonomy);
                    if ($taxonomy_object) {
                        $taxonomy_options[] = [
                            'label' => esc_html($taxonomy_object->label),
                            'value' => $taxonomy
                        ];
                    }
                }
            }
        }
        if (empty($taxonomy_options)) {
            $taxonomy_options[] = [
                'label' => esc_html__('No taxonomy found', 'quick-ajax-post-loader'),
                'value' => 0
            ];
        }
        return $taxonomy_options;
    }
    protected function get_term_options_for_taxonomy(?string $taxonomy = null){
        if (empty($taxonomy)) {
            // try to get taxonomy from saved shortcode value
            $taxonomy = $this->get_the_value_if_exist(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_TAXONOMY);
        }
        if (empty($taxonomy)) {
            // try to get first available taxonomy based on post type
            $post_type = $this->get_the_value_if_exist(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_POST_TYPE);
            if (empty($post_type)) {
                // fallback to default post type
                $post_type = QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_POST_TYPE_DEFAULT;
            }
            if (!empty($post_type)) {
                $taxonomies = get_object_taxonomies($post_type);
                if (!empty($taxonomies)) {
                    $taxonomy = $taxonomies[0]; // use first found taxonomy
                }
            }
        }
    
        if (empty($taxonomy)) {
            return '<span class="no-options">'.esc_html__('No taxonomy available', 'quick-ajax-post-loader').'</span>';
        }
        $terms = get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
        ]);
        if (empty($terms) || is_wp_error($terms)) {
            return '<span class="no-options">'.esc_html__('No terms found', 'quick-ajax-post-loader').'</span>';
        }
        $options = [];
        foreach ($terms as $term) {
            $options[] = [
                'label' => esc_html($term->name),
                'value' => $term->term_id
            ];
        }
        return $options;
    }

    protected function create_accordion_block($title, $content){
        return '<div class="quick-ajax-accordion-wrapper">
            <div class="quick-ajax-accordion-toggle" tabindex="0">
                <h3 class="accordion-title">'.$title.'</h3>
                <span class="accordion-icon">
                    <span></span>
                </span>
            </div>
            <div class="quick-ajax-accordion-content">
                '.$content.'
            </div>
        </div>';
    }
}