<?php 
if (!defined('ABSPATH')) {
    exit;
}

interface QAPL_Quick_Ajax_Enqueue_Handler_Interface {
    public function enqueue_frontend_styles_and_scripts();
    public function enqueue_admin_styles_and_scripts();
    public function register_hooks();
}