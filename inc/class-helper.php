<?php 
if (!defined('ABSPATH')) {
    exit;
}

class WPG_Quick_Ajax_Helper{
    public static $admin_config_loaded = false;
    public static $ajax_class_loaded = false;
    public static function quick_ajax_get_plugin_version() {
        return '1.0.1';
    }    
    public static function quick_ajax_plugin_name(){
        return 'Quick Ajax Post Loader';
    }    
    public static function quick_ajax_text_domain(){
        return 'wpg-quick-ajax-post-loader';
    }    
    public static function quick_ajax_element_exists($type, $name) {
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
                echo '<div class="notice notice-error"><p><strong>'.self::quick_ajax_plugin_name().'</strong> is not working properly. Error: A ' . $type_formatted . ' named <strong>' . esc_html($name) . '</strong> already exists, which may have been declared by another plugin.</p></div>';
            });            
            return false;
        }    
        return true;
    }
    public static function quick_ajax_file_exists($file_path) {
        if (file_exists($file_path)) {
            return $file_path;
        }
        trigger_error($file_path." not exist.", E_USER_WARNING);
        return false;
    }    
    public static function quick_ajax_get_plugin_dir_path() {
        return plugin_dir_path( dirname( __FILE__ ) );
    }
    public static function quick_ajax_get_plugin_dir_url() {
        return plugin_dir_url( dirname( __FILE__ ) );
    }
    public static function quick_ajax_plugin_js_directory() {
        return self::quick_ajax_get_plugin_dir_url() . 'js/';
    }
    public static function quick_ajax_plugin_css_directory() {
        return self::quick_ajax_get_plugin_dir_url() . 'css/';
    }
    public static function quick_ajax_plugin_admin_pages_config() {
        $file_path = self::quick_ajax_get_plugin_dir_path() . 'admin/admin-pages-config.php';
        if(self::quick_ajax_file_exists($file_path)){
            self::$admin_config_loaded = true;
            return $file_path;
        }
        return false;
    }
    public static function quick_ajax_plugin_settings_page() {
        if(self::$admin_config_loaded == true){
            $file_path = self::quick_ajax_get_plugin_dir_path() . 'admin/pages/settings-page.php';
            return $file_path;
        }
        return false;
    }
    public static function quick_ajax_plugin_shortcode_page() {
        if(self::$admin_config_loaded == true){
            $file_path = self::quick_ajax_get_plugin_dir_path() . 'admin/pages/shortcode-page.php';
            return $file_path;
        }
        return false;
    }
    public static function quick_ajax_plugin_ajax_class() {
        $file_path = self::quick_ajax_get_plugin_dir_path() . 'inc/class-ajax.php';
        if(self::quick_ajax_file_exists($file_path)){
            self::$ajax_class_loaded = true;
            return $file_path;
        };
        return false;
    }
    public static function quick_ajax_plugin_shortcode_class() {
        if(self::$ajax_class_loaded == true){
            return self::quick_ajax_get_plugin_dir_path() . 'inc/class-shortcode.php';
        }
        return false;
    }
    public static function quick_ajax_plugin_ajax_actions() {
        if(self::$ajax_class_loaded == true){
            return self::quick_ajax_get_plugin_dir_path() . 'inc/actions.php';
        }
        return false;
    }
    public static function quick_ajax_plugin_functions() {
        if(self::$ajax_class_loaded == true){
            return self::quick_ajax_get_plugin_dir_path() . 'inc/functions.php';
        }
        return false;        
    }
    public static function quick_ajax_get_templates_dir_path($file) {
        // Path to the template in the child theme (or the theme itself if not using a child theme)
        $child_theme_template_path = get_stylesheet_directory() . '/wpg-quick-ajax-post-loader/templates' . $file;
        // Check if the template exists in the child theme
        if (file_exists($child_theme_template_path)) {
            return $child_theme_template_path;
        } 
        // Path to the template in the parent theme
        $theme_template_path = get_template_directory() . '/wpg-quick-ajax-post-loader/templates' . $file;
        // Check if the template exists in the parent theme
        if (file_exists($theme_template_path)) {
            return $theme_template_path;
        }
        // Path to the template in the plugin
        $plugin_template_path = self::quick_ajax_get_plugin_dir_path() . 'templates' . $file;        
        // Check if the template exists in the plugin
        if (file_exists($plugin_template_path)) {
            return $plugin_template_path;
        }
        // Template was not found
        return false;
    }

    public static function quick_ajax_plugin_templates_post_item_template($template_name = false){
        // get the specified template file path if it exist or back to the default template.
        $template_name = empty($template_name) ? WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_post_item_template_default_value() : $template_name;
        $file_path = self::quick_ajax_get_templates_dir_path('/post_items/'.$template_name.'.php');
        if (!file_exists($file_path)) {
            $file_path = self::quick_ajax_get_templates_dir_path('/post-items/post-item.php');
        }
        return $file_path;
    }
    public static function quick_ajax_plugin_templates_loader_icon_template($template_name = false){
        // get the specified template file path if it exist or back to the default template.
        $template_name = empty($template_name) ? WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_loader_icon_default_value() : $template_name;
        $file_path = self::quick_ajax_get_templates_dir_path('/loader-icon/'.$template_name.'.php');
        if (!file_exists($file_path)) {
            $file_path = self::quick_ajax_get_templates_dir_path('/loader-icon/loader-icon.php');
        }       
        return $file_path;
    }
    private static function get_template_name_from_file($file_path, $temple_name) {
        $handle = fopen($file_path, 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) { 
                if (stripos($line, $temple_name) !== false) { 
                    $line = str_replace(['/* ', $temple_name, '*/'], '', $line);
                    $line = trim($line);
                    fclose($handle);
                    return !empty($line) ? $line : basename($file_path, '.php');
                }
            }
            fclose($handle);
        }
        return basename($file_path, '.php');
    }
    public static function quick_ajax_plugin_get_templates_items_array($template_file_location, $template_name, $default_file = false) {        
        $plugin_template_files = glob(self::quick_ajax_get_plugin_dir_path() . 'templates/'.$template_file_location);  
        // Attempt to get templates from the parent theme directory
        $parent_template_files = glob(get_template_directory() . '/wpg-quick-ajax-post-loader/templates/'.$template_file_location);
        // Attempt to get templates from the child theme directory
        $child_template_files = [];
        if(get_template_directory() !== get_stylesheet_directory()){
            $child_template_files = glob(get_stylesheet_directory() . '/wpg-quick-ajax-post-loader/templates/'.$template_file_location);  
        }
        // Merge the arrays, filtering out duplicates if directories are different
        $template_files = array_merge($plugin_template_files, $parent_template_files, $child_template_files);

        $file_names = [];
        foreach ($template_files as $file) {
            $file_name = basename($file, '.php');
            $file_names[] = [
                'file_name' => $file_name,
                'template_name' => self::get_template_name_from_file($file, $template_name)
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
    public static function quick_ajax_plugin_templates_no_posts(){
        $file_path = self::quick_ajax_get_templates_dir_path('/post-items/no-posts.php');
        return $file_path;
    }
    public static function quick_ajax_term_filter_button_data_button(){
        return 'quick-ajax-filter-button';
    }
    public static function quick_ajax_plugin_templates_term_filter_button(){
        $file_path = self::quick_ajax_get_templates_dir_path('/term-filter/term-filter-button.php');
        return $file_path;
    }    
    public static function quick_ajax_load_more_button_data_button(){
        return 'quick-ajax-load-more';
    }
    public static function quick_ajax_plugin_templates_load_more_button(){
        $file_path = self::quick_ajax_get_templates_dir_path('/load-more-button.php');
        return $file_path;
    }
    public static function quick_ajax_menu_slug() {
        return 'quick-ajax-menu';
    }
    public static function quick_ajax_cpt_slug() {
        return 'quick-ajax-creator';
    }
    public static function quick_ajax_settings_page_slug() {
        return 'quick-ajax-settings';
    }

    /* quick-ajax-creator shortcode field names */
    public static function quick_ajax_settings_wrapper_id() {
        return 'quick_ajax_settings_wrapper';
    }
    public static function quick_ajax_shortcode_page_select_post_type() {
        return 'qa_select_post_type';
    }
    public static function quick_ajax_shortcode_page_select_post_type_default_value(){
        return 'post';
    }
    public static function quick_ajax_shortcode_page_show_taxonomy_filter(){
        return 'qa_show_select_taxonomy';
    }
    public static function quick_ajax_shortcode_page_show_taxonomy_filter_default_value(){
        return 0;
    }
    public static function quick_ajax_shortcode_page_select_taxonomy(){
        return 'qa_select_taxonomy';
    }
    public static function quick_ajax_shortcode_page_select_posts_per_page(){
        return 'qa_select_posts_per_page';
    }
    public static function quick_ajax_shortcode_page_select_posts_per_page_default_value() {
        return 6;
    }
    public static function quick_ajax_shortcode_page_select_order(){
        return 'qa_select_order';
    }
    public static function quick_ajax_shortcode_page_select_order_default_value() {
        return 'DESC';
    }
    public static function quick_ajax_shortcode_page_select_orderby(){
        return 'qa_select_orderby';
    }    
    public static function quick_ajax_shortcode_page_select_orderby_default_value() {
        return 'date';
    }
    public static function quick_ajax_shortcode_page_select_post_status(){
        return 'qa_select_post_status';
    }
    public static function quick_ajax_shortcode_page_select_post_status_default_value(){
        return 'publish';
    }
    public static function quick_ajax_shortcode_page_set_post_not_in(){
        return 'qa_select_post_not_in';
    }
    public static function quick_ajax_shortcode_page_layout_select_columns_qty(){
        return 'qa_layout_select_columns_qty';
    }
    public static function quick_ajax_shortcode_page_layout_select_columns_qty_default_value(){
        return 3;
    }
    public static function quick_ajax_shortcode_page_layout_taxonomy_filter_class(){
        return 'qa_layout_add_taxonomy_filter_class';
    }
    public static function quick_ajax_shortcode_page_layout_container_class(){
        return 'qa_layout_add_container_class';
    }
    public static function quick_ajax_shortcode_page_layout_quick_ajax_css_style(){
        return 'qa_layout_quick_ajax_css_style';
    }
    public static function quick_ajax_shortcode_page_layout_quick_ajax_css_style_default_value(){
        return 1;
    }
    public static function quick_ajax_shortcode_page_layout_post_item_template(){
        return 'qa_layout_quick_ajax_post_item_template';
    }
    public static function quick_ajax_shortcode_page_layout_post_item_template_default_value(){
        return 'post-item';
    }
    public static function quick_ajax_shortcode_page_show_custom_load_more_post_quantity(){
        return 'qa_show_custom_load_more_post_quantity';
    }
    public static function quick_ajax_shortcode_page_show_custom_load_more_post_quantity_default_value(){
        return 0;
    }
    public static function quick_ajax_shortcode_page_select_custom_load_more_post_quantity(){
        return 'qa_select_custom_load_more_post_quantity';
    }
    public static function quick_ajax_shortcode_page_select_custom_load_more_post_quantity_default_value(){
        return 4;
    }
    public static function quick_ajax_shortcode_page_override_global_loader_icon(){
        return 'qa_override_global_loader_icon';
    }
    public static function quick_ajax_shortcode_page_override_global_loader_icon_default_value(){
        return 0;
    }
    public static function quick_ajax_shortcode_page_select_loader_icon(){
        return 'qa_loader_icon';
    }
    public static function quick_ajax_shortcode_page_select_loader_icon_default_value(){
        return 'loader-icon';
    }
    // attributes query names
    public static function quick_ajax_layout_quick_ajax_id(){
        return 'quick_ajax_id';
    }
    public static function quick_ajax_layout_quick_ajax_css_style(){
        return 'quick_ajax_css_style';
    }
    public static function quick_ajax_layout_grid_num_columns(){
        return 'grid_num_columns';
    }
    public static function quick_ajax_layout_post_item_template(){
        return 'post_item_template';
    }
    public static function quick_ajax_layout_taxonomy_filter_class(){
        return 'taxonomy_filter_class';
    }
    public static function quick_ajax_layout_container_class(){
        return 'container_class';
    }
    public static function quick_ajax_layout_load_more_posts(){
        return 'load_more_posts';
    }
    public static function quick_ajax_layout_select_loader_icon(){
        return 'loader_icon';
    }
    /* Quick AJAX Settings */
    public static function quick_ajax_admin_page_settings_field_option_group(){
        return 'quick-ajax-settings-group';
    }
    public static function quick_ajax_admin_page_global_options_name(){
        return 'quick-ajax-global-options';
    }
    /* Quick AJAX Global Options */
    public static function quick_ajax_global_options_field_select_loader_icon(){
        return self::quick_ajax_admin_page_global_options_name().'[loader_icon]';
    }
    

    public static function quick_ajax_initialize(){
        $initialize_list = [
            self::quick_ajax_plugin_admin_pages_config(),
            self::quick_ajax_plugin_settings_page(),
            self::quick_ajax_plugin_shortcode_page(),
            self::quick_ajax_plugin_ajax_class(),
            self::quick_ajax_plugin_ajax_actions(),
            self::quick_ajax_plugin_functions(),
            self::quick_ajax_plugin_shortcode_class()
        ];
        foreach ($initialize_list as $initialize) {
            if (self::quick_ajax_file_exists($initialize)) {
                require_once($initialize);
            }
        }
    }
}


if (WPG_Quick_Ajax_Helper::quick_ajax_element_exists('class','WPG_Quick_Ajax_Fields')) {
    class WPG_Quick_Ajax_Fields{
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
                'name' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_post_type(),
                'label' => __('Select Post Type:', 'wpg-quick-ajax-post-loader'),
                'type' => 'select',
                'options' => $post_type_options,
                'description' => __('Choose the post type you want to display using AJAX.', 'wpg-quick-ajax-post-loader')
            );
            return $field_properties;
        }
        //show taxonomy checkbox
        public static function get_field_show_taxonomy_filter(){
            return array(
                'name' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_show_taxonomy_filter(),
                'label' => __('Show Taxonomy Filter', 'wpg-quick-ajax-post-loader'),
                'type' => 'checkbox',
                'default' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_show_taxonomy_filter_default_value(),
                'description' => __('Enable filtering by taxonomy/category.', 'wpg-quick-ajax-post-loader')
            );
        }
        //select taxonomy
        public static function get_field_select_taxonomy(){
            return array(
                'name' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_taxonomy(),
                'label' => __('Select Taxonomy:', 'wpg-quick-ajax-post-loader'),
                'type' => 'select',
                'options' => '',
                'default' => '',
                'description' => __('Select the taxonomy to be used for filtering posts.', 'wpg-quick-ajax-post-loader')
            );
        }
        //post per page number
        public static function get_field_select_posts_per_page(){
            return array(
                'name' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_posts_per_page(),
                'label' => __('Posts Per Page:', 'wpg-quick-ajax-post-loader'),
                'type' => 'number',
                'options' => '',
                'default' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_posts_per_page_default_value(),
                'description' => __('Determine the number of posts to be loaded per AJAX request.', 'wpg-quick-ajax-post-loader')
            );
        }
        //select post order
        public static function get_field_select_order(){
            $order_options = array(
                array(
                    'label' => __('Descending - order from highest to lowest', 'wpg-quick-ajax-post-loader'),
                    'value' => 'DESC'
                ),
                array(
                    'label' => __('Ascending - order from lowest to highest', 'wpg-quick-ajax-post-loader'),
                    'value' => 'ASC'
                )
            );
            $field_properties = array(
                'name' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_order(),
                'label' => __('Posts Order:', 'wpg-quick-ajax-post-loader'),
                'type' => 'select',
                'options' => $order_options,
                'default' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_order_default_value(),
                'description' => __('Specify the order of posts.', 'wpg-quick-ajax-post-loader')
            );
            return $field_properties;
        }
        //select post orderby
        public static function get_field_select_orderby(){
            $orderby_options = array(
                array(
                    'label' => __('None: No specific sorting criteria', 'wpg-quick-ajax-post-loader'),
                    'value' => 'none'
                ),
                array(
                    'label' => __('ID: Sort by post ID', 'wpg-quick-ajax-post-loader'),
                    'value' => 'ID'
                ),
                array(
                    'label' => __('Author: Sort by author ID', 'wpg-quick-ajax-post-loader'),
                    'value' => 'author'
                ),
                array(
                    'label' => __('Title: Sort by post title', 'wpg-quick-ajax-post-loader'),
                    'value' => 'title'
                ),
                array(
                    'label' => __('Name: Sort by post slug', 'wpg-quick-ajax-post-loader'),
                    'value' => 'name'
                ),
                array(
                    'label' => __('Date: Sort by publication date', 'wpg-quick-ajax-post-loader'),
                    'value' => 'date'
                ),
                array(
                    'label' => __('Modified: Sort by last modified date', 'wpg-quick-ajax-post-loader'),
                    'value' => 'modified'
                ),
                array(
                    'label' => __('Parent: Sort by parent post ID', 'wpg-quick-ajax-post-loader'),
                    'value' => 'parent'
                ),
                array(
                    'label' => __('Random: Random order', 'wpg-quick-ajax-post-loader'),
                    'value' => 'rand'
                ),
                array(
                    'label' => __('Comments: Sort by comment count', 'wpg-quick-ajax-post-loader'),
                    'value' => 'comment_count'
                ),
                array(
                    'label' => __('Menu Order: Sort by custom menu order', 'wpg-quick-ajax-post-loader'),
                    'value' => 'menu_order'
                )
            );
            $field_properties = array(
                'name' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_orderby(),
                'label' => __('Posts Order by:', 'wpg-quick-ajax-post-loader'),
                'type' => 'select',
                'options' => $orderby_options,
                'default' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_orderby_default_value(),
                'description' => __('Choose the sorting criteria for posts.', 'wpg-quick-ajax-post-loader')
            );
            return $field_properties;
        }
        //select post status
        public static function get_field_select_post_status(){
            $post_status_options = array(
                array(
                    'label' => __('Publish: Published posts', 'wpg-quick-ajax-post-loader'),
                    'value' => 'publish'
                ),
                array(
                    'label' => __('Draft: Draft posts', 'wpg-quick-ajax-post-loader'),
                    'value' => 'draft'
                ),
                array(
                    'label' => __('Pending: Pending review posts', 'wpg-quick-ajax-post-loader'),
                    'value' => 'pending'
                ),
                array(
                    'label' => __('Private: Private posts', 'wpg-quick-ajax-post-loader'),
                    'value' => 'private'
                ),
                array(
                    'label' => __('Trash: Trashed posts', 'wpg-quick-ajax-post-loader'),
                    'value' => 'trash'
                ),
                array(
                    'label' => __('Auto-Draft: Auto-draft posts', 'wpg-quick-ajax-post-loader'),
                    'value' => 'auto-draft'
                ),
                array(
                    'label' => __('Inherit: Inherited posts', 'wpg-quick-ajax-post-loader'),
                    'value' => 'inherit'
                ),
            );
            $field_properties = array(
                'name' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_post_status(),
                'label' => __('Post Status:', 'wpg-quick-ajax-post-loader'),
                'type' => 'select',
                'options' => $post_status_options,
                'default' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_post_status_default_value(),
                'description' => __('Select the post status to be used by AJAX.', 'wpg-quick-ajax-post-loader')
            );
            return $field_properties;
        }
        //add Excluded Post IDs
        public static function get_field_set_post_not_in(){
            $field_properties = array(
                'name' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_set_post_not_in(),
                'label' => __('Excluded Post IDs', 'wpg-quick-ajax-post-loader'),
                'type' => 'text',
                'options' => '',
                'default' => '',
                'placeholder' => '3, 66, 999',            
                'description' => __('Enter a list of post IDs to exclude from the query.', 'wpg-quick-ajax-post-loader'),
            );
            return $field_properties;
        }
        //apply quick ajax css style
        public static function get_field_layout_quick_ajax_css_style(){
            $field_properties = array(
                'name' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_quick_ajax_css_style(),
                'label' => __('Apply Quick AJAX CSS Style', 'wpg-quick-ajax-post-loader'),
                'type' => 'checkbox',
                'options' => '',
                'default' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_quick_ajax_css_style_default_value(),
                'description' => __('Apply Quick AJAX CSS styles and column layout.', 'wpg-quick-ajax-post-loader')
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
                'name' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_select_columns_qty(),
                'label' => __('Number of columns:', 'wpg-quick-ajax-post-loader'),
                'type' => 'select',
                'options' => $columns_qty_options,
                'default' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_select_columns_qty_default_value(),
                'description' => __('Specify the quantity of columns.', 'wpg-quick-ajax-post-loader')
            );
            return $field_properties;
        }
        //select post item template
        public static function get_field_layout_post_item_template(){
            $post_item_template_options = array();
            $post_item_templates = WPG_Quick_Ajax_Helper::quick_ajax_plugin_get_templates_items_array('post-items/post-item*.php', 'Post Item Name:', WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_post_item_template_default_value());
            foreach($post_item_templates as $item){
                $post_item_template_options[] = array(
                    'label' => $item['template_name'],
                    'value' => $item['file_name']
                );
            }
            $field_properties = array(
                'name' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_post_item_template(),
                'label' => __('Select Post Item Template', 'wpg-quick-ajax-post-loader'),
                'type' => 'select',
                'options' => $post_item_template_options,
                'default' => WPG_Quick_Ajax_Helper::quick_ajax_plugin_templates_post_item_template(),
                'description' => __('Choose a template for displaying post items.', 'wpg-quick-ajax-post-loader')
            );
            return $field_properties;
        }
        //add custom class for taxonomy filter
        public static function get_field_layout_taxonomy_filter_class(){
            $field_properties = array(
                'name' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_taxonomy_filter_class(),
                'label' => __('Add class to taxonomy filter', 'wpg-quick-ajax-post-loader'),
                'type' => 'text',
                'options' => '',
                'default' => '',
                'placeholder' => __('class-name, another-class-name', 'wpg-quick-ajax-post-loader'),
                'description' => __('Add classes to the filter: class-one, class-two, class-three', 'wpg-quick-ajax-post-loader')
            );
            return $field_properties;
        }
        //add custom class for container
        public static function get_field_layout_container_class(){
            $field_properties = array(
                'name' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_container_class(),
                'label' => __('Add class to post container', 'wpg-quick-ajax-post-loader'),
                'type' => 'text',
                'options' => '',
                'default' => '',
                'placeholder' => __('class-name, another-class-name', 'wpg-quick-ajax-post-loader'),
                'description' => __('Add classes to the post container: class-one, class-two, class-three', 'wpg-quick-ajax-post-loader')
            );
            return $field_properties;
        }
        //show custom load more post quantity
        public static function get_field_show_custom_load_more_post_quantity(){
            $field_properties = array(
                'name' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_show_custom_load_more_post_quantity(),
                'label' => __('Custom Load More Post Quantity', 'wpg-quick-ajax-post-loader'),
                'type' => 'checkbox',
                'default' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_show_custom_load_more_post_quantity_default_value(),
                'description' => __('Load a different number of posts than the default page display.', 'wpg-quick-ajax-post-loader')
            );
            return $field_properties;
        }
        //select custom load more post quantity
        public static function get_field_select_custom_load_more_post_quantity(){
            $field_properties = array(
                'name' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_custom_load_more_post_quantity(),
                'label' => __('Custom Load More Post Quantity', 'wpg-quick-ajax-post-loader'),
                'type' => 'number',
                'default' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_custom_load_more_post_quantity_default_value(),
                'description' => __('Select the custom number of posts to load when using the "Load More" button.', 'wpg-quick-ajax-post-loader')
            );
            return $field_properties;
        }
        //override global loader icon
        public static function get_field_override_global_loader_icon(){
            $field_properties = array(
                'name' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_override_global_loader_icon(),
                'label' => __('Override Global Loader Icon', 'wpg-quick-ajax-post-loader'),
                'type' => 'checkbox',
                'default' => WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_override_global_loader_icon_default_value(),
                'description' => __('Set a different loader icon than the one specified in global options.', 'wpg-quick-ajax-post-loader')
            );
            return $field_properties;
        }
        //select loader icon
        public static function get_field_select_loader_icon(){
            $field_properties = self::select_loader_icon_properties(WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_loader_icon(), WPG_Quick_Ajax_Helper::quick_ajax_plugin_templates_loader_icon_template());
            return $field_properties;
        }
        //select loader icon global
        public static function get_global_field_select_loader_icon(){
            $field_properties = self::select_loader_icon_properties(WPG_Quick_Ajax_Helper::quick_ajax_global_options_field_select_loader_icon(), WPG_Quick_Ajax_Helper::quick_ajax_plugin_templates_loader_icon_template());
            
            return $field_properties;
        }
        private static function select_loader_icon_properties($field_name, $field_default_value) {
            $loader_icon_options = array();
            $loader_icon_templates = WPG_Quick_Ajax_Helper::quick_ajax_plugin_get_templates_items_array('loader-icon/*.php', 'Loader Icon Name:', WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_loader_icon_default_value());
            foreach($loader_icon_templates as $item){
                $loader_icon_options[] = array(
                    'label' => $item['template_name'],
                    'value' => $item['file_name']
                );
            }
            $field_properties = array(
                'name' => $field_name,
                'label' => __('Select Loader Icon', 'wpg-quick-ajax-post-loader'),
                'type' => 'select',
                'options' => $loader_icon_options,
                'default' => $field_default_value,
                'description' => __('Choose an icon to display as the loading indicator when the "Load More" button is clicked.', 'wpg-quick-ajax-post-loader')
            );
            return $field_properties;
        }
    }
}

WPG_Quick_Ajax_Helper::quick_ajax_initialize();
?>