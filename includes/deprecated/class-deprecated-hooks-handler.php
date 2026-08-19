<?php
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Deprecated_Hooks_Handler {
    private $deprecated_hooks = [];

    public function __construct(array $deprecated_hooks) {
        $this->deprecated_hooks = $deprecated_hooks;
    }

    public static function register(): void {
        $instance = new self(QAPL_Deprecated_Hooks_List::get_hooks());
        add_action('init', [$instance, 'handle_deprecated_hooks']);
        add_action('admin_notices', [$instance, 'display_admin_notice']);
    }

    public function handle_deprecated_hooks() {
        // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
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
        // phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
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
            // the whole sentence is one string, so the separator after the plugin name can follow
            // the typography rules of each language (French adds a space before the colon, CJK uses a full-width one)
            $notice = sprintf(
                /* translators: %s: plugin name */
                __('%s: Some hooks have been renamed. Please update your code:', 'quick-ajax-post-loader'),
                '<strong>' . esc_html(QAPL_Constants::PLUGIN_NAME) . '</strong>'
            );
            echo '<p>' . wp_kses_post($notice) . '</p>';
            echo '<ul>';
            foreach ($used_hooks as $old_hook => $new_hook) {
                echo '<li><code>' . esc_html($old_hook) . '</code> → <code>' . esc_html($new_hook) . '</code></li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    }
}

final class QAPL_Deprecated_Hooks_List {
    public static function get_hooks(): array {
        return [
            // Filter Wrapper Hooks
            'qapl_filter_wrapper_pre'      => QAPL_Constants::HOOK_FILTER_CONTAINER_BEFORE,
            'qapl_filter_wrapper_open'     => QAPL_Constants::HOOK_FILTER_CONTAINER_START,
            'qapl_filter_wrapper_close'    => QAPL_Constants::HOOK_FILTER_CONTAINER_END,
            'qapl_filter_wrapper_complete' => QAPL_Constants::HOOK_FILTER_CONTAINER_AFTER,

            // Posts Wrapper Hooks
            'qapl_posts_wrapper_pre'      => QAPL_Constants::HOOK_POSTS_CONTAINER_BEFORE,
            'qapl_posts_wrapper_open'     => QAPL_Constants::HOOK_POSTS_CONTAINER_START,
            'qapl_posts_wrapper_close'    => QAPL_Constants::HOOK_POSTS_CONTAINER_END,
            'qapl_posts_wrapper_complete' => QAPL_Constants::HOOK_POSTS_CONTAINER_AFTER,

            // Loader
            'qapl_loader_icon_pre'        => QAPL_Constants::HOOK_LOADER_BEFORE,
            'qapl_loader_icon_complete'   => QAPL_Constants::HOOK_LOADER_AFTER,

            // Filters
            'qapl_modify_query'           => QAPL_Constants::HOOK_MODIFY_POSTS_QUERY_ARGS,
            'qapl_modify_term_buttons'    => QAPL_Constants::HOOK_MODIFY_TAXONOMY_FILTER_BUTTONS,
        ];
    }
}

QAPL_Deprecated_Hooks_Handler::register();
