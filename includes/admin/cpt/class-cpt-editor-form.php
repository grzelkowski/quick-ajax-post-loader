<?php 
if (!defined('ABSPATH')) {
    exit;
}

abstract class QAPL_CPT_Editor_Form extends QAPL_Form_Content_Builder {
    protected $form_id;
    protected $meta_key;
    protected $post_type;
    private $is_initialized = false;

    public function __construct($form_id, $meta_key, $post_type) {
        parent::__construct(); 
        $this->form_id = $form_id;
        $this->meta_key = $meta_key;
        $this->post_type = $post_type;

        if($this->post_type){
            //editor hooks
            add_action('edit_form_after_title', array($this, 'add_quick_ajax_form'));            
            add_action('save_post_'.$this->post_type, array($this, 'save_quick_ajax_form'));
        }
    }
    protected function ensure_fields_initialized() {
        // lazy init to avoid rebuilding fields
        if ($this->is_initialized) {
            return;
        }
        $this->init_post_fields();
        $this->is_initialized = true;
    }
    abstract public function init_post_fields();
    abstract public function render_form();

    public function add_quick_ajax_form($post){ 
        if ($post->post_type !== $this->post_type) {
            return;
        }
        $this->ensure_fields_initialized();
        $this->set_value_provider(
            new QAPL_Post_Meta_Value_Provider($post->ID, $this->meta_key)
        );
        echo '<div class="quick-ajax-form-wrap '.esc_attr($this->get_quick_ajax_form_class()).'" id="' . esc_attr($this->form_id) . '">';
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output already escaped
        echo $this->render_form();
        wp_nonce_field(QAPL_Constants::NONCE_FORM_QUICK_AJAX_ACTION, QAPL_Constants::NONCE_FORM_QUICK_AJAX_FIELD);
        echo '</div>';
      
    }   
    
    public function save_quick_ajax_form($post_id) {
        $sanitizer = new QAPL_Field_Sanitizer();
        if (get_post_type($post_id) !== $this->post_type) {
            return;
        }
        // skip autosave requests
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        // skip post revisions
        if (wp_is_post_revision($post_id)) {
            return;
        }
        //verify nonce to prevent unauthorized save
        if (!isset($_POST[QAPL_Constants::NONCE_FORM_QUICK_AJAX_FIELD]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[QAPL_Constants::NONCE_FORM_QUICK_AJAX_FIELD])), QAPL_Constants::NONCE_FORM_QUICK_AJAX_ACTION)) {
            return;
        }       
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        //ensure field definitions exist for validation
        $this->ensure_fields_initialized();
        if (empty($this->field_registry->all())) {
            return;
        }
        $form_data = array();
        $fields_all = $this->field_registry->all();
        foreach ($fields_all as $field) {
            $name = $field->get_name();
            $raw_value = $_POST[$name] ?? null;
            if (is_array($raw_value) || is_string($raw_value)) {
                $raw_value = wp_unslash($raw_value);
            }
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized via $sanitizer->sanitize_field
            $value = $sanitizer->sanitize_field($field, $raw_value);
            $form_data[$name] = $value;
        }
        update_post_meta($post_id, $this->meta_key, $form_data);
    }
}