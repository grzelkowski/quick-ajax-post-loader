<?php 
if (!defined('ABSPATH')) {
    exit;
}
//require_once(plugin_dir_path( __FILE__ ).'/class-helper_new.php');

class QAPL_Quick_Ajax_Helper{
    private static $instance = null;
    private $file_helper;
    private $pages_helper;
    private function __construct() {
        $this->initialize_helpers();
        $this->initialize_components();
    }
    public static function get_plugin_info() {
        return [
            'version' => '1.6.0',
            'name' => 'Quick Ajax Post Loader',
            'text_domain' => 'quick-ajax-post-loader',
            'slug' => 'quick-ajax-post-loader',
            'minimum_php_version' => '7.4',
            'minimum_wp_version' => '5.6',
            'tested_wp_version' => '6.7.2'
        ];
    }
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();            
        }
        return self::$instance;
    }
    private function initialize_helpers() {
        $this->file_helper = QAPL_File_Helper::get_instance();
        $this->pages_helper = new QAPL_Pages_Helper($this->file_helper);

    }
    // initialize plugin components
    private function initialize_components() {
        $initialize_list = [
            $this->pages_helper->plugin_admin_pages_config(),
            $this->pages_helper->plugin_settings_page(),
            $this->pages_helper->plugin_shortcode_page(),
            $this->pages_helper->plugin_ajax_class(),
            $this->pages_helper->plugin_ajax_actions(),
            $this->pages_helper->plugin_functions(),
            $this->pages_helper->plugin_updater(),
            $this->pages_helper->plugin_shortcode_class(),
            $this->pages_helper->plugin_template_hooks(),
            $this->pages_helper->plugin_deprecated_hooks_handler(),
        ];        
        foreach ($initialize_list as $initialize) {
            if (($initialize !== false)) {
                require_once($initialize); 
            }
        }
    }
    public function enqueue_frontend_styles_and_scripts() {
        if (!is_admin()) {           
            $style_suffix = $this->get_file_suffix('/css/', 'style.css');
            $script_suffix = $this->get_file_suffix('/js/', 'script.js');
            $version = $this->is_dev_mode() ? time() : self::get_plugin_info()['version'];
            wp_enqueue_style('qapl-quick-ajax-style', $this->file_helper->get_plugin_css_directory() . 'style' . $style_suffix . '.css', [], $version);
            wp_enqueue_script('qapl-quick-ajax-script', $this->file_helper->get_plugin_js_directory() . 'script' . $script_suffix . '.js', ['jquery'], $version, true);
            wp_localize_script('qapl-quick-ajax-script', 'qapl_quick_ajax_helper', $this->get_localized_data());
        }
    }
    public function enqueue_admin_styles_and_scripts() {
        if (is_admin()) {
            // Check if the current page matches the plugin-related pages
            $plugin_pages = [self::cpt_shortcode_slug(), self::settings_page_slug()];
            if (qapl_quick_ajax_check_page_type($plugin_pages)) {
                $style_suffix = $this->get_file_suffix('/css/', 'admin-style.css');
                $script_suffix = $this->get_file_suffix('/js/', 'admin-script.js');
                $version = $this->is_dev_mode() ? time() : self::get_plugin_info()['version'];
                wp_enqueue_style('qapl-quick-ajax-admin-style', $this->file_helper->get_plugin_css_directory() . 'admin-style' . $style_suffix . '.css', [], $version);
                wp_register_script('qapl-quick-ajax-admin-script', $this->file_helper->get_plugin_js_directory() . 'admin-script' . $script_suffix . '.js', ['jquery'], $version, true);
                wp_localize_script('qapl-quick-ajax-admin-script', 'qapl_quick_ajax_helper', $this->get_admin_localized_data());
                wp_enqueue_script('qapl-quick-ajax-admin-script');
            }
        }
    }
    private function is_dev_mode() {
        // Check for the 'dev' parameter in the URL
        $dev_param = isset($_GET['dev']) ? sanitize_text_field(wp_unslash($_GET['dev'])) : null;
        if ($dev_param === 'true' || $dev_param === '1') {
            return true;
        }
        return false;
    }
    private function get_file_suffix($base_path, $file_name) {
        // Set the default suffix to '.min' if WP_DEBUG is disabled
        $default_suffix = defined('WP_DEBUG') && WP_DEBUG ? '' : '.min';
    
        // Check if develop_mode is enabled and the -dev file exists
        if ($this->is_dev_mode()){
            $file_parts = pathinfo($file_name);
            $base_name = $file_parts['filename'];
            $extension = isset($file_parts['extension']) ? '.' . $file_parts['extension'] : '';
            $dev_file = $base_path . $base_name . '-dev' . $extension;
            // Return the -dev suffix if the file exists
            if ($this->file_helper->file_exists($dev_file)) {
                return '-dev';
            }
        }
        // Return the default suffix if no -dev file exists
        return $default_suffix;
    }
    
    private function get_localized_data() {
        $nonce = wp_create_nonce(self::wp_nonce_form_quick_ajax_action());
        if (!$nonce) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                //error_log('Quick Ajax Post Loader: issue generating nonce.');
            }
            return [];
        }
        return [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' =>  $nonce,
            'helper' => [
                'block_id' => self::layout_quick_ajax_id(),
                'filter_data_button' => self::taxonomy_filter_button_data_button(),
                'sort_button' => self::sort_option_button_data_button(),
                'load_more_data_button' => self::load_more_button_data_button(),
            ]
        ];
    }
    private function get_admin_localized_data() {
        return [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce(self::wp_nonce_form_quick_ajax_action()),
            'quick_ajax_settings_wrapper' => self::settings_wrapper_id(),
            'quick_ajax_post_type' => self::shortcode_page_select_post_type(),
            'quick_ajax_taxonomy' => self::shortcode_page_select_taxonomy(),
            'quick_ajax_css_style' => self::layout_quick_ajax_css_style(),
            'grid_num_columns' => self::layout_container_num_columns(),
            'post_item_template' => self::layout_post_item_template(),
            'post_item_template_default' => self::shortcode_page_layout_post_item_template_default_value(),
            'taxonomy_filter_class' => self::layout_taxonomy_filter_class(),
            'container_class' => self::layout_container_class(),
            'load_more_posts' => self::layout_load_more_posts(),
            'loader_icon' => self::layout_select_loader_icon(),
            'loader_icon_default' => self::shortcode_page_select_loader_icon_default_value(),
            'ajax_initial_load' => self::query_settings_ajax_on_initial_load(),
            'infinite_scroll' => self::layout_ajax_infinite_scroll(),
            'quick_ajax_id' => self::layout_quick_ajax_id(),
        ];
    }
    /*
        public function load_admin_pages() {
        $admin_pages = [
            'admin_config' => $this->pages_helper->plugin_admin_pages_config(),
            'settings_page' => $this->pages_helper->plugin_settings_page(),
            'shortcode_page' => $this->pages_helper->plugin_shortcode_page(),
        ];

        foreach ($admin_pages as $page) {
            if ($page && $this->file_helper->file_exists($page)) {
                require_once($page);
            }
        }
    }

    //facade method to load AJAX classes
    public function load_ajax_classes() {
        $ajax_classes = [
            'ajax_class' => $this->pages_helper->plugin_ajax_class(),
            'ajax_actions' => $this->pages_helper->plugin_ajax_actions(),
        ];

        foreach ($ajax_classes as $class) {
            if ($class && $this->file_helper->file_exists($class)) {
                require_once($class);
            }
        }
    }

    //facade method for templates
    public function get_template_path($template_name, $type = 'post_items') {
        $base_path = $type === 'loader_icon' ? '/loader-icon/' : '/post-items/';
        return $this->file_helper->get_templates_file_path($template_name, $this->get_default_template($type), $base_path);
    }

    private function get_default_template($type) {
        return $type === 'loader_icon' ? 'loader-icon' : 'post-item';
    }


    }
    */
    
    public static function add_or_update_option_autoload($option_name, $default_value = '', $autoload = 'auto') {
        // Check if the option exists
        $existing_option = get_option($option_name, false);
    
        if ($existing_option !== false) {
            // Update autoload value if the option exists
            global $wpdb;
            $updated = $wpdb->update(
                $wpdb->options,
                ['autoload' => $autoload], // Update autoload field
                ['option_name' => $option_name],
                ['%s'],
                ['%s']
            );
    
            //clear cache after updating
            if ($updated !== false) {
                wp_cache_delete($option_name, 'options');
            }
        } else {
            // Add the option with specified autoload value
            add_option($option_name, $default_value, '', $autoload);
    
            //clear cache after adding
            wp_cache_delete($option_name, 'options');
        }
    }         

    public static function element_exists($type, $name) {
        $exists = false;
        $type_formatted = '';
        $plugin_info = self::get_plugin_info();
        $plugin_name = $plugin_info['name'];
        if ($type === 'class' && class_exists($name)) {
            $exists = true;
            $type_formatted = 'class';
        } else if ($type === 'function' && function_exists($name)) {
            $exists = true;
            $type_formatted = 'function';
        }
        if ($exists) {
            add_action('admin_notices', function() use ($name, $type_formatted, $plugin_name) {
                echo '<div class="notice notice-error"><p><strong>' . esc_html($plugin_name) . '</strong> is not working properly. Error: A ' . esc_html($type_formatted) . ' named <strong>' . esc_html($name) . '</strong> already exists, which may have been declared by another plugin.</p></div>';
            });
            return false;
        }   
        return true;
    }      

    //template post item   
    public function plugin_templates_post_item_template($template_name = false) {
        $default_name = self::shortcode_page_layout_post_item_template_default_value();
        return $this->file_helper->get_templates_file_path($template_name, $default_name, '/post-items/');
    }
    //template loader icon
    public function plugin_templates_loader_icon_template($template_name = false) {
        $default_name = self::shortcode_page_select_loader_icon_default_value();
        return $this->file_helper->get_templates_file_path($template_name, $default_name, '/loader-icon/');
    }
    public function plugin_templates_no_posts() {
        return $this->file_helper->get_templates_dir_path('/post-items/no-posts.php');
    }
    public static function taxonomy_filter_button_data_button(){
        return 'quick-ajax-filter-button';
    }
    public static function sort_option_button_data_button(){
        return 'quick-ajax-sort-option-button';
    }
    public function plugin_templates_taxonomy_filter_button() {
        return $this->file_helper->get_templates_dir_path('/taxonomy-filter/taxonomy-filter-button.php');
    }
    public static function load_more_button_data_button(){
        return 'quick-ajax-load-more-button';
    }
    public function plugin_templates_load_more_button() {
        return $this->file_helper->get_templates_dir_path('/load-more-button.php');
    }

    public static function generate_shortcode($post_id) {
        // guard clause to check if $post_id is valid
        $post_id = intval($post_id);
        if ($post_id <= 0) {
            return '';
        }
        // get the title of the post
        $post_title = get_the_title($post_id);
        // initialise variables
        $excluded_post_ids = '';
        // get serialized meta data from the post
        $serialized_data = get_post_meta($post_id, self::quick_ajax_shortcode_settings(), true);
        // check if serialized data exists and process it
        if ($serialized_data) {
            $form_data = maybe_unserialize($serialized_data);
            // ensure that the unserialized data is a valid array
            if (is_array($form_data)) {
                foreach ($form_data as $field_name => $field_value) {
                    // check for specific field and set excluded post ids
                    if ($field_name === self::shortcode_page_set_post_not_in() && !empty($field_value)) {
                        $excluded_post_ids = ' excluded_post_ids="' . esc_attr($field_value) . '"';
                    }
                }
            }
        }
        // generate the shortcode with post ID and title, include excluded post ids if available
        $shortcode = '[qapl-quick-ajax id="' . $post_id . '" title="' . esc_attr($post_title) . '"' . $excluded_post_ids . ']';
        //apply filters for extendability
        //$shortcode = apply_filters('generate_shortcode', $shortcode, $post_id)
        return $shortcode;
    }

    public static function menu_slug() {
        return 'qapl-menu';
    }
    public static function cpt_shortcode_slug() {
        return 'qapl-creator';
    }
    public static function settings_page_slug() {
        return 'qapl-settings';
    }

    /* quick-ajax-creator shortcode field names */
    public static function quick_ajax_shortcode_settings() {
        return '_qapl_quick_ajax_shortcode_settings';
    }
    /* old value, not in use from 1.3.3
    public static function quick_ajax_shortcode_code() {
        return 'qapl_quick_ajax_shortcode_code';
    }
    */
    public static function quick_ajax_plugin_version() {
        return 'qapl_quick_ajax_plugin_version';
    }
    public static function quick_ajax_plugin_cleanup_flags() {
        return 'qapl_quick_ajax_cleanup_flags';
    }
    public static function settings_wrapper_id() {
        return 'qapl_settings_wrapper';
    }
    public static function wp_nonce_form_quick_ajax_field() {
        return 'qapl_quick_ajax_nonce';
    }
    public static function wp_nonce_form_quick_ajax_action() {
        return 'qapl_quick_ajax_nonce_action';
    }
    public static function shortcode_page_select_post_type() {
        return 'qapl_select_post_type';
    }
    public static function shortcode_page_select_post_type_default_value(){
        return 'post';
    }
    public static function shortcode_page_show_taxonomy_filter(){
        return 'qapl_show_select_taxonomy';
    }
    public static function shortcode_page_show_taxonomy_filter_default_value(){
        return 0;
    }
    public static function shortcode_page_select_taxonomy(){
        return 'qapl_select_taxonomy';
    }
    public static function shortcode_page_select_posts_per_page(){
        return 'qapl_select_posts_per_page';
    }
    public static function shortcode_page_select_posts_per_page_default_value() {
        return 6;
    }
    public static function shortcode_page_select_order(){
        return 'qapl_select_order';
    }
    public static function shortcode_page_select_order_default_value() {
        return 'DESC';
    }
    public static function shortcode_page_select_orderby(){
        return 'qapl_select_orderby';
    }    
    public static function shortcode_page_select_orderby_default_value() {
        return 'date';
    }

    
    public static function shortcode_page_show_sort_button(){
        return 'qapl_show_order_button';
    }
    public static function shortcode_page_show_sort_button_default_value(){
        return 0;
    }
    public static function shortcode_page_select_sort_button_options(){
        return 'qapl_select_orderby_button_options';
    }
    public static function shortcode_page_select_sort_button_options_values() {
        $global_sort_labels = get_option(self::admin_page_global_options_name(), []);
        // Define sort options array with dynamic labels from global settings
        $sort_options = [
            [
                'value' => 'date-desc',
                'label' => isset($global_sort_labels['sort_option_date_desc_label'])
                    ? $global_sort_labels['sort_option_date_desc_label']
                    : __('Newest', 'quick-ajax-post-loader')
            ],
            [
                'value' => 'date-asc',
                'label' => isset($global_sort_labels['sort_option_date_asc_label'])
                    ? $global_sort_labels['sort_option_date_asc_label']
                    : __('Oldest', 'quick-ajax-post-loader')
            ],
            [
                'value' => 'comment_count-desc',
                'label' => isset($global_sort_labels['sort_option_comment_count_desc_label'])
                    ? $global_sort_labels['sort_option_comment_count_desc_label']
                    : __('Popular', 'quick-ajax-post-loader')
            ],
            [
                'value' => 'title-asc',
                'label' => isset($global_sort_labels['sort_option_title_desc_label'])
                    ? $global_sort_labels['sort_option_title_desc_label']
                    : __('A → Z', 'quick-ajax-post-loader')
            ],
            [
                'value' => 'title-desc',
                'label' => isset($global_sort_labels['sort_option_title_asc_label'])
                    ? $global_sort_labels['sort_option_title_asc_label']
                    : __('Z → A', 'quick-ajax-post-loader')
            ],
            [
                'value' => 'rand',
                'label' => isset($global_sort_labels['sort_option_rand_label'])
                    ? $global_sort_labels['sort_option_rand_label']
                    : __('Random', 'quick-ajax-post-loader')
            ],
        ];
    
        return $sort_options;
    }
    public static function shortcode_page_select_sort_button_options_default_value(){
        return 1;
    }
    public static function shortcode_page_show_inline_filter_sorting(){
        return 'qapl_show_inline_filter_sorting';
    }
    public static function shortcode_page_show_inline_filter_sorting_default_value(){
        return 1;
    }
    //public static function shortcode_page_select_post_status(){
    //    return 'qapl_select_post_status';
    //}
    public static function shortcode_page_select_post_status_default_value(){
        return 'publish';
    }
    public static function shortcode_page_ignore_sticky_posts(){
        return 'qapl_ignore_sticky_posts';
    }
    public static function shortcode_page_ignore_sticky_posts_default_value() {
        return false;
    }
    public static function shortcode_page_ajax_on_initial_load(){
        return 'qapl_ajax_on_initial_load';
    }
    public static function shortcode_page_ajax_on_initial_load_default_value(){
        return false;
    }
    public static function shortcode_page_ajax_infinite_scroll(){
        return 'qapl_ajax_infinite_scroll';
    }
    public static function shortcode_page_ajax_infinite_scroll_default_value(){
        return false;
    }
    public static function shortcode_page_set_post_not_in(){
        return 'qapl_select_post_not_in';
    }
    public static function shortcode_page_layout_select_columns_qty(){
        return 'qapl_layout_select_columns_qty';
    }
    public static function shortcode_page_layout_select_columns_qty_default_value(){
        return 3;
    }
    public static function shortcode_page_layout_taxonomy_filter_class(){
        return 'qapl_layout_add_taxonomy_filter_class';
    }
    public static function shortcode_page_layout_container_class(){
        return 'qapl_layout_add_container_class';
    }
    public static function shortcode_page_layout_quick_ajax_css_style(){
        return 'qapl_layout_quick_ajax_css_style';
    }
    public static function shortcode_page_layout_quick_ajax_css_style_default_value(){
        return 1;
    }
    public static function shortcode_page_layout_post_item_template(){
        return 'qapl_layout_quick_ajax_post_item_template';
    }
    public static function shortcode_page_layout_post_item_template_default_value(){
        return 'post-item';
    }
    public static function shortcode_page_show_custom_load_more_post_quantity(){
        return 'qapl_show_custom_load_more_post_quantity';
    }
    public static function shortcode_page_show_custom_load_more_post_quantity_default_value(){
        return 0;
    }
    public static function shortcode_page_select_custom_load_more_post_quantity(){
        return 'qapl_select_custom_load_more_post_quantity';
    }
    public static function shortcode_page_select_custom_load_more_post_quantity_default_value(){
        return 4;
    }
    public static function shortcode_page_override_global_loader_icon(){
        return 'qapl_override_global_loader_icon';
    }
    public static function shortcode_page_override_global_loader_icon_default_value(){
        return 0;
    }
    public static function shortcode_page_select_loader_icon(){
        return 'qapl_loader_icon';
    }
    public static function shortcode_page_select_loader_icon_default_value(){
        return 'loader-icon';
    }
    // attributes query names
    public static function query_settings_ajax_on_initial_load(){
       return 'ajax_initial_load';
    }
    public static function layout_quick_ajax_id(){
        return 'quick_ajax_id';
    }
    public static function layout_quick_ajax_css_style(){
        return 'quick_ajax_css_style';
    }
    public static function layout_container_num_columns(){
        return 'grid_num_columns';
    }
    public static function layout_post_item_template(){
        return 'post_item_template';
    }
    public static function layout_taxonomy_filter_class(){
        return 'taxonomy_filter_class';
    }
    public static function layout_container_class(){
        return 'container_class';
    }
    public static function layout_load_more_posts(){
        return 'load_more_posts';
    }
    public static function layout_select_loader_icon(){
        return 'loader_icon';
    }
    public static function layout_ajax_infinite_scroll(){
        return 'infinite_scroll';
    }
    /* Quick AJAX Settings */
    public static function admin_page_settings_field_option_group(){
        return 'qapl_settings_group';
    }
    public static function admin_page_global_options_name(){
        return 'qapl_quick_ajax_global_options';
    }
    /* Quick AJAX Global Options */
    public static function global_options_field_select_loader_icon(){
        return self::admin_page_global_options_name().'[loader_icon]';
    }
    public static function global_options_field_set_read_more_label(){
        return self::admin_page_global_options_name().'[read_more_label]';
    }
    public static function global_options_field_set_show_all_label(){
        return self::admin_page_global_options_name().'[show_all_label]';
    }
    public static function global_options_field_set_load_more_label(){
        return self::admin_page_global_options_name().'[load_more_label]';
    }
    public static function global_options_field_set_post_date_format(){
        return self::admin_page_global_options_name().'[post_date_format]';
    }
    //Sorting Options Labels
    public static function global_options_field_set_sort_option_date_desc_label(){
        return self::admin_page_global_options_name().'[sort_option_date_desc_label]';
    }
    public static function global_options_field_set_sort_option_date_asc_label(){
        return self::admin_page_global_options_name().'[sort_option_date_asc_label]';
    }
    public static function global_options_field_set_sort_option_comment_count_desc_label(){
        return self::admin_page_global_options_name().'[sort_option_comment_count_desc_label]';
    }
    public static function global_options_field_set_sort_option_title_desc_label(){
        return self::admin_page_global_options_name().'[sort_option_title_desc_label]';
    }
    public static function global_options_field_set_sort_option_title_asc_label(){
        return self::admin_page_global_options_name().'[sort_option_title_asc_label]';
    }
    public static function global_options_field_set_sort_option_rand_label(){
        return self::admin_page_global_options_name().'[sort_option_rand_label]';
    }
    /* Quick AJAX placeholders */
    /* not in use after removing placeholders
    public static function placeholder_read_more_label() {
        return [
            'placeholder' => '[[QAPL::READ_MORE_LABEL]]',
            'option_key' => 'read_more_label',
            'default' => __('Read More', 'quick-ajax-post-loader'),
        ];
    }
    public static function placeholder_show_all_label() {
        return [
            'placeholder' => '[[QAPL::SHOW_ALL_LABEL]]',
            'option_key' => 'show_all_label',
            'default' => __('Show All', 'quick-ajax-post-loader'),
        ];
    }
    public static function placeholder_load_more_label() {
        return [
            'placeholder' => '[[QAPL::LOAD_MORE_LABEL]]',
            'option_key' => 'load_more_label',
            'default' => __('Load More', 'quick-ajax-post-loader'),
        ];
    }
    public static function get_all_placeholders() {
        return [
            self::placeholder_read_more_label(),
            self::placeholder_load_more_label(),
            self::placeholder_show_all_label(),
        ];
    }
        */
}

