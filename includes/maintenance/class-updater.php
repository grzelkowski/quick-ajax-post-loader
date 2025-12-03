<?php
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Updater {
    private $current_version;
    private $new_version;
    private $update_strategies = array();

    public function __construct(array $update_strategies) {
        $this->current_version = get_option(QAPL_Constants::DB_OPTION_PLUGIN_VERSION);
        $this->new_version = QAPL_Constants::PLUGIN_VERSION;
        QAPL_Update_Validator::init(); // initialise flags
        $this->update_strategies = $update_strategies; // save the given update classes to the class property
    }
    public function run_all_updates() {
        $this->check_and_downgrade_version();

        foreach ($this->update_strategies as $version => $update_class) {
            if (version_compare($this->current_version, $version, '<')) {
                $this->run_single_update($version, $update_class);
            }
        }       
                
        // Save cleanup flags and update version
        $this->finalise_updates();
    }
    private function run_single_update(string $version, QAPL_Update_Interface $update_class) {
        try {
            $result = $update_class->run_update();
            if ($result === true) {
                $this->current_version = $version; // Update current version after successful update
                //error_log('QAPL Updater: Successfully updated to version ' . $version);
            } else {
                throw new Exception('Update returned false.');
            }
        } catch (Exception $e) {
            //error_log('QAPL Updater: Failed to update to version ' . $version . ' - ' . $e->getMessage());
        }
    }
    private function finalise_updates() {
        QAPL_Update_Validator::save_cleanup_flags();
        update_option(QAPL_Constants::DB_OPTION_PLUGIN_VERSION, $this->new_version);
    }
    private function check_and_downgrade_version(): bool {
        $stored_version = get_option(QAPL_Constants::DB_OPTION_PLUGIN_VERSION);
        if ($stored_version === false) {
            add_option(QAPL_Constants::DB_OPTION_PLUGIN_VERSION, $this->new_version, '', 'off');
            //error_log('QAPL Updater: Version record created.');
            return true;
        }
        if (version_compare($stored_version, $this->new_version, '>')) {
            update_option(QAPL_Constants::DB_OPTION_PLUGIN_VERSION, $this->new_version);
            //error_log('QAPL Updater: Version downgraded successfully.');
            return true;
        }
        return false;
    }
}
// Register the update strategies and execute updates
add_action('init', 'qapl_action_quick_ajax_check_version_and_run_updates');
function qapl_action_quick_ajax_check_version_and_run_updates() {
    $current_version = get_option(QAPL_Constants::DB_OPTION_PLUGIN_VERSION);
    $plugin_version = QAPL_Constants::PLUGIN_VERSION;
    if ($current_version !== $plugin_version) {
        // register update strategies and send them to the constructor
        $update_strategies = array(
            '1.3.2' => new QAPL_Update_Version_1_3_2(),
            '1.3.3' => new QAPL_Update_Version_1_3_3(),
            '1.3.4' => new QAPL_Update_Version_1_3_4(),
            '1.7.4' => new QAPL_Update_Version_1_7_4()
        );
        $updater = new QAPL_Updater($update_strategies);
        $updater->run_all_updates();
    }
}

interface QAPL_Update_Interface {
    public function run_update(): bool;
}

class QAPL_Update_Version_1_3_2 implements QAPL_Update_Interface {
    public function run_update(): bool {
        $results = array();
        $results[] = QAPL_Data_Migrator::migrate_meta_for_all_posts(QAPL_Constants::CPT_SHORTCODE_SLUG, 'qapl_quick_ajax_meta_box_shortcode_shortcode', 'qapl_quick_ajax_shortcode_code');
        $results[] = QAPL_Data_Migrator::migrate_meta_for_all_posts(QAPL_Constants::CPT_SHORTCODE_SLUG, 'qapl_settings_wrapper' , 'qapl_quick_ajax_shortcode_settings');
        return QAPL_Update_Validator::check_migration_results($results, '1.3.2');
    }
}

