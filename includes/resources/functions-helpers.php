<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('qapl_log')) {
    function qapl_log(string $message, string $level = 'info'): void {
        if (class_exists('QAPL_Quick_Ajax_Logger')) {
            QAPL_Quick_Ajax_Logger::log($message, $level);
        }
    }
}
if (!function_exists('qapl_log_classes')) {
    function qapl_log_classes(): void {
        if (class_exists('QAPL_Quick_Ajax_Logger')) {
            QAPL_Quick_Ajax_Logger::log_qapl_classes();
        }
    }
}
