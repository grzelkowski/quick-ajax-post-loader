<?php

if (!defined('ABSPATH')) {
    exit; // prevent direct access
}

interface QAPL_Post_Item_Date_Interface {
    public function render_date();
}

interface QAPL_Post_Item_Image_Interface {
    public function render_image();
}

interface QAPL_Post_Item_Title_Interface {
    public function render_title();
}

interface QAPL_Post_Item_Excerpt_Interface {
    public function render_excerpt();
}

interface QAPL_Post_Item_ReadMore_Interface {
    public function render_read_more();
}

interface QAPL_Load_More_Interface {
    public function render_load_more_button();
}

interface QAPL_End_Post_Message_Interface {
    public function render_end_post_message();
}

interface QAPL_No_Post_Message_Interface {
    public function render_no_post_message();
}

function qapl_output_template_post_date() {
    $template = QAPL_Post_Template_Context::get_template();
    if ($template && method_exists($template, 'render_date')) {
        echo wp_kses_post($template->render_date());
    }
}
function qapl_output_template_post_image() {
    $template = QAPL_Post_Template_Context::get_template();
    if ($template && method_exists($template, 'render_image')) {
        echo wp_kses_post($template->render_image());
    }
}
function qapl_output_template_post_title() {
    $template = QAPL_Post_Template_Context::get_template();
    if ($template && method_exists($template, 'render_title')) {
        echo wp_kses_post($template->render_title());
    }
}
function qapl_output_template_post_excerpt() {
    $template = QAPL_Post_Template_Context::get_template();
    if ($template && method_exists($template, 'render_excerpt')) {
        echo wp_kses_post($template->render_excerpt());
    }
}
function qapl_output_template_post_read_more() {
    $template = QAPL_Post_Template_Context::get_template();
    if ($template && method_exists($template, 'render_read_more')) {
        echo wp_kses_post($template->render_read_more());
    }
}
function qapl_output_template_button_load_more() {
    $template = QAPL_Post_Template_Context::get_template();
    if ($template && method_exists($template, 'render_load_more_button')) {
        echo wp_kses_post($template->render_load_more_button());
    }
}
function qapl_output_template_no_post_message() {
    $template = QAPL_Post_Template_Context::get_template();
    if ($template && method_exists($template, 'render_no_post_message')) {
        echo wp_kses_post($template->render_no_post_message());
    }
}
function qapl_output_template_end_post_message() {
    $template = QAPL_Post_Template_Context::get_template();
    if ($template && method_exists($template, 'render_end_post_message')) {
        echo wp_kses_post($template->render_end_post_message());
    }
}

class QAPL_Template_Config {
    protected $options = [];
    public function __construct(array $options = []) {
        $defaults = [
            'show_date'        => true,                // Show/hide date
            'date_format'      => get_option('date_format'),      // Date format
            'show_read_more'   => true,                // Show/hide "read more"
            'read_more_label'  => null,                // Read more label;
            'load_more_label'  => null,                // Load more label;
            'no_post_message' => null,                 // No post message;
            'end_post_message' => null,                // End post message;
        ];
        $this->options = wp_parse_args($options, $defaults);
    }
    public function get(string $key, $default = null) {
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }
    public function set(string $key, $value) {
        $this->options[$key] = $value;
    }
    public function toArray(): array {
        return $this->options;
    }
}

abstract class QAPL_Template_Base {
    protected $quick_ajax_id;
    protected $template_name;
    protected $config;

    public function __construct($quick_ajax_id, $template_name, QAPL_Template_Config $config, array $global_options) {
        $this->quick_ajax_id = $quick_ajax_id;
        $this->template_name = $template_name;
        $this->config = $config;

        // Initialize config using global options
        $this->init_config($global_options);
    }

    protected function init_config(array $global_options) {
        if ($this->config->get('show_date') === true) {
            $this->config->set('date_format', $global_options['date_format'] ?? get_option('date_format'));
        }
        if ($this->config->get('show_read_more') === true) {
            $this->config->set('read_more_label', !empty($global_options['read_more_label']) ? $global_options['read_more_label'] : __('Read More', 'quick-ajax-post-loader'));
        }
        if ($this->config->get('load_more_label') === null) {
            $this->config->set('load_more_label', !empty($global_options['load_more_label']) ? $global_options['load_more_label'] : __('Load More', 'quick-ajax-post-loader'));
        }
        if ($this->config->get('no_post_message') === null) {
            $this->config->set('no_post_message', !empty($global_options['no_post_message']) ? $global_options['no_post_message'] : __('No posts found', 'quick-ajax-post-loader'));
        }
        if ($this->config->get('end_post_message') === null) {
            $this->config->set('end_post_message', !empty($global_options['end_post_message']) ? $global_options['end_post_message'] : __('No more posts to load', 'quick-ajax-post-loader'));
        }
              
    }
}

