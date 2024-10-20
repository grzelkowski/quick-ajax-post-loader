<?php 
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_qapl_quick_ajax_load_posts', 'qapl_quick_ajax_load_posts');
add_action('wp_ajax_nopriv_qapl_quick_ajax_load_posts', 'qapl_quick_ajax_load_posts');
function qapl_quick_ajax_load_posts() {
    // nonce verification
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), QAPL_Quick_Ajax_Helper::wp_nonce_form_quick_ajax_action())) {
        wp_send_json_error('Quick Ajax Post Loader: Unauthorized request');
    }    
    
    if (isset($_POST['args'])) {       
        $ajax_class = new QAPL_Quick_Ajax_Handler;

        $args = isset($_POST['args']) ? $ajax_class->sanitize_json_to_array(wp_unslash($_POST['args'])) : array();
        $attributes = isset($_POST['attributes']) ? $ajax_class->sanitize_json_to_array(wp_unslash($_POST['attributes'])) : array();
        $button_type = isset($_POST['button_type']) ? sanitize_text_field(wp_unslash($_POST['button_type'])) : '';
        if ($button_type === 'ajax-load-more' && isset($attributes['load_more_posts'])) {
            $args['posts_per_page'] = intval($attributes['load_more_posts']);
        }
        
        $ajax_class->wp_query_args($args);
        $ajax_class->attributes = $attributes;
        $args = $ajax_class->args;

        $query = new WP_Query($args);
        ob_start();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                include(QAPL_Quick_Ajax_Helper::plugin_templates_post_item_template(esc_attr($ajax_class->attributes[QAPL_Quick_Ajax_Helper::layout_post_item_template()])));
            }          
            $ajax_class->load_more_button(esc_attr($query->get('paged')), esc_attr($query->max_num_pages), esc_attr($query->found_posts));
            wp_reset_postdata();
        } else {
            // No posts found
            include(QAPL_Quick_Ajax_Helper::plugin_templates_no_posts());
        }
        $output = ob_get_clean();
        wp_send_json_success([
            'output' => $output,
            'args' => $args,
        ]);
    }else {
        wp_send_json_error('Quick Ajax Post Loader: Invalid request, Missing arguments.');
    }
    wp_die();
}

add_action('wp_ajax_qapl_quick_ajax_get_taxonomies_by_post_type', 'qapl_quick_ajax_get_taxonomies_by_post_type');
add_action('wp_ajax_nopriv_qapl_quick_ajax_get_taxonomies_by_post_type', 'qapl_quick_ajax_get_taxonomies_by_post_type');
function qapl_quick_ajax_get_taxonomies_by_post_type() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), QAPL_Quick_Ajax_Helper::wp_nonce_form_quick_ajax_action())) {
        wp_send_json_error('Unauthorized request');
    }    

    if (!isset($_POST['post_type'])) {
        wp_send_json_error('Invalid request: Missing post type.');
    }
    $post_type = sanitize_text_field(wp_unslash($_POST['post_type']));
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