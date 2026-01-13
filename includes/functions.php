<?php 
if (!defined('ABSPATH')) {
    exit;
}

function qapl_get_controller_registry(): QAPL_Controller_Registry {
    static $instance = null;
    if ($instance === null) {
        $instance = new QAPL_Controller_Registry();
    }
    return $instance;
}
//add get qapl_render_post_container - echo qapl_render_post_container()
//add get qapl_render_taxonomy_filter
function qapl_render_post_container($args, $attributes = null, $render_context = null, $meta_query = null) {
    if (!is_array($args)) {
        return;
    }
    $manager = qapl_get_controller_registry();
    $controller = $manager->get_controller($args, $attributes);
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo $controller->render_post_container($args, $attributes, $render_context, $meta_query);
}
//alias for backward compatibility
function qapl_quick_ajax_post_grid($args, $attributes) {
    return qapl_render_post_container($args, $attributes);
}

function qapl_render_taxonomy_filter($args, $attributes, $taxonomy = null) {
    if (!is_array($args)) {
        return;
    }
    $manager = qapl_get_controller_registry();
    $controller = $manager->get_controller($args, $attributes);
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo $controller->render_taxonomy_filter($args, $attributes, $taxonomy);
}
//alias for backward compatibility
function qapl_quick_ajax_term_filter($args, $attributes, $taxonomy) {
    return qapl_render_taxonomy_filter($args, $attributes, $taxonomy);
}

function qapl_render_sort_controls($args, $attributes, $sort_options) {
    if (!is_array($args)) {
        return;
    }
    $manager = qapl_get_controller_registry();
    $controller = $manager->get_controller($args, $attributes);
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo $controller->render_sort_controls($args, $attributes, $sort_options);
}