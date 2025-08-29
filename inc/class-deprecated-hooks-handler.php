<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('QAPL_Deprecated_Hooks_Handler')) {
    class QAPL_Deprecated_Hooks_Handler {
        private $deprecated_hooks = [];

        public function __construct(array $deprecated_hooks) {
            $this->deprecated_hooks = $deprecated_hooks;

            add_action('init', [$this, 'handle_deprecated_hooks']);
            add_action('admin_notices', [$this, 'display_admin_notice']);
        }

        public function handle_deprecated_hooks() {
            foreach ($this->deprecated_hooks as $old_hook => $new_hook) {
                // Check for deprecated actions
                if (has_action($old_hook)) {
                    add_action($old_hook, function (...$args) use ($new_hook) {
                        do_action($new_hook, ...$args);
                    }, 10, 99);
                }

                // Check for deprecated filters
                if (has_filter($old_hook)) {
                    add_filter($old_hook, function ($value, ...$args) use ($new_hook) {
                        return apply_filters($new_hook, $value, ...$args);
                    }, 10, 99);
                }
            }
        }

        public function display_admin_notice() {
            if (!current_user_can('manage_options')) {
                return;
            }

            $used_hooks = array_filter($this->deprecated_hooks, function ($old_hook) {
                return has_action($old_hook) || has_filter($old_hook);
            }, ARRAY_FILTER_USE_KEY);

            if (!empty($used_hooks)) {
                echo '<div class="notice notice-warning">';
                echo '<p><strong>Quick AJAX Post Loader:</strong> Some hooks have been renamed. Please update your code:</p>';
                echo '<ul>';
                foreach ($used_hooks as $old_hook => $new_hook) {
                    echo '<li><code>' . esc_html($old_hook) . '</code> → <code>' . esc_html($new_hook) . '</code></li>';
                }
                echo '</ul>';
                echo '</div>';
            }
        }
    }
}

// Example of instantiating the class with the list of deprecated hooks
$deprecated_hooks = [
    // Filter Wrapper Hooks
    'qapl_filter_wrapper_pre' => QAPL_Quick_Ajax_Plugin_Constants::HOOK_FILTER_CONTAINER_BEFORE,
    'qapl_filter_wrapper_open' => QAPL_Quick_Ajax_Plugin_Constants::HOOK_FILTER_CONTAINER_START,
    'qapl_filter_wrapper_close' => QAPL_Quick_Ajax_Plugin_Constants::HOOK_FILTER_CONTAINER_END,
    'qapl_filter_wrapper_complete' => QAPL_Quick_Ajax_Plugin_Constants::HOOK_FILTER_CONTAINER_AFTER,

    // Posts Wrapper Hooks
    'qapl_posts_wrapper_pre' => QAPL_Quick_Ajax_Plugin_Constants::HOOK_POSTS_CONTAINER_BEFORE,
    'qapl_posts_wrapper_open' => QAPL_Quick_Ajax_Plugin_Constants::HOOK_POSTS_CONTAINER_START,
    'qapl_posts_wrapper_close' => QAPL_Quick_Ajax_Plugin_Constants::HOOK_POSTS_CONTAINER_END,
    'qapl_posts_wrapper_complete' => QAPL_Quick_Ajax_Plugin_Constants::HOOK_POSTS_CONTAINER_AFTER,

    // Load More Button Hooks
    //'qapl_load_more_button_pre' => QAPL_Quick_Ajax_Plugin_Constants::HOOK_LOAD_MORE_BEFORE,
    //'qapl_load_more_button_complete' => QAPL_Quick_Ajax_Plugin_Constants::HOOK_LOAD_MORE_AFTER,

    // Loader Icon Hooks
    'qapl_loader_icon_pre' => QAPL_Quick_Ajax_Plugin_Constants::HOOK_LOADER_BEFORE,
    'qapl_loader_icon_complete' => QAPL_Quick_Ajax_Plugin_Constants::HOOK_LOADER_AFTER,

    // Filters
    'qapl_modify_query' => QAPL_Quick_Ajax_Plugin_Constants::HOOK_MODIFY_POSTS_QUERY_ARGS,
    'qapl_modify_term_buttons' => QAPL_Quick_Ajax_Plugin_Constants::HOOK_MODIFY_TAXONOMY_FILTER_BUTTONS
];

new QAPL_Deprecated_Hooks_Handler($deprecated_hooks);