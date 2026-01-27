<?php
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Creator_Columns {
    public static function init() {
        $slug = QAPL_Constants::CPT_SHORTCODE_SLUG;
        add_filter('manage_'.$slug.'_posts_columns', [__CLASS__, 'add_shortcode_column']);
        add_action('manage_'.$slug.'_posts_custom_column', [__CLASS__, 'render_shortcode_column'], 10, 2);
        add_filter('manage_edit-'.$slug.'_sortable_columns', [__CLASS__, 'make_shortcode_column_sortable']);
        add_action('pre_get_posts', [__CLASS__, 'shortcode_column_orderby']);
    }
    public static function add_shortcode_column($columns) {
        $new = [];
        $author = isset($columns['author']) ? $columns['author'] : null;
        if ($author) {
            unset($columns['author']); //remove author if exists
        }
        foreach ($columns as $key => $value) {
            $new[$key] = $value;
            //add Shortcode after title
            if ($key === 'title') {
                $new['qapl_shortcode'] = __('Shortcode', 'quick-ajax-post-loader');
                //add Author after shortcode
                if ($author) {
                $new['author'] = $author;
                } else {
                    $new['author'] = __('Author', 'quick-ajax-post-loader');
                }
            }
        }
        return $new;
    }
    public static function render_shortcode_column($column, $post_id) {
        if ($column === 'qapl_shortcode') {
            $shortcode = QAPL_Shortcode_Generator::generate_shortcode($post_id);
            echo '<div class="quick-ajax-shortcode">' . esc_html($shortcode)  . '</div>';
        }
    }
    public static function make_shortcode_column_sortable($columns) {
        $columns['qapl_shortcode'] = 'ID';
        return $columns;
    }
    public static function shortcode_column_orderby($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }
        $orderby = $query->get('orderby');
        if ($orderby === 'qapl_shortcode') {
            $query->set('orderby', 'ID'); // sort by ID
        }
    }
}

QAPL_Creator_Columns::init();
