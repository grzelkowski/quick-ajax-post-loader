<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Plugin_Starter{
    private QAPL_Resource_Manager_Interface $resources;
    private QAPL_Enqueue_Handler_Interface $enqueue;

    public function __construct(QAPL_Resource_Manager_Interface $resources, QAPL_Enqueue_Handler_Interface $enqueue){
        $this->resources=$resources; //set deps
        $this->enqueue=$enqueue; //set deps
    }
    private function verify_classes(): void {
        if (!class_exists('QAPL_Utilities')) {
            qapl_log('Missing class: QAPL_Utilities', 'warning');
        }
        
        $classes = [
                'QAPL_Constants',
                'QAPL_Initializer',
                'QAPL_File_Manager',
                'QAPL_Resource_Manager',
                'QAPL_Enqueue_Handler',
                'QAPL_Form_Field',
                'QAPL_Form_Field_Builder',
                'QAPL_Form_Field_Factory',
                'QAPL_Form_Content_Builder',
                'QAPL_Shortcode_Attributes_Provider',
                'QAPL_Shortcode_Params_Handler',
                'QAPL_Shortcode_Post_Meta_Handler',
                'QAPL_Shortcode_Query_Args_Provider',
                'QAPL_Shortcode',
                'QAPL_Shortcode_Generator',
                'QAPL_Ajax_Frontend_Render',
                'QAPL_Ajax_Admin_Controller',
                'QAPL_Ajax_Frontend_Controller',
                'QAPL_Controller_Registry',
                'QAPL_Template_Config',
                'QAPL_Template_Base',
                'QAPL_Template_Post_Item',
                'QAPL_Template_Post_Item_Qapl_Full_Background_Image',
                'QAPL_Template_Load_More_Button',
                'QAPL_Template_End_Post_Message',
                'QAPL_Template_No_Post_Message',
                'QAPL_Template_Empty_Filters',
                'QAPL_Post_Template_Factory',
                'QAPL_Post_Template_Context',
                'QAPL_Admin_Menu',
                'QAPL_CPT_Editor_Form',
                'QAPL_Creator_Post_Type',
                'QAPL_Creator_Columns',
                'QAPL_CPT_Creator_Form',
                'QAPL_Creator_Shortcode_Box',
                'QAPL_Creator_Editor',
                'QAPL_Admin_Options_Page_Form',
                'QAPL_Settings_Tab_Options',
                'QAPL_Settings_Tab_PHP_Snippet',
                'QAPL_Settings_Tab_Help',
                'QAPL_Settings_Tab_Cleanup',
                'QAPL_Settings_Page',
                'QAPL_Updater',
                'QAPL_Data_Migrator',
                'QAPL_Update_Validator',
                'QAPL_Cleaner',
                'QAPL_Data_Cleaner',
        ];        
        QAPL_Utilities::verify_classes_exist($classes, 'Plugin_Starter');
    }

    public function start():void{
        $this->resources->initialize_components(); //init components
        $this->resources->initialize_pages(); //init admin pages
        $this->verify_classes();
        $this->enqueue->register_hooks(); //enqueue scripts/styles
        QAPL_Initializer::maybe_run_tests();
    }
}