<?php 
if (!defined('ABSPATH')) {
    exit;
}
if (WPG_Quick_Ajax_Helper::quick_ajax_element_exists('class','WPG_Quick_Ajax_Creator_Settings_Page')) {
    class WPG_Quick_Ajax_Creator_Settings_Page extends WPG_Quick_Ajax_Manage_Options_Form {
        private $tabIndex = 0;
        public function render_quick_ajax_page_heading() {
        return '<h1>'.__('Quick AJAX settings', 'wpg-quick-ajax-post-loader').'</h1>';
        }
        public function init_quick_ajax_creator_fields(){            
            //select custom load more post quantity
            $field_properties = WPG_Quick_Ajax_Fields::get_global_field_select_loader_icon();
            $this->create_field($field_properties);
            //select post type
            $field_properties = WPG_Quick_Ajax_Fields::get_field_select_post_type();
            $this->create_field($field_properties);
            //show taxonomy checkbox
            $field_properties = WPG_Quick_Ajax_Fields::get_field_show_taxonomy_filter();
            $this->create_field($field_properties);
            //select taxonomy
            $field_properties = WPG_Quick_Ajax_Fields::get_field_select_taxonomy();
            $this->create_field($field_properties);
            //post per page number
            $field_properties = WPG_Quick_Ajax_Fields::get_field_select_posts_per_page();
            $this->create_field($field_properties);
            //select post order
            $field_properties = WPG_Quick_Ajax_Fields::get_field_select_order();        
            $this->create_field($field_properties);
            //select post orderby
            $field_properties = WPG_Quick_Ajax_Fields::get_field_select_orderby();    
            $this->create_field($field_properties);
            //select post status
            $field_properties = WPG_Quick_Ajax_Fields::get_field_select_post_status();
            $this->create_field($field_properties);
            //add Excluded Post IDs
            $field_properties = WPG_Quick_Ajax_Fields::get_field_set_post_not_in();
            $this->create_field($field_properties);
            //set ignore sticky
            $field_properties = WPG_Quick_Ajax_Fields::get_field_set_ignore_sticky_posts();
            $this->create_field($field_properties);
            //apply quick ajax css style
            $field_properties = WPG_Quick_Ajax_Fields::get_field_layout_quick_ajax_css_style();
            $field_properties['default'] = 0;
            $this->create_field($field_properties);
            //select number of columns
            $field_properties = WPG_Quick_Ajax_Fields::get_field_layout_select_columns_qty();  
            $this->create_field($field_properties);
            //select post item template
            $field_properties = WPG_Quick_Ajax_Fields::get_field_layout_post_item_template();
            $this->create_field($field_properties);
            //add custom class for taxonomy filter
            $field_properties = WPG_Quick_Ajax_Fields::get_field_layout_taxonomy_filter_class();
            $this->create_field($field_properties);
            //add custom class for container
            $field_properties = WPG_Quick_Ajax_Fields::get_field_layout_container_class();
            $this->create_field($field_properties);
            //show custom load more post quantity
            $field_properties = WPG_Quick_Ajax_Fields::get_field_show_custom_load_more_post_quantity();
            $this->create_field($field_properties);
            //select custom load more post quantity
            $field_properties = WPG_Quick_Ajax_Fields::get_field_select_custom_load_more_post_quantity();
            $this->create_field($field_properties);
            //override loader icon
            $field_properties = WPG_Quick_Ajax_Fields::get_field_override_global_loader_icon();
            $this->create_field($field_properties);
            //select loader icon
            $field_properties = WPG_Quick_Ajax_Fields::get_field_select_loader_icon();
            $this->create_field($field_properties);
        }
        
        public function init_quick_ajax_content(){
            $tab_title = __('Global Options', 'wpg-quick-ajax-post-loader');
            $this->add_quick_ajax_page_content($this->tabIndex++, $tab_title,  $this->quick_ajax_content_global_options());
            $tab_title = __('Function Generator', 'wpg-quick-ajax-post-loader');
            $this->add_quick_ajax_page_content($this->tabIndex++, $tab_title, $this->quick_ajax_content_function_generator());
            $tab_title = __('Help', 'wpg-quick-ajax-post-loader');
            $this->add_quick_ajax_page_content($this->tabIndex++, $tab_title, $this->quick_ajax_content_help());
        }

        private function quick_ajax_content_global_options() {
            $content = '<div id="quick-ajax-example-code"><h3>'.__('Global Options', 'wpg-quick-ajax-post-loader').'</h3></div>';
            $content .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_global_options_field_select_loader_icon());
            $content .= get_submit_button(__('Save Settings', 'wpg-quick-ajax-post-loader'), 'primary', 'save_settings_button', false);
            return $content;
        }
        
        private function quick_ajax_content_function_generator() {
            $form_tab_function_generator = '<h3>'.__('Function Generator', 'wpg-quick-ajax-post-loader').'</h3>
            <div class="function-generator-wrap">
                <div class="function-generator-options" id="'.WPG_Quick_Ajax_Helper::quick_ajax_settings_wrapper_id().'">';
            $form_tab_function_generator .= '<div class="quick-ajax-layout-settings">';
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_post_type());
            //show taxonomy checkbox
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_show_taxonomy_filter(), true);
            //select taxonomy
            $taxonomies = array();
            $selected_option = $this->get_the_value_if_exist(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_post_type());
            if (empty($selected_option)) {
            $selected_option = WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_post_type_default_value();
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
            $this->fields[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_taxonomy()]['options'] = $taxonomy_options;
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_taxonomy(), WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_show_taxonomy_filter());
            $form_tab_function_generator .= '</div>';
            //post settings
            $form_tab_function_generator .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
            $form_tab_function_generator .= '<h4>'.__('Query Settings', 'wpg-quick-ajax-post-loader').'</h4>';            
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_post_status());
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_posts_per_page());
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_order());
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_orderby());
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_set_post_not_in());
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_ignore_sticky_posts());
            $form_tab_function_generator .= '</div>';
            $form_tab_function_generator .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
            $form_tab_function_generator .= '<h4>'.__('Layout Settings', 'wpg-quick-ajax-post-loader').'</h4>';
            //Layout Settings
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_quick_ajax_css_style(), true);
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_select_columns_qty(), WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_quick_ajax_css_style());
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_post_item_template());
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_taxonomy_filter_class(), WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_show_taxonomy_filter());
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_container_class());
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_show_custom_load_more_post_quantity(), true);
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_custom_load_more_post_quantity(), WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_show_custom_load_more_post_quantity());
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_override_global_loader_icon(),true);
            $form_tab_function_generator .= $this->add_field(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_loader_icon(), WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_override_global_loader_icon());
            $form_tab_function_generator .= '</div>';
            $form_tab_function_generator .= '</div>';
            $form_tab_function_generator .= '<div class="function-generator-result">';  
            $form_tab_function_generator .= '<div class="function-generator-buttons">
                            <button class="generate-function-button button button-primary" data-output="code-snippet-2" type="button">'.__('Generate Function', 'wpg-quick-ajax-post-loader').'</button>
                            <button class="copy-button button button-primary" data-copy="code-snippet-2" type="button">'.__('Copy Code', 'wpg-quick-ajax-post-loader').'</button>
                        </div>';  
            $form_tab_function_generator .= '<pre id="code-snippet-2" style="margin-top:20px"></pre>';
            $form_tab_function_generator .= '</div>';
            $form_tab_function_generator .= '</div>';
            return $form_tab_function_generator;
        }

        private function quick_ajax_content_help() {
            $content = '<h3>'.__('Help', 'wpg-quick-ajax-post-loader').'</h3>';
            $locale = get_locale();
            if($locale == 'pl_PL'){
                $location = 'help/'.$locale.'/';
            }else{
                $location ='help/';
            }
            // Paths to files
            $help_files = [
                'help-quick-ajax-intro.php',
                'help-quick-ajax-templates-post-item.php',
                'help-quick-ajax-templates-loader-icon.php',
                'help-quick-ajax-templates-term-filter-button.php',
                'help-quick-ajax-function-generator.php',
                'help-quick-ajax-post-grid.php',
                'help-quick-ajax-term-filter.php',                
                'help-quick-ajax-args.php',
                'help-quick-ajax-attributes.php',
                'help-quick-ajax-add-action.php',
                'help-quick-ajax-add-filters-quick-ajax-modify-query.php',
                'help-quick-ajax-add-filters-quick-ajax-modify-term-buttons.php',
            ];
            // Loop the files and create accordion blocks
            foreach ($help_files as $file) {
                $accordion_content = include $location.$file; // Include returns array with 'title' and 'content'
                // Check if array exists
                if (is_array($accordion_content) && isset($accordion_content['title'], $accordion_content['content'])) {
                    $accordion_block = $this->create_accordion_block($accordion_content['title'], $accordion_content['content']);
                    $content .= $accordion_block;
                }
            }
            return $content;
        }
    }
}
