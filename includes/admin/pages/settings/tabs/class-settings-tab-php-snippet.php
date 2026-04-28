<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Settings_Tab_PHP_Snippet implements QAPL_Settings_Tab_Interface{
    private $settings_page;

    public function __construct($settings_page) {
        $this->settings_page = $settings_page;
    }
    // init fields for PHP Snippet Generator
    public function define_fields(): void {
        // register all fields for php snippet generator
        $this->settings_page->register_fields_batch([
            //select post type
            QAPL_Form_Field_Factory::build_select_post_type_field(),            
            //show taxonomy checkbox
            QAPL_Form_Field_Factory::build_show_taxonomy_filter_field(),
            //select taxonomy
            QAPL_Form_Field_Factory::build_select_taxonomy_field(),
            //display show all button
            QAPL_Form_Field_Factory::build_display_show_all_button_field(),
            //manual term selection checkbox
            QAPL_Form_Field_Factory::build_manual_term_selection_field(),
            //manual selected terms multiselect
            QAPL_Form_Field_Factory::build_manual_selected_terms_field(),
            //post per page number
            QAPL_Form_Field_Factory::build_posts_per_page_field(),
            //select post order
            QAPL_Form_Field_Factory::build_select_order_field(),
            //select post orderby
            QAPL_Form_Field_Factory::build_select_orderby_field(),
            
            //Sorting Settings
            QAPL_Form_Field_Factory::build_show_sort_button_field(),
            //select sort options
            QAPL_Form_Field_Factory::build_select_sort_button_options_field(),

            //Additional Settings
            //add Excluded Post IDs
            QAPL_Form_Field_Factory::build_excluded_post_ids_field(),
            //set ignore sticky
            QAPL_Form_Field_Factory::build_ignore_sticky_posts_field(),
            //load posts via AJAX on initial load
            QAPL_Form_Field_Factory::build_ajax_on_initial_load_field(),
            //Infinite Scroll
            QAPL_Form_Field_Factory::build_ajax_infinite_scroll_field(),
            //Show end message
            QAPL_Form_Field_Factory::build_show_end_message_field(),

            //Additional Settings
            //apply quick ajax css style
            QAPL_Form_Field_Factory::build_quick_ajax_css_style_field(),
            //select number of columns
            QAPL_Form_Field_Factory::build_select_columns_qty_field(),
            //select post item template
            QAPL_Form_Field_Factory::build_post_item_template_field(),
            //add custom class for taxonomy filter
            QAPL_Form_Field_Factory::build_taxonomy_filter_class_field(),
            //add custom class for container
            QAPL_Form_Field_Factory::build_container_class_field(),
            //show custom load more post quantity
            QAPL_Form_Field_Factory::build_show_custom_load_more_post_quantity_field(),
            //select custom load more post quantity
            QAPL_Form_Field_Factory::build_select_custom_load_more_post_quantity_field(),
            //override loader icon
            QAPL_Form_Field_Factory::build_override_global_loader_icon_field(),
            //select loader icon
            QAPL_Form_Field_Factory::build_select_loader_icon(),
        ]);
    }
    public function register_content(int $tabIndex): void {
        $tab_title = esc_html__('PHP Snippet Generator', 'quick-ajax-post-loader');
        $content = $this->build_content();
        $this->settings_page->add_quick_ajax_page_content($tabIndex, $tab_title, $content);
    }
    private function build_content() {
            $form_tab_function_generator = '<h3>'.esc_html__('PHP Snippet Generator', 'quick-ajax-post-loader').'</h3>
            <div class="function-generator-wrap">
                <div class="function-generator-options" id="'.QAPL_Constants::SETTINGS_WRAPPER_ID.'">';
            $form_tab_function_generator .= '<p style="margin-top:0; margin-bottom:20px;">'.__('Generate a fully functional PHP snippet to embed your custom AJAX post loader into your template. No shortcode needed.', 'quick-ajax-post-loader').'</p>'; 
            $form_tab_function_generator .= '<div class="quick-ajax-layout-settings">';
            $form_tab_function_generator .= '<h4>'.__('General Settings', 'quick-ajax-post-loader').'</h4>';
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_SELECT_POST_TYPE);

            //show taxonomy checkbox
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => true,
            ]); 
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER, $field_options);
            
            //taxonomy select option
            $this->settings_page->update_field_options(QAPL_Constants::QUERY_SETTING_SELECT_TAXONOMY,$this->settings_page->get_taxonomy_options());
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => false,
                'visible_if' => [
                    QAPL_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER => '1'
                ]
            ]); 
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_SELECT_TAXONOMY, $field_options);

            //display show all button
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => true,
                'visible_if' => [
                    QAPL_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER => '1'
                ]
            ]);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::LAYOUT_SETTING_DISPLAY_SHOW_ALL_BUTTON, $field_options);

            // manual term selection checkbox
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => true,
                'visible_if' => [
                    QAPL_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER => '1'
                ]
            ]);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_MANUAL_TERM_SELECTION, $field_options);
            
            // assign term options to field
            $this->settings_page->update_field_options(QAPL_Constants::QUERY_SETTING_SELECTED_TERMS, $this->settings_page->get_term_options());

            // render field with multiple conditions
            $field_options = $this->settings_page->field_options_wrapper([
                'visible_if' => [
                    QAPL_Constants::QUERY_SETTING_MANUAL_TERM_SELECTION => '1',
                    QAPL_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER => '1'
                ]
            ]);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_SELECTED_TERMS, $field_options);

            //end manual term selection checkbox 
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_SELECT_POSTS_PER_PAGE);
            $form_tab_function_generator .= '</div>';
            //post settings
            $form_tab_function_generator .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
            $form_tab_function_generator .= '<h4>'.__('Sorting Settings', 'quick-ajax-post-loader').'</h4>';
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_SELECT_ORDERBY);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_SELECT_ORDER);
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => true,
            ]);  
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_SHOW_SORT_BUTTON, $field_options);
            $field_options = $this->settings_page->field_options_wrapper([
                'visible_if' => [
                    QAPL_Constants::QUERY_SETTING_SHOW_SORT_BUTTON => '1'
                ]
            ]);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_SELECT_SORT_BUTTON_OPTIONS, $field_options);
            $form_tab_function_generator .= '</div>';
            $form_tab_function_generator .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
            $form_tab_function_generator .= '<h4>'.esc_html__('Additional Settings', 'quick-ajax-post-loader').'</h4>';
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_SET_POST_NOT_IN);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_IGNORE_STICKY_POSTS);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_AJAX_ON_INITIAL_LOAD);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_AJAX_INFINITE_SCROLL);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_SHOW_END_MESSAGE);
            $form_tab_function_generator .= '</div>';
            $form_tab_function_generator .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
            $form_tab_function_generator .= '<h4>'.__('Layout Settings', 'quick-ajax-post-loader').'</h4>';
            //Layout Settings
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => true,
            ]); 
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::LAYOUT_SETTING_QUICK_AJAX_CSS_STYLE, $field_options);
            $field_options = $this->settings_page->field_options_wrapper([
                'visible_if' => [
                    QAPL_Constants::LAYOUT_SETTING_QUICK_AJAX_CSS_STYLE => '1'
                ]
            ]);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::LAYOUT_SETTING_SELECT_COLUMNS_QTY, $field_options);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::LAYOUT_SETTING_POST_ITEM_TEMPLATE);
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => false,
                'visible_if' => [
                    QAPL_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER => '1'
                ]
            ]);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::LAYOUT_SETTING_TAXONOMY_FILTER_CLASS, $field_options);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::LAYOUT_SETTING_CONTAINER_CLASS);
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => true,
            ]); 
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_SHOW_CUSTOM_LOAD_MORE_POST_QUANTITY, $field_options);
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => false,
                'visible_if' => [
                    QAPL_Constants::QUERY_SETTING_SHOW_CUSTOM_LOAD_MORE_POST_QUANTITY => '1'
                ]
            ]); 
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::QUERY_SETTING_SELECT_CUSTOM_LOAD_MORE_POST_QUANTITY, $field_options);
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => true,
            ]); 
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::LAYOUT_SETTING_OVERRIDE_GLOBAL_LOADER_ICON, $field_options);
            $field_options = $this->settings_page->field_options_wrapper([
                'is_trigger' => false,
                'visible_if' => [
                    QAPL_Constants::LAYOUT_SETTING_OVERRIDE_GLOBAL_LOADER_ICON => '1'
                ]
            ]);
            $form_tab_function_generator .= $this->settings_page->render_field(QAPL_Constants::LAYOUT_SETTING_SELECT_LOADER_ICON, $field_options);
            $form_tab_function_generator .= '</div>';
            $form_tab_function_generator .= '</div>';
            //Function generation buttons
            $form_tab_function_generator .= '<div class="function-generator-result">';  
            $form_tab_function_generator .= '<div class="function-generator-buttons">
                            <button type="button" class="generate-function-button button button-primary" data-output="code-snippet-2">'.__('Generate Function', 'quick-ajax-post-loader').'</button>
                            <button type="button" class="copy-button-text button button-primary" data-copy="code-snippet-2" data-label-copied="'.__('Copied', 'quick-ajax-post-loader').'">'.__('Copy Code', 'quick-ajax-post-loader').'</button>
                        </div>';  
            $form_tab_function_generator .= '<pre id="code-snippet-2"></pre>';
            $form_tab_function_generator .= '</div>';
            $form_tab_function_generator .= '</div>';
            return $form_tab_function_generator;
        }
}