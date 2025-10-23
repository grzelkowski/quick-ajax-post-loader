<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Quick_Ajax_File_Manager implements QAPL_Quick_Ajax_File_Manager_Interface {
    private $plugin_dir_path;
    private $plugin_dir_url;

    public function __construct() {
        $this->plugin_dir_path = plugin_dir_path(dirname(__DIR__));
        $this->plugin_dir_url = plugin_dir_url(dirname(__DIR__));
    }
    public function file_exists(string $file_path) {
        $full_path = $this->plugin_dir_path . ltrim($file_path, '/');
        if (!file_exists($full_path)) {
            return false;
        }
        return $full_path;
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
    public function get_templates_dir_path(string $file) {
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
        qapl_log('Template file not found: '.$file, 'warning');
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
            if (!file_exists($file_path)) {
                qapl_log('Template file: "'.$template_name .'" not found. default name:'.$default_name.', path:'. $base_path, 'error');
            }
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
        $default_name = QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_POST_ITEM_TEMPLATE_DEFAULT;
        return $this->get_templates_file_path($template_name, $default_name, '/post-items/');
    }
    //template loader icon
    public function get_loader_icon_template(string $template_name = ''): string {
        $default_name = QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_SELECT_LOADER_ICON_DEFAULT;
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