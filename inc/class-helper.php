<?php 
if (!defined('ABSPATH')) {
    exit;
}
use QAPL_Quick_Ajax_Plugin_Constants as QAPLC;

class QAPL_Quick_Ajax_Plugin_Starter{
    private $initializer;
    private $enqueue_handler;

    public function __construct(QAPL_Quick_Ajax_Plugin_Bootstrap_Interface $initializer, QAPL_Quick_Ajax_Enqueue_Handler_Interface $enqueue_handler) {
        $this->initializer = $initializer;
        $this->enqueue_handler = $enqueue_handler;
    }

    public function run(): void {        
        $this->initializer->initialize_plugin_components();
        $this->initializer->initialize_plugin_pages();
        $this->enqueue_handler->register_hooks();
    }
}

interface QAPL_Quick_Ajax_Plugin_Bootstrap_Interface {
    public function initialize_plugin_pages();
    public function initialize_plugin_components();
}
class QAPL_Quick_Ajax_Plugin_Bootstrap implements QAPL_Quick_Ajax_Plugin_Bootstrap_Interface {
    //private $file_helper;
    private $pages_helper;
    public function __construct(QAPL_Quick_Ajax_Resource_Manager_Interface $pages_helper) {
        //$this->file_helper = $file_helper;
        $this->pages_helper = $pages_helper;
    }
    public function initialize_plugin_pages(): void {
        // initialize file and page helpers or any other required helpers
        $this->pages_helper->initialize_pages();
    }
    public function initialize_plugin_components(): void {
        // initialize other plugin components like AJAX handlers, shortcode classes, etc.
        $this->pages_helper->initialize_components();
    }
}

interface QAPL_Quick_Ajax_Enqueue_Handler_Interface {
    public function enqueue_frontend_styles_and_scripts();
    public function enqueue_admin_styles_and_scripts();
    public function register_hooks();
}
class QAPL_Quick_Ajax_Enqueue_Handler implements QAPL_Quick_Ajax_Enqueue_Handler_Interface {
    private $file_helper;

    public function __construct(QAPL_Quick_Ajax_File_Manager_Interface $file_helper) {
        $this->file_helper = $file_helper;
    }
    public function register_hooks(): void {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_styles_and_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_styles_and_scripts']);
    }
    public function enqueue_frontend_styles_and_scripts() {
        if (!is_admin()) {           
            $style_suffix = $this->get_file_suffix('/css/', 'style.css');
            $script_suffix = $this->get_file_suffix('/js/', 'script.js');
            $version = $this->get_version();
            wp_enqueue_style('qapl-quick-ajax-style', $this->file_helper->get_plugin_css_directory() . 'style' . $style_suffix . '.css', [], $version);
            wp_enqueue_script('qapl-quick-ajax-script', $this->file_helper->get_plugin_js_directory() . 'script' . $script_suffix . '.js', ['jquery'], $version, true);
            wp_localize_script('qapl-quick-ajax-script', 'qapl_quick_ajax_helper', $this->get_localized_data());
        }
    }
    public function enqueue_admin_styles_and_scripts() {
        if (is_admin()) {
            // Check if the current page matches the plugin-related pages
            $plugin_pages = [QAPLC::CPT_SHORTCODE_SLUG, QAPLC::SETTINGS_PAGE_SLUG];
            if (qapl_quick_ajax_check_page_type($plugin_pages)) {
                $style_suffix = $this->get_file_suffix('/css/', 'admin-style.css');
                $script_suffix = $this->get_file_suffix('/js/', 'admin-script.js');
                $version = $this->get_version();
                wp_enqueue_style('qapl-quick-ajax-admin-style', $this->file_helper->get_plugin_css_directory() . 'admin-style' . $style_suffix . '.css', [], $version);
                wp_register_script('qapl-quick-ajax-admin-script', $this->file_helper->get_plugin_js_directory() . 'admin-script' . $script_suffix . '.js', ['jquery'], $version, true);
                wp_localize_script('qapl-quick-ajax-admin-script', 'qapl_quick_ajax_helper', $this->get_admin_localized_data());
                wp_enqueue_script('qapl-quick-ajax-admin-script');
            }
        }
    }
    private function is_dev_mode() {
        // return true if QAPL_DEV_MODE is defined and enabled
        if((defined('QAPL_DEV_MODE') && QAPL_DEV_MODE)) {
            return true;
        }
        return false;
    }
    private function get_version(): string {
        return $this->is_dev_mode() ? (string) time() : QAPLC::PLUGIN_VERSION;
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
        $nonce = wp_create_nonce(QAPLC::NONCE_FORM_QUICK_AJAX_ACTION);
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
                'block_id' => QAPLC::ATTRIBUTE_QUICK_AJAX_ID,
                'filter_data_button' => QAPLC::TERM_FILTER_BUTTON_DATA_BUTTON,
                'sort_button' => QAPLC::SORT_OPTION_BUTTON_DATA_BUTTON,
                'load_more_data_button' => QAPLC::LOAD_MORE_BUTTON_DATA_BUTTON,
            ]
        ];
    }
    private function get_admin_localized_data() {
        return [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce(QAPLC::NONCE_FORM_QUICK_AJAX_ACTION),
            'quick_ajax_settings_wrapper' => QAPLC::SETTINGS_WRAPPER_ID,
            'quick_ajax_post_type' => QAPLC::QUERY_SETTING_SELECT_POST_TYPE,
            'quick_ajax_taxonomy' => QAPLC::QUERY_SETTING_SELECT_TAXONOMY,
            'quick_ajax_manual_selected_terms' => QAPLC::QUERY_SETTING_SELECTED_TERMS,
            'quick_ajax_css_style' => QAPLC::ATTRIBUTE_QUICK_AJAX_CSS_STYLE,
            'grid_num_columns' => QAPLC::ATTRIBUTE_GRID_NUM_COLUMNS,
            'post_item_template' => QAPLC::ATTRIBUTE_POST_ITEM_TEMPLATE,
            'post_item_template_default' => QAPLC::LAYOUT_SETTING_POST_ITEM_TEMPLATE_DEFAULT,
            'taxonomy_filter_class' => QAPLC::ATTRIBUTE_TAXONOMY_FILTER_CLASS,
            'container_class' => QAPLC::ATTRIBUTE_CONTAINER_CLASS,
            'load_more_posts' => QAPLC::ATTRIBUTE_LOAD_MORE_POSTS,
            'loader_icon' => QAPLC::ATTRIBUTE_LOADER_ICON,
            'loader_icon_default' => QAPLC::LAYOUT_SETTING_SELECT_LOADER_ICON_DEFAULT,
            'ajax_initial_load' => QAPLC::AJAX_SETTING_AJAX_INITIAL_LOAD,
            'infinite_scroll' => QAPLC::ATTRIBUTE_AJAX_INFINITE_SCROLL,
            'show_end_message' => QAPLC::ATTRIBUTE_SHOW_END_MESSAGE,
            'quick_ajax_id' => QAPLC::ATTRIBUTE_QUICK_AJAX_ID,
        ];
    }
}

