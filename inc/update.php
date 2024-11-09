<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('QAPL_Quick_Ajax_Plugin_Update')) {
class QAPL_Quick_Ajax_Plugin_Update {    
    public function __construct() {
        add_action('admin_init', array($this, 'check_update'));
        add_filter('site_transient_update_plugins', array($this, 'add_custom_update_notification'));
        add_filter('plugin_row_meta', array($this, 'plugin_row_meta'), 10, 2);
    }

    public function check_update() {
        $cache_key = 'qapl_quick_ajax_post_loader_latest_version';
        $latest_version = get_transient($cache_key);

        $plugin_info = QAPL_Quick_Ajax_Helper::get_plugin_info();
        $current_version = $plugin_info['version'];
        $plugin_slug = $plugin_info['slug'];

        if (!$current_version) {
            error_log('Quick Ajax Post Loader: Current version not found.');
            return;
        }

        if (!$latest_version) {
            $repository_url = 'https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&request[slug]=' . urlencode($plugin_slug);
            $response = wp_remote_get($repository_url, ['httpversion' => '1.1']);

            if (is_wp_error($response)) {
                error_log('Quick Ajax Post Loader: Failed to get update information.');
                return;
            }

            $plugin_data = json_decode(wp_remote_retrieve_body($response), true);
            if (isset($plugin_data['version'])) {
                $latest_version = $plugin_data['version'];
                set_transient($cache_key, $latest_version, 12 * HOUR_IN_SECONDS);
            } else {
                error_log('Quick Ajax Post Loader: Unable to parse the plugin version from the response.');
                return;
            }
        }

        if ($latest_version && $current_version && version_compare($latest_version, $current_version, '>')) {
            update_option('qapl_quick_ajax_post_loader_update_available', $latest_version);
        }
    }

    public function add_custom_update_notification($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }

        $plugin_info = QAPL_Quick_Ajax_Helper::get_plugin_info();
        $current_version = $plugin_info['version'];
        $plugin_slug = $plugin_info['slug'];
        $plugin_basename = $plugin_slug . '/' . $plugin_slug . '.php';

        if (!$current_version) {
            error_log('Quick Ajax Post Loader: Current version is null in update check.');
            return $transient;
        }

        $update_version = get_option('qapl_quick_ajax_post_loader_update_available');
        
        if ($update_version && version_compare($update_version, $current_version, '>')) {
            $obj = $this->prepare_update_object($plugin_info, $plugin_basename, $update_version);
            $transient->response[$plugin_basename] = $obj;
        }

        return $transient;
    }

    private function prepare_update_object($plugin_info, $plugin_basename, $update_version) {
        $plugin_slug = $plugin_info['slug'];
        $download_url = 'https://downloads.wordpress.org/plugin/' . $plugin_slug . '.' . $update_version . '.zip';

        $obj = new stdClass();
        $obj->id = 'wordpress.org/plugins/' . $plugin_slug;
        $obj->slug = $plugin_slug;
        $obj->plugin = $plugin_basename;
        $obj->new_version = $update_version;
        $obj->url = $plugin_info['repository_url'];
        $obj->package = $download_url;
        $obj->requires = $plugin_info['minimum_wp_version'];
        $obj->tested = $plugin_info['tested_wp_version'];
        $obj->requires_php = $plugin_info['minimum_php_version'];

        return $obj;
    }

    public function plugin_row_meta($links, $file) {
        $plugin_info = QAPL_Quick_Ajax_Helper::get_plugin_info();
        $plugin_slug = $plugin_info['slug'];
        if ($file == $plugin_slug . '/' . $plugin_slug . '.php') {
            $links[] = '<a href="' . esc_url($plugin_info['repository_url']) . '" target="_blank">View details</a>';
        }
        return $links;
    }
}

new QAPL_Quick_Ajax_Plugin_Update();
}