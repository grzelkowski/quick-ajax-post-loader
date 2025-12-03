<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Enqueue_Handler implements QAPL_Enqueue_Handler_Interface {
    private QAPL_File_Manager_Interface $file_manager;

    public function __construct(QAPL_File_Manager_Interface $file_manager) {
        $this->file_manager = $file_manager;
    }
    public function register_hooks(): void {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_styles_and_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_styles_and_scripts'], 10, 1);

    }
    public function enqueue_frontend_styles_and_scripts(): void {
        if (is_admin()){
             return; 
        }          
        $style_suffix = $this->get_file_suffix('/css/', 'style.css');
        $script_suffix = $this->get_file_suffix('/js/', 'script.js');
        $version = $this->get_version();

        $style_url = $this->file_manager->get_plugin_css_directory() . 'style' . $style_suffix . '.css';
        $script_url = $this->file_manager->get_plugin_js_directory() . 'script' . $script_suffix . '.js';

        wp_enqueue_style('qapl-quick-ajax-style', $style_url, [], $version);        
        wp_register_script('qapl-quick-ajax-script', $script_url, ['jquery'], $version, true);
        wp_localize_script('qapl-quick-ajax-script', 'qapl_quick_ajax_data', $this->get_localized_data());
        wp_enqueue_script('qapl-quick-ajax-script');        
    }
    public function enqueue_admin_styles_and_scripts(): void {
        if (!is_admin()){
             return; 
        }  
        // Check if the current page matches the plugin-related pages
        $plugin_pages = [QAPL_Constants::CPT_SHORTCODE_SLUG, QAPL_Constants::SETTINGS_PAGE_SLUG];
        if (!qapl_quick_ajax_check_page_type($plugin_pages)) {
            return; 
        }
        $style_suffix = $this->get_file_suffix('/css/', 'admin-style.css');
        $script_suffix = $this->get_file_suffix('/js/', 'admin-script.js');
        $version = $this->get_version();

        $style_url = $this->file_manager->get_plugin_css_directory() . 'admin-style' . $style_suffix . '.css';
        $script_url = $this->file_manager->get_plugin_js_directory() . 'admin-script' . $script_suffix . '.js';

        wp_enqueue_style('qapl-quick-ajax-admin-style', $style_url, [], $version);
        wp_register_script('qapl-quick-ajax-admin-script', $script_url, ['jquery'], $version, true);
        wp_localize_script('qapl-quick-ajax-admin-script', 'qapl_quick_ajax_admin_data', $this->get_admin_localized_data());
        wp_enqueue_script('qapl-quick-ajax-admin-script');
    }
    private function is_dev_mode(): bool {
        // return true if QAPL_DEV_MODE is defined and enabled
        if((defined('QAPL_DEV_MODE') && QAPL_DEV_MODE)) {
            return true;
        }
        return false;
    }
    private function get_version(): string {
        return $this->is_dev_mode() ? (string) time() : QAPL_Constants::PLUGIN_VERSION;
    }    
    private function get_file_suffix($base_path, $file_name): string {
        // Set the default suffix to '.min' if SCRIPT_DEBUG is disabled
        $default_suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
    
        // Check if develop_mode is enabled and the -dev file exists
        if ($this->is_dev_mode()){
            $file_parts = pathinfo($file_name);
            $base_name = $file_parts['filename'];
            $extension = isset($file_parts['extension']) ? '.' . $file_parts['extension'] : '';
            $dev_file = $base_path . $base_name . '-dev' . $extension;
            // Return the -dev suffix if the file exists
            if ($this->file_manager->file_exists($dev_file)) {
                return '-dev';
            }
        }
        // Return the default suffix if no -dev file exists
        return $default_suffix;
    }
    
    private function get_localized_data() {
        $nonce = wp_create_nonce(QAPL_Constants::NONCE_FORM_QUICK_AJAX_ACTION);
        if (!$nonce) {
            return [];
        }
        return [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' =>  $nonce,
            'constants' => [
                'block_id' => QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID,
                'filter_data_button' => QAPL_Constants::TERM_FILTER_BUTTON_DATA_BUTTON,
                'sort_button' => QAPL_Constants::SORT_OPTION_BUTTON_DATA_BUTTON,
                'load_more_data_button' => QAPL_Constants::LOAD_MORE_BUTTON_DATA_BUTTON,
            ]
        ];
    }
    private function get_admin_localized_data() {
        return [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce(QAPL_Constants::NONCE_FORM_QUICK_AJAX_ACTION),
            'constants' => [
                'quick_ajax_settings_wrapper' => QAPL_Constants::SETTINGS_WRAPPER_ID,
                'quick_ajax_post_type' => QAPL_Constants::QUERY_SETTING_SELECT_POST_TYPE,
                'quick_ajax_taxonomy' => QAPL_Constants::QUERY_SETTING_SELECT_TAXONOMY,
                'quick_ajax_manual_selected_terms' => QAPL_Constants::QUERY_SETTING_SELECTED_TERMS,
                'quick_ajax_css_style' => QAPL_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE,
                'grid_num_columns' => QAPL_Constants::ATTRIBUTE_GRID_NUM_COLUMNS,
                'post_item_template' => QAPL_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE,
                'post_item_template_default' => QAPL_Constants::LAYOUT_SETTING_POST_ITEM_TEMPLATE_DEFAULT,
                'taxonomy_filter_class' => QAPL_Constants::ATTRIBUTE_TAXONOMY_FILTER_CLASS,
                'container_class' => QAPL_Constants::ATTRIBUTE_CONTAINER_CLASS,
                'load_more_posts' => QAPL_Constants::ATTRIBUTE_LOAD_MORE_POSTS,
                'loader_icon' => QAPL_Constants::ATTRIBUTE_LOADER_ICON,
                'loader_icon_default' => QAPL_Constants::LAYOUT_SETTING_SELECT_LOADER_ICON_DEFAULT,
                'ajax_initial_load' => QAPL_Constants::AJAX_SETTING_AJAX_INITIAL_LOAD,
                'infinite_scroll' => QAPL_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL,
                'show_end_message' => QAPL_Constants::ATTRIBUTE_SHOW_END_MESSAGE,
                'quick_ajax_id' => QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID,
            ]
        ];
    }
}