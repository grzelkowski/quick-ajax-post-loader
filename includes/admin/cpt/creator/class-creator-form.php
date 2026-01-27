<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_CPT_Creator_Form extends QAPL_CPT_Editor_Form {
    public function init_post_fields(){
        //select post type   
        $field = QAPL_Form_Field_Factory::build_select_post_type_field();
        $this->create_field($field);
        //show taxonomy checkbox
        $field = QAPL_Form_Field_Factory::build_show_taxonomy_filter_field();
        $this->create_field($field);
        //select taxonomy
        $field = QAPL_Form_Field_Factory::build_select_taxonomy_field();
        $this->create_field($field);
        //manual term selection checkbox
        $field = QAPL_Form_Field_Factory::build_manual_term_selection_field();
        $this->create_field($field);
        //manual selected terms multiselect
        $field = QAPL_Form_Field_Factory::build_manual_selected_terms_field();
        $this->create_field($field);
        //post per page number
        $field = QAPL_Form_Field_Factory::build_posts_per_page_field();
        $this->create_field($field);
        //select post order
        $field = QAPL_Form_Field_Factory::build_select_order_field();
        $this->create_field($field);
        //select post orderby
        $field = QAPL_Form_Field_Factory::build_select_orderby_field();
        $this->create_field($field);

        //show sort button
        $field = QAPL_Form_Field_Factory::build_show_sort_button_field();
        $this->create_field($field);
        //select sort options
        $field = QAPL_Form_Field_Factory::build_select_sort_button_options_field();
        $this->create_field($field);
        //inline Filter & Sorting
        $field = QAPL_Form_Field_Factory::build_show_inline_filter_sorting_field();
        $this->create_field($field);

        //add Excluded Post IDs
        $field = QAPL_Form_Field_Factory::build_excluded_post_ids_field();
        $this->create_field($field);
        //set ignore sticky
        $field = QAPL_Form_Field_Factory::build_ignore_sticky_posts_field();
        $this->create_field($field);
        //load posts via AJAX on initial load
        $field = QAPL_Form_Field_Factory::build_ajax_on_initial_load_field();
        $this->create_field($field);
        //Infinite Scroll
        $field = QAPL_Form_Field_Factory::build_ajax_infinite_scroll_field();
        $this->create_field($field);
        //Show end message
        $field = QAPL_Form_Field_Factory::build_show_end_message_field();
        $this->create_field($field);
        //apply quick ajax css style
        $field = QAPL_Form_Field_Factory::build_quick_ajax_css_style_field();
        $this->create_field($field);
        //select number of columns
        $field = QAPL_Form_Field_Factory::build_select_columns_qty_field();
        $this->create_field($field);
        //select post item template
        $field = QAPL_Form_Field_Factory::build_post_item_template_field();
        $this->create_field($field);
        //add custom class for taxonomy filter
        $field = QAPL_Form_Field_Factory::build_taxonomy_filter_class_field();
        $this->create_field($field);
        //add custom class for container
        $field = QAPL_Form_Field_Factory::build_container_class_field();
        $this->create_field($field);
        //show custom load more post quantity
        $field = QAPL_Form_Field_Factory::build_show_custom_load_more_post_quantity_field();
        $this->create_field($field);
        //select custom load more post quantity
        $field = QAPL_Form_Field_Factory::build_select_custom_load_more_post_quantity_field();
        $this->create_field($field);
        //override loader icon
        $field = QAPL_Form_Field_Factory::build_override_global_loader_icon_field();
        $this->create_field($field);
        //select loader icon
        $field = QAPL_Form_Field_Factory::build_select_loader_icon();
        $this->create_field($field);
    }
    
    public function render_form() {
        $shortcode_page = '<div class="quick-ajax-layout-settings">';

        // general query settings
        // ==============================
        $shortcode_page .= '<h4>'.esc_html__('General Settings', 'quick-ajax-post-loader').'</h4>';

        //select post type
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_SELECT_POST_TYPE);

        //show taxonomy checkbox
        $field_options = $this->field_options([
            'is_trigger' => true,
        ]);   
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER, $field_options);

        //select taxonomy
        $this->fields[QAPL_Constants::QUERY_SETTING_SELECT_TAXONOMY]['options'] = $this->get_taxonomy_options_for_post_type();            
        $field_options = $this->field_options([
            'is_trigger' => false,
            'visible_if' => [
                QAPL_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER => '1'
            ]
        ]);            
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_SELECT_TAXONOMY, $field_options);

        // manual term selection checkbox
        $field_options = $this->field_options([
            'is_trigger' => true,
            'visible_if' => [
                QAPL_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER => '1'
            ]
        ]);
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_MANUAL_TERM_SELECTION, $field_options);
        
        // assign term options to field
        $this->fields[QAPL_Constants::QUERY_SETTING_SELECTED_TERMS]['options'] = $this->get_term_options_for_taxonomy();

        // render field with multiple conditions
        $field_options = $this->field_options([
            'visible_if' => [
                QAPL_Constants::QUERY_SETTING_MANUAL_TERM_SELECTION => '1',
                QAPL_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER => '1'
            ]
        ]);
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_SELECTED_TERMS, $field_options);

        //end manual term selection checkbox 
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_SELECT_POSTS_PER_PAGE);
        $shortcode_page .= '</div>';

        // sorting settings
        // ==============================

        $shortcode_page .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
        $shortcode_page .= '<h4>'.esc_html__('Sorting Settings', 'quick-ajax-post-loader').'</h4>';

        // orderby field
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_SELECT_ORDERBY);

        // order direction
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_SELECT_ORDER);

        // toggle sort button
        $field_options = $this->field_options([
            'is_trigger' => true,
        ]);  
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_SHOW_SORT_BUTTON, $field_options);

        // sort button options
        $field_options = $this->field_options([
            'visible_if' => [
                QAPL_Constants::QUERY_SETTING_SHOW_SORT_BUTTON => '1'
            ]
        ]);
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_SELECT_SORT_BUTTON_OPTIONS, $field_options);

        // inline filter and sorting
        $field_options = $this->field_options([
            'visible_if' => [
                QAPL_Constants::QUERY_SETTING_SHOW_SORT_BUTTON => '1'
            ]
        ]);
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_SHOW_INLINE_FILTER_SORTING, $field_options);
        $shortcode_page .= '</div>';

        // advanced query settings
        // ==============================

        $shortcode_page .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
        $shortcode_page .= '<h4>'.esc_html__('Additional Settings', 'quick-ajax-post-loader').'</h4>';

        // exclude specific post ids
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_SET_POST_NOT_IN);

        // ignore sticky posts
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_IGNORE_STICKY_POSTS);

         // ajax on initial load
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_AJAX_ON_INITIAL_LOAD);

        // infinite scroll
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_AJAX_INFINITE_SCROLL);

        // show end message
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_SHOW_END_MESSAGE);
        $shortcode_page .= '</div>';

        // layout and ui settings
        // ==============================
        $shortcode_page .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
        $shortcode_page .= '<h4>'.esc_html__('layout Settings', 'quick-ajax-post-loader').'</h4>';

        // enable quick ajax css style
        $field_options = $this->field_options([
            'is_trigger' => true,
        ]); 
        $shortcode_page .= $this->add_field(QAPL_Constants::LAYOUT_SETTING_QUICK_AJAX_CSS_STYLE, $field_options);

        // select columns quantity
        $field_options = $this->field_options([
            'visible_if' => [
                QAPL_Constants::LAYOUT_SETTING_QUICK_AJAX_CSS_STYLE => '1'
            ]
        ]);
        $shortcode_page .= $this->add_field(QAPL_Constants::LAYOUT_SETTING_SELECT_COLUMNS_QTY, $field_options);

        // post item template
        $shortcode_page .= $this->add_field(QAPL_Constants::LAYOUT_SETTING_POST_ITEM_TEMPLATE);

        // taxonomy filter custom class
        $field_options = $this->field_options([
            'is_trigger' => false,
            'visible_if' => [
                QAPL_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER => '1'
            ]
        ]);
        $shortcode_page .= $this->add_field(QAPL_Constants::LAYOUT_SETTING_TAXONOMY_FILTER_CLASS, $field_options);

        // container custom class
        $shortcode_page .= $this->add_field(QAPL_Constants::LAYOUT_SETTING_CONTAINER_CLASS);

        // toggle custom load more quantity
        $field_options = $this->field_options([
            'is_trigger' => true,
        ]); 
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_SHOW_CUSTOM_LOAD_MORE_POST_QUANTITY, $field_options);

        // select custom load more quantity
        $field_options = $this->field_options([
            'is_trigger' => false,
            'visible_if' => [
                QAPL_Constants::QUERY_SETTING_SHOW_CUSTOM_LOAD_MORE_POST_QUANTITY => '1'
            ]
        ]); 
        $shortcode_page .= $this->add_field(QAPL_Constants::QUERY_SETTING_SELECT_CUSTOM_LOAD_MORE_POST_QUANTITY, $field_options);

        // override global loader icon
        $field_options = $this->field_options([
            'is_trigger' => true,
        ]); 
        $shortcode_page .= $this->add_field(QAPL_Constants::LAYOUT_SETTING_OVERRIDE_GLOBAL_LOADER_ICON, $field_options);

        // select loader icon
        $field_options = $this->field_options([
            'is_trigger' => false,
            'visible_if' => [
                QAPL_Constants::LAYOUT_SETTING_OVERRIDE_GLOBAL_LOADER_ICON => '1'
            ]
        ]);
        $shortcode_page .= $this->add_field(QAPL_Constants::LAYOUT_SETTING_SELECT_LOADER_ICON, $field_options);
        $shortcode_page .= '</div>';

        return $shortcode_page;
    }
}