interface QAPL_Quick_Ajax_Resource_Manager_Interface {
    public function initialize_components();
    public function initialize_pages();
}
class QAPL_Quick_Ajax_Resource_Manager implements QAPL_Quick_Ajax_Resource_Manager_Interface {

    private QAPL_Quick_Ajax_File_Manager_Interface $file_helper;

    // constants for page files
    private const PAGE_FILES = [
        'admin_pages_config' => 'admin/admin-pages-config.php',
        'settings_page'      => 'admin/pages/settings-page.php',
        'shortcode_page'     => 'admin/pages/shortcode-page.php',
    ];

    // constants for component files
    private const COMPONENT_FILES = [
        'ajax_class'                => 'inc/class-ajax.php',
        'shortcode_class'           => 'inc/class-shortcode.php',
        'ajax_actions'              => 'inc/actions.php',
        'functions'                 => 'inc/functions.php',
        'updater'                   => 'inc/class-updater.php',
        'template_hooks'            => 'inc/class-template-hooks.php',
        'deprecated_hooks_handler'  => 'inc/class-deprecated-hooks-handler.php',
        'dev-hooks'                 => 'dev-tools/dev-hooks.php',
    ];

    //construct expects instance of QAPL_File_Helper
    public function __construct(QAPL_Quick_Ajax_File_Manager_Interface $file_helper) {
        $this->file_helper = $file_helper;
    }
    public function initialize_pages(): void{
        $this->load_files(self::PAGE_FILES);
    }

    public function initialize_components(): void{
        $this->load_files(self::COMPONENT_FILES);
    }

    private function load_files(array $files): void{
        foreach ($files as $file) {
            // start: refactoring class
            if (class_exists('QAPL_Refactoring_Helper')) {
                $resolver = new QAPL_Refactoring_Helper($this->file_helper);
                $new_file = $resolver->get_new_file($file);
                if($new_file){
                    $file = $new_file;
                }
            }
            // end: refactoring class
            $path = $this->file_helper->file_exists($file);
            if ($path !== false) {
                require_once $path;
            }
        }
    }
}

interface QAPL_Quick_Ajax_File_Manager_Interface {
    public function file_exists($file_path);
    public function get_plugin_directory();
    public function get_plugin_js_directory();
    public function get_plugin_css_directory();
    public function get_templates_dir_path($file);
}
class QAPL_Quick_Ajax_File_Manager implements QAPL_Quick_Ajax_File_Manager_Interface {
    private $plugin_dir_path;
    private $plugin_dir_url;

