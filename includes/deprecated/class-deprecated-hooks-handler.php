<?php
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Deprecated_Hooks_Handler {
    private $deprecated_actions = [];
    private $deprecated_filters = [];
    private $deprecated_hooks = [];

    public function __construct(array $deprecated_actions, array $deprecated_filters) {
        $this->deprecated_actions = $deprecated_actions;
        $this->deprecated_filters = $deprecated_filters;
        $this->deprecated_hooks = array_merge($deprecated_actions, $deprecated_filters);
    }

    public static function register(): void {
        $instance = new self(QAPL_Deprecated_Hooks_List::get_actions(), QAPL_Deprecated_Hooks_List::get_filters());
        add_action('init', [$instance, 'handle_deprecated_hooks']);
        add_action('admin_notices', [$instance, 'display_admin_notice']);
    }

    // the plugin fires only the new names, so the bridge listens on the new hook
    // and passes the call on to the old one, where the user's code is still attached
    public function handle_deprecated_hooks() {
        // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
        foreach ($this->deprecated_actions as $old_hook => $new_hook) {
            if (has_action($old_hook)) {
                add_action($new_hook, function (...$args) use ($old_hook) {
                    do_action($old_hook, ...$args);
                }, 10, 99);
            }
        }
        foreach ($this->deprecated_filters as $old_hook => $new_hook) {
            if (has_filter($old_hook)) {
                add_filter($new_hook, function ($value, ...$args) use ($old_hook) {
                    return apply_filters($old_hook, $value, ...$args);
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