class QAPL_Template_Post_Item extends QAPL_Template_Base
    implements  QAPL_Post_Item_Date_Interface, 
                QAPL_Post_Item_Image_Interface, 
                QAPL_Post_Item_Title_Interface, 
                QAPL_Post_Item_Excerpt_Interface, 
                QAPL_Post_Item_ReadMore_Interface {

    public function render_date() {
        if (!$this->config->get('show_date')) {
            return '';
        }
        $date_format = $this->config->get('date_format');
        $output = '<div class="qapl-post-date"><span>' . esc_html(get_the_date($date_format)) . '</span></div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_DATE, $output, $this->template_name, $this->quick_ajax_id);
    }

    public function render_image() {
        $output = has_post_thumbnail()
            //? '<div class="qapl-post-image">' . get_the_post_thumbnail(get_the_ID(), 'large', ['loading' => 'lazy']) . '</div>'
            ? '<div class="qapl-post-image">' . get_the_post_thumbnail(get_the_ID(), 'large', ['alt' => esc_attr(get_the_title()), 'loading' => 'lazy']) . '</div>'
            : '<div class="qapl-post-image qapl-no-image"></div>';

        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_IMAGE, $output, $this->template_name, $this->quick_ajax_id);
    }

    public function render_title() {
        $output = '<div class="qapl-post-title"><h3>' . esc_html(get_the_title()) . '</h3></div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_TITLE, $output, $this->template_name, $this->quick_ajax_id);
    }

    public function render_excerpt() {
        $output = '<div class="qapl-post-description"><p>' . esc_html(wp_trim_words(get_the_excerpt(), 20)) . '</p></div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_EXCERPT, $output, $this->template_name, $this->quick_ajax_id);
    }

    public function render_read_more() {
        if (!$this->config->get('show_read_more')) {
            return '';
        }
        $label = $this->config->get('read_more_label');
        $output = '<div class="qapl-read-more"><p>' . esc_html($label) . '</p></div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_READ_MORE, $output, $this->template_name, $this->quick_ajax_id);
    }
}


class QAPL_Template_Post_Item_Qapl_Full_Background_Image extends QAPL_Template_Base
    implements  QAPL_Post_Item_Date_Interface, 
                QAPL_Post_Item_Image_Interface, 
                QAPL_Post_Item_Title_Interface, 
                QAPL_Post_Item_Excerpt_Interface, 
                QAPL_Post_Item_ReadMore_Interface {

    public function render_date() {
        if (!$this->config->get('show_date')) {
            return '';
        }
        $date_format = $this->config->get('date_format');
        $output = '<div class="qapl-post-date"><span>' . esc_html(get_the_date($date_format)) . '</span></div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_DATE, $output, $this->template_name, $this->quick_ajax_id);
    }

    public function render_image() {
        $output = has_post_thumbnail()
            //? '<img src="' . esc_url(get_the_post_thumbnail_url(null, "full")) . '" alt="' . esc_attr(get_the_title()) . '" class="qapl-post-image">'
            ? get_the_post_thumbnail(get_the_ID(), 'large', array('alt' => esc_attr(get_the_title()), 'class'  => 'qapl-post-image', 'loading' => 'lazy'))
            : '<span class="qapl-no-image"></span>';

        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_IMAGE, $output, $this->template_name, $this->quick_ajax_id);
    }

    public function render_title() {
        $output = '<div class="qapl-post-title"><h3>' . esc_html(get_the_title()) . '</h3></div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_TITLE, $output, $this->template_name, $this->quick_ajax_id);
    }

    public function render_excerpt() {
        $output = '<div class="qapl-post-description"><p>' . esc_html(wp_trim_words(get_the_excerpt(), 20)) . '</p></div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_EXCERPT, $output, $this->template_name, $this->quick_ajax_id);
    }
    
    public function render_read_more() {
        if (!$this->config->get('show_read_more')) {
            return '';
        }
        $label = $this->config->get('read_more_label');
        $output = '<div class="qapl-read-more"><p>' . esc_html($label) . '</p></div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_READ_MORE, $output, $this->template_name, $this->quick_ajax_id);
    }
}

