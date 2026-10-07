<?php
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Deprecated_Hooks_List {
    // old name => new name, split by type - an action and a filter need a different bridge
    public static function get_actions(): array {
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
        ];
    }

    public static function get_filters(): array {
        return [
            'qapl_modify_query'           => QAPL_Constants::HOOK_MODIFY_POSTS_QUERY_ARGS,
            'qapl_modify_term_buttons'    => QAPL_Constants::HOOK_MODIFY_TAXONOMY_FILTER_BUTTONS,
        ];
    }

    // all hooks together, used by the admin notice
    public static function get_hooks(): array {
        return array_merge(self::get_actions(), self::get_filters());
    }
}
