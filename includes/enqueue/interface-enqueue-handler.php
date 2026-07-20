<?php 
if (!defined('ABSPATH')) {
    exit;
}

interface QAPL_Enqueue_Handler_Interface {
    public function enqueue_frontend_styles_and_scripts(): void;
    public function enqueue_admin_styles_and_scripts(): void;
    public function register_hooks(): void;
}