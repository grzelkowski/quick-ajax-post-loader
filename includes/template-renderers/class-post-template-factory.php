<?php 
if (!defined('ABSPATH')) {
    exit;
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
        //injected options; fall back to get_option() when a caller passes none
        $global_options = !empty($container_settings['global_options'])
            ? $container_settings['global_options']
            : get_option(QAPL_Constants::GLOBAL_OPTIONS_NAME);
        if (!is_array($global_options)) {
            $global_options = [];
        }
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
