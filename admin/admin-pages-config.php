<?php 
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('QAPL_Quick_Ajax_Admin_Pages')) {
    class QAPL_Quick_Ajax_Admin_Pages {
        public function __construct() {
            add_action('admin_menu', array($this, 'add_quick_ajax_menu'));
            add_action('admin_menu', array($this, 'add_quick_ajax_settings_page'));
            add_action('admin_init', array($this, 'register_quick_ajax_settings'));
        }
        public function add_quick_ajax_menu(){
            // Quick Ajax Menu
            add_menu_page(
                'Quick AJAX',
                'Quick AJAX',
                'manage_options',
                QAPL_Quick_Ajax_Helper::menu_slug(),
                array($this, 'options_page_content'),
                'dashicons-editor-code',
                80
            );
            // "Add New"
            add_submenu_page(
                QAPL_Quick_Ajax_Helper::menu_slug(),
                __('Add New', 'quick-ajax-post-loader'),
                __('Add New', 'quick-ajax-post-loader'),
                'edit_posts',
                'post-new.php?post_type=' . QAPL_Quick_Ajax_Helper::cpt_shortcode_slug()
            );
        }
        public function add_quick_ajax_settings_page() {
            // "settings"
            add_submenu_page(
                QAPL_Quick_Ajax_Helper::menu_slug(),
                __('Settings & Features', 'quick-ajax-post-loader'),
                __('Settings & Features', 'quick-ajax-post-loader'),
                'manage_options',
                QAPL_Quick_Ajax_Helper::settings_page_slug(),
                array($this, 'render_quick_ajax_settings_page')
            );
        }
        public function render_quick_ajax_settings_page() {
            // "settings Page"
            if (!current_user_can('manage_options')) {
                wp_die(esc_html(__('You do not have sufficient permissions to access this page.', 'quick-ajax-post-loader')));
            }
            if (class_exists('QAPL_Quick_Ajax_Creator_Settings_Page')) {
                $form = new QAPL_Quick_Ajax_Creator_Settings_Page(QAPL_Quick_Ajax_Helper::admin_page_settings_field_option_group(), QAPL_Quick_Ajax_Helper::admin_page_global_options_name());
                $form->render_quick_ajax_page();
            }
        }
        public function register_quick_ajax_settings() {
            // Register the settings group
            register_setting(
                QAPL_Quick_Ajax_Helper::admin_page_settings_field_option_group(),
                QAPL_Quick_Ajax_Helper::admin_page_global_options_name(),
                array($this, 'quick_ajax_sanitize_callback')
            );
        }
        
        public function quick_ajax_sanitize_callback($values){
        //error_log('quick_ajax_sanitize_callback: ' . print_r($values, true));
        $sanitized_value = is_array($values) ? array() : '';
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $sanitized_value[$key] = sanitize_text_field($value);
            }
        } else {
            $sanitized_value = sanitize_text_field($values);
        }
        //error_log('Sanitized value: ' . print_r($sanitized_value, true));
        return $sanitized_value;
        }    
    }
    $quick_ajax_admin_pages = new QAPL_Quick_Ajax_Admin_Pages();
}