class QAPL_File_Helper {
    private static $instance = null;
    private $plugin_dir_path;
    private $plugin_dir_url;

    private function __construct($plugin_dir_path, $plugin_dir_url) {
        $this->plugin_dir_path = $plugin_dir_path;
        $this->plugin_dir_url = $plugin_dir_url;
    }

    public static function get_instance() {
        if (self::$instance === null) {
            // set base plugin directory paths
            $plugin_dir_path = plugin_dir_path(__DIR__);
            $plugin_dir_url = plugin_dir_url(__DIR__);
            self::$instance = new self($plugin_dir_path, $plugin_dir_url);
        }
        return self::$instance;
    }
    public function file_exists($file_path) {
        //print_r($file_path);
        $full_path = $this->plugin_dir_path . ltrim($file_path, '/');
        return file_exists($full_path) ? $full_path : false;
    }
    public function get_plugin_directory() {
        return $this->plugin_dir_url;
    }
    public function get_plugin_js_directory() {
        return $this->plugin_dir_url . 'js/';
    }
    public function get_plugin_css_directory() {
        return $this->plugin_dir_url . 'css/';
    }
    
    //template dir path
    public function get_templates_dir_path($file) {
        // Path to the template in the child theme (or the theme itself if not using a child theme)
        $child_theme_template_path = get_stylesheet_directory() . '/quick-ajax-post-loader/templates' . $file;
        // Check if the template exists in the child theme
        if (file_exists($child_theme_template_path)) {
            return $child_theme_template_path;
        } 
        // Path to the template in the parent theme
        $theme_template_path = get_template_directory() . '/quick-ajax-post-loader/templates' . $file;
        // Check if the template exists in the parent theme
        if (file_exists($theme_template_path)) {
            return $theme_template_path;
        }
        // Path to the template in the plugin
        $plugin_template_path = $this->plugin_dir_path . 'templates' . $file;        
        // Check if the template exists in the plugin
        if (file_exists($plugin_template_path)) {
            return $plugin_template_path;
        }
        // Template was not found
        return false;
    }    
    //template file path
    public function get_templates_file_path($template_name, $default_name, $base_path) {
        // Use the provided template name if given; otherwise, use the default name.
        $template_name = sanitize_file_name(empty($template_name) ? $default_name : $template_name);
        $file_path = $this->get_templates_dir_path($base_path . $template_name . '.php');
        // Check if the template file exists. If not, use the default file.
        if (!file_exists($file_path)) {
            $file_path = $this->get_templates_dir_path($base_path . $default_name . '.php');
        }    
        return $file_path;
    }

