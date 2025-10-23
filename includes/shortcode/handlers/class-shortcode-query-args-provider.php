<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Shortcode_Query_Args_Provider {
    private $shortcode_params;
    private $shortcode_postmeta;

    public function __construct(array $shortcode_params, array $postmeta) {
        $this->shortcode_params = $shortcode_params;
        $this->shortcode_postmeta = $postmeta;
    }
    // return shortcode param if set else get value from postmeta
    public function get_arg_value($shortcode_key, $meta_key = null) {
        // check if param exists in shortcode
        if (!empty($this->shortcode_params[$shortcode_key])) {
            return $this->shortcode_params[$shortcode_key];
        }
        // fallback to meta key if not provided
        if (!$meta_key) {
            $meta_key = $shortcode_key;
        }
        // check if param exists in postmeta
        if (isset($this->shortcode_postmeta[$meta_key])) {
            return $this->shortcode_postmeta[$meta_key];
        }        
        return '';
    }
}