class QAPL_Update_Version_1_3_3 implements QAPL_Update_Interface {
    public function run_update(): bool {
        $results = array();
        $results[] = QAPL_Data_Migrator::migrate_option('qapl-global-options', QAPL_Constants::GLOBAL_OPTIONS_NAME);
        $results[] = QAPL_Data_Migrator::migrate_meta_for_all_posts(QAPL_Constants::CPT_SHORTCODE_SLUG, 'qapl_quick_ajax_shortcode_settings', QAPL_Constants::DB_POSTMETA_SHORTCODE_SETTINGS);
        $return = QAPL_Update_Validator::check_migration_results($results, '1.3.3');
        return $return;
    }    
}
class QAPL_Update_Version_1_3_4 implements QAPL_Update_Interface {
    public function run_update(): bool {
        $results = array();
        $results[] = QAPL_Data_Migrator::update_autoload_for_option(QAPL_Constants::GLOBAL_OPTIONS_NAME,'off');
        $results[] = QAPL_Data_Migrator::update_autoload_for_option(QAPL_Constants::DB_OPTION_PLUGIN_VERSION,'off');
        $return = QAPL_Update_Validator::check_migration_results($results, '1.3.4');
        return $return;
    }    
}
class QAPL_Update_Version_1_7_4 implements QAPL_Update_Interface {
    // fix wrong label assignment for title ASC and DESC sort options due to naming issue
    public function run_update(): bool {
        $option_name = QAPL_Constants::GLOBAL_OPTIONS_NAME;
        $options = get_option($option_name, array());
        if (!is_array($options)) {
            return true; // nothing to update
        }
        $asc_key  = 'sort_option_title_asc_label';
        $desc_key = 'sort_option_title_desc_label';
        if (isset($options[$asc_key], $options[$desc_key])) {
            $temp = $options[$asc_key];
            $options[$asc_key]  = $options[$desc_key];
            $options[$desc_key] = $temp;
            update_option($option_name, $options);
        }
        return true;
    }
}




class QAPL_Data_Migrator {
    public static function migrate_option($old_key, $new_key, $autoload = 'auto') {
        // get the old option value
        $old_value = get_option($old_key);
        $return = 1; // no changes
        // if the old option does not exist, return "no changes"
        if (!empty($old_value)) {
            $return = 3; // no changes, old record exists

            // check if the new option already exists
            $new_value = get_option($new_key);
            if ($new_value === false || empty($new_value)) {
                // new option does not exist, migrate the value
                $added = add_option($new_key, $old_value, '', $autoload);
                if (!$added) {
                    // log the failure and return 0
                    //error_log('QAPL_Data_Migrator: Failed to migrate option from ' . $old_key . ' to ' . $new_key);
                    $return = 0; // migration failed
                }
                $return = 2; // migrated successfully
            }
        }
        return $return;
    }

    public static function migrate_meta_for_all_posts($post_type, $old_key, $new_key) {
        $args = array(
            'post_type'      => $post_type,
            'posts_per_page' => -1, // get all posts
            'meta_query'     => array(
                array(
                    'key'     => $old_key, // only get posts with the old meta key
                    'compare' => 'EXISTS'
                )
            )
        );
        $query = new WP_Query($args);
        $migrated = array();
        if ($query->have_posts()) {
            foreach ($query->posts as $post) {
                // Migrate meta for each post
                $migrate = self::migrate_single_post_meta($post->ID, $old_key, $new_key);
                $migrated[] = $migrate;
                if ($migrate === 0) {
                    //error_log('QAPL_Data_Migrator: Failed to migrate meta for post ID ' . $post->ID . ' from ' . $old_key . ' to ' . $new_key);
                }
            }
        }
        wp_reset_postdata();
        if (in_array(0, $migrated)) {
            $return = 0; // 0 failed
        } elseif (in_array(2, $migrated)) {
            $return = 2; // 2 migrated 
        } elseif (in_array(3, $migrated)) {
            $return = 3; // no changes, old record exists
        }else{
            $return = 1; // 1 no changes
        }
        return $return;
    }

    public static function migrate_single_post_meta($post_id, $old_key, $new_key) {
        // check if new meta key already exists
        $existing_data = sanitize_text_field(get_post_meta($post_id, $new_key, true));
        // get existing data from the old meta key (sanitize it for safety)
        $old_data = sanitize_text_field(get_post_meta($post_id, $old_key, true));
        $return = 1;
        if ($old_data !== '') {
            $return = 3; // old key still exists
            if(empty($existing_data)){                
                $updated = update_post_meta($post_id, $new_key, $old_data);
                if ($updated === false) {
                    //error_log('QAPL_Data_Migrator: Failed to update post meta for post ID ' . $post_id . ' with key ' . $new_key);
                    $return = 0; // migration failed
                }else{
                    $return = 2; // migrated successfully
                }                
            }
        }
        // 0 failed
        // 1 success, no changes, no records to remove
        // 2 migrated, old record exists
        // 3 no changes, old record exists
        return $return;
    }
    public static function update_autoload_for_option($option_name, $autoload = 'auto') {
        $existing_option = get_option($option_name, false);
        if ($existing_option === false) {
            return 1;
        }
        $return = 1;
        global $wpdb;
        $updated = $wpdb->update(
            $wpdb->options,
            array( 'autoload' => $autoload ), // set autoload to the new value
            array( 'option_name' => $option_name ),
            array( '%s' ),
            array( '%s' )
        );
        if ( $updated === false ) {
            $return = 0; 
        }
        wp_cache_delete($option_name, 'options');
        // 0 failed
        // 1 success, no records to remove
        return $return;
    }
}

