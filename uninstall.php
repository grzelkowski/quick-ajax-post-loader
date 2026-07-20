<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

//uninstall.php runs WITHOUT the plugin loaded - constants must be required manually
if (!class_exists('QAPL_Constants')) {
    require_once __DIR__ . '/includes/resources/class-constants.php';
}

//respect the user's choice: remove data only when explicitly enabled in settings
$qapl_options = get_option(QAPL_Constants::GLOBAL_OPTIONS_NAME);
if (!is_array($qapl_options) || empty($qapl_options['delete_data_on_uninstall'])) {
    return; // keep all data for a possible reinstall
}

//remove plugin options
delete_option(QAPL_Constants::GLOBAL_OPTIONS_NAME);
delete_option(QAPL_Constants::DB_OPTION_PLUGIN_VERSION);
delete_option(QAPL_Constants::DB_OPTION_PLUGIN_CLEANUP_FLAGS);
delete_option('qapl-global-options'); // legacy key (pre-1.3.3)

//remove shortcode CPT posts with their postmeta (wp_delete_post removes postmeta automatically)
$qapl_post_ids = get_posts([
    'post_type'   => QAPL_Constants::CPT_SHORTCODE_SLUG,
    'post_status' => array_keys(get_post_stati()), // include trashed, drafts, etc.
    'numberposts' => -1,
    'fields'      => 'ids',
]);
foreach ($qapl_post_ids as $qapl_post_id) {
    wp_delete_post($qapl_post_id, true); // true = bypass trash, delete permanently
}
