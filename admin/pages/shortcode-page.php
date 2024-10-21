<?php 
if (!defined('ABSPATH')) {
    exit;
}

add_action('edit_form_after_title', 'qapl_quick_ajax_display_shortcode_on_single_page');
function qapl_quick_ajax_display_shortcode_on_single_page($post) {
    if ($post && $post->post_type === QAPL_Quick_Ajax_Helper::cpt_shortcode_slug()) {
        $input_value = get_post_meta($post->ID, 'qapl_quick_ajax_meta_box_shortcode_shortcode', true);
        if (!empty($input_value)) {
            echo '<div id="shortcode-box-wrap" class="click-and-select-all">';
            echo '<span>' . esc_html__('Copy and paste this shortcode on the page to display the posts list', 'quick-ajax-post-loader') . '</span>';
            echo '<div>';
            echo '<pre><code id="' . esc_attr(QAPL_Quick_Ajax_Helper::meta_box_shortcode_name()) . '">' . esc_html($input_value) . '</code></pre>';
            echo '</div></div>';
        }
    }
}
add_action( 'save_post_'.QAPL_Quick_Ajax_Helper::cpt_shortcode_slug(), 'qapl_save_quick_ajax_meta_box_shortcode' );
function qapl_save_quick_ajax_meta_box_shortcode( $post_id ) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!isset($_POST[QAPL_Quick_Ajax_Helper::wp_nonce_form_quick_ajax_field()]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[QAPL_Quick_Ajax_Helper::wp_nonce_form_quick_ajax_field()])), QAPL_Quick_Ajax_Helper::wp_nonce_form_quick_ajax_action())) {
        return;
    } 

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }    
    if(isset($_POST['post_title']) && !empty($_POST['post_title'])){
        $title = sanitize_text_field(wp_unslash($_POST['post_title']));
    }else{
        $title =  'Untitled';
    }
    $excluded_post_ids = '';
    /*
    if (isset($_POST[QAPL_Quick_Ajax_Helper::shortcode_page_set_post_not_in()]) && !empty($_POST[QAPL_Quick_Ajax_Helper::shortcode_page_set_post_not_in()])) {
        $excluded_post_ids = ' excluded_post_ids="' . esc_attr(sanitize_text_field($_POST[QAPL_Quick_Ajax_Helper::shortcode_page_set_post_not_in()])) . '"';
    }
    */
    if (isset($_POST[QAPL_Quick_Ajax_Helper::shortcode_page_set_post_not_in()]) && !empty($_POST[QAPL_Quick_Ajax_Helper::shortcode_page_set_post_not_in()])) {
        $post_not_in_ids = sanitize_text_field(wp_unslash($_POST[QAPL_Quick_Ajax_Helper::shortcode_page_set_post_not_in()]));
        $excluded_ids_array = array_filter(array_map('absint', explode(',', $post_not_in_ids)));
        if (!empty($excluded_ids_array)) {
            $excluded_post_ids = ' excluded_post_ids="' . esc_attr(implode(', ', $excluded_ids_array)) . '"';
        }
    }
    
    $input_value = '[qapl-quick-ajax id="'. $post_id.'" title="'.esc_attr($title).'"'.$excluded_post_ids.']';
    update_post_meta($post_id, 'qapl_quick_ajax_meta_box_shortcode_shortcode', $input_value);
}

