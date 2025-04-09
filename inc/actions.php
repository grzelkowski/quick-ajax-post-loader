<?php 
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_qapl_quick_ajax_load_posts', 'qapl_quick_ajax_load_posts');
add_action('wp_ajax_nopriv_qapl_quick_ajax_load_posts', 'qapl_quick_ajax_load_posts');
function qapl_quick_ajax_load_posts() {
    if (!defined('DOING_AJAX') || !DOING_AJAX) {
        wp_send_json_error(['message' => 'Quick Ajax Post Loader: Not an AJAX request']);
    }
    // nonce verification
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), QAPL_Quick_Ajax_Helper::wp_nonce_form_quick_ajax_action())) {
        wp_send_json_error(['message' => 'Quick Ajax Post Loader: Unauthorized request']);
    }
    // validate and sanitize input
    if (empty($_POST['args'])) {
        wp_send_json_error(['message' => 'Quick Ajax Post Loader: Invalid request, Missing arguments.']);
    } else {
        $qapl_helper = QAPL_Quick_Ajax_Helper::get_instance();
        $ajax_class = new QAPL_Quick_Ajax_Handler;

        // Sanitize 'args'
        $args = [];
        if (isset($_POST['args'])) {
            $args = $ajax_class->sanitize_json_to_array(wp_unslash($_POST['args'])); // Sanitize JSON to array
        }

        // Sanitize 'attributes'
        $attributes = [];
        if (isset($_POST['attributes'])) {
            $attributes = $ajax_class->sanitize_json_to_array(wp_unslash($_POST['attributes'])); // Sanitize JSON to array
        }

        // Sanitize 'button_type'
        $button_type = '';
        if (isset($_POST['button_type'])) {
            $button_type = sanitize_text_field(wp_unslash($_POST['button_type'])); // Sanitize string input
        }

        if ($button_type === 'quick-ajax-load-more-button' && isset($attributes['load_more_posts']) && $attributes['load_more_posts'] > 0) {
            //investigate, load more works good without this parameter
         //   $args['posts_per_page'] = intval($attributes['load_more_posts']);
        }
        
        $ajax_class->wp_query_args($args);
        $ajax_class->attributes = $attributes;
        $args = $ajax_class->args;


        $container_settings = [
            'quick_ajax_id' => $ajax_class->attributes['quick_ajax_id'],
            'template_name' => $ajax_class->attributes['post_item_template'],
        ];
        $qapl_post_template = QAPL_Post_Template_Factory::get_template($container_settings);
        QAPL_Post_Template_Context::set_template($qapl_post_template);
        $query = new WP_Query($args);

        ob_start();        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $template_path = $qapl_helper->plugin_templates_post_item_template(esc_attr($ajax_class->attributes[QAPL_Quick_Ajax_Helper::layout_post_item_template()]));
                if ($template_path && file_exists($template_path)) {
                    include $template_path;
                } else {
                    wp_send_json_error(['message' => 'Quick Ajax Post Loader: Template file not found']);
                }
            }          
            
        } else {
            // No posts found
            $no_posts_template = $qapl_helper->plugin_templates_no_posts();
            if ($no_posts_template && file_exists($no_posts_template)) {
                include $no_posts_template;
            } else {
                wp_send_json_error(['message' => 'Quick Ajax Post Loader: No posts template file not found']);
            }
        }
        wp_reset_postdata();
       
        $output = ob_get_clean();
        $load_more = $ajax_class->load_more_button(esc_attr($query->get('paged')), esc_attr($query->max_num_pages), esc_attr($query->found_posts), esc_attr($ajax_class->attributes['infinite_scroll']));
        $show_end_message = $ajax_class->render_end_of_posts_message($ajax_class->attributes['show_end_message'], $load_more, esc_attr($query->max_num_pages), esc_attr($ajax_class->attributes['quick_ajax_id']));
       
        QAPL_Post_Template_Context::clear_template();
        //$output = $ajax_class->replace_placeholders($output);
        wp_send_json_success([
            'output' => $output,
            'args' => $args,
            'load_more' => $load_more,
            'show_end_message' => $show_end_message
            //'attributes' => $attributes,
        ]);
    }
    wp_die();
}

add_action('wp_ajax_qapl_quick_ajax_get_taxonomies_by_post_type', 'qapl_quick_ajax_get_taxonomies_by_post_type');
add_action('wp_ajax_nopriv_qapl_quick_ajax_get_taxonomies_by_post_type', 'qapl_quick_ajax_get_taxonomies_by_post_type');
function qapl_quick_ajax_get_taxonomies_by_post_type() {
    if (!defined('DOING_AJAX') || !DOING_AJAX) {
        wp_send_json_error(['message' => 'Quick Ajax Post Loader: Not an AJAX request']);
    }
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), QAPL_Quick_Ajax_Helper::wp_nonce_form_quick_ajax_action())) {
        wp_send_json_error(['message' => 'Quick Ajax Post Loader: Unauthorized request']);
    }

    if (empty($_POST['post_type'])) {
        wp_send_json_error(['message' => 'Quick Ajax Post Loader: Invalid request, missing post type.']);
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
    }else{
        ?>
        <option value="0"><?php echo esc_html__('No taxonomy found', 'quick-ajax-post-loader'); ?></option>
        <?php
    }
    $output = ob_get_clean();
    wp_send_json_success($output);
    wp_die();
}
