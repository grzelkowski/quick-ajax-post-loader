<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Quick_Ajax_Initializer {

    private static bool $initialized = false;
    private static string $plugin_dir_path;
    private static string $includes_dir_path;
    private const REQUIRED_CLASSES = [
        'QAPL_Quick_Ajax_File_Manager',
        'QAPL_Quick_Ajax_Resource_Manager',
        'QAPL_Quick_Ajax_Enqueue_Handler',
        'QAPL_Quick_Ajax_Plugin_Starter',
    ];
    public static function initialize():void{
        if (self::$initialized) {
            return; 
        }
        self::$initialized = true;
        self::$plugin_dir_path = plugin_dir_path(__DIR__);   // quick-ajax-post-loader/
        self::$includes_dir_path = __DIR__ . '/'; // quick-ajax-post-loader/includes/
        //require all classes
        self::require_resources();
        self::require_enqueue();
        self::require_plugin_starter();
        self::load_dev_tools();

        //check if classes exists
        if (!class_exists('QAPL_Quick_Ajax_Utilities')) {
            qapl_log('Missing class: QAPL_Quick_Ajax_Utilities', 'warning');
        }
        if (class_exists('QAPL_Quick_Ajax_Utilities')) {
            $verify = QAPL_Quick_Ajax_Utilities::verify_classes_exist(self::REQUIRED_CLASSES, 'Initializer');
            if ($verify === false) {
                return;
            }
        }
        //build services
        $file_manager = new QAPL_Quick_Ajax_File_Manager();
        $resource_manager = new QAPL_Quick_Ajax_Resource_Manager($file_manager);
        $enqueue_handler = new QAPL_Quick_Ajax_Enqueue_Handler($file_manager);
        //wire starter with interfaces
        $plugin_starter = new QAPL_Quick_Ajax_Plugin_Starter($resource_manager,$enqueue_handler);
        //run plugin
        $plugin_starter->start();
    }
    private static function require_resources():void{
        $base = self::$includes_dir_path . 'resources/';
        require_once $base . 'functions-helpers.php';
        require_once $base . 'interface-file-manager.php';
        require_once $base . 'class-file-manager.php';
        require_once $base . 'interface-resource-manager.php';
        require_once $base . 'class-resource-manager.php';
        require_once $base . 'class-utilities.php';
    }
    private static function require_enqueue():void{
        $base = self::$includes_dir_path . 'enqueue/';
        require_once $base . 'interface-enqueue-handler.php';
        require_once $base . 'class-enqueue-handler.php';
    }
    private static function require_plugin_starter():void{
        $base = self::$includes_dir_path;
        require_once $base . 'class-plugin-starter.php';
    }
    private static function load_dev_tools(): void {
        $dev_tools_path = self::$plugin_dir_path . 'dev-tools/dev-class-logger.php';
        if (file_exists($dev_tools_path)) {
            require_once $dev_tools_path;
        }
    }
}
