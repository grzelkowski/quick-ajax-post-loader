<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Quick_Ajax_Logger {
    public static function log(string $message, string $level = 'info'): void {
    if (!defined('WP_DEBUG') || WP_DEBUG !== true || !defined('WP_DEBUG_LOG') || WP_DEBUG_LOG !== true) {
        return;
    }
        $prefix = strtoupper($level);
        error_log("Quick Ajax Post Loader [".$prefix."]: ".$message);
    }
    public static function backtrace(){
        if (!defined('WP_DEBUG') || WP_DEBUG !== true) {
        return;
    }
        error_log(print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), true));
    }
}