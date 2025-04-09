<?php 
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('QAPL_Quick_Ajax_Creator_Settings_Page')) {
    class QAPL_Quick_Ajax_Creator_Settings_Page extends QAPL_Quick_Ajax_Manage_Options_Form {
        private $tabIndex = 0;
        public function render_quick_ajax_page_heading() {
        return '<h1>'.esc_html__('Quick AJAX settings', 'quick-ajax-post-loader').'</h1>';
        }
        //initialize all fields for the settings page
        public function init_quick_ajax_creator_fields(){             
            $this->init_global_options_fields();
            $this->init_function_generator_fields();
            $this->init_content_clear_old_data_fields();
        }
        //initialize global options fields
        private function init_global_options_fields() {
            //select loader icon
            $field_properties = QAPL_Form_Fields_Helper::get_global_field_select_loader_icon();
            $this->create_field($field_properties);
            $field_properties = QAPL_Form_Fields_Helper::get_global_options_field_set_read_more_label();
            $this->create_field($field_properties);
            $field_properties = QAPL_Form_Fields_Helper::get_global_options_field_set_show_all_label();
            $this->create_field($field_properties);
            $field_properties = QAPL_Form_Fields_Helper::get_global_options_field_set_load_more_label();
            $this->create_field($field_properties);
            $field_properties = QAPL_Form_Fields_Helper::get_global_options_field_set_end_post_message();
            $this->create_field($field_properties);

            //Sorting Options
            $field_properties = QAPL_Form_Fields_Helper::get_global_options_field_set_sort_option_date_desc_label();
            $this->create_field($field_properties);
            $field_properties = QAPL_Form_Fields_Helper::get_global_options_field_set_sort_option_date_asc_label();
            $this->create_field($field_properties);
            $field_properties = QAPL_Form_Fields_Helper::get_global_options_field_set_sort_option_comment_count_desc_label();
            $this->create_field($field_properties);
            $field_properties = QAPL_Form_Fields_Helper::get_global_options_field_set_sort_option_title_desc_label();
            $this->create_field($field_properties);
            $field_properties = QAPL_Form_Fields_Helper::get_global_options_field_set_sort_option_title_asc_label();
            $this->create_field($field_properties);
            $field_properties = QAPL_Form_Fields_Helper::get_global_options_field_set_sort_option_rand_label();
            $this->create_field($field_properties);
        }
        //initialize function generator fields
        private function init_function_generator_fields() {
            //select post type
            $field_properties = QAPL_Form_Fields_Helper::get_field_select_post_type();
            $this->create_field($field_properties);
            //show taxonomy checkbox
            $field_properties = QAPL_Form_Fields_Helper::get_field_show_taxonomy_filter();
            $this->create_field($field_properties);
            //select taxonomy
            $field_properties = QAPL_Form_Fields_Helper::get_field_select_taxonomy();
            $this->create_field($field_properties);
            //select post status
            //$field_properties = QAPL_Form_Fields_Helper::get_field_select_post_status();
            //$this->create_field($field_properties);
            //post per page number
            $field_properties = QAPL_Form_Fields_Helper::get_field_select_posts_per_page();
            $this->create_field($field_properties);
            //select post order
            $field_properties = QAPL_Form_Fields_Helper::get_field_select_order();        
            $this->create_field($field_properties);
            //select post orderby
            $field_properties = QAPL_Form_Fields_Helper::get_field_select_orderby();    
            $this->create_field($field_properties);
            
            //Sorting Settings
            $field_properties = QAPL_Form_Fields_Helper::get_field_show_sort_button();        
            $this->create_field($field_properties);
            //select sort options
            $field_properties = QAPL_Form_Fields_Helper::get_field_select_sort_button_options();    
            $this->create_field($field_properties);
            //Additional Settings

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

            //Additional Settings
            //apply quick ajax css style
            $field_properties = QAPL_Form_Fields_Helper::get_field_layout_quick_ajax_css_style();
            $field_properties['default'] = 0;
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

        private function init_content_clear_old_data_fields() {
            //remove old data
            $field_properties = QAPL_Form_Fields_Helper::get_global_field_remove_old_data();
            $this->create_field($field_properties);
        }
        public function init_quick_ajax_content(){
            $tab_title = esc_html__('Global Options', 'quick-ajax-post-loader');
            $this->add_quick_ajax_page_content($this->tabIndex++, $tab_title,  $this->quick_ajax_content_global_options());
            $tab_title = esc_html__('Function Generator', 'quick-ajax-post-loader');
            $this->add_quick_ajax_page_content($this->tabIndex++, $tab_title, $this->quick_ajax_content_function_generator());
            $tab_title = esc_html__('Help', 'quick-ajax-post-loader');
            $this->add_quick_ajax_page_content($this->tabIndex++, $tab_title, $this->quick_ajax_content_help());
            $cleanup_flags = QAPL_Quick_Ajax_Helper::quick_ajax_plugin_cleanup_flags();
            if (!empty(get_option($cleanup_flags))) {
                $tab_title = esc_html__('Purge Old Data', 'quick-ajax-post-loader');
                $this->add_quick_ajax_page_content($this->tabIndex++, $tab_title, $this->quick_ajax_content_clear_old_data());
            }
        }

        //settings_fields to variable
        private function settings_fields_to_variable($option_group) {
            $output = '<input type="hidden" name="option_page" value="' . esc_attr($option_group) . '" />';
            $output .= '<input type="hidden" name="action" value="update" />';
            $output .= wp_nonce_field("update-options", "_wpnonce", true, false);
        
            //check if $_SERVER['REQUEST_URI'] is set
            $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
        
            $output .= '<input type="hidden" name="_wp_http_referer" value="' . esc_attr($request_uri) . '" />';
            return $output;
        }

        private function quick_ajax_content_global_options() {
            ob_start();
            settings_fields($this->option_group);
            $settings_fields_html = ob_get_clean();
            $content = '<div id="quick-ajax-example-code">';
            $content .= '<form method="post" action="options.php">';            
            $content .= '<h3>'.__('Global Options', 'quick-ajax-post-loader').'</h3>';
            $content .= $this->add_field(QAPL_Quick_Ajax_Helper::global_options_field_select_loader_icon());
            $content .= $this->add_field(QAPL_Quick_Ajax_Helper::global_options_field_set_read_more_label());
            $content .= $this->add_field(QAPL_Quick_Ajax_Helper::global_options_field_set_show_all_label());
            $content .= $this->add_field(QAPL_Quick_Ajax_Helper::global_options_field_set_load_more_label());
            $content .= $this->add_field(QAPL_Quick_Ajax_Helper::global_options_field_set_end_post_message());
            $content .= '<h4>'.__('Sorting Option Labels', 'quick-ajax-post-loader').'</h4>';
            $content .= $this->add_field(QAPL_Quick_Ajax_Helper::global_options_field_set_sort_option_date_desc_label());
            $content .= $this->add_field(QAPL_Quick_Ajax_Helper::global_options_field_set_sort_option_date_asc_label());
            $content .= $this->add_field(QAPL_Quick_Ajax_Helper::global_options_field_set_sort_option_comment_count_desc_label());
            $content .= $this->add_field(QAPL_Quick_Ajax_Helper::global_options_field_set_sort_option_title_desc_label());
            $content .= $this->add_field(QAPL_Quick_Ajax_Helper::global_options_field_set_sort_option_title_asc_label());
            $content .= $this->add_field(QAPL_Quick_Ajax_Helper::global_options_field_set_sort_option_rand_label());
            $content .= $settings_fields_html;
            $content .= get_submit_button(esc_html__('Save Settings', 'quick-ajax-post-loader'), 'primary', 'save_settings_button', false);
            $content .= '</form>';
            $content .= '</div>';
            return $content;
        }
        
        private function quick_ajax_content_function_generator() {
            $form_tab_function_generator = '<h3>'.esc_html__('Function Generator', 'quick-ajax-post-loader').'</h3>
            <div class="function-generator-wrap">
                <div class="function-generator-options" id="'.QAPL_Quick_Ajax_Helper::settings_wrapper_id().'">';
            $form_tab_function_generator .= '<div class="quick-ajax-layout-settings">';
            $form_tab_function_generator .= '<h4>'.__('General Settings', 'quick-ajax-post-loader').'</h4>';
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_post_type());
            //show taxonomy checkbox
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter(), true);
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
            if (!empty($taxonomies)) {
                foreach ($taxonomies as $taxonomy) {
                    $taxonomy_object = get_taxonomy($taxonomy);
                    if ($taxonomy_object) {
                        $taxonomy_options[] = array(
                            'label' => esc_html($taxonomy_object->label),
                            'value' => esc_attr($taxonomy)
                        );
                    }
                }
            }            
            $this->fields[QAPL_Quick_Ajax_Helper::shortcode_page_select_taxonomy()]['options'] = $taxonomy_options;
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_taxonomy(), QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter());
            //$form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_post_status());
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_posts_per_page());
            $form_tab_function_generator .= '</div>';
            //post settings
            $form_tab_function_generator .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
            $form_tab_function_generator .= '<h4>'.__('Sorting Settings', 'quick-ajax-post-loader').'</h4>';
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_order());
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_orderby());
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_show_sort_button(), true);
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_sort_button_options(), QAPL_Quick_Ajax_Helper::shortcode_page_show_sort_button());
            $form_tab_function_generator .= '</div>';
            $form_tab_function_generator .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
            $form_tab_function_generator .= '<h4>'.esc_html__('Additional Settings', 'quick-ajax-post-loader').'</h4>';
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_set_post_not_in());
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_ignore_sticky_posts());
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_ajax_on_initial_load());
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_ajax_infinite_scroll());
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_show_end_message());
            $form_tab_function_generator .= '</div>';
            $form_tab_function_generator .= '<div class="quick-ajax-layout-settings" style="margin-top:20px">';
            $form_tab_function_generator .= '<h4>'.__('Layout Settings', 'quick-ajax-post-loader').'</h4>';
            //Layout Settings
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_layout_quick_ajax_css_style(), true);
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_layout_select_columns_qty(), QAPL_Quick_Ajax_Helper::shortcode_page_layout_quick_ajax_css_style());
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_layout_post_item_template());
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_layout_taxonomy_filter_class(), QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter());
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_layout_container_class());
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_show_custom_load_more_post_quantity(), true);
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_custom_load_more_post_quantity(), QAPL_Quick_Ajax_Helper::shortcode_page_show_custom_load_more_post_quantity());
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_override_global_loader_icon(),true);
            $form_tab_function_generator .= $this->add_field(QAPL_Quick_Ajax_Helper::shortcode_page_select_loader_icon(), QAPL_Quick_Ajax_Helper::shortcode_page_override_global_loader_icon());
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
        
        private function quick_ajax_content_help() {
            $content = '<h3>' . __('Help Content', 'quick-ajax-post-loader') . '</h3>';
            $base_help_dir = plugin_dir_path(__FILE__) . 'help/';
            $locale = get_locale();
            switch ($locale) {
                case 'pl_PL':
                    $help_json_file_name = 'help_en_US.json';
                    break;
                default:
                    $help_json_file_name = 'help_en_US.json';
                    break;
            }
            $help_json_file_path = $base_help_dir.$help_json_file_name;
            if (!file_exists($help_json_file_path)) {
                return $content . '<p>' . __('Help file not found.', 'quick-ajax-post-loader') . '</p>';
            }        
            $json_data = file_get_contents($help_json_file_path);
            $help_data = json_decode($json_data, true);        
            if (!is_array($help_data)) {
                return $content . '<p>' . __('Invalid help file format.', 'quick-ajax-post-loader') . '</p>';
            }        
            foreach ($help_data as $section_key => $section) {
                $accordion_content ='';
                if (!isset($section['title']) || !isset($section['content'])) {
                    continue;
                }        
                $section_title = esc_html(strip_tags($section['title']));
                $section_content = wp_kses_post($section['content']);        
                $accordion_content .= '<div class="quick-ajax-section">';
                $accordion_content .= '<h3>' . esc_html($section_title) . '</h3>';
                $accordion_content .= '<div class="quick-ajax-section-content">' . $section_content . '</div>';
                $accordion_content .= '</div>';
                if (!empty($accordion_content)) {
                    $content .= $this->create_accordion_block($section_title, $accordion_content);
                }
            }
        
            return $content;
        }
        
        

        private function quick_ajax_content_clear_old_data() {
            $action_url = esc_url(admin_url('admin-post.php')); // use admin-post.php for admin actions
            $content = '<div id="quick-ajax-clear-data">';
            $content .= '<h3>' . esc_html__('Purge Old Data', 'quick-ajax-post-loader') . '</h3>';
            $content .= '<form method="post" action="' . $action_url . '">';
            $content .= $this->add_field('qapl_remove_old_meta', false, true); // add additional form field
            $content .= '<input type="hidden" name="action" value="qapl_purge_unused_data" />';
            $content .= '<input type="hidden" name="qapl_purge_unused_data" value="1" />'; // set value to "1" for consistency
            $content .= wp_nonce_field('qapl_purge_unused_data', 'qapl_purge_nonce', true, false); // create nonce field for security
            $content .= get_submit_button(esc_html__('Purge Unused Data', 'quick-ajax-post-loader'), 'primary', 'purge_data_button', false); // generate submit button
            $content .= '</form>';
            $content .= '</div>';
            return $content;
        }
        
    }
}