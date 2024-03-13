<?php 
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_quick_ajax_load_posts', 'quick_ajax_load_posts');
add_action('wp_ajax_nopriv_quick_ajax_load_posts', 'quick_ajax_load_posts');
function quick_ajax_load_posts() {
    if (isset($_POST['args'])) {
        $ajax_class = new WPG_Quick_Ajax_Handler;
        if($_POST['button_type'] == 'ajax-load-more'){
            if(isset($_POST['attributes']['load_more_posts'])){
                $_POST['args']['posts_per_page'] = $_POST['attributes']['load_more_posts'];
            }
        }
        $ajax_class->quick_ajax_wp_query_args($_POST['args']);
        $ajax_class->attributes = $_POST['attributes'];
        $args = $ajax_class->args;
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                include(WPG_Quick_Ajax_Helper::quick_ajax_plugin_templates_post_item_template($ajax_class->attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_post_item_template()]));
            }          
            $ajax_class->load_more_button($query->get('paged'), $query->max_num_pages, $query->found_posts);
            wp_reset_postdata();
        } else {
            // No posts found
            include(WPG_Quick_Ajax_Helper::quick_ajax_plugin_templates_no_posts());
        }
    }
    wp_die();
}

add_action('wp_ajax_get_taxonomies_by_post_type', 'get_taxonomies_by_post_type');
add_action('wp_ajax_nopriv_get_taxonomies_by_post_type', 'get_taxonomies_by_post_type');
function get_taxonomies_by_post_type() {
    if (!isset($_POST['post_type'])) {
        wp_send_json_error('Invalid request');
    }
    $post_type = sanitize_text_field($_POST['post_type']);
    $taxonomies = get_object_taxonomies($post_type, 'objects');
    ob_start();
    if (!empty($taxonomies)) {
        foreach ($taxonomies as $taxonomy) {
            ?>
            <option value="<?php echo esc_attr($taxonomy->name); ?>">
                <?php echo esc_html($taxonomy->label); ?>
            </option>
            <?php
        }
    }
    $output = ob_get_clean();
    wp_send_json_success($output);
}
?>