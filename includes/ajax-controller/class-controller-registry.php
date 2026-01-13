<?php 
if (!defined('ABSPATH')) {
    exit;
}
final class QAPL_Controller_Registry {
    private $controllers = [];
    public function get_controller($args, $attributes): QAPL_Ajax_Frontend_Render {
        if (!is_array($args) || !is_array($attributes)) {
            //if no args
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