if (QAPL_Quick_Ajax_Helper::element_exists('class','QAPL_Quick_Ajax_Form_Creator')) {
    class QAPL_Quick_Ajax_Form_Creator extends QAPL_Quick_Ajax_Post_Type_Form {
    
        public function init_quick_ajax_creator_fields(){
            //select post type   
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_select_post_type();
            $this->create_field($field_properties);
            //show taxonomy checkbox
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_show_taxonomy_filter();
            $this->create_field($field_properties);
            //select taxonomy
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_select_taxonomy();
            $this->create_field($field_properties);
            //post per page number
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_select_posts_per_page();
            $this->create_field($field_properties);
            //select post order
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_select_order();        
            $this->create_field($field_properties);
            //select post orderby
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_select_orderby();    
            $this->create_field($field_properties);
            //select post status
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_select_post_status();
            $this->create_field($field_properties);
            //add Excluded Post IDs
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_set_post_not_in();
            $this->create_field($field_properties);
            //set ignore sticky
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_set_ignore_sticky_posts();
            $this->create_field($field_properties);
            //apply quick ajax css style
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_layout_quick_ajax_css_style();
            $this->create_field($field_properties);
            //select number of columns
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_layout_select_columns_qty();
            $this->create_field($field_properties);
            //select post item template
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_layout_post_item_template();
            $this->create_field($field_properties);
            //add custom class for taxonomy filter
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_layout_taxonomy_filter_class();
            $this->create_field($field_properties);
            //add custom class for container
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_layout_container_class();
            $this->create_field($field_properties);
            //show custom load more post quantity
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_show_custom_load_more_post_quantity();
            $this->create_field($field_properties);
            //select custom load more post quantity
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_select_custom_load_more_post_quantity();
            $this->create_field($field_properties);
            //override loader icon
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_override_global_loader_icon();
            $this->create_field($field_properties);
            //select loader icon
            $field_properties = QAPL_Quick_Ajax_Fields::get_field_select_loader_icon();
            $this->create_field($field_properties);
        }
        
        public function render_quick_ajax_form() {
            $shortcode_page = '';
            //select post type
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_post_type());
            //show taxonomy checkbox
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter(), true);
            //select taxonomy
            $taxonomies = array();
            $selected_option = $this->get_the_value_if_exist(QAPL_Quick_Ajax_Helper::shortcode_page_select_post_type());
            if (empty($selected_option)) {
                $selected_option = QAPL_Quick_Ajax_Helper::shortcode_page_select_post_type_default_value();
            }
            $post_type_object = get_post_type_object($selected_option);
            if ($post_type_object) {
                $taxonomies = get_object_taxonomies($selected_option);
            }
            $taxonomy_options = array();
            if (!empty($taxonomies)){ 
                foreach ($taxonomies as $taxonomy) : 
                        $taxonomy_object = get_taxonomy($taxonomy);
                        if ($taxonomy_object) :
                        $taxonomy_options[] = array(
                            'label' => esc_html($taxonomy_object->label),
                            'value' => $taxonomy
                        );
                        endif;
                endforeach;
            }
            $this->fields[QAPL_Quick_Ajax_Helper::shortcode_page_select_taxonomy()]['options'] = $taxonomy_options;
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_taxonomy(), QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter());

            //post settings
            $shortcode_page .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
            $shortcode_page .= '<h4>'.esc_html__('Query Settings', 'quick-ajax-post-loader').'</h4>';
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_post_status());
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_posts_per_page());
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_order());
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_orderby());
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_set_post_not_in());
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_ignore_sticky_posts());
            $shortcode_page .= '</div>';

            //layout Settings
            $shortcode_page .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
            $shortcode_page .= '<h4>'.esc_html__('layout Settings', 'quick-ajax-post-loader').'</h4>';
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_layout_quick_ajax_css_style(),true);
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_layout_select_columns_qty(), QAPL_Quick_Ajax_Helper::shortcode_page_layout_quick_ajax_css_style());
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_layout_post_item_template());
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_layout_taxonomy_filter_class(), QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter());
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_layout_container_class());
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_show_custom_load_more_post_quantity(),true);
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_custom_load_more_post_quantity(), QAPL_Quick_Ajax_Helper::shortcode_page_show_custom_load_more_post_quantity());
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_override_global_loader_icon(),true);
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_loader_icon(), QAPL_Quick_Ajax_Helper::shortcode_page_override_global_loader_icon());
            $shortcode_page .= '</div>';

            return $shortcode_page;
        }
    }

  //  $post_id = isset($_GET['post']) ? $_GET['post'] : '';
    $post_id = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);
    $post_type = get_post_type($post_id);
    if (empty($post_type)) {
        $post_type = filter_input(INPUT_GET, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($post_type)) {
            $post_type = filter_input(INPUT_POST, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
    }
    if ($post_type === QAPL_Quick_Ajax_Helper::cpt_shortcode_slug()) {
        $form = new QAPL_Quick_Ajax_Form_Creator(QAPL_Quick_Ajax_Helper::settings_wrapper_id(),$post_type);
    }
}