class QAPL_Template_Load_More_Button extends QAPL_Template_Base implements QAPL_Load_More_Interface {
    public function render_load_more_button() {   
        $label = $this->config->get('load_more_label');
        $output = '<button type="button" class="qapl-load-more-button qapl-button" data-button="'.QAPL_Constants::LOAD_MORE_BUTTON_DATA_BUTTON.'">' . esc_html($label) . '</button>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_LOAD_MORE_BUTTON, $output, $this->quick_ajax_id);
    }
}

class QAPL_Template_End_Post_Message extends QAPL_Template_Base implements QAPL_End_Post_Message_Interface {
    public function render_end_post_message() {   
        $end_post_message = $this->config->get('end_post_message');
        $output = '<p>' . esc_html($end_post_message) . '</p>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_END_POST_MESSAGE, $output, $this->quick_ajax_id);
    }
}

class QAPL_Template_No_Post_Message extends QAPL_Template_Base implements QAPL_No_Post_Message_Interface {
    public function render_no_post_message() {   
        $no_post_message = $this->config->get('no_post_message');
        $output = '<p>' . esc_html($no_post_message) . '</p>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_NO_POST_MESSAGE, $output, $this->quick_ajax_id);
    }
}


abstract class QAPL_Template_Empty_Filters {
    public function render_date() { return ''; }
    public function render_image() { return ''; }
    public function render_title() { return ''; }
    public function render_excerpt() { return ''; }
    public function render_read_more() { return ''; }
    public function render_load_more_button() { return ''; }
    public function render_no_post_message() { return ''; }
    public function render_end_post_message() { return ''; }
}


class QAPL_Post_Template_Factory {
    private static $available_templates = [
        'post-item' => QAPL_Template_Post_Item::class,
        'post-item-qapl-full-background-image' => QAPL_Template_Post_Item_Qapl_Full_Background_Image::class,
        'load-more-button' => QAPL_Template_Load_More_Button::class,
        'no-post-message' => QAPL_Template_No_Post_Message::class,
        'end-post-message' => QAPL_Template_End_Post_Message::class,

    ];
    public static function get_template($container_settings) {
        $template_name = $container_settings['template_name'] ?? '';
        $quick_ajax_id = $container_settings['quick_ajax_id'] ?? '';
        $config_array = $container_settings['config'] ?? [];
        $global_options = get_option(QAPL_Constants::GLOBAL_OPTIONS_NAME, []);
        $config = new QAPL_Template_Config($config_array);

        /*
        //generate the class name dynamically
        $class_name = self::generate_class_name($template_name);

        //return an instance of the class if it exists
        if (class_exists($class_name)) {
            return new $class_name($quick_ajax_id, $template_name, $config, $global_options);
        }*/
        if (isset(self::$available_templates[$template_name])) {
            $class_name = self::$available_templates[$template_name];
            return new $class_name($quick_ajax_id, $template_name, $config, $global_options);
        }
        //if the class doesn't exist
        return new class($quick_ajax_id, $template_name, $config, $global_options) extends QAPL_Template_Empty_Filters {};
    }
    
    private static function generate_class_name($template_name) {
        // Example conversions:
        // Input:  "post-item-qapl-full-background-image"
        // Output: "QAPL_Template_Post_Item_Qapl_Full_Background_Image"

        //replace dashes and underscores with spaces
        $formatted_name = str_replace('-', ' ', $template_name);
        //capitalize each word
        $formatted_name = ucwords($formatted_name);        
        //replace spaces with underscores
        $formatted_name = str_replace(' ', '_', $formatted_name);        
        //prepend the base class prefix
        return 'QAPL_Quick_Ajax_Template_' . $formatted_name;
    }

    public static function get_template_methods($template_name) {
        $class_name = self::generate_class_name($template_name);

        if (!class_exists($class_name)) {
            return [];
        }

        return get_class_methods($class_name);
    }
}

class QAPL_Post_Template_Context {
    private static $current_template;

    public static function set_template($template) {
        self::$current_template = $template;
    }

    public static function get_template() {
        return self::$current_template;
    }

    public static function clear_template() {
        self::$current_template = null;
    }
}