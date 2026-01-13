<?php 
if (!defined('ABSPATH')) {
    exit;
}

abstract class QAPL_CPT_Editor_Form extends QAPL_Form_Content_Builder {
    protected $form_id;
    protected $meta_key;
    protected $post_type;

    public function __construct($form_id, $meta_key, $post_type) {
        $this->form_id = $form_id;
        $this->meta_key = $meta_key;
        $this->post_type = $post_type;

        if($this->post_type){
            // add_action('wp_loaded', array($this, 'init_post_fields'), 10);
            //register all fields
            $this->init_post_fields();
            //editor hooks
            add_action('edit_form_after_title', array($this, 'add_quick_ajax_form'));
            add_action('save_post_'.$this->post_type, array($this, 'save_quick_ajax_form'));
        }
    }
    abstract public function init_post_fields();
    abstract public function render_form();
    
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
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output already escaped
            echo $this->render_form();
            wp_nonce_field(QAPL_Constants::NONCE_FORM_QUICK_AJAX_ACTION, QAPL_Constants::NONCE_FORM_QUICK_AJAX_FIELD);
            echo '</div>';
        }
    }   
    
    public function save_quick_ajax_form($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!isset($_POST[QAPL_Constants::NONCE_FORM_QUICK_AJAX_FIELD]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[QAPL_Constants::NONCE_FORM_QUICK_AJAX_FIELD])), QAPL_Constants::NONCE_FORM_QUICK_AJAX_ACTION)) {
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
        //error_log(print_r($_POST, true));
        //error_log(print_r($form_data, true));
        $serialized_data = serialize($form_data);
        update_post_meta($post_id, $this->meta_key, $serialized_data);
    }
}