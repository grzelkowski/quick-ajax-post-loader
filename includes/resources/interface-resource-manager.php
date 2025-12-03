<?php 
if (!defined('ABSPATH')) {
    exit;
}

interface QAPL_Resource_Manager_Interface {
    public function initialize_components();
    public function initialize_pages();
}