    private static function find_template_files($path) {
        $files = [];
        foreach (glob($path) as $file) {
            if (is_file($file)) {
                $files[] = $file;
            }
        }
        return $files;
    }

    private static function get_template_name_from_file($file_path, $template_name) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        WP_Filesystem();
        global $wp_filesystem;

        if (!$wp_filesystem || !$wp_filesystem->exists($file_path)) {
            return basename(sanitize_file_name($file_path), '.php');
        }

        $file_contents = $wp_filesystem->get_contents($file_path);
        $lines = explode("\n", $file_contents);
    
        foreach ($lines as $line) {
            if (stripos($line, $template_name) !== false) {
                $line = str_replace(['/* ', $template_name, '*/'], '', $line);
                $line = trim($line);
                return !empty($line) ? $line : basename($file_path, '.php');
            }
        }    
        return basename($file_path, '.php');
    }

    public function plugin_get_templates_items_array($template_file_location, $template_name, $default_file = false) {
        $plugin_template_files = self::find_template_files($this->plugin_dir_path . 'templates/' . $template_file_location);
        // Attempt to get templates from the parent theme directory
        $parent_template_files = self::find_template_files(get_template_directory() . '/quick-ajax-post-loader/templates/' . $template_file_location);
        // Attempt to get templates from the child theme directory
        $child_template_files = [];
        if (get_template_directory() !== get_stylesheet_directory()) {
            $child_template_files = self::find_template_files(get_stylesheet_directory() . '/quick-ajax-post-loader/templates/' . $template_file_location);
        }

        $template_files_map = [];
        foreach ([$plugin_template_files, $parent_template_files, $child_template_files] as $files) {
            foreach ($files as $file_path) {
                $file_name = sanitize_file_name(basename($file_path, '.php'));
                $template_files_map[$file_name] = sanitize_text_field($file_path);
            }
        }

        $file_names = [];
        foreach ($template_files_map as $file_name => $file_path) {
            $file_names[] = [
                'file_name' => $file_name,
                'template_name' => self::get_template_name_from_file($file_path, $template_name)
            ];
        }

        if (!empty($default_file)) {
            // Iterate over the array to find the default file
            foreach ($file_names as $index => $file) {
                if ($file['file_name'] == $default_file) {
                    // Remove the item from its current position
                    $item = array_splice($file_names, $index, 1)[0];
                    // Add the item at the beginning of the array
                    array_unshift($file_names, $item);
                    break;
                }
            }
        }
        return $file_names;
    }  
}

