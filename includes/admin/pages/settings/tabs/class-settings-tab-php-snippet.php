<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Settings_Tab_PHP_Snippet{
    private $settings_page;

    public function __construct($settings_page) {
        $this->settings_page = $settings_page;
    }
    // init fields for PHP Snippet Generator
    public function register_fields() {
        //select post type
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_select_post_type_field();
        $this->settings_page->register_field($field);
        //show taxonomy checkbox
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_show_taxonomy_filter_field();
        $this->settings_page->register_field($field);
        //select taxonomy
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_select_taxonomy_field();
        $this->settings_page->register_field($field);
        //manual term selection checkbox
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_manual_term_selection_field();
        $this->settings_page->register_field($field);
        //manual selected terms multiselect
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_manual_selected_terms_field();
        $this->settings_page->register_field($field);
        //post per page number
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_posts_per_page_field();
        $this->settings_page->register_field($field);
        //select post order
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_select_order_field();
        $this->settings_page->register_field($field);
        //select post orderby
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_select_orderby_field();
        $this->settings_page->register_field($field);
        
        //Sorting Settings
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_show_sort_button_field();
        $this->settings_page->register_field($field);
        //select sort options
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_select_sort_button_options_field();
        $this->settings_page->register_field($field);
        //Additional Settings

        //add Excluded Post IDs
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_excluded_post_ids_field();
        $this->settings_page->register_field($field);
        //set ignore sticky
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_ignore_sticky_posts_field();
        $this->settings_page->register_field($field);
        //load posts via AJAX on initial load
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_ajax_on_initial_load_field();
        $this->settings_page->register_field($field);
        //Infinite Scroll
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_ajax_infinite_scroll_field();
        $this->settings_page->register_field($field);
        //Show end message
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_show_end_message_field();
        $this->settings_page->register_field($field);

        //Additional Settings
        //apply quick ajax css style
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_quick_ajax_css_style_field();
        $field_properties['default'] = 0;
        $this->settings_page->register_field($field);
        //select number of columns
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_select_columns_qty_field();
        $this->settings_page->register_field($field);
        //select post item template
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_post_item_template_field();
        $this->settings_page->register_field($field);
        //add custom class for taxonomy filter
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_taxonomy_filter_class_field();
        $this->settings_page->register_field($field);
        //add custom class for container
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_container_class_field();
        $this->settings_page->register_field($field);
        //show custom load more post quantity
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_show_custom_load_more_post_quantity_field();
        $this->settings_page->register_field($field);
        //select custom load more post quantity
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_select_custom_load_more_post_quantity_field();
        $this->settings_page->register_field($field);
        //override loader icon
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_override_global_loader_icon_field();
        $this->settings_page->register_field($field);
        //select loader icon
        $field = QAPL_Quick_Ajax_Form_Field_Factory::build_select_loader_icon();
        $this->settings_page->register_field($field);
    }
    public function register_content($tabIndex) {
        $tab_title = esc_html__('PHP Snippet Generator', 'quick-ajax-post-loader');
        $content = $this->build_content();
        $this->settings_page->add_quick_ajax_page_content($tabIndex, $tab_title, $content);
    }
    private function build_content() {
            $form_tab_function_generator = '<h3>'.esc_html__('PHP Snippet Generator', 'quick-ajax-post-loader').'</h3>
            <div class="function-generator-wrap">
                <div class="function-generator-options" id="'.QAPL_Quick_Ajax_Constants::SETTINGS_WRAPPER_ID.'">';
            $form_tab_function_generator .= '<p style="margin-top:0; margin-bottom:20px;">'.__('Generate a fully functional PHP snippet to embed your custom AJAX post loader into your template. No shortcode needed.', 'quick-ajax-post-loader').'</p>'; 
            $form_tab_function_generator .= '<div class="quick-ajax-layout-settings">';
            $form_tab_function_generator .= '<h4>'.__('General Settings', 'quick-ajax-post-loader').'</h4>';
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_POST_TYPE);
            //show taxonomy checkbox
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => true,
            ]); 
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER, $field_options);
            //taxonomy select option
            $this->settings_page->update_field_options(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_TAXONOMY,$this->settings_page->get_taxonomy_options());
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => false,
                'visible_if' => [
                    QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER => '1'
                ]
            ]); 
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_TAXONOMY, $field_options);
            // manual term selection checkbox
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => true,
                'visible_if' => [
                    QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER => '1'
                ]
            ]);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_MANUAL_TERM_SELECTION, $field_options);
            
            // assign term options to field
            $this->settings_page->update_field_options(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECTED_TERMS, $this->settings_page->get_term_options());

            // render field with multiple conditions
            $field_options = $this->settings_page->field_options_wrapper([
                'visible_if' => [
                    QAPL_Quick_Ajax_Constants::QUERY_SETTING_MANUAL_TERM_SELECTION => '1',
                    QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER => '1'
                ]
            ]);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECTED_TERMS, $field_options);

            //end manual term selection checkbox 
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_POSTS_PER_PAGE);
            $form_tab_function_generator .= '</div>';
            //post settings
            $form_tab_function_generator .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
            $form_tab_function_generator .= '<h4>'.__('Sorting Settings', 'quick-ajax-post-loader').'</h4>';
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_ORDERBY);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_ORDER);
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => true,
            ]);  
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_SORT_BUTTON, $field_options);
            $field_options = $this->settings_page->field_options_wrapper([
                'visible_if' => [
                    QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_SORT_BUTTON => '1'
                ]
            ]);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_SORT_BUTTON_OPTIONS, $field_options);
            $form_tab_function_generator .= '</div>';
            $form_tab_function_generator .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
            $form_tab_function_generator .= '<h4>'.esc_html__('Additional Settings', 'quick-ajax-post-loader').'</h4>';
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SET_POST_NOT_IN);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_IGNORE_STICKY_POSTS);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_AJAX_ON_INITIAL_LOAD);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_AJAX_INFINITE_SCROLL);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_END_MESSAGE);
            $form_tab_function_generator .= '</div>';
            $form_tab_function_generator .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
            $form_tab_function_generator .= '<h4>'.__('Layout Settings', 'quick-ajax-post-loader').'</h4>';
            //Layout Settings
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => true,
            ]); 
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_QUICK_AJAX_CSS_STYLE, $field_options);
            $field_options = $this->settings_page->field_options_wrapper([
                'visible_if' => [
                    QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_QUICK_AJAX_CSS_STYLE => '1'
                ]
            ]);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_SELECT_COLUMNS_QTY, $field_options);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_POST_ITEM_TEMPLATE);
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => false,
                'visible_if' => [
                    QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER => '1'
                ]
            ]);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_TAXONOMY_FILTER_CLASS, $field_options);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_CONTAINER_CLASS);
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => true,
            ]); 
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_CUSTOM_LOAD_MORE_POST_QUANTITY, $field_options);
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => false,
                'visible_if' => [
                    QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_CUSTOM_LOAD_MORE_POST_QUANTITY => '1'
                ]
            ]); 
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_CUSTOM_LOAD_MORE_POST_QUANTITY, $field_options);
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => true,
            ]); 
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_OVERRIDE_GLOBAL_LOADER_ICON, $field_options);
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => false,
                'visible_if' => [
                    QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_OVERRIDE_GLOBAL_LOADER_ICON => '1'
                ]
            ]);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_SELECT_LOADER_ICON, $field_options);
            $form_tab_function_generator .= '</div>';
            $form_tab_function_generator .= '</div>';
            //Function generation buttons
            $form_tab_function_generator .= '<div class="function-generator-result">';  
            $form_tab_function_generator .= '<div class="function-generator-buttons">
                            <button class="generate-function-button button button-primary" data-output="code-snippet-2" type="button">'.__('Generate Function', 'quick-ajax-post-loader').'</button>
                            <button class="copy-button button button-primary" data-copy="code-snippet-2" type="button">'.__('Copy Code', 'quick-ajax-post-loader').'</button>
                        </div>';  
            $form_tab_function_generator .= '<pre id="code-snippet-2" style="margin-top:20px"></pre>';
            $form_tab_function_generator .= '</div>';
            $form_tab_function_generator .= '</div>';
            return $form_tab_function_generator;
        }
}