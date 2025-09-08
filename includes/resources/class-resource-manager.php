<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Quick_Ajax_Resource_Manager implements QAPL_Quick_Ajax_Resource_Manager_Interface {

    private QAPL_Quick_Ajax_File_Manager_Interface $file_manager;

    // constants for page files
    private const PAGE_FILES = [
        'admin_pages_config'        => 'includes/admin/admin-pages-config.php',
        'settings_page'             => 'includes/admin/pages/settings-page.php',
        'shortcode_page'            => 'includes/admin/pages/shortcode-page.php',
    ];

    // constants for component files
    private const COMPONENT_FILES = [
        //form
        'interface-form-field'      => 'includes/form/interface-form-field.php',
        'class-form-field'          => 'includes/form/class-form-field.php',
        'class-form-field-builder'  => 'includes/form/class-form-field-builder.php',
        'class-form-field-factory'  => 'includes/form/class-form-field-factory.php',
        //shortcode
        'class-shortcode'           => 'includes/shortcode/class-shortcode.php',
        'shortcode-generator'       => 'includes/shortcode/class-shortcode-generator.php',
        //AJAX
        'class-ajax'                => 'includes/ajax/class-ajax.php',
        'ajax-actions'              => 'includes/ajax/actions.php',
        'template-renderers'        => 'includes/template-renderers/class-template-hooks.php',
        'functions'                 => 'includes/functions.php',
        //maintenance / compatibility
        'updater'                   => 'includes/maintenance/class-updater.php',
        'deprecated-hooks-handler'  => 'includes/deprecated/class-deprecated-hooks-handler.php',
        'dev-hooks'                 => 'dev-tools/dev-hooks.php',
    ];
    

    //construct expects instance of QAPL_Quick_Ajax_File_Manager
    public function __construct(QAPL_Quick_Ajax_File_Manager_Interface $file_manager) {
        $this->file_manager = $file_manager;
    }
    public function initialize_pages(): void{
        $this->load_files(self::PAGE_FILES);
    }

    public function initialize_components(): void{
        $this->load_files(self::COMPONENT_FILES);
    }

    private function load_files(array $files): void{
        //refactoring class        
        if (class_exists('QAPL_Refactoring_Helper')) {
            $dev_file_replacer = new QAPL_Refactoring_Helper($this->file_manager);
        }else{
            $dev_file_replacer = false;
        }
        foreach ($files as $file) {
            if($dev_file_replacer){
                $dev_file = $dev_file_replacer->get_new_file($file);
                if(!empty($dev_file)){
                    $file = $dev_file;
                }
            }
            $path = $this->file_manager->file_exists($file);
            if ($path !== false) {
                require_once $path;
            }
        }
    }
}