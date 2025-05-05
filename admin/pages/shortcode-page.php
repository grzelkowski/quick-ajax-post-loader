<?php 
if (!defined('ABSPATH')) {
    exit;
}

// add new element after title
add_action('edit_form_after_title', 'qapl_quick_ajax_display_shortcode_on_single_page');
function qapl_quick_ajax_display_shortcode_on_single_page($post) {
    //check the post type
    if ($post && $post->post_type === QAPL_Quick_Ajax_Helper::cpt_shortcode_slug()) {
        $shortcode = QAPL_Quick_Ajax_Helper::generate_shortcode($post->ID);
        echo '<div id="shortcode-box-wrap">';
        echo '<span class="shortcode-description">' . esc_html__('Copy and paste this shortcode on the page to display the posts list', 'quick-ajax-post-loader') . '</span>';
        echo '<div class="click-and-select-all">';
        echo '<pre><code>' . esc_html($shortcode) . '</code></pre>';
        echo '</div>';
        echo '</div>';
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
            //manual term selection checkbox
            $field_properties = QAPL_Form_Fields_Helper::get_field_manual_term_selection();
            $this->create_field($field_properties);
            //manual selected terms multiselect
            $field_properties = QAPL_Form_Fields_Helper::get_field_manual_selected_terms();
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

            //show sort button
            $field_properties = QAPL_Form_Fields_Helper::get_field_show_sort_button();
            $this->create_field($field_properties);
            //select sort options
            $field_properties = QAPL_Form_Fields_Helper::get_field_select_sort_button_options();
            $this->create_field($field_properties);
            $field_properties = QAPL_Form_Fields_Helper::get_field_show_inline_filter_sorting();
            $this->create_field($field_properties);

            //select post status
            //$field_properties = QAPL_Form_Fields_Helper::get_field_select_post_status();
            //$this->create_field($field_properties);
            //add Excluded Post IDs
            $field_properties = QAPL_Form_Fields_Helper::get_field_set_post_not_in();
            $this->create_field($field_properties);
            //set ignore sticky
            $field_properties = QAPL_Form_Fields_Helper::get_field_set_ignore_sticky_posts();
            $this->create_field($field_properties);
            //load posts via AJAX on initial load
            $field_properties = QAPL_Form_Fields_Helper::get_field_set_ajax_on_initial_load();
            $this->create_field($field_properties);
            //Infinite Scroll
            $field_properties = QAPL_Form_Fields_Helper::get_field_set_ajax_infinite_scroll();
            $this->create_field($field_properties);
            //Show end message
            $field_properties = QAPL_Form_Fields_Helper::get_field_set_show_end_message();
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
            $shortcode_page = '<div class="quick-ajax-layout-settings">';
            $shortcode_page .= '<h4>'.esc_html__('General Settings', 'quick-ajax-post-loader').'</h4>';
            //select post type
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_post_type());
            //show taxonomy checkbox
            $field_options = $this->field_options([
                'is_trigger' => true,
            ]);   
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter(), $field_options);
            //select taxonomy
            $this->fields[QAPL_Quick_Ajax_Helper::shortcode_page_select_taxonomy()]['options'] = $this->get_taxonomy_options_for_post_type();            
            $field_options = $this->field_options([
                'is_trigger' => false,
                'visible_if' => [
                    QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter() => '1'
                ]
            ]);            
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_taxonomy(), $field_options);
            //$shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_post_status());

            // manual term selection checkbox
            $field_options = $this->field_options([
                'is_trigger' => true,
                'visible_if' => [
                    QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter() => '1'
                ]
            ]);
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_manual_term_selection(), $field_options);
            
            // assign term options to field
            $this->fields[QAPL_Quick_Ajax_Helper::shortcode_page_manual_selected_terms()]['options'] = $this->get_term_options_for_taxonomy();

            // render field with multiple conditions
            $field_options = $this->field_options([
                'visible_if' => [
                    QAPL_Quick_Ajax_Helper::shortcode_page_manual_term_selection() => '1',
                    QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter() => '1'
                ]
            ]);
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_manual_selected_terms(), $field_options);

            //end manual term selection checkbox 
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_posts_per_page());
            $shortcode_page .= '</div>';
            $shortcode_page .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
            $shortcode_page .= '<h4>'.esc_html__('Sorting Settings', 'quick-ajax-post-loader').'</h4>';
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_order());
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_orderby());
            $field_options = $this->field_options([
                'is_trigger' => true,
            ]);  
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_show_sort_button(), $field_options);
            $field_options = $this->field_options([
                'visible_if' => [
                    QAPL_Quick_Ajax_Helper::shortcode_page_show_sort_button() => '1'
                ]
            ]);
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_sort_button_options(), $field_options);
            $field_options = $this->field_options([
                'visible_if' => [
                    QAPL_Quick_Ajax_Helper::shortcode_page_show_sort_button() => '1'
                ]
            ]);
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_show_inline_filter_sorting(), $field_options);
            $shortcode_page .= '</div>';
            $shortcode_page .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
            $shortcode_page .= '<h4>'.esc_html__('Additional Settings', 'quick-ajax-post-loader').'</h4>';
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_set_post_not_in());
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_ignore_sticky_posts());
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_ajax_on_initial_load());
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_ajax_infinite_scroll());
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_show_end_message());
            $shortcode_page .= '</div>';

            //layout Settings
            $shortcode_page .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
            $shortcode_page .= '<h4>'.esc_html__('layout Settings', 'quick-ajax-post-loader').'</h4>';
            $field_options = $this->field_options([
                'is_trigger' => true,
            ]); 
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_layout_quick_ajax_css_style(), $field_options);
            $field_options = $this->field_options([
                'visible_if' => [
                    QAPL_Quick_Ajax_Helper::shortcode_page_layout_quick_ajax_css_style() => '1'
                ]
            ]);
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_layout_select_columns_qty(), $field_options);
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_layout_post_item_template());
            $field_options = $this->field_options([
                'is_trigger' => false,
                'visible_if' => [
                    QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter() => '1'
                ]
            ]);
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_layout_taxonomy_filter_class(), $field_options);
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_layout_container_class());
            $field_options = $this->field_options([
                'is_trigger' => true,
            ]); 
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_show_custom_load_more_post_quantity(),$field_options);
            $field_options = $this->field_options([
                'is_trigger' => false,
                'visible_if' => [
                    QAPL_Quick_Ajax_Helper::shortcode_page_show_custom_load_more_post_quantity() => '1'
                ]
            ]); 
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_custom_load_more_post_quantity(), $field_options);
            $field_options = $this->field_options([
                'is_trigger' => true,
            ]); 
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_override_global_loader_icon(), $field_options);
            $field_options = $this->field_options([
                'is_trigger' => false,
                'visible_if' => [
                    QAPL_Quick_Ajax_Helper::shortcode_page_override_global_loader_icon() => '1'
                ]
            ]);
            $shortcode_page .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_loader_icon(), $field_options);
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