if (!class_exists('QAPL_Quick_Ajax_Post_Type')) {
    class QAPL_Quick_Ajax_Post_Type {
        public function __construct() {
            add_action('init', array($this, 'register_quick_ajax_post_type'));
            add_action('manage_' . QAPL_Quick_Ajax_Helper::cpt_shortcode_slug() . '_posts_columns', array($this, 'quick_ajax_shortcode_column'));
            add_action('manage_' . QAPL_Quick_Ajax_Helper::cpt_shortcode_slug() . '_posts_custom_column', array($this, 'quick_ajax_shortcode_column_content'), 10, 2);
            add_filter('manage_' . QAPL_Quick_Ajax_Helper::cpt_shortcode_slug() . '_posts_columns', array($this, 'quick_ajax_shortcode_column_sort'));
        }

        public function register_quick_ajax_post_type() {
        // Quick Ajax CPT
            $labels = array(
                'name'               => __('Quick Ajax Shortcodes', 'quick-ajax-post-loader'),
                'singular_name'      => __('Quick Ajax Shortcode', 'quick-ajax-post-loader'),
                'add_new'            => __('Add New', 'quick-ajax-post-loader'),
                'add_new_item'       => __('Add New Quick Ajax', 'quick-ajax-post-loader'),
                'edit_item'          => __('Edit Quick Ajax', 'quick-ajax-post-loader'),
                'new_item'           => __('New Quick Ajax', 'quick-ajax-post-loader'),
                'view_item'          => __('View Quick Ajax', 'quick-ajax-post-loader'),
                'search_items'       => __('Search Quick Ajax', 'quick-ajax-post-loader'),
                'not_found'          => __('No Items found', 'quick-ajax-post-loader'),
                'not_found_in_trash' => __('No Items found in trash', 'quick-ajax-post-loader'),
                'parent_item_colon'  => '',
                'menu_name'          => __('Shortcodes', 'quick-ajax-post-loader'),
            );
            $args = array(
                'labels'              => $labels,
                'public'              => false,
                'publicly_queryable'  => false,
                'show_ui'             => true,
                'show_in_menu'        => QAPL_Quick_Ajax_Helper::menu_slug(),
                'query_var'           => true,
                'rewrite'             => array( 'slug' => QAPL_Quick_Ajax_Helper::cpt_shortcode_slug() ),
                'capability_type'     => 'post',
                'has_archive'         => true,
                'hierarchical'        => false,
                'menu_position'       => 25,
                'supports'            => array( 'title'),
                'menu_icon'            => 'dashicons-editor-code'
            );
            register_post_type( QAPL_Quick_Ajax_Helper::cpt_shortcode_slug(), $args );
        }

        public function quick_ajax_shortcode_column($columns) {
            //add Shortcode Column
            $columns['quick_ajax_shortcode_column'] = 'Shortcode';
            return $columns;
        }

        public function quick_ajax_shortcode_column_content($column_name, $post_id) {
            //add Shortcode Column Content
            if ($column_name === 'quick_ajax_shortcode_column') {                
                $excluded_post_ids = '';
                $shortcode = QAPL_Quick_Ajax_Helper::generate_shortcode($post_id);
                echo '<div class="quick-ajax-shortcode">' . esc_html($shortcode)  . '</div>';
            }
        }

        public function quick_ajax_shortcode_column_sort($columns) {
            //sort Columns
            $new_columns = array('cb' => $columns['cb']);
            unset($columns['cb']);
            $new_columns['title'] = $columns['title'];
            $new_columns['quick_ajax_shortcode_column'] = __('Shortcode', 'quick-ajax-post-loader');
            $new_columns['author'] = __('Author', 'quick-ajax-post-loader');
            $new_columns['date'] = $columns['date'];
            return array_merge($new_columns, $columns);
        }
    }
    $quick_ajax_post_type = new QAPL_Quick_Ajax_Post_Type();
}


