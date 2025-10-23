<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Ajax_Query_Builder{
    private $quick_ajax_id = 0;

    public function get_quick_ajax_id() {
        return $this->quick_ajax_id;
    }
    public function wp_query_args($args, $attributes = false){
        // sanitize and normalize input
        $args = $this->normalize_args($args);

        $this->generate_block_id($attributes);

        //normalize input args (sanitize selected_terms, post__not_in, etc.)
        //$this->input_args = $this->normalize_args($args);
        //$this->action_args = $this->input_args;
        
        // generate query args (post_type, tax_query, etc.)
        $query_args = $this->initialize_query_args($args);

        $query_args['post_status'] = QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_POST_STATUS_DEFAULT;

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
        if(isset($args['tax_query']) && !empty($args['tax_query'])){
            $this->args['tax_query'] = $args['tax_query'];
        }
        */
        // remove empty values
        $query_args = array_filter($query_args, function($value) {
            return !empty($value) || $value === 0 || $value === '0';
        });
        $query_args = apply_filters(QAPL_Quick_Ajax_Constants::HOOK_MODIFY_POSTS_QUERY_ARGS, $query_args, $this->quick_ajax_id);

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
    private function normalize_args($args) {
        // convert comma-separated string to array of integers
        if (isset($args['post__not_in'])) {
            $args['post__not_in'] = $this->sanitize_to_int_array($args['post__not_in']);
        }        
        if (isset($args['selected_terms'])) {
            $args['selected_terms'] = $this->sanitize_to_int_array($args['selected_terms']);
        } 
        return $args;
    }
    private function initialize_query_args($args) {
        // Set default query arguments
        $query_args = $this->query_args_base_query_args($args);
        $query_args = $this->query_args_add_tax_query($query_args, $args);            
        $query_args = $this->query_args_apply_offset_or_paged($query_args, $args);
        return $query_args;    
    }
    private function query_args_base_query_args($args) {
        return [
            'post_type' => isset($args['post_type']) ? sanitize_text_field($args['post_type']) : null,
            'posts_per_page' => isset($args['posts_per_page']) ? intval($args['posts_per_page']) : QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_POSTS_PER_PAGE_DEFAULT,
            'orderby' => isset($args['orderby']) ? sanitize_text_field($args['orderby']) : QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_ORDERBY_DEFAULT,
            'order' => isset($args['order']) ? sanitize_text_field($args['order']) : QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_ORDER_DEFAULT,
            'post__not_in' => $args['post__not_in'] ?? [],
            'ignore_sticky_posts' => isset($args['ignore_sticky_posts']) ? intval($args['ignore_sticky_posts']) : QAPL_Quick_Ajax_Constants::QUERY_SETTING_IGNORE_STICKY_POSTS_DEFAULT,
            'paged' => isset($args['paged']) ? intval($args['paged']) : 1,
        ];
    }  
    private function query_args_apply_offset_or_paged($query_args, $args) {
        // Check if 'offset' is provided and use it instead of 'paged'
        if (isset($args['offset']) && !is_null($args['offset'])) {
            // Set the offset value and remove 'paged' from the query
            $query_args['offset'] = intval($args['offset']);
            unset($query_args['paged']);
        }
        return $query_args;
    }
    private function query_args_add_tax_query($query_args, $args) {
        $taxonomy = isset($args['selected_taxonomy']) ? sanitize_text_field($args['selected_taxonomy']) : '';
        $terms = isset($args['selected_terms']) ? $args['selected_terms'] : [];
    
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
            $attributes = [QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID => sanitize_text_field($attributes)];
        }
        if (isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID])) {
            $existing_id = sanitize_text_field($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID]);
            // if id already starts with 'p' or 'c', keep it untouched
            if (preg_match('/^[pc]\d+$/', $existing_id)) {
                $this->quick_ajax_id = $existing_id;
                return;
            }
            // Prefix 'p' for 'shortcode' equal to true, otherwise 'c'
            $prefix = (isset($attributes['shortcode']) && $attributes['shortcode'] === true) ? 'p' : 'c';              
            $this->quick_ajax_id = esc_attr($prefix . $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID]);
            return;
        } else {
            // Increment qapl_id if 'quick_ajax_id' is not set
            $this->quick_ajax_id++;
        }
    }
}