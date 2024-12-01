<?php 
if (!defined('ABSPATH')) {
    exit;
}

// add new element after title
add_action('edit_form_after_title', 'qapl_quick_ajax_display_shortcode_on_single_page');
function qapl_quick_ajax_display_shortcode_on_single_page($post) {
    //check the post type
    if ($post && $post->post_type === QAPL_Quick_Ajax_Helper::cpt_shortcode_slug()) {
           
        $excluded_post_ids = '';
        $serialized_data = get_post_meta($post->ID, QAPL_Quick_Ajax_Helper::quick_ajax_shortcode_settings(), true);
        if ($serialized_data) {
            $form_data = maybe_unserialize($serialized_data);
            if (is_array($form_data)) { // ensure data is valid
                foreach ($form_data as $field_name => $field_value) {
                    if ($field_name === QAPL_Quick_Ajax_Helper::shortcode_page_set_post_not_in() && !empty($field_value)) { 
                        $excluded_post_ids = ' excluded_post_ids="'.$field_value.'"';
                    }
                }
            }
        }
        $shortcode = '[qapl-quick-ajax id="' . intval($post->ID) . '" title="' . esc_attr($post->post_title) . '"'.$excluded_post_ids.']';
        echo '<div id="shortcode-box-wrap" class="click-and-select-all">';
        echo '<span>' . esc_html__('Copy and paste this shortcode on the page to display the posts list', 'quick-ajax-post-loader') . '</span>';
        echo '<div>';
        echo '<pre><code>' . esc_html($shortcode) . '</code></pre>';
        echo '</div></div>';
    }
}

if (!class_exists('QAPL_Quick_Ajax_Form_Creator')) {
    class QAPL_Quick_Ajax_Form_Creator extends QAPL_Quick_Ajax_Post_Type_Form {
    
        public function init_quick_ajax_creator_fields(){
            //select post type   
            $field_properties = QAPL_Form_Fields_Helper::get_field_select_post_type();
            $this->create_field($field_properties);
            //show taxonomy checkbox
            $field_properties = QAPL_Form_Fields_Helper::get_field_show_taxonomy_filter();
            $this->create_field($field_properties);
            //select taxonomy
            $field_properties = QAPL_Form_Fields_Helper::get_field_select_taxonomy();
            $this->create_field($field_properties);
            //post per page number
            $field_properties = QAPL_Form_Fields_Helper::get_field_select_posts_per_page();
            $this->create_field($field_properties);
            //select post order
            $field_properties = QAPL_Form_Fields_Helper::get_field_select_order();        
            $this->create_field($field_properties);
            //select post orderby
            $field_properties = QAPL_Form_Fields_Helper::get_field_select_orderby();    
            $this->create_field($field_properties);
            //select post status
            $field_properties = QAPL_Form_Fields_Helper::get_field_select_post_status();
            $this->create_field($field_properties);
            //add Excluded Post IDs
            $field_properties = QAPL_Form_Fields_Helper::get_field_set_post_not_in();
            $this->create_field($field_properties);
            //set ignore sticky
            $field_properties = QAPL_Form_Fields_Helper::get_field_set_ignore_sticky_posts();
            $this->create_field($field_properties);
            //apply quick ajax css style
            $field_properties = QAPL_Form_Fields_Helper::get_field_layout_quick_ajax_css_style();
            $this->create_field($field_properties);
            //select number of columns
            $field_properties = QAPL_Form_Fields_Helper::get_field_layout_select_columns_qty();
            $this->create_field($field_properties);
            //select post item template
            $field_properties = QAPL_Form_Fields_Helper::get_field_layout_post_item_template();
            $this->create_field($field_properties);
            //add custom class for taxonomy filter
            $field_properties = QAPL_Form_Fields_Helper::get_field_layout_taxonomy_filter_class();
            $this->create_field($field_properties);
            //add custom class for container
            $field_properties = QAPL_Form_Fields_Helper::get_field_layout_container_class();
            $this->create_field($field_properties);
            //show custom load more post quantity
            $field_properties = QAPL_Form_Fields_Helper::get_field_show_custom_load_more_post_quantity();
            $this->create_field($field_properties);
            //select custom load more post quantity
            $field_properties = QAPL_Form_Fields_Helper::get_field_select_custom_load_more_post_quantity();
            $this->create_field($field_properties);
            //override loader icon
            $field_properties = QAPL_Form_Fields_Helper::get_field_override_global_loader_icon();
            $this->create_field($field_properties);
            //select loader icon
            $field_properties = QAPL_Form_Fields_Helper::get_field_select_loader_icon();
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
            }else{
                $taxonomy_options[] = array(
                    'label' => esc_html__('No taxonomy found', 'quick-ajax-post-loader'),
                    'value' => 0
                );
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
        $form = new QAPL_Quick_Ajax_Form_Creator(QAPL_Quick_Ajax_Helper::settings_wrapper_id(), QAPL_Quick_Ajax_Helper::quick_ajax_shortcode_settings(), $post_type);
    }
}