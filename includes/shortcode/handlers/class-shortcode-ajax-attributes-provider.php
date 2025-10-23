<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Shortcode_Ajax_Attributes_Provider {
    private $shortcode_params = [];
    private $shortcode_postmeta = [];

    public function __construct(array $shortcode_params, array $shortcode_postmeta) {
        $this->shortcode_params = $shortcode_params;
        $this->shortcode_postmeta = $shortcode_postmeta;
    }
    public function get_attributes() {
        return $this->create_shortcode_attributes();
    }

    private function create_shortcode_attributes() {
        $attributes = array();        
        if (!empty($this->shortcode_params['id'])) {
            $attributes['shortcode'] = true;
            $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID] = absint($this->shortcode_params['id']);
        } else {
            $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID] = $this->get_sanitized_attribute([
                'shortcode_key' => QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID,
                'type' => 'number',
            ]);
        }
        $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE,
            'postmeta_key' => QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_QUICK_AJAX_CSS_STYLE,
            'type' => 'string',
        ]);
        $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_GRID_NUM_COLUMNS] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Constants::ATTRIBUTE_GRID_NUM_COLUMNS,
            'postmeta_key' => QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_SELECT_COLUMNS_QTY,
            'type' => 'number',
        ]);
        $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE,
            'postmeta_key' => QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_POST_ITEM_TEMPLATE,
            'type' => 'string',
        ]);
        $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_TAXONOMY_FILTER_CLASS] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Constants::ATTRIBUTE_TAXONOMY_FILTER_CLASS,
            'postmeta_key' => QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_TAXONOMY_FILTER_CLASS,
            'type' => 'html_class',
        ]);
        $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS,
            'postmeta_key' => QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_CONTAINER_CLASS,
            'type' => 'html_class',
        ]);
        $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOAD_MORE_POSTS] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOAD_MORE_POSTS,
            'postmeta_key' => QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_CUSTOM_LOAD_MORE_POST_QUANTITY,
            'only_if_meta_key_true' => QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_CUSTOM_LOAD_MORE_POST_QUANTITY,
            'type' => 'number',
        ]);
        $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOADER_ICON] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOADER_ICON,
            'postmeta_key' => QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_SELECT_LOADER_ICON,
            'only_if_meta_key_true' => QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_OVERRIDE_GLOBAL_LOADER_ICON,
            'type' => 'string',
        ]);
        $attributes[QAPL_Quick_Ajax_Constants::AJAX_SETTING_AJAX_INITIAL_LOAD] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Constants::AJAX_SETTING_AJAX_INITIAL_LOAD,
            'postmeta_key' => QAPL_Quick_Ajax_Constants::QUERY_SETTING_AJAX_ON_INITIAL_LOAD,
            'type' => 'bool',
        ]);
        $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL,
            'postmeta_key' => QAPL_Quick_Ajax_Constants::QUERY_SETTING_AJAX_INFINITE_SCROLL,
            'type' => 'bool',
        ]);
        $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_SHOW_END_MESSAGE] = $this->get_sanitized_attribute([
            'shortcode_key' => QAPL_Quick_Ajax_Constants::ATTRIBUTE_SHOW_END_MESSAGE,
            'postmeta_key' => QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_END_MESSAGE,
            'type' => 'bool',
        ]);  
        return !empty($attributes) ? $attributes : false;
    }

    private function get_sanitized_attribute(array $config) {
        /**
         * - try to get value from shortcode args first
         * - if not found, try to get value from shortcode postmeta settings
         * - if 'only_if_meta_key_true' is set, check its value before using postmeta value
         * - if value is still not found, return empty value (0 or empty string)
         * - sanitize the value based on given type:
         *   - 'number' = return as integer
         *   - 'bool' = return as 1 or 0
         *   - 'html_class' = sanitize as safe css class
         *   - 'string' = sanitize as safe text
         */
        $shortcode_key = $config['shortcode_key'] ?? null;
        $meta_key = $config['postmeta_key'] ?? null;
        $type = $config['type'] ?? 'string';
        $only_if_meta_key_true = $config['only_if_meta_key_true'] ?? null;
        $value = null;
        
        // try to get value from shortcode args
        if (!empty($this->shortcode_params[$shortcode_key])) {
            $value = $this->shortcode_params[$shortcode_key];
        // if not found try to get value from shortcode settings
        } elseif (!empty($meta_key) && isset($this->shortcode_postmeta[$meta_key])) {
            // check if additional meta key condition is required
            if (!empty($only_if_meta_key_true)) {
                if (!empty($this->shortcode_postmeta[$only_if_meta_key_true])) {
                    $value = $this->shortcode_postmeta[$meta_key];
                } else {
                    $value = null;
                }
            } else {
                $value = $this->shortcode_postmeta[$meta_key];
            }
        }
        // return default empty value if value is still null
        if ($value === null) {
            return ($type === 'number' || $type === 'bool') ? 0 : '';
        }
        // sanitize value based on type
        switch ($type) {
            case 'number':
                return intval($value);
            case 'bool':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            case 'html_class':
                    $classes = preg_split('/[\s,]+/', $value, -1, PREG_SPLIT_NO_EMPTY);
                    $sanitized_classes = array_map('sanitize_html_class', $classes);
                    return implode(' ', $sanitized_classes);
            case 'string':
            default:
                return sanitize_text_field($value);
        }
    }
}