    public function __construct() {
        $this->plugin_dir_path = plugin_dir_path(__DIR__);
        $this->plugin_dir_url = plugin_dir_url(__DIR__);
    }
    public function file_exists($file_path) {
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

    public function get_templates_items_array($template_file_location, $template_name, $default_file = false) {
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
    //template post item
     public function get_post_item_template(string $template_name = ''): string {
        $default_name = QAPLC::LAYOUT_SETTING_POST_ITEM_TEMPLATE_DEFAULT;
        return $this->get_templates_file_path($template_name, $default_name, '/post-items/');
    }
    //template loader icon
    public function get_loader_icon_template(string $template_name = ''): string {
        $default_name = QAPLC::LAYOUT_SETTING_SELECT_LOADER_ICON_DEFAULT;
        return $this->get_templates_file_path($template_name, $default_name, '/loader-icon/');
    }
    //template for no posts found message
    public function get_no_posts_template(): string {
        return $this->get_templates_dir_path('/post-items/no-posts.php');
    }
    //template for end of posts message
    public function get_end_posts_template(): string {
        return $this->get_templates_dir_path('/post-items/end-posts.php');
    }
    //template for taxonomy filter button
    public function get_taxonomy_filter_button_template(): string {
        return $this->get_templates_dir_path('/taxonomy-filter/taxonomy-filter-button.php');
    }
    //template for load more button
    public function get_load_more_button_template(): string {
        return $this->get_templates_dir_path('/load-more-button.php');
    }
}
class QAPL_Quick_Ajax_Utilities {
    public static function element_exists(string $type, string $name) {
        $exists = false;
        $type_formatted = '';
        $plugin_name = QAPLC::PLUGIN_NAME;
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
    public static function add_or_update_option_autoload(string $option_name, $default_value = '', string $autoload = 'auto'): void {
        global $wpdb;
        
        // Check if the option exists
        $existing_option = get_option($option_name, false);
    
        if ($existing_option !== false) {
            // Update autoload value if the option exists            
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
}
class QAPL_Quick_Ajax_Shortcode_Generator {

    public static function generate_shortcode(int $post_id): string {
        // guard clause to check if $post_id is valid
        if ($post_id <= 0) {
            return '';
        }
        // get the title of the post
        $post_title = get_the_title($post_id);
        // initialise variables
        $excluded_post_ids = '';
        // get serialized meta data from the post
        $serialized_data = get_post_meta($post_id, QAPLC::DB_POSTMETA_SHORTCODE_SETTINGS, true);
        // check if serialized data exists and process it
        if ($serialized_data) {
            $form_data = maybe_unserialize($serialized_data);
            // ensure that the unserialized data is a valid array
            if (is_array($form_data)) {
                $excluded_key = QAPLC::QUERY_SETTING_SET_POST_NOT_IN;
                if (isset($form_data[$excluded_key]) && !empty($form_data[$excluded_key])) {
                    $excluded_post_ids = ' excluded_post_ids="' . esc_attr($form_data[$excluded_key]) . '"';
                }
            }
        }
        // generate the shortcode with post ID and title, include excluded post ids if available
        $shortcode = '[qapl-quick-ajax id="' . $post_id . '" title="' . esc_attr($post_title) . '"' . $excluded_post_ids . ']';
        return $shortcode;
    }
}
///////////////////////
// Fields


//interface for form field data
//it defines the methods that must exist in any field class
interface QAPL_Quick_Ajax_Form_Field_Interface {
    public function get_name(): string;
    public function get_label(): string;
    public function get_type(): string;
    public function get_options(): array;
    public function get_default();
    public function get_description(): string;
    public function get_placeholder(): string;
    public function get_field(): array;
}

//this class holds all field data,
//it is the final field object
class QAPL_Quick_Ajax_Form_Field implements QAPL_Quick_Ajax_Form_Field_Interface {
    private string $name;
    private string $label;
    private string $type;
    private array $options;
    private $default;
    private string $description;
    private string $placeholder;

    //this constructor sets all properties for the field
    public function __construct(string $name, string $label, string $type, array $options, $default, string $description, string $placeholder) {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
        $this->options = $options;
        $this->default = $default;
        $this->description = $description;
        $this->placeholder = $placeholder;
    }
    //simple get methods to return each property
    public function get_name(): string {
        return $this->name;
    }
    public function get_label(): string {
        return $this->label;
    }
    public function get_type(): string {
        return $this->type;
    }
    public function get_options(): array {
        return $this->options;
    }
    public function get_default() {
        return $this->default;
    }
    public function get_description(): string {
        return $this->description;
    }
    public function get_placeholder(): string {
        return $this->placeholder;
    }
    //returns all field data in array format
    public function get_field(): array {
        return [
            'name' => $this->name,
            'label' => $this->label,
            'type' => $this->type,
            'options' => $this->options,
            'default' => $this->default,
            'description' => $this->description,
            'placeholder' => $this->placeholder,
        ];
    }
}

//this class helps build the field object step by step
//it is useful if you don't want to pass all parameters in constructor
class QAPL_Quick_Ajax_Form_Field_Builder {
    private string $name = '';
    private string $label = '';
    private string $type = 'text';
    private array $options = [];
    private $default = '';
    private string $description = '';
    private string $placeholder = '';

    public function set_name(string $name): self {
        $this->name = $name;
        return $this;
    }
    public function set_label(string $label): self {
        $this->label = $label;
        return $this;
    }
    public function set_type(string $type): self {
        $this->type = $type;
        return $this;
    }
    public function set_options(array $options): self {
        $this->options = $options;
        return $this;
    }
    public function set_default($default): self {
        $this->default = $default;
        return $this;
    }
    public function set_description(string $description): self {
        $this->description = $description;
        return $this;
    }
    public function set_placeholder(string $placeholder): self {
        $this->placeholder = $placeholder;
        return $this;
    }

    //finally this method creates the final field object
    //using all the properties you set before
    public function build(): QAPL_Quick_Ajax_Form_Field_Interface {
        return new QAPL_Quick_Ajax_Form_Field(
            $this->name,
            $this->label,
            $this->type,
            $this->options,
            $this->default,
            $this->description,
            $this->placeholder
        );
    }
}

class QAPL_Quick_Ajax_Form_Field_Factory {
    //select post type  
    public static function build_select_post_type_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $post_types = get_post_types(['public' => true], 'objects');
        $options = [];
        foreach ($post_types as $post_type) {
            if (isset($post_type->labels->name) && $post_type->labels->name !== 'Media') {
                $options[] = [
                    'label' => $post_type->labels->name,
                    'value' => $post_type->name
                ];
            }
        }
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_SELECT_POST_TYPE);
        $builder->set_label(__('Select Post Type', 'quick-ajax-post-loader'));
        $builder->set_type('select');
        $builder->set_options($options);
        $builder->set_default(QAPLC::QUERY_SETTING_SELECT_POST_TYPE_DEFAULT);
        $builder->set_description(__('Choose the post type you want to display using AJAX.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //show taxonomy checkbox
    public static function build_show_taxonomy_filter_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_SHOW_TAXONOMY_FILTER);
        $builder->set_label(__('Show Taxonomy Filter', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPLC::QUERY_SETTING_SHOW_TAXONOMY_FILTER_DEFAULT);
        $builder->set_description(__('Enable filtering by taxonomy/category.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //select taxonomy   
    public static function build_select_taxonomy_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $options = [];
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_SELECT_TAXONOMY);
        $builder->set_label(__('Select Taxonomy', 'quick-ajax-post-loader'));
        $builder->set_type('select');
        $builder->set_options($options);
        $builder->set_default('');
        $builder->set_description(__('Select the taxonomy to be used for filtering posts.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //manual term selection checkbox
    public static function build_manual_term_selection_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_MANUAL_TERM_SELECTION);
        $builder->set_label(__('Select Specific Terms', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPLC::QUERY_SETTING_MANUAL_TERM_SELECTION_DEFAULT);
        $builder->set_description(__('Enable manual selection of taxonomy terms to be used for filtering.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //manual selected terms multiselect
    public static function build_manual_selected_terms_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_SELECTED_TERMS);
        $builder->set_label(__('Choose Terms', 'quick-ajax-post-loader'));
        $builder->set_type('multiselect');
        $builder->set_options([]);
        $builder->set_default([]);
        $builder->set_description(__('Select the specific terms to be used for filtering posts. If left empty, no results will be shown.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //post per page number
    public static function build_posts_per_page_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_SELECT_POSTS_PER_PAGE);
        $builder->set_label(__('Posts Per Page', 'quick-ajax-post-loader'));
        $builder->set_type('number');
        $builder->set_default(QAPLC::QUERY_SETTING_SELECT_POSTS_PER_PAGE_DEFAULT);
        $builder->set_description(__('Determine the number of posts to be loaded per AJAX request.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //select post order
    public static function build_select_order_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $order_options = [
            [
                'label' => __('Descending - order from highest to lowest', 'quick-ajax-post-loader'),
                'value' => 'DESC'
            ],
            [
                'label' => __('Ascending - order from lowest to highest', 'quick-ajax-post-loader'),
                'value' => 'ASC'
            ],
        ];
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_SELECT_ORDER);
        $builder->set_label(__('Default Sort Order', 'quick-ajax-post-loader'));
        $builder->set_type('select');
        $builder->set_options($order_options);
        $builder->set_default(QAPLC::QUERY_SETTING_SELECT_ORDER_DEFAULT);
        $builder->set_description(__('Specify the order of posts.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //select post orderby
    public static function build_select_orderby_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $orderby_options = [
            [
                'label' => __('Date: Sort by publication date', 'quick-ajax-post-loader'),
                'value' => 'date'
            ],
            [
                'label' => __('Title: Sort by post title', 'quick-ajax-post-loader'),
                'value' => 'title'
            ],
            [
                'label' => __('Comments: Sort by comment count', 'quick-ajax-post-loader'),
                'value' => 'comment_count'
            ],
            [
                'label' => __('Random: Random order', 'quick-ajax-post-loader'),
                'value' => 'rand'
            ],
        ];
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_SELECT_ORDERBY);
        $builder->set_label(__('Default Sort By', 'quick-ajax-post-loader'));
        $builder->set_type('select');
        $builder->set_options($orderby_options);
        $builder->set_default(QAPLC::QUERY_SETTING_SELECT_ORDERBY_DEFAULT);
        $builder->set_description(__('Choose the sorting criteria for posts.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //show sort button
    public static function build_show_sort_button_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_SHOW_SORT_BUTTON);
        $builder->set_label(__('Show Sorting Button', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPLC::QUERY_SETTING_SHOW_SORT_BUTTON_DEFAULT);
        $builder->set_description(__('Enable a button that allows users to switch between ascending and descending order.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //select sort button
    public static function build_select_sort_button_options_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $global_sort_labels = get_option(QAPL_Quick_Ajax_Plugin_Constants::GLOBAL_OPTIONS_NAME, []);
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
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_SELECT_SORT_BUTTON_OPTIONS);
        $builder->set_label(__('Available Sorting Options', 'quick-ajax-post-loader'));
        $builder->set_type('multiselect');
        $builder->set_options($sort_options);
        $builder->set_default(QAPLC::QUERY_SETTING_SELECT_SORT_BUTTON_OPTIONS_DEFAULT);
        $builder->set_description(__('Select which sorting options will be available to users.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //Inline Filter & Sorting
    public static function build_show_inline_filter_sorting_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_SHOW_INLINE_FILTER_SORTING);
        $builder->set_label(__('Inline Filter & Sorting', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPLC::QUERY_SETTING_SHOW_INLINE_FILTER_SORTING_DEFAULT);
        $builder->set_description(__('Display taxonomy filter and sorting options in a single row to save space and improve layout.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    /*
    public static function create_post_status_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $post_status_options = [
            [
                'label' => __('Publish: Published posts', 'quick-ajax-post-loader'),
                'value' => 'publish'
            ],
            [
                'label' => __('Draft: Draft posts', 'quick-ajax-post-loader'),
                'value' => 'draft'
            ],
            [
                'label' => __('Pending: Pending review posts', 'quick-ajax-post-loader'),
                'value' => 'pending'
            ],
            [
                'label' => __('Private: Private posts', 'quick-ajax-post-loader'),
                'value' => 'private'
            ],
            [
                'label' => __('Trash: Trashed posts', 'quick-ajax-post-loader'),
                'value' => 'trash'
            ],
            [
                'label' => __('Auto-Draft: Auto-draft posts', 'quick-ajax-post-loader'),
                'value' => 'auto-draft'
            ],
            [
                'label' => __('Inherit: Inherited posts', 'quick-ajax-post-loader'),
                'value' => 'inherit'
            ],
        ];
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_SELECT_POST_STATUS);
        $builder->set_label(__('Post Status', 'quick-ajax-post-loader'));
        $builder->set_type('select');
        $builder->set_options($post_status_options);
        $builder->set_default(QAPLC::QUERY_SETTING_SELECT_POST_STATUS_DEFAULT);
        $builder->set_description(__('Select the post status to be used by AJAX.', 'quick-ajax-post-loader'));
        return $builder->build();
    }*/
    //add Excluded Post IDs
    public static function build_excluded_post_ids_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_SET_POST_NOT_IN);
        $builder->set_label(__('Excluded Post IDs', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default('');
        $builder->set_placeholder('3, 66, 999');
        $builder->set_description(__('Enter a list of post IDs to exclude from the query.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //set Ignore Sticky Posts
    public static function build_ignore_sticky_posts_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_IGNORE_STICKY_POSTS);
        $builder->set_label(__('Ignore Sticky Posts', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPLC::QUERY_SETTING_IGNORE_STICKY_POSTS_DEFAULT);
        $builder->set_description(__('Specify to ignore sticky posts, treating them as regular posts in the query.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //add Load Posts via AJAX on Initial Load
    public static function build_ajax_on_initial_load_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_AJAX_ON_INITIAL_LOAD);
        $builder->set_label(__('Load Initial Posts via AJAX', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPLC::QUERY_SETTING_AJAX_ON_INITIAL_LOAD_DEFAULT);
        $builder->set_description(__('Enable this option to load the initial set of posts via AJAX on page load. This can help in cases where caching might cause outdated content to be displayed.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    // add Infinite Scroll via AJAX
    public static function build_ajax_infinite_scroll_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_AJAX_INFINITE_SCROLL);
        $builder->set_label(__('Enable Infinite Scroll', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPLC::QUERY_SETTING_AJAX_INFINITE_SCROLL_DEFAULT);
        $builder->set_description(__('Enable this option to automatically load more posts via AJAX as the user scrolls down the page.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    // show end message
    public static function build_show_end_message_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_SHOW_END_MESSAGE);
        $builder->set_label(__('Show End Message', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPLC::QUERY_SETTING_SHOW_END_MESSAGE_DEFAULT);
        $builder->set_description(__('Display a message when there are no more posts to load via AJAX.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //apply quick ajax css style
    public static function build_quick_ajax_css_style_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::LAYOUT_SETTING_QUICK_AJAX_CSS_STYLE);
        $builder->set_label(__('Apply Quick AJAX CSS Style', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPLC::LAYOUT_SETTING_QUICK_AJAX_CSS_STYLE_DEFAULT);
        $builder->set_description(__('Apply Quick AJAX CSS styles and column layout.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //select number of columns
    public static function build_select_columns_qty_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $columns_options = [];
                for ($i = 1; $i <= 12; $i++) {
            $columns_options[] = array(
                'label' =>  strval($i),
                'value' => $i
            );
        }
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::LAYOUT_SETTING_SELECT_COLUMNS_QTY);
        $builder->set_label(__('Number of Columns', 'quick-ajax-post-loader'));
        $builder->set_type('select');
        $builder->set_options($columns_options);
        $builder->set_default(QAPLC::LAYOUT_SETTING_SELECT_COLUMNS_QTY_DEFAULT);
        $builder->set_description(__('Specify the quantity of columns.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //select post item template
    public static function build_post_item_template_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $file_manager = new QAPL_Quick_Ajax_File_Manager();
        $templates = $file_manager->get_templates_items_array('post-items/post-item*.php', 'Post Item Name:', QAPLC::LAYOUT_SETTING_POST_ITEM_TEMPLATE_DEFAULT);
        $options = [];
        foreach ($templates as $template) {
            $options[] = [
                'label' => $template['template_name'],
                'value' => $template['file_name']
            ];
        }
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::LAYOUT_SETTING_POST_ITEM_TEMPLATE);
        $builder->set_label(__('Select Post Item Template', 'quick-ajax-post-loader'));
        $builder->set_type('select');
        $builder->set_options($options);
        $builder->set_default(QAPLC::LAYOUT_SETTING_POST_ITEM_TEMPLATE_DEFAULT);
        $builder->set_description(__('Choose a template for displaying post items.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //add custom class for taxonomy filter
    public static function build_taxonomy_filter_class_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::LAYOUT_SETTING_TAXONOMY_FILTER_CLASS);
        $builder->set_label(__('Add Class to Taxonomy Filter', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(QAPLC::LAYOUT_SETTING_TAXONOMY_FILTER_CLASS_DEFAULT);
        $builder->set_placeholder(__('class-name, another-class-name', 'quick-ajax-post-loader'));
        $builder->set_description(__('Add classes to the filter: class-one, class-two, class-three', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //add custom class for container
    public static function build_container_class_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::LAYOUT_SETTING_CONTAINER_CLASS);
        $builder->set_label(__('Add Class to Post Container', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(QAPLC::LAYOUT_SETTING_CONTAINER_CLASS_DEFAULT);
        $builder->set_placeholder(__('class-name, another-class-name', 'quick-ajax-post-loader'));
        $builder->set_description(__('Add classes to the post container: class-one, class-two, class-three', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //show custom load more post quantity
    public static function build_show_custom_load_more_post_quantity_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_SHOW_CUSTOM_LOAD_MORE_POST_QUANTITY);
        $builder->set_label(__('Load More Post Quantity', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPLC::QUERY_SETTING_SHOW_CUSTOM_LOAD_MORE_POST_QUANTITY_DEFAULT);
        $builder->set_description(__('Load a different number of posts after the initial display.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //select custom load more post quantity
    public static function build_select_custom_load_more_post_quantity_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::QUERY_SETTING_SELECT_CUSTOM_LOAD_MORE_POST_QUANTITY);
        $builder->set_label(__('Posts Per Load (After Initial)', 'quick-ajax-post-loader'));
        $builder->set_type('number');
        $builder->set_default(QAPLC::QUERY_SETTING_SELECT_CUSTOM_LOAD_MORE_POST_QUANTITY_DEFAULT);
        $builder->set_description(__('Set how many posts to load each time the "Load More" button is clicked.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //override global loader icon
    public static function build_override_global_loader_icon_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::LAYOUT_SETTING_OVERRIDE_GLOBAL_LOADER_ICON);
        $builder->set_label(__('Override Global Loader Icon', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPLC::LAYOUT_SETTING_OVERRIDE_GLOBAL_LOADER_ICON_DEFAULT);
        $builder->set_description(__('Set a different loader icon than the one specified in global options.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //select loader icon
    public static function build_select_loader_icon(): QAPL_Quick_Ajax_Form_Field_Interface {
        return self::loader_icon_get_field(
            QAPLC::LAYOUT_SETTING_SELECT_LOADER_ICON,
            QAPLC::LAYOUT_SETTING_SELECT_LOADER_ICON_DEFAULT
        );
    }
    //select loader icon global
    public static function build_global_select_loader_icon(): QAPL_Quick_Ajax_Form_Field_Interface {
        return self::loader_icon_get_field(
            QAPLC::GLOBAL_LOADER_ICON_FIELD,
            QAPLC::LAYOUT_SETTING_SELECT_LOADER_ICON_DEFAULT
        );
    }
    //build loader icon select field
    private static function loader_icon_get_field(string $name, string $default): QAPL_Quick_Ajax_Form_Field_Interface {
        $file_helper = new QAPL_Quick_Ajax_File_Manager();
        $templates = $file_helper->get_templates_items_array('loader-icon/*.php','Loader Icon Name:',$default);
        $options = [];
        foreach ($templates as $item) {
            $options[] = [
                'label' => $item['template_name'],
                'value' => $item['file_name']
            ];
        }
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name($name);
        $builder->set_label(__('Select Loader Icon', 'quick-ajax-post-loader'));
        $builder->set_type('select');
        $builder->set_options($options);
        $builder->set_default($default);
        $builder->set_description(__('Choose an icon to display as the loading indicator when the "Load More" button is clicked.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set read more
    public static function build_global_read_more_text_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::GLOBAL_READ_MORE_LABEL_FIELD);
        $builder->set_label(__('Set "Read More" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('Read More', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Enter custom label for Read More', 'quick-ajax-post-loader'));
        $builder->set_description(__('Customize the "Read More" text for your templates. This label will appear as a link or button for each post item. Examples: "Read More", "Continue Reading", or "Learn More".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set show all label
    public static function build_global_show_all_label_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::GLOBAL_SHOW_ALL_LABEL_FIELD);
        $builder->set_label(__('Set "Show All" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('Show All', 'quick-ajax-post-loader'));  
        $builder->set_placeholder(__('Enter custom label for Show All', 'quick-ajax-post-loader'));
        $builder->set_description(__('Customize the "Show All" text label for the filter. This label will appear as an option to display all posts without filtering. Examples: "Show All", "View All", or "Display All".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set load more label
    public static function build_global_load_more_label_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::GLOBAL_LOAD_MORE_LABEL_FIELD);
        $builder->set_label(__('Set "Load More" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('Load More', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Enter custom label for Load More', 'quick-ajax-post-loader'));
        $builder->set_description(__('Customize the "Load More" button text. This label will appear on the button used to load additional posts dynamically. Examples: "Load More", "Show More", or "View More".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set no post message
    public static function build_global_no_post_message_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::GLOBAL_NO_POST_MESSAGE_FIELD);
        $builder->set_label(__('Set "No Posts Found" Message', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('No posts found', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Enter message for no posts found', 'quick-ajax-post-loader'));
        $builder->set_description(__('Customize the message shown when no posts match the selected filters. Examples: "No posts found", "Nothing to display", or "Try adjusting your filters".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set end post message
    public static function build_global_end_post_message_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::GLOBAL_END_POST_MESSAGE_FIELD);
        $builder->set_label(__('Set "End of Posts" Message', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('No more posts to load', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Enter message for end of posts', 'quick-ajax-post-loader'));
        $builder->set_description(__('Customize the message that appears when there are no more posts to load. Examples: "No more posts", "You have reached the end", or "That\'s all for now".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set post date format
    /*
    public static function build_global_post_date_format_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::GLOBAL_POST_DATE_FORMAT_FIELD);
        $builder->set_label(__('Set Date Format', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default('F j, Y');
        $builder->set_placeholder(__('Enter date format (e.g., F j, Y)', 'quick-ajax-post-loader'));
        $builder->set_description(__('Customize the format for displaying post dates. This text will replace the default date format. For example: "F j, Y" (January 1, 2023) or "Y-m-d" (2023-01-01). Refer to the PHP date format documentation for more options.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    */
    //build global options set sort option date desc label
    public static function build_global_sort_option_date_desc_label_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::GLOBAL_SORT_OPTION_DATE_DESC_LABEL_FIELD);
        $builder->set_label(__('Set "Newest" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('Newest', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Newest', 'quick-ajax-post-loader'));
        $builder->set_description(__('Set the label for sorting posts from newest to oldest (based on publication date). Examples: "Newest", "Latest", "Recent".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set sort option date asc label
    public static function build_global_sort_option_date_asc_label_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::GLOBAL_SORT_OPTION_DATE_ASC_LABEL_FIELD);
        $builder->set_label(__('Set "Oldest" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('Oldest', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Oldest', 'quick-ajax-post-loader'));
        $builder->set_description(__('Set the label for sorting posts from oldest to newest (based on publication date). Examples: "Oldest", "First", "Earliest".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set sort option comment count desc label
    public static function build_global_sort_option_comment_count_desc_label_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::GLOBAL_SORT_OPTION_COMMENT_COUNT_DESC_LABEL_FIELD);
        $builder->set_label(__('Set "Popular" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('Popular', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Popular', 'quick-ajax-post-loader'));
        $builder->set_description(__('Set the label for sorting posts by the highest number of comments. Examples: "Popular", "Trending", "Most Discussed".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set sort option title asc label
    public static function build_global_sort_option_title_asc_label_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::GLOBAL_SORT_OPTION_TITLE_ASC_LABEL_FIELD);
        $builder->set_label(__('Set "A → Z" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('A → Z', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('A → Z', 'quick-ajax-post-loader'));
        $builder->set_description(__('Set the label for sorting posts alphabetically (A to Z) based on the post title. Examples: "Alphabetical", "A → Z", "Sort by Name".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set sort option title desc label
    public static function build_global_sort_option_title_desc_label_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::GLOBAL_SORT_OPTION_TITLE_DESC_LABEL_FIELD);
        $builder->set_label(__('Set "Z → A" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('Z → A', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Z → A', 'quick-ajax-post-loader'));
        $builder->set_description(__('Set the label for sorting posts alphabetically (Z to A) based on the post title. Examples: "Reverse Alphabetical", "Z → A", "Sort by Name Descending".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set sort option rand label
    public static function build_global_sort_option_rand_label_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::GLOBAL_SORT_OPTION_RAND_LABEL_FIELD);
        $builder->set_label(__('Set "Random" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('Random', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Random', 'quick-ajax-post-loader'));
        $builder->set_description(__('Set the label for sorting posts in a random order. Examples: "Shuffle", "Random", "Surprise Me".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global field remove old data
    public static function build_global_remove_old_data_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPLC::REMOVE_OLD_DATA_FIELD);
        $builder->set_label(__('Confirm Purge of Old Data', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPLC::REMOVE_OLD_DATA_FIELD_DEFAULT);
        $builder->set_description(__('Choose this option to remove old, unused data from the database. This will help keep your site clean and efficient. Be aware that if you switch back to an older version of the plugin, it might not work as expected.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
}







////////////////////////////////
class QAPL_Quick_Ajax_Plugin_Constants{
    // Plugin info
    public const PLUGIN_VERSION = '1.8.0';
    public const PLUGIN_NAME = 'Quick Ajax Post Loader';
    public const PLUGIN_TEXT_DOMAIN = 'quick-ajax-post-loader';
    public const PLUGIN_SLUG = 'quick-ajax-post-loader';
    public const PLUGIN_MINIMUM_PHP_VERSION = '7.4';
    public const PLUGIN_MINIMUM_WP_VERSION = '5.6';
    public const PLUGIN_TESTED_WP_VERSION = '6.8';

    // Menu and page slugs
    public const PLUGIN_MENU_SLUG = 'qapl-menu';
    public const CPT_SHORTCODE_SLUG = 'qapl-creator';
    public const SETTINGS_PAGE_SLUG = 'qapl-settings';

    // Quick AJAX Creator shortcode field names
    public const DB_POSTMETA_SHORTCODE_SETTINGS = '_qapl_quick_ajax_shortcode_settings';

    // Old value, not in use since 1.3.3
    // public const DB_SHORTCODE_CODE = 'qapl_quick_ajax_shortcode_code'; // Uncomment if needed later

    // Plugin metadata
    public const DB_OPTION_PLUGIN_VERSION = 'qapl_quick_ajax_plugin_version';
    public const DB_OPTION_PLUGIN_CLEANUP_FLAGS = 'qapl_quick_ajax_cleanup_flags';

    // Settings
    public const SETTINGS_WRAPPER_ID = 'qapl_settings_wrapper';

    // Nonce fields
    public const NONCE_FORM_QUICK_AJAX_FIELD = 'qapl_quick_ajax_nonce';
    public const NONCE_FORM_QUICK_AJAX_ACTION = 'qapl_quick_ajax_nonce_action';

    // Query settings field names
    public const QUERY_SETTING_SELECT_POST_TYPE = 'qapl_select_post_type';
    public const QUERY_SETTING_SELECT_POST_TYPE_DEFAULT = 'post';

    public const QUERY_SETTING_SHOW_TAXONOMY_FILTER = 'qapl_show_select_taxonomy';
    public const QUERY_SETTING_SHOW_TAXONOMY_FILTER_DEFAULT = 0;

    public const QUERY_SETTING_SELECT_TAXONOMY = 'qapl_select_taxonomy';

    public const QUERY_SETTING_MANUAL_TERM_SELECTION = 'qapl_manual_term_selection';
    public const QUERY_SETTING_MANUAL_TERM_SELECTION_DEFAULT = 0;

    public const QUERY_SETTING_SELECTED_TERMS = 'qapl_manual_selected_terms';

    public const QUERY_SETTING_SELECT_POSTS_PER_PAGE = 'qapl_select_posts_per_page';
    public const QUERY_SETTING_SELECT_POSTS_PER_PAGE_DEFAULT = 6;

    public const QUERY_SETTING_SELECT_ORDER = 'qapl_select_order';
    public const QUERY_SETTING_SELECT_ORDER_DEFAULT = 'DESC';

    public const QUERY_SETTING_SELECT_ORDERBY = 'qapl_select_orderby';
    public const QUERY_SETTING_SELECT_ORDERBY_DEFAULT = 'date';

    public const QUERY_SETTING_SHOW_SORT_BUTTON = 'qapl_show_order_button';
    public const QUERY_SETTING_SHOW_SORT_BUTTON_DEFAULT = 0;
    
    public const QUERY_SETTING_SELECT_SORT_BUTTON_OPTIONS = 'qapl_select_orderby_button_options';
    public const QUERY_SETTING_SELECT_SORT_BUTTON_OPTIONS_DEFAULT = 1;

    public const QUERY_SETTING_SHOW_INLINE_FILTER_SORTING = 'qapl_show_inline_filter_sorting';
    public const QUERY_SETTING_SHOW_INLINE_FILTER_SORTING_DEFAULT = 1;

    public const QUERY_SETTING_SELECT_POST_STATUS = 'qapl_select_post_status';
    public const QUERY_SETTING_SELECT_POST_STATUS_DEFAULT = 'publish';

    public const QUERY_SETTING_IGNORE_STICKY_POSTS = 'qapl_ignore_sticky_posts';
    public const QUERY_SETTING_IGNORE_STICKY_POSTS_DEFAULT = false;

    public const QUERY_SETTING_AJAX_ON_INITIAL_LOAD = 'qapl_ajax_on_initial_load';
    public const QUERY_SETTING_AJAX_ON_INITIAL_LOAD_DEFAULT = false;

    public const QUERY_SETTING_AJAX_INFINITE_SCROLL = 'qapl_ajax_infinite_scroll';
    public const QUERY_SETTING_AJAX_INFINITE_SCROLL_DEFAULT = false;

    public const QUERY_SETTING_SHOW_END_MESSAGE = 'qapl_show_end_post_message';
    public const QUERY_SETTING_SHOW_END_MESSAGE_DEFAULT = false;

    public const QUERY_SETTING_SET_POST_NOT_IN = 'qapl_select_post_not_in';
    public const QUERY_SETTING_SET_POST_NOT_IN_DEFAULT = false;

    public const QUERY_SETTING_SHOW_CUSTOM_LOAD_MORE_POST_QUANTITY = 'qapl_show_custom_load_more_post_quantity';
    public const QUERY_SETTING_SHOW_CUSTOM_LOAD_MORE_POST_QUANTITY_DEFAULT = 0;

    public const QUERY_SETTING_SELECT_CUSTOM_LOAD_MORE_POST_QUANTITY = 'qapl_select_custom_load_more_post_quantity';
    public const QUERY_SETTING_SELECT_CUSTOM_LOAD_MORE_POST_QUANTITY_DEFAULT = 4;

    // Layout settings field names
    public const LAYOUT_SETTING_SELECT_COLUMNS_QTY = 'qapl_layout_select_columns_qty';
    public const LAYOUT_SETTING_SELECT_COLUMNS_QTY_DEFAULT = 3;

    public const LAYOUT_SETTING_TAXONOMY_FILTER_CLASS = 'qapl_layout_add_taxonomy_filter_class';
    public const LAYOUT_SETTING_TAXONOMY_FILTER_CLASS_DEFAULT = false;

    public const LAYOUT_SETTING_CONTAINER_CLASS = 'qapl_layout_add_container_class';
    public const LAYOUT_SETTING_CONTAINER_CLASS_DEFAULT = false;

    public const LAYOUT_SETTING_QUICK_AJAX_CSS_STYLE = 'qapl_layout_quick_ajax_css_style';
    public const LAYOUT_SETTING_QUICK_AJAX_CSS_STYLE_DEFAULT = 1;

    public const LAYOUT_SETTING_POST_ITEM_TEMPLATE = 'qapl_layout_quick_ajax_post_item_template';
    public const LAYOUT_SETTING_POST_ITEM_TEMPLATE_DEFAULT = 'post-item';

    public const LAYOUT_SETTING_OVERRIDE_GLOBAL_LOADER_ICON = 'qapl_override_global_loader_icon';
    public const LAYOUT_SETTING_OVERRIDE_GLOBAL_LOADER_ICON_DEFAULT = 0;

    public const LAYOUT_SETTING_SELECT_LOADER_ICON = 'qapl_loader_icon';
    public const LAYOUT_SETTING_SELECT_LOADER_ICON_DEFAULT = 'loader-icon';

    // Ajax settings
    public const AJAX_SETTING_AJAX_INITIAL_LOAD = 'ajax_initial_load';

    // Attributes query names
    public const ATTRIBUTE_QUICK_AJAX_ID = 'quick_ajax_id';
    public const ATTRIBUTE_QUICK_AJAX_CSS_STYLE = 'quick_ajax_css_style';
    public const ATTRIBUTE_GRID_NUM_COLUMNS = 'grid_num_columns';
    public const ATTRIBUTE_POST_ITEM_TEMPLATE = 'post_item_template';
    public const ATTRIBUTE_TAXONOMY_FILTER_CLASS = 'taxonomy_filter_class';
    public const ATTRIBUTE_CONTAINER_CLASS = 'container_class';
    public const ATTRIBUTE_LOAD_MORE_POSTS = 'load_more_posts';
    public const ATTRIBUTE_LOADER_ICON = 'loader_icon';
    public const ATTRIBUTE_AJAX_INFINITE_SCROLL = 'infinite_scroll';
    public const ATTRIBUTE_SHOW_END_MESSAGE = 'show_end_message';

    // Quick AJAX settings
    public const ADMIN_PAGE_SETTINGS_GROUP = 'qapl_settings_group'; // used by register_setting to group fields for shared validation and security in admin forms
    public const GLOBAL_OPTIONS_NAME = 'qapl_quick_ajax_global_options';

    // Global options
    public const GLOBAL_LOADER_ICON_FIELD = self::GLOBAL_OPTIONS_NAME . '[loader_icon]';
    public const GLOBAL_READ_MORE_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[read_more_label]';
    //public const GLOBAL_POST_DATE_FORMAT_FIELD = self::GLOBAL_OPTIONS_NAME . '[post_date_format]';
    public const GLOBAL_SHOW_ALL_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[show_all_label]';
    public const GLOBAL_LOAD_MORE_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[load_more_label]';
    public const GLOBAL_NO_POST_MESSAGE_FIELD = self::GLOBAL_OPTIONS_NAME . '[no_post_message]';
    public const GLOBAL_END_POST_MESSAGE_FIELD = self::GLOBAL_OPTIONS_NAME . '[end_post_message]';
    public const GLOBAL_SORT_OPTION_DATE_DESC_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[sort_option_date_desc_label]';
    public const GLOBAL_SORT_OPTION_DATE_ASC_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[sort_option_date_asc_label]';
    public const GLOBAL_SORT_OPTION_COMMENT_COUNT_DESC_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[sort_option_comment_count_desc_label]';
    public const GLOBAL_SORT_OPTION_TITLE_ASC_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[sort_option_title_asc_label]';
    public const GLOBAL_SORT_OPTION_TITLE_DESC_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[sort_option_title_desc_label]';
    public const GLOBAL_SORT_OPTION_RAND_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[sort_option_rand_label]';
    
    //Settings Page
    public const REMOVE_OLD_DATA_FIELD = 'qapl_remove_old_meta';
    public const REMOVE_OLD_DATA_FIELD_DEFAULT = 0;
    // Buttons
    public const TERM_FILTER_BUTTON_DATA_BUTTON = 'quick-ajax-filter-button';
    public const SORT_OPTION_BUTTON_DATA_BUTTON = 'quick-ajax-sort-option-button';
    public const LOAD_MORE_BUTTON_DATA_BUTTON = 'quick-ajax-load-more-button';

    // Hooks
    // Filter Container Hooks
    public const HOOK_FILTER_CONTAINER_BEFORE = 'qapl_filter_container_before';
    public const HOOK_FILTER_CONTAINER_START = 'qapl_filter_container_start';
    public const HOOK_FILTER_CONTAINER_END = 'qapl_filter_container_end';
    public const HOOK_FILTER_CONTAINER_AFTER = 'qapl_filter_container_after';

    // Posts Container Hooks
    public const HOOK_POSTS_CONTAINER_BEFORE = 'qapl_posts_container_before';
    public const HOOK_POSTS_CONTAINER_START = 'qapl_posts_container_start';
    public const HOOK_POSTS_CONTAINER_END = 'qapl_posts_container_end';
    public const HOOK_POSTS_CONTAINER_AFTER = 'qapl_posts_container_after';

    // Load More Button Hooks
    //public const HOOK_LOAD_MORE_BEFORE = 'qapl_load_more_before';
    //public const HOOK_LOAD_MORE_AFTER = 'qapl_load_more_after';

    // Loader Hooks
    public const HOOK_LOADER_BEFORE = 'qapl_loader_before';
    public const HOOK_LOADER_AFTER = 'qapl_loader_after';

    // Filters
    public const HOOK_MODIFY_POSTS_QUERY_ARGS = 'qapl_modify_posts_query_args';
    public const HOOK_MODIFY_TAXONOMY_FILTER_BUTTONS = 'qapl_modify_taxonomy_filter_buttons';
    public const HOOK_MODIFY_SORTING_OPTIONS_VARIANTS = 'qapl_modify_sorting_options_variants';

    // Template Hooks
    public const HOOK_TEMPLATE_POST_ITEM_DATE = 'qapl_template_post_item_date';
    public const HOOK_TEMPLATE_POST_ITEM_IMAGE = 'qapl_template_post_item_image';
    public const HOOK_TEMPLATE_POST_ITEM_TITLE = 'qapl_template_post_item_title';
    public const HOOK_TEMPLATE_POST_ITEM_EXCERPT = 'qapl_template_post_item_excerpt';
    public const HOOK_TEMPLATE_POST_ITEM_READ_MORE = 'qapl_template_post_item_read_more';
    public const HOOK_TEMPLATE_LOAD_MORE_BUTTON = 'qapl_template_load_more_button';
    public const HOOK_TEMPLATE_NO_POST_MESSAGE = 'qapl_template_no_post_message';
    public const HOOK_TEMPLATE_END_POST_MESSAGE = 'qapl_template_end_post_message';
}

$file_helper = new QAPL_Quick_Ajax_File_Manager();
$enqueue_handler = new QAPL_Quick_Ajax_Enqueue_Handler($file_helper);
$pages_helper = new QAPL_Quick_Ajax_Resource_Manager($file_helper);
$plugin_bootstrap = new QAPL_Quick_Ajax_Plugin_Bootstrap($pages_helper);

$plugin_starter = new QAPL_Quick_Ajax_Plugin_Starter($plugin_bootstrap, $enqueue_handler);
$plugin_starter->run();
