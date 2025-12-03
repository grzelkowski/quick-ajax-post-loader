<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Ajax_Query_Builder{
    private $quick_ajax_id = 0;

    public function get_quick_ajax_id() {
        return $this->quick_ajax_id;
    }
    public function wp_query_args($source_args, $attributes = false){
        // sanitize and normalize input
        $source_args = $this->normalize_args($source_args);

        if (!$this->quick_ajax_id) {
            $this->generate_block_id($attributes);
        }

        //normalize input args (sanitize selected_terms, post__not_in, etc.)
        //$this->input_args = $this->normalize_args($source_args);
        //$this->action_args = $this->input_args;
        
        // generate query args (post_type, tax_query, etc.)
        $query_args = $this->initialize_query_args($source_args);

        $query_args['post_status'] = QAPL_Constants::QUERY_SETTING_SELECT_POST_STATUS_DEFAULT;

        /*
        if (isset($quick_ajax_args['post_type']) && !empty($quick_ajax_args['post_type'])) {
            foreach ($quick_ajax_args as $key => $value) {
                if (!empty($value)) {
                    $this->args[$key] = $value;
                }
            }
        }
        */

        /* not in use yet
        if(isset($source_args['tax_query']) && !empty($source_args['tax_query'])){
            $this->args['tax_query'] = $source_args['tax_query'];
        }
        */
        // remove empty values
        $query_args = array_filter($query_args, function($value) {
            return !empty($value) || $value === 0 || $value === '0';
        });
        $query_args = apply_filters(QAPL_Constants::HOOK_MODIFY_POSTS_QUERY_ARGS, $query_args, $this->quick_ajax_id);

        if (empty($query_args)) {
            return false;
        }else{
            return $query_args; 
        }
    }
    private function sanitize_to_int_array($value) {
        // if it's a string (e.g. "1,2,3"), split it by comma or whitespace
        if (!is_array($value)) {
            $value = preg_split('/[,\s]+/', $value);
        }        
        // normalize and sanitize all values
        $value = array_map('absint', $value);        
        // remove empty values (0s, nulls, etc.)
        $value = array_filter($value, function($id) {
            return $id > 0;
        });        
        // remove duplicates
        $int_array = array_values(array_unique($value));        
        return $int_array;
    }
    private function normalize_args($source_args) {
        // convert comma-separated string to array of integers
        if (isset($source_args['post__not_in'])) {
            $source_args['post__not_in'] = $this->sanitize_to_int_array($source_args['post__not_in']);
        }        
        if (isset($source_args['selected_terms'])) {
            $source_args['selected_terms'] = $this->sanitize_to_int_array($source_args['selected_terms']);
        } 
        return $source_args;
    }
    private function initialize_query_args($source_args) {
        // Set default query arguments
        $query_args = $this->query_args_base_query_args($source_args);
        $query_args = $this->query_args_add_tax_query($query_args, $source_args);            
        $query_args = $this->query_args_apply_offset_or_paged($query_args, $source_args);
        return $query_args;    
    }
    private function query_args_base_query_args($source_args) {
        return [
            'post_type' => isset($source_args['post_type']) ? sanitize_text_field($source_args['post_type']) : null,
            'posts_per_page' => isset($source_args['posts_per_page']) ? intval($source_args['posts_per_page']) : QAPL_Constants::QUERY_SETTING_SELECT_POSTS_PER_PAGE_DEFAULT,
            'orderby' => isset($source_args['orderby']) ? sanitize_text_field($source_args['orderby']) : QAPL_Constants::QUERY_SETTING_SELECT_ORDERBY_DEFAULT,
            'order' => isset($source_args['order']) ? sanitize_text_field($source_args['order']) : QAPL_Constants::QUERY_SETTING_SELECT_ORDER_DEFAULT,
            'post__not_in' => $source_args['post__not_in'] ?? [],
            'ignore_sticky_posts' => isset($source_args['ignore_sticky_posts']) ? intval($source_args['ignore_sticky_posts']) : QAPL_Constants::QUERY_SETTING_IGNORE_STICKY_POSTS_DEFAULT,
            'paged' => isset($source_args['paged']) ? intval($source_args['paged']) : 1,
        ];
    }  
    private function query_args_apply_offset_or_paged($query_args, $source_args) {
        // Check if 'offset' is provided and use it instead of 'paged'
        if (isset($source_args['offset']) && !is_null($source_args['offset'])) {
            // Set the offset value and remove 'paged' from the query
            $query_args['offset'] = intval($source_args['offset']);
            unset($query_args['paged']);
        }
        return $query_args;
    }
    private function query_args_add_tax_query($query_args, $source_args) {
        $taxonomy = isset($source_args['selected_taxonomy']) ? sanitize_text_field($source_args['selected_taxonomy']) : '';
        $terms = isset($source_args['selected_terms']) ? $source_args['selected_terms'] : [];
    
        if ($taxonomy && !empty($terms)) {
            $query_args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $terms,
                'operator' => 'IN',
            ];
        } elseif ($taxonomy) {
            $query_args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'operator' => 'EXISTS',
            ];
        }        
        return $query_args;
    }
    public function generate_tax_query($base_args, $taxonomy, $term_id) {
        unset($base_args['paged'], $base_args['offset']);
        $base_args['tax_query'] = [
            [
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $term_id,
                'operator' => 'IN',
            ],
        ];
        return $base_args;
    }
    private function generate_block_id($attributes = false) {
        if (!is_array($attributes)) {
            $attributes = [QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID => sanitize_text_field($attributes)];
        }
        if (isset($attributes[QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID])) {
            $existing_id = sanitize_text_field($attributes[QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID]);
            // if id already starts with 'p' or 'c', keep it untouched
            if (preg_match('/^[pc]\d+$/', $existing_id)) {
                $this->quick_ajax_id = $existing_id;
                return;
            }
            // Prefix 'p' for 'shortcode' equal to true, otherwise 'c'
            $prefix = (isset($attributes['shortcode']) && $attributes['shortcode'] === true) ? 'p' : 'c';              
            $this->quick_ajax_id = esc_attr($prefix . $attributes[QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID]);
            return;
        } else {
            $this->quick_ajax_id = uniqid('c', false);
        }
    }
}