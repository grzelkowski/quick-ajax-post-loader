<?php 
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Shares one QAPL_Ajax_Frontend_Render instance per unique ($args, $attributes) pair
 * within a single request. Required by the PHP template functions
 * (qapl_render_post_container / qapl_render_taxonomy_filter / qapl_render_sort_controls):
 * the shared instance guarantees a consistent generated quick_ajax_id across the
 * filter, sort and posts containers when the caller does not pass quick_ajax_id.
 * Deliberately NOT used in the AJAX controller - each AJAX request renders once,
 * so there is nothing to share there.
 */

final class QAPL_Controller_Registry {
    private $controllers = [];
    public function get_controller($args, $attributes): QAPL_Ajax_Frontend_Render {
        if (!is_array($args) || !is_array($attributes)) {
            //if args or attributes are not arrays
            $key = 'default';
        } else {
            $key = md5(wp_json_encode([$args, $attributes])); //key generation
        }
        if (!isset($this->controllers[$key])) {
            $this->controllers[$key] = new QAPL_Ajax_Frontend_Render();
        }
        return $this->controllers[$key];
    }
}