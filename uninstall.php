<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}
delete_option('quick-ajax-global-options');
delete_option('quick_ajax_post_loader_update_available');