class QAPL_Pages_Helper{
    private $file_helper;    
     //construct expects instance of QAPL_File_Helper
    public function __construct(QAPL_File_Helper $file_helper) {
        $this->file_helper = $file_helper;
    }
    public function plugin_admin_pages_config() {
        return $this->file_helper->file_exists('admin/admin-pages-config.php');
    }
    public function plugin_settings_page() {
        return $this->file_helper->file_exists('admin/pages/settings-page.php');
    }
    public function plugin_shortcode_page() {
        return $this->file_helper->file_exists('admin/pages/shortcode-page.php');
    }
    public function plugin_ajax_class() {
        return $this->file_helper->file_exists('inc/class-ajax.php');
    }
    public function plugin_shortcode_class() {
        return $this->file_helper->file_exists('inc/class-shortcode.php');
    }
    public function plugin_ajax_actions() {
        return $this->file_helper->file_exists('inc/actions.php');
    }
    public function plugin_functions() {
        return $this->file_helper->file_exists('inc/functions.php');
    }
    public function plugin_updater() {
        return $this->file_helper->file_exists('inc/class-updater.php');
    }
    public function plugin_template_hooks() {
        return $this->file_helper->file_exists('inc/class-template-hooks.php');
    }
    public function plugin_deprecated_hooks_handler() {
        return $this->file_helper->file_exists('inc/class-deprecated-hooks-handler.php');
    }
}

