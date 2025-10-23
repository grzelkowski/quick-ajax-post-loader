<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Quick_Ajax_Resource_Manager implements QAPL_Quick_Ajax_Resource_Manager_Interface {

    private QAPL_Quick_Ajax_File_Manager_Interface $file_manager;

    // constants for page files
    private const PAGE_FILES = [
        'class-admin-menu'                  => 'includes/admin/class-admin-menu.php', //main admin menu and submenu

        //CPT
        'class-post-form'                   => 'includes/admin/cpt/class-cpt-editor-form.php', //abstract form for post type meta box
        // creator CPT
        'class-creator-post-type'           => 'includes/admin/cpt/creator/class-creator-post-type.php',
        'class-creator-columns'             => 'includes/admin/cpt/creator/class-creator-columns.php',
        'class-creator-form'                => 'includes/admin/cpt/creator/class-creator-form.php',
        'class-creator-shortcode-box'       => 'includes/admin/cpt/creator/class-creator-shortcode-box.php',
        'class-creator-editor'              => 'includes/admin/cpt/creator/class-creator-editor.php',
        
        //admin pages
        'class-options-form'                => 'includes/admin/pages/class-admin-options-page-form.php', //abstract form for option page
        // settings page
        'class-settings-tab-options'        => 'includes/admin/pages/settings/tabs/class-settings-tab-options.php',
        'class-settings-tab-php-snippet'    => 'includes/admin/pages/settings/tabs/class-settings-tab-php-snippet.php',
        'class-settings-tab-help'           => 'includes/admin/pages/settings/tabs/class-settings-tab-help.php',
        'class-settings-tab-cleanup'        => 'includes/admin/pages/settings/tabs/class-settings-tab-cleanup.php',
        'class-settings-page'               => 'includes/admin/pages/settings/class-settings-page.php',

    ];

    // constants for component files
    private const COMPONENT_FILES = [
        //form
        'interface-form-field'              => 'includes/form/interface-form-field.php', //interface for form field
        'class-form-field'                  => 'includes/form/class-form-field.php', //final form field object
        'class-form-field-builder'          => 'includes/form/class-form-field-builder.php', //builder for form fields
        'class-form-field-factory'          => 'includes/form/class-form-field-factory.php', //factory to create form fields
        'class-content-builder'             => 'includes/form/class-form-content-builder.php', //base class to build form fields html
        //shortcode
        'class-shortcode-ajax-attributes'   => 'includes/shortcode/handlers/class-shortcode-ajax-attributes-provider.php',
        'class-shortcode-params'            => 'includes/shortcode/handlers/class-shortcode-params-handler.php',
        'class-shortcode-post-meta'         => 'includes/shortcode/handlers/class-shortcode-post-meta-handler.php',
        'class-shortcode-query-args'        => 'includes/shortcode/handlers/class-shortcode-query-args-provider.php',
        'class-shortcode'                   => 'includes/shortcode/class-shortcode.php',
        'shortcode-generator'               => 'includes/shortcode/class-shortcode-generator.php',
        //AJAX
        'class-ajax-helper'                 => 'includes/ajax-render/class-ajax-helper.php',
        'class-ajax-query-builder'          => 'includes/ajax-render/class-ajax-query-builder.php',
        'class-ajax-ui-renderer'            => 'includes/ajax-render/class-ajax-ui-renderer.php',
        'class-ajax-layout-renderer'        => 'includes/ajax-render/class-ajax-layout-renderer.php',
        'class-ajax-render-handler'         => 'includes/ajax-render/class-ajax-render-handler.php',
        'class-ajax-controller'             => 'includes/ajax-render/class-ajax-controller.php',
        'ajax-actions'                      => 'includes/ajax/class-ajax-action-controller.php',
        //template-renderers
        'template-renderers'                => 'includes/template-renderers/class-template-hooks.php',
        //functions
        'functions'                         => 'includes/functions.php',
        //maintenance / compatibility
        'updater'                           => 'includes/maintenance/class-updater.php',
        'deprecated-hooks-handler'          => 'includes/deprecated/class-deprecated-hooks-handler.php',
        'dev-hooks'                         => 'dev-tools/dev-hooks.php',
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