<?php
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Cleaner {
    private $cleanup_flags;
    private $cleanup_strategies = [];

    public function __construct($cleanup_strategies) {
        $this->cleanup_flags = get_option(QAPL_Constants::DB_OPTION_PLUGIN_CLEANUP_FLAGS, []);
        if (!is_array($this->cleanup_flags)) {
            $this->cleanup_flags = [];
        }

        //add strategies class
        $this->cleanup_strategies = $cleanup_strategies;
    }

    public function purge_unused_data() {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'quick-ajax-post-loader'));
        }

        foreach ($this->cleanup_flags as $version => $status) {
            if ($status && isset($this->cleanup_strategies[$version])) {
                $strategy = $this->cleanup_strategies[$version];
                try {
                    $result = $strategy->run_cleanup();
                    if ($result !== true) {
                        throw new Exception('QAPL_Cleaner: Cleanup for version '.$version.' failed.');
                    }
                    unset($this->cleanup_flags[$version]);
                } catch (Exception $e) {
                    //error_log('QAPL_Cleaner: ' . $e->getMessage());
                    break;
                }
            }
        }
        // save the flag if exists
        QAPL_Update_Validator::save_cleanup_flags();
    }
}

function qapl_action_quick_ajax_handle_purge_unused_data_request() {
    
    // check for required capabilities
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'quick-ajax-post-loader'));
    }
    // verify nonce for security
    if (!isset($_POST['qapl_purge_nonce']) || !wp_verify_nonce(wp_unslash($_POST['qapl_purge_nonce']), QAPL_Constants::NONCE_PURGE_UNUSED_DATA_ACTION)) {
        wp_safe_redirect(admin_url(QAPL_Constants::PLUGIN_MENU_SLUG . '&page=' . QAPL_Constants::SETTINGS_PAGE_SLUG . '&tab=clear_old_data&status=invalid_nonce'));
        exit;
    }
    // require the explicit confirmation checkbox before purging
    if (empty($_POST[QAPL_Constants::REMOVE_OLD_DATA_FIELD])) {
        wp_safe_redirect(admin_url(QAPL_Constants::PLUGIN_MENU_SLUG . '&page=' . QAPL_Constants::SETTINGS_PAGE_SLUG . '&tab=clear_old_data&status=not_confirmed'));
        exit;
    }
    // check the hidden input value
    if (!isset($_POST['qapl_purge_unused_data']) || sanitize_text_field(wp_unslash($_POST['qapl_purge_unused_data'])) !== '1') {
        wp_safe_redirect(admin_url(QAPL_Constants::PLUGIN_MENU_SLUG . '&page=' . QAPL_Constants::SETTINGS_PAGE_SLUG . '&tab=clear_old_data&status=invalid_request'));
        exit;
    }
    // initialize cleanup strategies
    $cleanup_strategies = array(
        '1.3.2' => new QAPL_Cleanup_Version_1_3_2(),
        '1.3.3' => new QAPL_Cleanup_Version_1_3_3()
    );
    // create cleaner instance and perform cleanup
    $cleaner = new QAPL_Cleaner($cleanup_strategies);
    $cleaner->purge_unused_data();

    // redirect to success page
    wp_safe_redirect(admin_url(QAPL_Constants::PLUGIN_MENU_SLUG . '&page=' . QAPL_Constants::SETTINGS_PAGE_SLUG . '&tab=clear_old_data&status=success'));
    exit;
}
function qapl_action_quick_ajax_display_purge_notice() {
    if (!current_user_can('manage_options')) {
        return;
    }
    // only on the plugin settings page
    if (filter_input(INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== QAPL_Constants::SETTINGS_PAGE_SLUG) {
        return;
    }
    $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (!$status) {
        return;
    }
    // whitelist: known status => [css type, message]; raw value is never echoed
    $notices = [
        'success'         => ['success', __('Unused data has been purged successfully.', 'quick-ajax-post-loader')],
        'not_confirmed'   => ['warning', __('Please confirm the purge by selecting the checkbox before submitting.', 'quick-ajax-post-loader')],
        'invalid_nonce'   => ['error',   __('Security check failed. Please reload the page and try again.', 'quick-ajax-post-loader')],
        'invalid_request' => ['error',   __('Invalid request. The purge was not performed.', 'quick-ajax-post-loader')],
    ];
    if (!isset($notices[$status])) {
        return;
    }
    [$type, $message] = $notices[$status];
    printf('<div class="notice notice-%s is-dismissible"><p>%s</p></div>', esc_attr($type), esc_html($message));
}




final class QAPL_Data_Cleaner {
    public static function remove_old_meta_for_all_posts($post_type, $meta_key_to_remove) {
        // phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- one time migration during plugin update
        $args = array(
            'post_type'      => $post_type,
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => $meta_key_to_remove,
                    'compare' => 'EXISTS'
                )
            )
        );
        $query = new WP_Query($args);
        // phpcs:enable WordPress.DB.SlowDBQuery.slow_db_query_meta_query
        $success = true;

        if ($query->have_posts()) {
            foreach ($query->posts as $post) {
                $meta_exists = get_post_meta($post->ID, $meta_key_to_remove, true);
                if ($meta_exists !== '') {
                    $deleted = delete_post_meta($post->ID, $meta_key_to_remove);
                    if ($deleted === false) {
                        //error_log('QAPL_Data_Cleaner: Failed to delete post meta for post ID ' . $post->ID . ' with key ' . $meta_key_to_remove);
                        $success = false;
                    }
                }
            }
        }
        wp_reset_postdata();
        return $success;
    }
}

interface QAPL_Data_Clean_Interface {
    public function run_cleanup(): bool;
}

final class QAPL_Cleanup_Version_1_3_2 implements QAPL_Data_Clean_Interface {
    public function run_cleanup(): bool {
        $results = [];
        $results[] = QAPL_Data_Cleaner::remove_old_meta_for_all_posts(QAPL_Constants::CPT_SHORTCODE_SLUG, 'qapl_quick_ajax_meta_box_shortcode_shortcode');
        $results[] = QAPL_Data_Cleaner::remove_old_meta_for_all_posts(QAPL_Constants::CPT_SHORTCODE_SLUG, 'qapl_settings_wrapper');
        return QAPL_Update_Validator::check_result_array_if_all_true($results);
    }
}

final class QAPL_Cleanup_Version_1_3_3 implements QAPL_Data_Clean_Interface {
    public function run_cleanup(): bool {
        $results = [];
        $deleted = delete_option('qapl-global-options');
        if ($deleted === false) {
            //error_log('QAPL Cleaner: Failed to delete the option "qapl-global-options" due to an unexpected error.');
            $results[] = false; 
        }
        $results[] = QAPL_Data_Cleaner::remove_old_meta_for_all_posts(QAPL_Constants::CPT_SHORTCODE_SLUG, 'qapl_quick_ajax_shortcode_settings');
        $results[] = QAPL_Data_Cleaner::remove_old_meta_for_all_posts(QAPL_Constants::CPT_SHORTCODE_SLUG, 'qapl_quick_ajax_shortcode_code');
        return QAPL_Update_Validator::check_result_array_if_all_true($results);
    }
}