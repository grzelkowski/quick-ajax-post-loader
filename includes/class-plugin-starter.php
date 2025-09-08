<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Quick_Ajax_Plugin_Starter{
    private QAPL_Quick_Ajax_Resource_Manager_Interface $resources;
    private QAPL_Quick_Ajax_Enqueue_Handler_Interface $enqueue;

    public function __construct(QAPL_Quick_Ajax_Resource_Manager_Interface $resources, QAPL_Quick_Ajax_Enqueue_Handler_Interface $enqueue){
        $this->resources=$resources; //set deps
        $this->enqueue=$enqueue; //set deps
    }
    private function verify_classes(): bool {
        if (!class_exists('QAPL_Quick_Ajax_Utilities')) {
            if (class_exists('QAPL_Quick_Ajax_Logger')) {
                QAPL_Quick_Ajax_Logger::log('Missing class: QAPL_Quick_Ajax_Utilities', 'warning');
            }
            return true;
        }
        $classes = [
                'QAPL_Quick_Ajax_Form_Field',
                'QAPL_Quick_Ajax_Form_Field_Builder',
                'QAPL_Quick_Ajax_Form_Field_Factory',
                'QAPL_Quick_Ajax_Shortcode_Generator',
                'QAPL_Shortcode_Params_Handler',
                'QAPL_Shortcode_Post_Meta_Handler',
                'QAPL_Shortcode_Query_Args_Provider',
                'QAPL_Shortcode_Ajax_Attributes_Provider',
                'QAPL_Quick_Ajax_Shortcode',
                'QAPL_Quick_Ajax_Handler',
        ];        
        $verify = QAPL_Quick_Ajax_Utilities::verify_classes_exist($classes, 'Plugin_Starter');
        return $verify;
    }

    public function start():void{
        $this->resources->initialize_components(); //init components
        $this->resources->initialize_pages(); //init admin pages
        if ($this->verify_classes() == false) {
            return;
        }
        $this->enqueue->register_hooks(); //enqueue scripts/styles
    }
}