class QAPL_Form_Fields_Helper{
    private static $file_helper;
    //set the QAPL_File_Helper instance if not already done
    private static function get_file_helper() {
        if (!self::$file_helper) {
            self::$file_helper = QAPL_File_Helper::get_instance();
        }
        return self::$file_helper;
    }
    //select post type  
    public static function get_field_select_post_type(){
        $post_types = get_post_types(array('public' => true, 'publicly_queryable' => true), 'objects');
        $post_type_options = array();
        foreach ($post_types as $post_type) {
            if (isset($post_type->labels->name) && $post_type->labels->name !== 'Media') {
                $post_type_options[] = array(
                    'label' => $post_type->label,
                    'value' => $post_type->name,
                );
            }
        }
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_select_post_type(),
            'label' => __('Select Post Type', 'quick-ajax-post-loader'),
            'type' => 'select',
            'options' => $post_type_options,
            'description' => __('Choose the post type you want to display using AJAX.', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    //show taxonomy checkbox
    public static function get_field_show_taxonomy_filter(){
        return array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter(),
            'label' => __('Show Taxonomy Filter', 'quick-ajax-post-loader'),
            'type' => 'checkbox',
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter_default_value(),
            'description' => __('Enable filtering by taxonomy/category.', 'quick-ajax-post-loader')
        );
    }
    //select taxonomy
    public static function get_field_select_taxonomy(){
        return array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_select_taxonomy(),
            'label' => __('Select Taxonomy', 'quick-ajax-post-loader'),
            'type' => 'select',
            'options' => '',
            'default' => '',
            'description' => __('Select the taxonomy to be used for filtering posts.', 'quick-ajax-post-loader')
        );
    }
    //post per page number
    public static function get_field_select_posts_per_page(){
        return array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_select_posts_per_page(),
            'label' => __('Posts Per Page', 'quick-ajax-post-loader'),
            'type' => 'number',
            'options' => '',
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_select_posts_per_page_default_value(),
            'description' => __('Determine the number of posts to be loaded per AJAX request.', 'quick-ajax-post-loader')
        );
    }
    //select post order
    public static function get_field_select_order(){
        $order_options = array(
            array(
                'label' => __('Descending - order from highest to lowest', 'quick-ajax-post-loader'),
                'value' => 'DESC'
            ),
            array(
                'label' => __('Ascending - order from lowest to highest', 'quick-ajax-post-loader'),
                'value' => 'ASC'
            )
        );
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_select_order(),
            'label' => __('Default Sort Order', 'quick-ajax-post-loader'),
            'type' => 'select',
            'options' => $order_options,
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_select_order_default_value(),
            'description' => __('Specify the order of posts.', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }

    //select post orderby
    public static function get_field_select_orderby(){
        $orderby_options = [
            ['value' => 'date', 'label' => __('Date: Sort by publication date', 'quick-ajax-post-loader')],
            ['value' => 'title', 'label' => __('Title: Sort by post title', 'quick-ajax-post-loader')],
            ['value' => 'comment_count', 'label' => __('Comments: Sort by comment count', 'quick-ajax-post-loader')],
            ['value' => 'rand', 'label' => __('Random: Random order', 'quick-ajax-post-loader')],
            //['value' => 'menu_order', 'label' => __('Menu Order: Sort by custom menu order', 'quick-ajax-post-loader')],
        ];
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_select_orderby(),
            'label' => __('Default Sort By', 'quick-ajax-post-loader'),
            'type' => 'select',
            'options' => $orderby_options,
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_select_orderby_default_value(),
            'description' => __('Choose the sorting criteria for posts.', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }

    //show sort button
    public static function get_field_show_sort_button(){
        return array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_show_sort_button(),
            'label' => __('Show Sorting Button', 'quick-ajax-post-loader'),
            'type' => 'checkbox',
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_show_sort_button_default_value(),
            'description' => __('Enable a button that allows users to switch between ascending and descending order.', 'quick-ajax-post-loader')
        );
    }

    //select sort button
    public static function get_field_select_sort_button_options(){
        return array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_select_sort_button_options(),
            'label' => __('Available Sorting Options', 'quick-ajax-post-loader'),
            'type' => 'multiselect',
            'options' => QAPL_Quick_Ajax_Helper::shortcode_page_select_sort_button_options_values(),
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_select_sort_button_options_default_value(),
            'description' => __('Select which sorting options will be available to users.', 'quick-ajax-post-loader')
        );
    }
    public static function get_field_show_inline_filter_sorting(){
        return array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_show_inline_filter_sorting(),
            'label' => __('Inline Filter & Sorting', 'quick-ajax-post-loader'),
            'type' => 'checkbox',
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_show_inline_filter_sorting_default_value(),
            'description' => __('Display taxonomy filter and sorting options in a single row to save space and improve layout', 'quick-ajax-post-loader')
        );
    }

    //select post status
    /*public static function get_field_select_post_status(){
        $post_status_options = array(
            array(
                'label' => __('Publish: Published posts', 'quick-ajax-post-loader'),
                'value' => 'publish'
            ),
            array(
                'label' => __('Draft: Draft posts', 'quick-ajax-post-loader'),
                'value' => 'draft'
            ),
            array(
                'label' => __('Pending: Pending review posts', 'quick-ajax-post-loader'),
                'value' => 'pending'
            ),
            array(
                'label' => __('Private: Private posts', 'quick-ajax-post-loader'),
                'value' => 'private'
            ),
            array(
                'label' => __('Trash: Trashed posts', 'quick-ajax-post-loader'),
                'value' => 'trash'
            ),
            array(
                'label' => __('Auto-Draft: Auto-draft posts', 'quick-ajax-post-loader'),
                'value' => 'auto-draft'
            ),
            array(
                'label' => __('Inherit: Inherited posts', 'quick-ajax-post-loader'),
                'value' => 'inherit'
            ),
        );
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_select_post_status(),
            'label' => __('Post Status', 'quick-ajax-post-loader'),
            'type' => 'select',
            'options' => $post_status_options,
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_select_post_status_default_value(),
            'description' => __('Select the post status to be used by AJAX.', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }*/
    //add Excluded Post IDs
    public static function get_field_set_post_not_in(){
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_set_post_not_in(),
            'label' => __('Excluded Post IDs', 'quick-ajax-post-loader'),
            'type' => 'text',
            'options' => '',
            'default' => '',
            'placeholder' => '3, 66, 999',            
            'description' => __('Enter a list of post IDs to exclude from the query.', 'quick-ajax-post-loader'),
        );
        return $field_properties;
    }
    //set Ignore Sticky Posts
    public static function get_field_set_ignore_sticky_posts(){
        return array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_ignore_sticky_posts(),
            'label' => __('Ignore Sticky Posts', 'quick-ajax-post-loader'),
            'type' => 'checkbox',
            'options' => '',
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_ignore_sticky_posts_default_value(),
            'description' => __('Specify to ignore sticky posts, treating them as regular posts in the query.', 'quick-ajax-post-loader')
        );
    }
    //add Load Posts via AJAX on Initial Load
    public static function get_field_set_ajax_on_initial_load(){
        return array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_ajax_on_initial_load(),
            'label' => __('Load Initial Posts via AJAX', 'quick-ajax-post-loader'),
            'type' => 'checkbox',
            'options' => '',
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_ajax_on_initial_load_default_value(),
            'description' => __('Enable this option to load the initial set of posts via AJAX on page load. This can help in cases where caching might cause outdated content to be displayed.', 'quick-ajax-post-loader')
        );
    }
    // add Infinite Scroll via AJAX
    public static function get_field_set_ajax_infinite_scroll(){
        return array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_ajax_infinite_scroll(),
            'label' => __('Enable Infinite Scroll', 'quick-ajax-post-loader'),
            'type' => 'checkbox',
            'options' => '',
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_ajax_infinite_scroll_default_value(),
            'description' => __('Enable this option to automatically load more posts via AJAX as the user scrolls down the page.', 'quick-ajax-post-loader')
        );
    }
    //apply quick ajax css style
    public static function get_field_layout_quick_ajax_css_style(){
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_layout_quick_ajax_css_style(),
            'label' => __('Apply Quick AJAX CSS Style', 'quick-ajax-post-loader'),
            'type' => 'checkbox',
            'options' => '',
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_layout_quick_ajax_css_style_default_value(),
            'description' => __('Apply Quick AJAX CSS styles and column layout.', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    //select number of columns
    public static function get_field_layout_select_columns_qty(){
        $columns_qty_options = array();
        for ($i = 1; $i <= 12; $i++) {
            $columns_qty_options[] = array(
                'label' =>  strval($i),
                'value' => $i
            );
        }
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_layout_select_columns_qty(),
            'label' => __('Number of Columns', 'quick-ajax-post-loader'),
            'type' => 'select',
            'options' => $columns_qty_options,
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_layout_select_columns_qty_default_value(),
            'description' => __('Specify the quantity of columns.', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    //select post item template
    public static function get_field_layout_post_item_template(){
        $post_item_template_options = array();
        $file_helper = self::get_file_helper();
        $post_item_templates = $file_helper->plugin_get_templates_items_array('post-items/post-item*.php', 'Post Item Name:', QAPL_Quick_Ajax_Helper::shortcode_page_layout_post_item_template_default_value());
        foreach($post_item_templates as $item){
            $post_item_template_options[] = array(
                'label' => $item['template_name'],
                'value' => $item['file_name']
            );
        }
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_layout_post_item_template(),
            'label' => __('Select Post Item Template', 'quick-ajax-post-loader'),
            'type' => 'select',
            'options' => $post_item_template_options,
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_layout_post_item_template_default_value(),
            'description' => __('Choose a template for displaying post items.', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    //add custom class for taxonomy filter
    public static function get_field_layout_taxonomy_filter_class(){
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_layout_taxonomy_filter_class(),
            'label' => __('Add class to taxonomy filter', 'quick-ajax-post-loader'),
            'type' => 'text',
            'options' => '',
            'default' => '',
            'placeholder' => __('class-name, another-class-name', 'quick-ajax-post-loader'),
            'description' => __('Add classes to the filter: class-one, class-two, class-three', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    //add custom class for container
    public static function get_field_layout_container_class(){
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_layout_container_class(),
            'label' => __('Add class to post container', 'quick-ajax-post-loader'),
            'type' => 'text',
            'options' => '',
            'default' => '',
            'placeholder' => __('class-name, another-class-name', 'quick-ajax-post-loader'),
            'description' => __('Add classes to the post container: class-one, class-two, class-three', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    //show custom load more post quantity
    public static function get_field_show_custom_load_more_post_quantity(){
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_show_custom_load_more_post_quantity(),
            'label' => __('Custom Load More Post Quantity', 'quick-ajax-post-loader'),
            'type' => 'checkbox',
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_show_custom_load_more_post_quantity_default_value(),
            'description' => __('Load a different number of posts than the default page display.', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    //select custom load more post quantity
    public static function get_field_select_custom_load_more_post_quantity(){
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_select_custom_load_more_post_quantity(),
            'label' => __('Custom Load More Post Quantity', 'quick-ajax-post-loader'),
            'type' => 'number',
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_select_custom_load_more_post_quantity_default_value(),
            'description' => __('Select the custom number of posts to load when using the "Load More" button.', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    //override global loader icon
    public static function get_field_override_global_loader_icon(){
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::shortcode_page_override_global_loader_icon(),
            'label' => __('Override Global Loader Icon', 'quick-ajax-post-loader'),
            'type' => 'checkbox',
            'default' => QAPL_Quick_Ajax_Helper::shortcode_page_override_global_loader_icon_default_value(),
            'description' => __('Set a different loader icon than the one specified in global options.', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    //select loader icon
    public static function get_field_select_loader_icon(){
        $field_properties = self::select_loader_icon_properties(QAPL_Quick_Ajax_Helper::shortcode_page_select_loader_icon(), QAPL_Quick_Ajax_Helper::shortcode_page_select_loader_icon_default_value());
        return $field_properties;
    }
    //select loader icon global
    public static function get_global_field_select_loader_icon(){
        $field_properties = self::select_loader_icon_properties(QAPL_Quick_Ajax_Helper::global_options_field_select_loader_icon(), QAPL_Quick_Ajax_Helper::shortcode_page_select_loader_icon_default_value());
        return $field_properties;
    }
    private static function select_loader_icon_properties($field_name, $field_default_value) {
        $loader_icon_options = array();
        $file_helper = self::get_file_helper();
        $loader_icon_templates = $file_helper->plugin_get_templates_items_array('loader-icon/*.php', 'Loader Icon Name:', QAPL_Quick_Ajax_Helper::shortcode_page_select_loader_icon_default_value());
        foreach($loader_icon_templates as $item){
            $loader_icon_options[] = array(
                'label' => $item['template_name'],
                'value' => $item['file_name']
            );
        }
        $field_properties = array(
            'name' => $field_name,
            'label' => __('Select Loader Icon', 'quick-ajax-post-loader'),
            'type' => 'select',
            'options' => $loader_icon_options,
            'default' => $field_default_value,
            'description' => __('Choose an icon to display as the loading indicator when the "Load More" button is clicked.', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    public static function get_global_options_field_set_read_more_label(){
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::global_options_field_set_read_more_label(),
            'label' => __('Set "Read More" Label', 'quick-ajax-post-loader'),
            'type' => 'text',
            'options' => '', // Not required for text field
            'default' => __('Read More', 'quick-ajax-post-loader'),
            'placeholder' => __('Enter custom label for Read More', 'quick-ajax-post-loader'),
            'description' => __('Customize the "Read More" text for your templates. This label will appear as a link or button for each post item. Examples: "Read More", "Continue Reading", or "Learn More".', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    public static function get_global_options_field_set_show_all_label(){
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::global_options_field_set_show_all_label(),
            'label' => __('Set "Show All" Label', 'quick-ajax-post-loader'),
            'type' => 'text',
            'options' => '', // Not required for text field
            'default' => __('Show All', 'quick-ajax-post-loader'),
            'placeholder' => __('Enter custom label for Show All', 'quick-ajax-post-loader'),
            'description' => __('Customize the "Show All" text label for the filter. This label will appear as an option to display all posts without filtering. Examples: "Show All", "View All", or "Display All".', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    public static function get_global_options_field_set_load_more_label() {
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::global_options_field_set_load_more_label(),
            'label' => __('Set "Load More" Label', 'quick-ajax-post-loader'),
            'type' => 'text',
            'options' => '', // Not required for text field
            'default' => __('Load More', 'quick-ajax-post-loader'),
            'placeholder' => __('Enter custom label for Load More', 'quick-ajax-post-loader'),
            'description' => __('Customize the "Load More" button text. This label will appear on the button used to load additional posts dynamically. Examples: "Load More", "Show More", or "View More".', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    public static function get_global_options_field_set_post_date_format() {
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::global_options_field_set_post_date_format(),
            'label' => __('Set Date Format', 'quick-ajax-post-loader'),
            'type' => 'text',
            'options' => '', // Not required for text field
            'default' => 'F j, Y',
            'placeholder' => __('Enter date format (e.g., F j, Y)', 'quick-ajax-post-loader'),
        'description' => __('Customize the format for displaying post dates. This text will replace the default date format. For example: "F j, Y" (January 1, 2023) or "Y-m-d" (2023-01-01). Refer to the PHP date format documentation for more options.', 'quick-ajax-post-loader'),
        );
        return $field_properties;
    }
    public static function get_global_options_field_set_sort_option_date_desc_label() {
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::global_options_field_set_sort_option_date_desc_label(),
            'label' => __('Set "Newest" Label', 'quick-ajax-post-loader'),
            'type' => 'text',
            'options' => '', // Not required for text field
            'default' => __('Newest', 'quick-ajax-post-loader'),
            'placeholder' => __('Newest', 'quick-ajax-post-loader'),
            'description' => __('Set the label for sorting posts from newest to oldest (based on publication date). Examples: "Newest", "Latest", "Recent".', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    public static function get_global_options_field_set_sort_option_date_asc_label() {
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::global_options_field_set_sort_option_date_asc_label(),
            'label' => __('Set "Oldest" Label', 'quick-ajax-post-loader'),
            'type' => 'text',
            'options' => '', // Not required for text field
            'default' => __('Oldest', 'quick-ajax-post-loader'),
            'placeholder' => __('Oldest', 'quick-ajax-post-loader'),
            'description' => __('Set the label for sorting posts from oldest to newest (based on publication date). Examples: "Oldest", "First", "Earliest".', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    public static function get_global_options_field_set_sort_option_comment_count_desc_label() {
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::global_options_field_set_sort_option_comment_count_desc_label(),
            'label' => __('Set "Popular" Label', 'quick-ajax-post-loader'),
            'type' => 'text',
            'options' => '', // Not required for text field
            'default' => __('Popular', 'quick-ajax-post-loader'),
            'placeholder' => __('Popular', 'quick-ajax-post-loader'),
            'description' => __('Set the label for sorting posts by the highest number of comments. Examples: "Popular", "Trending", "Most Discussed".', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    public static function get_global_options_field_set_sort_option_title_desc_label() {
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::global_options_field_set_sort_option_title_desc_label(),
            'label' => __('Set "A → Z" Label', 'quick-ajax-post-loader'),
            'type' => 'text',
            'options' => '', // Not required for text field
            'default' => __('A → Z', 'quick-ajax-post-loader'),
            'placeholder' => __('A → Z', 'quick-ajax-post-loader'),
            'description' => __('Set the label for sorting posts alphabetically (A to Z) based on the post title. Examples: "Alphabetical", "A → Z", "Sort by Name".', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    public static function get_global_options_field_set_sort_option_title_asc_label() {
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::global_options_field_set_sort_option_title_asc_label(),
            'label' => __('Set "Z → A" Label', 'quick-ajax-post-loader'),
            'type' => 'text',
            'options' => '', // Not required for text field
            'default' => __('Z → A', 'quick-ajax-post-loader'),
            'placeholder' => __('Z → A', 'quick-ajax-post-loader'),
            'description' => __('Set the label for sorting posts alphabetically (Z to A) based on the post title. Examples: "Reverse Alphabetical", "Z → A", "Sort by Name Descending".', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    public static function get_global_options_field_set_sort_option_rand_label() {
        $field_properties = array(
            'name' => QAPL_Quick_Ajax_Helper::global_options_field_set_sort_option_rand_label(),
            'label' => __('Set "Random" Label', 'quick-ajax-post-loader'),
            'type' => 'text',
            'options' => '', // Not required for text field
            'default' => __('Random', 'quick-ajax-post-loader'),
            'placeholder' => __('Random', 'quick-ajax-post-loader'),
            'description' => __('Set the label for sorting posts in a random order. Examples: "Shuffle", "Random", "Surprise Me".', 'quick-ajax-post-loader')
        );
        return $field_properties;
    }
    public static function get_global_field_remove_old_data(){
        return array(
            'name' => 'qapl_remove_old_meta',
            'label' => __('Confirm Purge of Old Data', 'quick-ajax-post-loader'),
            'type' => 'checkbox',
            'options' => '',
            'default' => 0,
            'description' => __('Choose this option to remove old, unused data from the database. This will help keep your site clean and efficient. Be aware that if you switch back to an older version of the plugin, it might not work as expected.', 'quick-ajax-post-loader'),
        );
    }
}

if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Hooks {
    // Filter Container Hooks
    const HOOK_FILTER_CONTAINER_BEFORE = 'qapl_filter_container_before';
    const HOOK_FILTER_CONTAINER_START = 'qapl_filter_container_start';
    const HOOK_FILTER_CONTAINER_END = 'qapl_filter_container_end';
    const HOOK_FILTER_CONTAINER_AFTER = 'qapl_filter_container_after';

    // Posts Container Hooks
    const HOOK_POSTS_CONTAINER_BEFORE = 'qapl_posts_container_before';
    const HOOK_POSTS_CONTAINER_START = 'qapl_posts_container_start';
    const HOOK_POSTS_CONTAINER_END = 'qapl_posts_container_end';
    const HOOK_POSTS_CONTAINER_AFTER = 'qapl_posts_container_after';

    // Load More Button Hooks
    //const HOOK_LOAD_MORE_BEFORE = 'qapl_load_more_before';
    //const HOOK_LOAD_MORE_AFTER = 'qapl_load_more_after';

    // Loader Hooks
    const HOOK_LOADER_BEFORE = 'qapl_loader_before';
    const HOOK_LOADER_AFTER = 'qapl_loader_after';

    // Filters
    const HOOK_MODIFY_POSTS_QUERY_ARGS = 'qapl_modify_posts_query_args';
    const HOOK_MODIFY_TAXONOMY_FILTER_BUTTONS = 'qapl_modify_taxonomy_filter_buttons';
    const HOOK_MODIFY_SORTING_OPTIONS_VARIANTS = 'qapl_modify_sorting_options_variants';

    // Template Hooks
    const HOOK_TEMPLATE_POST_ITEM_DATE = 'qapl_template_post_item_date';
    const HOOK_TEMPLATE_POST_ITEM_IMAGE = 'qapl_template_post_item_image';
    const HOOK_TEMPLATE_POST_ITEM_TITLE = 'qapl_template_post_item_title';
    const HOOK_TEMPLATE_POST_ITEM_EXCERPT = 'qapl_template_post_item_excerpt';
    const HOOK_TEMPLATE_POST_ITEM_READ_MORE = 'qapl_template_post_item_read_more';
    const HOOK_TEMPLATE_LOAD_MORE_BUTTON = 'qapl_template_load_more_button';
}


/*
class QAPL_Logger {
    private static $instance = null;
    private $file_helper;
    private $logs = [];

    private function __construct() {
        $this->file_helper = QAPL_File_Helper::get_instance();
    }
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function addLog($message, $level = 'info') {
        $this->logs[] = '[' . strtoupper($level) . '] ' . $message;
    }
    public function getLogs() {
        return $this->logs;
    }

    public function saveLogs() {
        $log_file = $this->file_helper->get_plugin_directory() . '/app.log';
        file_put_contents($log_file, implode(PHP_EOL, $this->logs) . PHP_EOL, FILE_APPEND);
        $this->logs = [];
    }
}
*/
/* not in use after removing placeholders
class QAPL_Placeholder_Replacer{
    private static $instance = null;
    private $placeholders = [];
    private $helper;
    private $global_options;

    public function __construct() {
        // Initialize helper instance
        $this->helper = QAPL_Quick_Ajax_Helper::get_instance();
        // Fetch global options once and store them
        $this->global_options = get_option($this->helper->admin_page_global_options_name(), []);
        // Initialize placeholders
        $this->initialize_placeholders();
    }
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Initialize placeholders from global options.
    private function initialize_placeholders() {
        $all_placeholders = QAPL_Quick_Ajax_Helper::get_all_placeholders();
        foreach ($all_placeholders as $placeholder_config) {
            $option_key = $placeholder_config['option_key'];
            $default_value = $placeholder_config['default'];
            $placeholder = $placeholder_config['placeholder'];
            // Use global option value or fallback to default
            $this->placeholders[$placeholder] = $this->global_options[$option_key] ?? $default_value;
        }
    }
    public function replace_placeholders($content) {
        // replace placeholders in content
        foreach ($this->placeholders as $placeholder => $value) {
            $content = str_replace($placeholder, $value, $content);
        }
        return $content;
    }
    public function get_placeholders() {
        // return all placeholders
        return $this->placeholders;
    }
}
    */