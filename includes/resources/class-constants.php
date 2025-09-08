<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Quick_Ajax_Constants{
    // Plugin info
    public const PLUGIN_VERSION = '1.8.2';
    public const PLUGIN_NAME = 'Quick Ajax Post Loader';
    public const PLUGIN_TEXT_DOMAIN = 'quick-ajax-post-loader';
    public const PLUGIN_SLUG = 'quick-ajax-post-loader';
    public const PLUGIN_MINIMUM_PHP_VERSION = '7.4';
    public const PLUGIN_MINIMUM_WP_VERSION = '5.6';
    public const PLUGIN_TESTED_WP_VERSION = '6.8';


    // Menu and page slugs
    public const PLUGIN_MENU_SLUG = 'qapl-menu';
    public const CPT_SHORTCODE_SLUG = 'qapl-creator';
    public const SETTINGS_PAGE_SLUG = 'qapl-settings';

    // Quick AJAX Creator shortcode field names
    public const DB_POSTMETA_SHORTCODE_SETTINGS = '_qapl_quick_ajax_shortcode_settings';

    // Old value, not in use since 1.3.3
    // public const DB_SHORTCODE_CODE = 'qapl_quick_ajax_shortcode_code'; // Uncomment if needed later

    // Plugin metadata
    public const DB_OPTION_PLUGIN_VERSION = 'qapl_quick_ajax_plugin_version';
    public const DB_OPTION_PLUGIN_CLEANUP_FLAGS = 'qapl_quick_ajax_cleanup_flags';

    // Settings
    public const SETTINGS_WRAPPER_ID = 'qapl_settings_wrapper';

    // Nonce fields
    public const NONCE_FORM_QUICK_AJAX_FIELD = 'qapl_quick_ajax_nonce';
    public const NONCE_FORM_QUICK_AJAX_ACTION = 'qapl_quick_ajax_nonce_action';

    // Query settings field names
    public const QUERY_SETTING_SELECT_POST_TYPE = 'qapl_select_post_type';
    public const QUERY_SETTING_SELECT_POST_TYPE_DEFAULT = 'post';

    public const QUERY_SETTING_SHOW_TAXONOMY_FILTER = 'qapl_show_select_taxonomy';
    public const QUERY_SETTING_SHOW_TAXONOMY_FILTER_DEFAULT = 0;

    public const QUERY_SETTING_SELECT_TAXONOMY = 'qapl_select_taxonomy';

    public const QUERY_SETTING_MANUAL_TERM_SELECTION = 'qapl_manual_term_selection';
    public const QUERY_SETTING_MANUAL_TERM_SELECTION_DEFAULT = 0;

    public const QUERY_SETTING_SELECTED_TERMS = 'qapl_manual_selected_terms';

    public const QUERY_SETTING_SELECT_POSTS_PER_PAGE = 'qapl_select_posts_per_page';
    public const QUERY_SETTING_SELECT_POSTS_PER_PAGE_DEFAULT = 6;

    public const QUERY_SETTING_SELECT_ORDER = 'qapl_select_order';
    public const QUERY_SETTING_SELECT_ORDER_DEFAULT = 'DESC';

    public const QUERY_SETTING_SELECT_ORDERBY = 'qapl_select_orderby';
    public const QUERY_SETTING_SELECT_ORDERBY_DEFAULT = 'date';

    public const QUERY_SETTING_SHOW_SORT_BUTTON = 'qapl_show_order_button';
    public const QUERY_SETTING_SHOW_SORT_BUTTON_DEFAULT = 0;
    
    public const QUERY_SETTING_SELECT_SORT_BUTTON_OPTIONS = 'qapl_select_orderby_button_options';
    public const QUERY_SETTING_SELECT_SORT_BUTTON_OPTIONS_DEFAULT = 1;

    public const QUERY_SETTING_SHOW_INLINE_FILTER_SORTING = 'qapl_show_inline_filter_sorting';
    public const QUERY_SETTING_SHOW_INLINE_FILTER_SORTING_DEFAULT = 1;

    public const QUERY_SETTING_SELECT_POST_STATUS = 'qapl_select_post_status';
    public const QUERY_SETTING_SELECT_POST_STATUS_DEFAULT = 'publish';

    public const QUERY_SETTING_IGNORE_STICKY_POSTS = 'qapl_ignore_sticky_posts';
    public const QUERY_SETTING_IGNORE_STICKY_POSTS_DEFAULT = false;

    public const QUERY_SETTING_AJAX_ON_INITIAL_LOAD = 'qapl_ajax_on_initial_load';
    public const QUERY_SETTING_AJAX_ON_INITIAL_LOAD_DEFAULT = false;

    public const QUERY_SETTING_AJAX_INFINITE_SCROLL = 'qapl_ajax_infinite_scroll';
    public const QUERY_SETTING_AJAX_INFINITE_SCROLL_DEFAULT = false;

    public const QUERY_SETTING_SHOW_END_MESSAGE = 'qapl_show_end_post_message';
    public const QUERY_SETTING_SHOW_END_MESSAGE_DEFAULT = false;

    public const QUERY_SETTING_SET_POST_NOT_IN = 'qapl_select_post_not_in';
    public const QUERY_SETTING_SET_POST_NOT_IN_DEFAULT = false;

    public const QUERY_SETTING_SHOW_CUSTOM_LOAD_MORE_POST_QUANTITY = 'qapl_show_custom_load_more_post_quantity';
    public const QUERY_SETTING_SHOW_CUSTOM_LOAD_MORE_POST_QUANTITY_DEFAULT = 0;

    public const QUERY_SETTING_SELECT_CUSTOM_LOAD_MORE_POST_QUANTITY = 'qapl_select_custom_load_more_post_quantity';
    public const QUERY_SETTING_SELECT_CUSTOM_LOAD_MORE_POST_QUANTITY_DEFAULT = 4;

    // Layout settings field names
    public const LAYOUT_SETTING_SELECT_COLUMNS_QTY = 'qapl_layout_select_columns_qty';
    public const LAYOUT_SETTING_SELECT_COLUMNS_QTY_DEFAULT = 3;

    public const LAYOUT_SETTING_TAXONOMY_FILTER_CLASS = 'qapl_layout_add_taxonomy_filter_class';
    public const LAYOUT_SETTING_TAXONOMY_FILTER_CLASS_DEFAULT = false;

    public const LAYOUT_SETTING_CONTAINER_CLASS = 'qapl_layout_add_container_class';
    public const LAYOUT_SETTING_CONTAINER_CLASS_DEFAULT = false;

    public const LAYOUT_SETTING_QUICK_AJAX_CSS_STYLE = 'qapl_layout_quick_ajax_css_style';
    public const LAYOUT_SETTING_QUICK_AJAX_CSS_STYLE_DEFAULT = 1;

    public const LAYOUT_SETTING_POST_ITEM_TEMPLATE = 'qapl_layout_quick_ajax_post_item_template';
    public const LAYOUT_SETTING_POST_ITEM_TEMPLATE_DEFAULT = 'post-item';

    public const LAYOUT_SETTING_OVERRIDE_GLOBAL_LOADER_ICON = 'qapl_override_global_loader_icon';
    public const LAYOUT_SETTING_OVERRIDE_GLOBAL_LOADER_ICON_DEFAULT = 0;

    public const LAYOUT_SETTING_SELECT_LOADER_ICON = 'qapl_loader_icon';
    public const LAYOUT_SETTING_SELECT_LOADER_ICON_DEFAULT = 'loader-icon';

    // Ajax settings
    public const AJAX_SETTING_AJAX_INITIAL_LOAD = 'ajax_initial_load';

    // Attributes query names
    public const ATTRIBUTE_QUICK_AJAX_ID = 'quick_ajax_id';
    public const ATTRIBUTE_QUICK_AJAX_CSS_STYLE = 'quick_ajax_css_style';
    public const ATTRIBUTE_GRID_NUM_COLUMNS = 'grid_num_columns';
    public const ATTRIBUTE_POST_ITEM_TEMPLATE = 'post_item_template';
    public const ATTRIBUTE_TAXONOMY_FILTER_CLASS = 'taxonomy_filter_class';
    public const ATTRIBUTE_CONTAINER_CLASS = 'container_class';
    public const ATTRIBUTE_LOAD_MORE_POSTS = 'load_more_posts';
    public const ATTRIBUTE_LOADER_ICON = 'loader_icon';
    public const ATTRIBUTE_AJAX_INFINITE_SCROLL = 'infinite_scroll';
    public const ATTRIBUTE_SHOW_END_MESSAGE = 'show_end_message';

    // Quick AJAX settings
    public const ADMIN_PAGE_SETTINGS_GROUP = 'qapl_settings_group'; // used by register_setting to group fields for shared validation and security in admin forms
    public const GLOBAL_OPTIONS_NAME = 'qapl_quick_ajax_global_options';

    // Global options
    public const GLOBAL_LOADER_ICON_FIELD = self::GLOBAL_OPTIONS_NAME . '[loader_icon]';
    public const GLOBAL_READ_MORE_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[read_more_label]';
    //public const GLOBAL_POST_DATE_FORMAT_FIELD = self::GLOBAL_OPTIONS_NAME . '[post_date_format]';
    public const GLOBAL_SHOW_ALL_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[show_all_label]';
    public const GLOBAL_LOAD_MORE_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[load_more_label]';
    public const GLOBAL_NO_POST_MESSAGE_FIELD = self::GLOBAL_OPTIONS_NAME . '[no_post_message]';
    public const GLOBAL_END_POST_MESSAGE_FIELD = self::GLOBAL_OPTIONS_NAME . '[end_post_message]';
    public const GLOBAL_SORT_OPTION_DATE_DESC_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[sort_option_date_desc_label]';
    public const GLOBAL_SORT_OPTION_DATE_ASC_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[sort_option_date_asc_label]';
    public const GLOBAL_SORT_OPTION_COMMENT_COUNT_DESC_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[sort_option_comment_count_desc_label]';
    public const GLOBAL_SORT_OPTION_TITLE_ASC_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[sort_option_title_asc_label]';
    public const GLOBAL_SORT_OPTION_TITLE_DESC_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[sort_option_title_desc_label]';
    public const GLOBAL_SORT_OPTION_RAND_LABEL_FIELD = self::GLOBAL_OPTIONS_NAME . '[sort_option_rand_label]';
    
    //Settings Page
    public const REMOVE_OLD_DATA_FIELD = 'qapl_remove_old_meta';
    public const REMOVE_OLD_DATA_FIELD_DEFAULT = 0;
    // Buttons
    public const TERM_FILTER_BUTTON_DATA_BUTTON = 'quick-ajax-filter-button';
    public const SORT_OPTION_BUTTON_DATA_BUTTON = 'quick-ajax-sort-option-button';
    public const LOAD_MORE_BUTTON_DATA_BUTTON = 'quick-ajax-load-more-button';

    // Hooks
    // Filter Container Hooks
    public const HOOK_FILTER_CONTAINER_BEFORE = 'qapl_filter_container_before';
    public const HOOK_FILTER_CONTAINER_START = 'qapl_filter_container_start';
    public const HOOK_FILTER_CONTAINER_END = 'qapl_filter_container_end';
    public const HOOK_FILTER_CONTAINER_AFTER = 'qapl_filter_container_after';

    // Posts Container Hooks
    public const HOOK_POSTS_CONTAINER_BEFORE = 'qapl_posts_container_before';
    public const HOOK_POSTS_CONTAINER_START = 'qapl_posts_container_start';
    public const HOOK_POSTS_CONTAINER_END = 'qapl_posts_container_end';
    public const HOOK_POSTS_CONTAINER_AFTER = 'qapl_posts_container_after';

    // Load More Button Hooks
    //public const HOOK_LOAD_MORE_BEFORE = 'qapl_load_more_before';
    //public const HOOK_LOAD_MORE_AFTER = 'qapl_load_more_after';

    // Loader Hooks
    public const HOOK_LOADER_BEFORE = 'qapl_loader_before';
    public const HOOK_LOADER_AFTER = 'qapl_loader_after';

    // Filters
    public const HOOK_MODIFY_POSTS_QUERY_ARGS = 'qapl_modify_posts_query_args';
    public const HOOK_MODIFY_TAXONOMY_FILTER_BUTTONS = 'qapl_modify_taxonomy_filter_buttons';
    public const HOOK_MODIFY_SORTING_OPTIONS_VARIANTS = 'qapl_modify_sorting_options_variants';

    // Template Hooks
    public const HOOK_TEMPLATE_POST_ITEM_DATE = 'qapl_template_post_item_date';
    public const HOOK_TEMPLATE_POST_ITEM_IMAGE = 'qapl_template_post_item_image';
    public const HOOK_TEMPLATE_POST_ITEM_TITLE = 'qapl_template_post_item_title';
    public const HOOK_TEMPLATE_POST_ITEM_EXCERPT = 'qapl_template_post_item_excerpt';
    public const HOOK_TEMPLATE_POST_ITEM_READ_MORE = 'qapl_template_post_item_read_more';
    public const HOOK_TEMPLATE_LOAD_MORE_BUTTON = 'qapl_template_load_more_button';
    public const HOOK_TEMPLATE_NO_POST_MESSAGE = 'qapl_template_no_post_message';
    public const HOOK_TEMPLATE_END_POST_MESSAGE = 'qapl_template_end_post_message';
}