class QAPL_Update_Validator {
    private static $cleanup_flags = array();

    public static function init() {
        $flags = get_option(QAPL_Constants::DB_OPTION_PLUGIN_CLEANUP_FLAGS, array());
        if (is_array($flags)) {
            self::$cleanup_flags = array_map('boolval', $flags);
        }else {
            self::$cleanup_flags = array(); // ensure it's always an array
        }
    }

    public static function check_migration_results(array $results, string $version_flag): bool {
        // 0 failed
        // 1 success, no changes, no records to remove
        // 2 migrated, old record exists
        // 3 no changes, old record exists
        //error_log('migration ' . json_encode($version_flag)); 
        // if result == 3 no changes, old record exists
        if (in_array(3, $results, true)) {
            //error_log('result == no changes, old record exists ' . $version_flag);
            self::$cleanup_flags[$version_flag] = true;
        }
        // if result == 2 migration successful return 'true'
        if (in_array(2, $results, true)) {
            //error_log('result == 2 migration successful ' . $version_flag);
            self::$cleanup_flags[$version_flag] = true;
        }

        // if return == 0, failed return 'false'
        if (in_array(0, $results, true)) {
            //error_log('return == 0, failed return ' . $version_flag);
            return false;
        }
        //error_log('1 return true / no migration ' . $version_flag);
        //if 1 return true / no migration, no records to remove, no flag
        return true;
    }
    public static function check_result_array_if_all_true(array $results): bool {
        foreach ($results as $value) {
            if (!$value) {
                return false; // Return false immediately if any value is not true
            }
        }
        return true; // Return true if all values are true
    }
    public static function get_cleanup_flags(): array {
        return self::$cleanup_flags;
    }
    public static function set_cleanup_flag(string $version_flag) {
        self::$cleanup_flags[$version_flag] = true;
    }
    public static function save_cleanup_flags() {
       self::$cleanup_flags = array_map('boolval', self::$cleanup_flags);
       //error_log('QAPL_Cleaner: cleanup ' . json_encode(self::$cleanup_flags));
        if (empty(self::$cleanup_flags)) {
            delete_option(QAPL_Constants::DB_OPTION_PLUGIN_CLEANUP_FLAGS);
        } else {
            if (get_option(QAPL_Constants::DB_OPTION_PLUGIN_CLEANUP_FLAGS) === false) {
                add_option(QAPL_Constants::DB_OPTION_PLUGIN_CLEANUP_FLAGS, self::$cleanup_flags, '', 'off');
            } else {
                update_option(QAPL_Constants::DB_OPTION_PLUGIN_CLEANUP_FLAGS, self::$cleanup_flags);
            }
        }
    }

}

/* CLEAN */
if (!class_exists('QAPL_Cleaner')) {
    class QAPL_Cleaner {
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
            wp_redirect(admin_url('admin.php?page=qapl-settings'));
            exit;
        }
    }
    add_action('admin_post_qapl_purge_unused_data', 'qapl_action_quick_ajax_handle_purge_unused_data_request');
    function qapl_action_quick_ajax_handle_purge_unused_data_request() {
        
        // check for required capabilities
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'quick-ajax-post-loader'));
        }
        // verify nonce for security
        if (!isset($_POST['qapl_purge_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['qapl_purge_nonce'])), 'qapl_purge_unused_data')) {
            wp_redirect(admin_url('admin.php?page=qapl-settings&tab=clear_old_data&status=invalid_nonce'));
            exit;
        }
        // check the hidden input value
        if (!isset($_POST['qapl_purge_unused_data']) || sanitize_text_field(wp_unslash($_POST['qapl_purge_unused_data'])) !== '1') {
            wp_redirect(admin_url('admin.php?page=qapl-settings&tab=clear_old_data&status=invalid_request'));
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
        wp_redirect(admin_url('admin.php?page=qapl-settings&tab=clear_old_data&status=success'));
        exit;
    }
    
}

class QAPL_Data_Cleaner {
    public static function remove_old_meta_for_all_posts($post_type, $meta_key_to_remove) {
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

class QAPL_Cleanup_Version_1_3_2 implements QAPL_Data_Clean_Interface {
    public function run_cleanup(): bool {
        $results = [];
        $results[] = QAPL_Data_Cleaner::remove_old_meta_for_all_posts(QAPL_Constants::CPT_SHORTCODE_SLUG, 'qapl_quick_ajax_meta_box_shortcode_shortcode');
        $results[] = QAPL_Data_Cleaner::remove_old_meta_for_all_posts(QAPL_Constants::CPT_SHORTCODE_SLUG, 'qapl_settings_wrapper');
        return QAPL_Update_Validator::check_result_array_if_all_true($results);
    }
}

class QAPL_Cleanup_Version_1_3_3 implements QAPL_Data_Clean_Interface {
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