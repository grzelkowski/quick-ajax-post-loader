<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Quick_Ajax_Helper{
    public static $admin_config_loaded = false;
    public static $ajax_class_loaded = false;
    public static function get_plugin_version() {
        return '1.2.1';
    }    
    public static function plugin_name(){
        return 'Quick Ajax Post Loader';
    }    
    public static function text_domain(){
        return 'quick-ajax-post-loader';
    }    
    public static function element_exists($type, $name) {
        $exists = false;
        $type_formatted = '';
        if ($type === 'class' && class_exists($name)) {
            $exists = true;
            $type_formatted = 'class';
        } else if ($type === 'function' && function_exists($name)) {
            $exists = true;
            $type_formatted = 'function';
        }
        if ($exists) {
            add_action('admin_notices', function() use ($name, $type_formatted) {
                echo '<div class="notice notice-error"><p><strong>'.esc_html(self::plugin_name()).'</strong> is not working properly. Error: A ' . esc_html($type_formatted) . ' named <strong>' . esc_html($name) . '</strong> already exists, which may have been declared by another plugin.</p></div>';
            });            
            return false;
        }    
        return true;
    }
    public static function file_exists($file_path) {
        if (file_exists($file_path)) {
            return $file_path;
        }
        //error_log('File ' . esc_url($file_path) . ' does not exist.');
        return false;
    }   
    public static function get_plugin_dir_path() {
        return plugin_dir_path( dirname( __FILE__ ) );
    }
    public static function get_plugin_dir_url() {
        return plugin_dir_url( dirname( __FILE__ ) );
    }
    public static function get_plugin_js_directory() {
        return self::get_plugin_dir_url() . 'js/';
    }
    public static function get_plugin_css_directory() {
        return self::get_plugin_dir_url() . 'css/';
    }
    public static function plugin_admin_pages_config() {
        $file_path = self::get_plugin_dir_path() . 'admin/admin-pages-config.php';
        if(self::file_exists($file_path)){
            self::$admin_config_loaded = true;
            return $file_path;
        }
        return false;
    }
    public static function plugin_settings_page() {
        if(self::$admin_config_loaded == true){
            $file_path = self::get_plugin_dir_path() . 'admin/pages/settings-page.php';
            return $file_path;
        }
        return false;
    }
    public static function plugin_shortcode_page() {
        if(self::$admin_config_loaded == true){
            $file_path = self::get_plugin_dir_path() . 'admin/pages/shortcode-page.php';
            return $file_path;
        }
        return false;
    }
    public static function plugin_ajax_class() {
        $file_path = self::get_plugin_dir_path() . 'inc/class-ajax.php';
        if(self::file_exists($file_path)){
            self::$ajax_class_loaded = true;
            return $file_path;
        };
        return false;
    }
    public static function plugin_shortcode_class() {
        if(self::$ajax_class_loaded == true){
            return self::get_plugin_dir_path() . 'inc/class-shortcode.php';
        }
        return false;
    }
    public static function plugin_ajax_actions() {
        if(self::$ajax_class_loaded == true){
            return self::get_plugin_dir_path() . 'inc/actions.php';
        }
        return false;
    }
    public static function plugin_functions() {
        if(self::$ajax_class_loaded == true){
            return self::get_plugin_dir_path() . 'inc/functions.php';
        }
        return false;        
    }
    public static function plugin_update() {
        if(self::$ajax_class_loaded == true){
            return self::get_plugin_dir_path() . 'inc/update.php';
        }
        return false;        
    }
    //template dir path
    public static function get_templates_dir_path($file) {
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
        $plugin_template_path = self::get_plugin_dir_path() . 'templates' . $file;        
        // Check if the template exists in the plugin
        if (file_exists($plugin_template_path)) {
            return $plugin_template_path;
        }
        // Template was not found
        return false;
    }
    //template file path
    public static function get_templates_file_path($template_name, $default_name, $base_path){
        // Use the provided template name if given; otherwise, use the default name.
        $template_name = sanitize_file_name(empty($template_name) ? $default_name : $template_name);
        $file_path = sanitize_text_field(self::get_templates_dir_path($base_path . $template_name . '.php'));
        // Check if the template file exists. If not, use the default file.
        if (!file_exists($file_path)) {
            $file_path = self::get_templates_dir_path($base_path . $default_name . '.php');
        }    
        return $file_path;
    } 
    //template post item   
    public static function plugin_templates_post_item_template($template_name = false){
        $default_name = QAPL_Quick_Ajax_Helper::shortcode_page_layout_post_item_template_default_value();
        return self::get_templates_file_path($template_name, $default_name, '/post-items/');
    }
    //template loader icon
    public static function plugin_templates_loader_icon_template($template_name = false){
        $default_name = QAPL_Quick_Ajax_Helper::shortcode_page_select_loader_icon_default_value();
        return self::get_templates_file_path($template_name, $default_name, '/loader-icon/');
    }
    
    private static function get_template_name_from_file($file_path, $template_name) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
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
    private static function find_template_files($path) {
        $files = [];
        foreach (glob($path) as $file) {
            if (is_file($file)) {
                $files[] = $file;
            }
        }
        return $files;
    }
    public static function plugin_get_templates_items_array($template_file_location, $template_name, $default_file = false) {        
        $plugin_template_files = self::find_template_files(self::get_plugin_dir_path() . 'templates/'.$template_file_location);
        // Attempt to get templates from the parent theme directory
        $parent_template_files = self::find_template_files(get_template_directory() . '/quick-ajax-post-loader/templates/'.$template_file_location);
        // Attempt to get templates from the child theme directory
        $child_template_files = [];
        if(get_template_directory() !== get_stylesheet_directory()){
            $child_template_files = self::find_template_files(get_stylesheet_directory() . '/quick-ajax-post-loader/templates/'.$template_file_location);  
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
        if(!empty($default_file)){
            // Iterate over the array to find the default file
            foreach ($file_names as $index => $file) {
                if ($file['file_name'] == $default_file) {
                    // Remove the item from its current position
                    $item = array_splice($file_names, $index, 1)[0];
                    // Add the item at the beginning of the array
                    array_unshift($file_names, $item);
                    // Break the loop after moving the default file to the start
                    break;
                }
            }
        }
        return $file_names;
    }
    public static function plugin_templates_no_posts(){
        $file_path = self::get_templates_dir_path('/post-items/no-posts.php');
        return $file_path;
    }
    public static function term_filter_button_data_button(){
        return 'quick-ajax-filter-button';
    }
    public static function plugin_templates_term_filter_button(){
        $file_path = self::get_templates_dir_path('/term-filter/term-filter-button.php');
        return $file_path;
    }    
    public static function load_more_button_data_button(){
        return 'quick-ajax-load-more';
    }
    public static function plugin_templates_load_more_button(){
        $file_path = self::get_templates_dir_path('/load-more-button.php');
        return $file_path;
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
    public static function settings_wrapper_id() {
        return 'qapl_settings_wrapper';
    }
    public static function meta_box_shortcode_name() {
        return 'qapl_quick_ajax_meta_box_shortcode';
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
    public static function shortcode_page_select_post_status(){
        return 'qapl_select_post_status';
    }
    public static function shortcode_page_select_post_status_default_value(){
        return 'publish';
    }
    public static function shortcode_page_ignore_sticky_posts(){
        return 'qapl_ignore_sticky_posts';
    }
    public static function shortcode_page_ignore_sticky_posts_default_value() {
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
    public static function layout_quick_ajax_id(){
        return 'quick_ajax_id';
    }
    public static function layout_quick_ajax_css_style(){
        return 'quick_ajax_css_style';
    }
    public static function layout_grid_num_columns(){
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
    /* Quick AJAX Settings */
    public static function admin_page_settings_field_option_group(){
        return 'qapl-settings-group';
    }
    public static function admin_page_global_options_name(){
        return 'qapl-global-options';
    }
    /* Quick AJAX Global Options */
    public static function global_options_field_select_loader_icon(){
        return self::admin_page_global_options_name().'[loader_icon]';
    }
    

    public static function initialize(){
        $initialize_list = [
            self::plugin_admin_pages_config(),
            self::plugin_settings_page(),
            self::plugin_shortcode_page(),
            self::plugin_ajax_class(),
            self::plugin_ajax_actions(),
            self::plugin_functions(),
            //self::plugin_update(),
            self::plugin_shortcode_class()
        ];
        foreach ($initialize_list as $initialize) {
            if ($initialize && self::file_exists($initialize)) {
                require_once($initialize);
            }         
        }
    }
}


if (QAPL_Quick_Ajax_Helper::element_exists('class','QAPL_Quick_Ajax_Fields')) {
    class QAPL_Quick_Ajax_Fields{
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
                'label' => __('Select Post Type:', 'quick-ajax-post-loader'),
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
                'label' => __('Select Taxonomy:', 'quick-ajax-post-loader'),
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
                'label' => __('Posts Per Page:', 'quick-ajax-post-loader'),
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
                'label' => __('Posts Order:', 'quick-ajax-post-loader'),
                'type' => 'select',
                'options' => $order_options,
                'default' => QAPL_Quick_Ajax_Helper::shortcode_page_select_order_default_value(),
                'description' => __('Specify the order of posts.', 'quick-ajax-post-loader')
            );
            return $field_properties;
        }
        //select post orderby
        public static function get_field_select_orderby(){
            $orderby_options = array(
                array(
                    'label' => __('None: No specific sorting criteria', 'quick-ajax-post-loader'),
                    'value' => 'none'
                ),
                array(
                    'label' => __('ID: Sort by post ID', 'quick-ajax-post-loader'),
                    'value' => 'ID'
                ),
                array(
                    'label' => __('Author: Sort by author ID', 'quick-ajax-post-loader'),
                    'value' => 'author'
                ),
                array(
                    'label' => __('Title: Sort by post title', 'quick-ajax-post-loader'),
                    'value' => 'title'
                ),
                array(
                    'label' => __('Name: Sort by post slug', 'quick-ajax-post-loader'),
                    'value' => 'name'
                ),
                array(
                    'label' => __('Date: Sort by publication date', 'quick-ajax-post-loader'),
                    'value' => 'date'
                ),
                array(
                    'label' => __('Modified: Sort by last modified date', 'quick-ajax-post-loader'),
                    'value' => 'modified'
                ),
                array(
                    'label' => __('Parent: Sort by parent post ID', 'quick-ajax-post-loader'),
                    'value' => 'parent'
                ),
                array(
                    'label' => __('Random: Random order', 'quick-ajax-post-loader'),
                    'value' => 'rand'
                ),
                array(
                    'label' => __('Comments: Sort by comment count', 'quick-ajax-post-loader'),
                    'value' => 'comment_count'
                ),
                array(
                    'label' => __('Menu Order: Sort by custom menu order', 'quick-ajax-post-loader'),
                    'value' => 'menu_order'
                )
            );
            $field_properties = array(
                'name' => QAPL_Quick_Ajax_Helper::shortcode_page_select_orderby(),
                'label' => __('Posts Order by:', 'quick-ajax-post-loader'),
                'type' => 'select',
                'options' => $orderby_options,
                'default' => QAPL_Quick_Ajax_Helper::shortcode_page_select_orderby_default_value(),
                'description' => __('Choose the sorting criteria for posts.', 'quick-ajax-post-loader')
            );
            return $field_properties;
        }
        //select post status
        public static function get_field_select_post_status(){
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
                'label' => __('Post Status:', 'quick-ajax-post-loader'),
                'type' => 'select',
                'options' => $post_status_options,
                'default' => QAPL_Quick_Ajax_Helper::shortcode_page_select_post_status_default_value(),
                'description' => __('Select the post status to be used by AJAX.', 'quick-ajax-post-loader')
            );
            return $field_properties;
        }
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
                'label' => __('Number of columns:', 'quick-ajax-post-loader'),
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
            $post_item_templates = QAPL_Quick_Ajax_Helper::plugin_get_templates_items_array('post-items/post-item*.php', 'Post Item Name:', QAPL_Quick_Ajax_Helper::shortcode_page_layout_post_item_template_default_value());
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
                'default' => QAPL_Quick_Ajax_Helper::plugin_templates_post_item_template(),
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
            $field_properties = self::select_loader_icon_properties(QAPL_Quick_Ajax_Helper::shortcode_page_select_loader_icon(), QAPL_Quick_Ajax_Helper::plugin_templates_loader_icon_template());
            return $field_properties;
        }
        //select loader icon global
        public static function get_global_field_select_loader_icon(){
            $field_properties = self::select_loader_icon_properties(QAPL_Quick_Ajax_Helper::global_options_field_select_loader_icon(), QAPL_Quick_Ajax_Helper::plugin_templates_loader_icon_template());
            return $field_properties;
        }
        private static function select_loader_icon_properties($field_name, $field_default_value) {
            $loader_icon_options = array();
            $loader_icon_templates = QAPL_Quick_Ajax_Helper::plugin_get_templates_items_array('loader-icon/*.php', 'Loader Icon Name:', QAPL_Quick_Ajax_Helper::shortcode_page_select_loader_icon_default_value());
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
    }
}

QAPL_Quick_Ajax_Helper::initialize();
