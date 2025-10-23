<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Ajax_Render_Handler{
    private $file_manager;
    private $ui_renderer;
    private $layout_renderer;
    private $global_options;

    public function __construct() {
        // get global options once
        $this->global_options = get_option(QAPL_Quick_Ajax_Constants::GLOBAL_OPTIONS_NAME, []);

        // initialize dependencies
        $this->file_manager   = new QAPL_Quick_Ajax_File_Manager();
        $this->ui_renderer    = new QAPL_Ajax_UI_Renderer($this->file_manager, $this->global_options);
        $this->layout_renderer = new QAPL_Ajax_Layout_Renderer($this->file_manager, $this->ui_renderer);
    }

    // example public getter methods if you need them later
    public function get_ui_renderer() {
        return $this->ui_renderer;
    }

    public function get_layout_renderer() {
        return $this->layout_renderer;
    }

    public function get_file_manager() {
        return $this->file_manager;
    }

    public function get_global_options() {
        return $this->global_options;
    }
}