abstract class QAPL_Quick_Ajax_Content_Builder{
    protected $fields = array();
    protected $existing_values = array();
    protected function create_field($field_properties) {
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
    private function add_checkbox_field($field_name, $show_hide_element_id = false, $required = false){
        $checked = $this->get_the_value_if_exist($field_name);
        $field_container_class = $field_container_data_item = '';
        if(!empty($show_hide_element_id) && !is_string($show_hide_element_id) && $show_hide_element_id === true){
            $field_container_class =' show-hide-element';
        }elseif(!empty($show_hide_element_id) && is_string($show_hide_element_id)){
            $show_hide_element = $this->show_hide_element($show_hide_element_id);
            $field_container_data_item = $show_hide_element['field_container_data_item'];
            $field_container_class = $show_hide_element['field_container_class'];   
        }
        $is_required = $required ? 'required' : '';
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
    private function add_select_field($field_name, $show_hide_element_id = false){
        $current_value = $this->get_the_value_if_exist($field_name);
        $show_hide_element = $this->show_hide_element($show_hide_element_id);
        $field_container_data_item = $show_hide_element['field_container_data_item'];
        $field_container_class = $show_hide_element['field_container_class'];   
        
        $field = '<div class="quick-ajax-field-container quick-ajax-checkbox-field' . $field_container_class . '"' . $field_container_data_item . '>';
        $field .= '<label for="' . esc_attr($this->fields[$field_name]['name']) . '">' . esc_html($this->fields[$field_name]['label']) . '</label>';
        $field .= '<div class="quick-ajax-field">';
        $field .= '<select name="' . esc_attr($this->fields[$field_name]['name']) . '" id="' . esc_attr($this->fields[$field_name]['name']) . '">';

        foreach ($this->fields[$field_name]['options'] as $option) {
            $field .= '<option value="' . esc_attr($option['value']) . '"' . selected($current_value, $option['value'], false) . '>';
            $field .= esc_html($option['label']);
            $field .= '</option>';
        }

        $field .= '</select>';
        $field .= $this->add_field_description($this->fields[$field_name]['description']);
        $field .= '</div>';
        $field .= '</div>';

        return $field;
    }
    private function add_multiselect_field($field_name, $show_hide_element_id = false) {
        $current_values = $this->get_the_value_if_exist($field_name);
        $show_hide_element = $this->show_hide_element($show_hide_element_id);
        $field_container_data_item = $show_hide_element['field_container_data_item'];
        $field_container_class = $show_hide_element['field_container_class'];
        $field = '<div class="quick-ajax-field-container quick-ajax-multiselect-field' . $field_container_class . '"' . $field_container_data_item . '>';
        $field .= '<label>' . esc_html($this->fields[$field_name]['label']) . '</label>';
        $field .= '<div class="quick-ajax-field">';
    
        foreach ($this->fields[$field_name]['options'] as $option) {
            if(is_array($current_values)){
                $checked = in_array($option['value'], $current_values) ? 'checked' : '';
            }else{
                $checked = $option['value'] ? 'checked' : '';
            }
            $field .= '<div class="quick-ajax-multiselect-option">';         
            $field .= '<label class="quick-ajax-checkbox">';
            $field .= '<input type="checkbox" name="' . esc_attr($this->fields[$field_name]['name']) . '[]" value="' . esc_attr($option['value']) . '" ' . $checked . '>';
            $field .= esc_html($option['label']);
            $field .= '</label>';
            $field .= '</div>';
        }
    
        $field .= $this->add_field_description($this->fields[$field_name]['description']);
        $field .= '</div>';
        $field .= '</div>';
    
        return $field;
    }
    
    private function add_number_field($field_name, $show_hide_element_id = false){
        $current_value = $this->get_the_value_if_exist($field_name);
        $show_hide_element = $this->show_hide_element($show_hide_element_id);
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
    private function add_text_input_field($field_name, $show_hide_element_id = false){
        $current_value = $this->get_the_value_if_exist($field_name);
        $show_hide_element = $this->show_hide_element($show_hide_element_id);
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
    private function add_color_picker_field($field_name, $show_hide_element_id = false){
        ob_start();
        $current_value = $this->get_the_value_if_exist($field_name);
        $show_hide_element = $this->show_hide_element($show_hide_element_id);
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
    private function show_hide_element($show_hide_element_id){
        $element_data['field_container_data_item'] = '';
        $element_data['field_container_class'] = '';
        if(!empty($show_hide_element_id)){
            $element_data['field_container_data_item'] = ' data-item="'.$show_hide_element_id.'"';
            $toggle_value = $this->get_the_value_if_exist($show_hide_element_id);
            if(empty($toggle_value) || $toggle_value == 0){
                $element_data['field_container_class'] = ' inactive';
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
    protected function wp_kses_allowed_tags(){
        return array(
            'div' => array(
                'class' => array(),
                'id' => array(),
                'style' => array(),
                'tabindex' => array(),
                'data-item' => array(),
                'role' => array(),
                'hidden' => array(),
            ),
            'button' => array(
                'type' => array(),
                'class' => array(),
                'style' => array(),
                'id' => array(),                
                'data-tab' => array(),
                'data-output' => array(),
                'data-copy' => array(),
                'role' => array(),
                'aria-selected' => array(),
                'aria-controls' => array(),
            ),
            'input' => array(
                'type' => array(),
                'name' => array(),
                'id' => array(),
                'value' => array(),
                'checked' => array(),
                'style' => array(),
                'placeholder' => array(),
                'class' => array(),
                'disabled' => array(),
                'readonly' => array(),
                'size' => array(),
                'maxlength' => array(),
                'min' => array(),
                'max' => array(),
                'step' => array(),
                'required' => array(),
            ),
            'select' => array(
                'name' => array(),
                'id' => array(),
                'style' => array(),
            ),
            'option' => array(
                'value' => array(),
                'selected' => array(),
                'style' => array(),
            ),
            'label' => array(
                'for' => array(),
                'style' => array(),
            ),
            'span' => array(
                'class' => array(),
                'style' => array(),
            ),
            'p' => array(
                'class' => array(),
                'style' => array(),
            ),
            'h1' => array(
                'class' => array(),
                'style' => array(),
            ),
            'h2' => array(
                'class' => array(),
                'style' => array(),
            ),
            'h3' => array(
                'class' => array(),
                'style' => array(),
            ),
            'h4' => array(
                'class' => array(),
                'style' => array(),
            ),
            'h5' => array(
                'class' => array(),
                'style' => array(),
            ),
            'h6' => array(
                'class' => array(),
                'style' => array(),
            ),
            'strong' => array(
                'class' => array(),
            ),
            'ul' => array(
                'class' => array(),
                'style' => array(),
                'id' => array(),
            ),
            'li' => array(
                'class' => array(),
                'style' => array(),
                'id' => array(),
            ),
            'code' => array(
                'class' => array(),
            ),
            'pre' => array(
                'class' => array(),
                'id' => array(),
            ),            
            'form' => array(
                'class' => array(),
                'id' => array(),
                'action' => array(),
                'method' => array(),
            ),
        );
    } 
}

abstract class QAPL_Quick_Ajax_Post_Type_Form extends QAPL_Quick_Ajax_Content_Builder {
    protected $form_id;
    protected $meta_key;
    protected $post_type;
    public function __construct($form_id, $meta_key, $post_type) {
        $this->form_id = $form_id;
        $this->meta_key = $meta_key;
        $this->post_type = $post_type;
        if($this->post_type){
            add_action('wp_loaded', array($this, 'init_quick_ajax_creator_fields'), 10);
            add_action('edit_form_after_title', array($this, 'add_quick_ajax_form'));
            add_action('save_post_'.$this->post_type, array($this, 'save_quick_ajax_form'));
        }
    }
    abstract public function init_quick_ajax_creator_fields();
    abstract public function render_quick_ajax_form();
    
    private function unserialize_data($post_id) {
        $serialized_data = get_post_meta($post_id, $this->meta_key, true);
        if ($serialized_data) {
            $form_data = maybe_unserialize($serialized_data);            
            if (is_array($form_data)) { // Check if the data was successfully unserialized
                foreach ($form_data as $field_name => $field_value) {
                    $this->existing_values[$field_name] = array(
                        'name' => $field_name,
                        'value' => $field_value
                    );
                }
            }
        }else {
            // Log the error if unserialization fails
            if (defined('WP_DEBUG') && WP_DEBUG) {
                //error_log('Quick Ajax Post Loader - Failed to unserialize data for post ID: ' . $post_id);
            }
        }
    }    
    
    public function add_quick_ajax_form($post){ 
        if ($post->post_type === $this->post_type) {
            $this->unserialize_data($post->ID);
            echo '<div class="quick-ajax-form-wrap '.esc_attr($this->get_quick_ajax_form_class()).'" id="' . esc_attr($this->form_id) . '">';
            echo wp_kses($this->render_quick_ajax_form(), $this->wp_kses_allowed_tags());
            wp_nonce_field(QAPL_Quick_Ajax_Helper::wp_nonce_form_quick_ajax_action(), QAPL_Quick_Ajax_Helper::wp_nonce_form_quick_ajax_field());
            echo '</div>';
        }
    }   
    
    public function save_quick_ajax_form($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!isset($_POST[QAPL_Quick_Ajax_Helper::wp_nonce_form_quick_ajax_field()]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[QAPL_Quick_Ajax_Helper::wp_nonce_form_quick_ajax_field()])), QAPL_Quick_Ajax_Helper::wp_nonce_form_quick_ajax_action())) {
            return;
        }       
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }        
        $form_data = array();
        foreach ($this->fields as $field) {
            if (($field['type'] == 'checkbox') && !isset($_POST[$field['name']])) {
                $form_data[$field['name']] = 0;
            } elseif (isset($_POST[$field['name']])) {
                if (is_array($_POST[$field['name']])) {
                    $field_value = array_map('sanitize_text_field', wp_unslash($_POST[$field['name']]));
                    $form_data[$field['name']] = $field_value;
                }else{
                    $field_value = sanitize_text_field(wp_unslash($_POST[$field['name']]));
                    $form_data[$field['name']] = $field_value;
                }
            }
        }        
        $serialized_data = serialize($form_data);
        update_post_meta($post_id, $this->meta_key, $serialized_data);
    }
}

abstract class QAPL_Quick_Ajax_Manage_Options_Form extends QAPL_Quick_Ajax_Content_Builder {
    protected $option_group;
    protected $option_name;
    protected $tabs = [];
    
    public function __construct($option_group, $option_name) {
        $this->option_group = $option_group;
        $this->option_name = $option_name;
        $this->unserialize_data();
        $this->init_quick_ajax_creator_fields();
        $this->init_quick_ajax_content();
    }
    
    abstract public function render_quick_ajax_page_heading();
    abstract public function init_quick_ajax_creator_fields();
    abstract public function init_quick_ajax_content();

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

    public function render_quick_ajax_page(){ 
        echo '<div class="wrap">';
        echo '<div class="quick-ajax-heading">';
        echo wp_kses($this->render_quick_ajax_page_heading(), $this->wp_kses_allowed_tags());
        echo '</div>';
        echo '<div class="quick-ajax-form-wrap ' . esc_attr($this->get_quick_ajax_form_class()) . '" id="form-' . esc_attr($this->option_group) . '">';
        //echo '<form method="post" action="options.php">';
        //settings_fields($this->option_group); // Output security fields for the registered settings
        echo wp_kses($this->render_quick_ajax_tabs_navigation(), $this->wp_kses_allowed_tags());
        echo wp_kses($this->render_quick_ajax_tabs_content(), $this->wp_kses_allowed_tags());
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