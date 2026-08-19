<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Ajax_Query_Builder{
    private $quick_ajax_id = 0;

    public function get_quick_ajax_id() {
        return $this->quick_ajax_id;
    }
    public function wp_query_args($source_args, $attributes = []){
        // sanitize and normalize input
        $source_args = $this->normalize_args($source_args);

        if (!$this->quick_ajax_id) {
            $this->generate_block_id($attributes);
        }

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
        $display_show_all_button = isset($attributes[QAPL_Constants::ATTRIBUTE_DISPLAY_SHOW_ALL_BUTTON]) ? $attributes[QAPL_Constants::ATTRIBUTE_DISPLAY_SHOW_ALL_BUTTON] : QAPL_Constants::LAYOUT_SETTING_DISPLAY_SHOW_ALL_BUTTON_DEFAULT;

        $query_args = $this->adjust_tax_query_for_initial_state($query_args, $display_show_all_button);
        $query_args = apply_filters(QAPL_Constants::HOOK_MODIFY_POSTS_QUERY_ARGS, $query_args, $this->quick_ajax_id);

        if (empty($query_args)) {
            return false;
        }
        return $query_args;        
    }
    private function adjust_tax_query_for_initial_state($query_args, $display_show_all_button): array {
        // if show all enabled - do nothing
        if ($display_show_all_button !== 0) {
            return $query_args;
        }
        // if no tax query
        if (empty($query_args['tax_query']) || !is_array($query_args['tax_query'])) {
            return $query_args;
        }
        // if no terms defined get first taxonomy term
        if (empty($query_args['tax_query'][0]['terms']) || !is_array($query_args['tax_query'][0]['terms'])) {
            // if no taxonomy
            if (empty($query_args['tax_query'][0]['taxonomy'])) {
                return $query_args;
            }
            $taxonomy = $query_args['tax_query'][0]['taxonomy'];
            $terms = get_terms([
                'taxonomy' => $taxonomy,
                'hide_empty' => true,
                'number' => 1,
                'fields' => 'ids',
            ]);
            if (!is_wp_error($terms) && !empty($terms)) {
                $query_args['tax_query'][0]['terms'] = [$terms[0]];
                $query_args['tax_query'][0]['field'] = 'term_id';
                // remove exists operator
                if (isset($query_args['tax_query'][0]['operator'])) {
                    unset($query_args['tax_query'][0]['operator']);
                }
            }
            return $query_args;
        }
        //if show all button not visible show only first term posts on initial load
        $first_term = $query_args['tax_query'][0]['terms'][0];
        $query_args['tax_query'][0]['terms'] = [$first_term];
        return $query_args;
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
            // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in -- controlled input small dataset
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
        $query_args = $this->query_args_add_search($query_args, $source_args);
        $query_args = $this->query_args_apply_offset_or_paged($query_args, $source_args);
        return $query_args;    
    }
    private function query_args_base_query_args($source_args) {
        // phpcs:disable WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in -- intentional usage
        $post_type = isset($source_args['post_type']) ? sanitize_text_field($source_args['post_type']) : null;
        // reject post types that are not public / publicly queryable - blocks probing of internal CPTs via the nopriv AJAX endpoint
        if ($post_type !== null) {
            $post_type_object = get_post_type_object($post_type);
            if (!$post_type_object || (!$post_type_object->public && !$post_type_object->publicly_queryable)) {
                $post_type = null;
            }
        }
        $posts_per_page = isset($source_args['posts_per_page']) ? intval($source_args['posts_per_page']) : QAPL_Constants::QUERY_SETTING_SELECT_POSTS_PER_PAGE_DEFAULT;
        // hard cap matching the admin field's own limit (see build_posts_per_page_field, max => 1000);
        // rejects -1/huge values sent directly to the AJAX endpoint
        if ($posts_per_page < 1 || $posts_per_page > 1000) {
            $posts_per_page = QAPL_Constants::QUERY_SETTING_SELECT_POSTS_PER_PAGE_DEFAULT;
        }
        return [
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'orderby' => isset($source_args['orderby']) ? sanitize_text_field($source_args['orderby']) : QAPL_Constants::QUERY_SETTING_SELECT_ORDERBY_DEFAULT,
            'order' => isset($source_args['order']) ? sanitize_text_field($source_args['order']) : QAPL_Constants::QUERY_SETTING_SELECT_ORDER_DEFAULT,
            'post__not_in' => $source_args['post__not_in'] ?? [],
            'ignore_sticky_posts' => isset($source_args['ignore_sticky_posts']) ? intval($source_args['ignore_sticky_posts']) : QAPL_Constants::QUERY_SETTING_IGNORE_STICKY_POSTS_DEFAULT,
            'paged' => isset($source_args['paged']) ? intval($source_args['paged']) : 1,
        ];
        // phpcs:enable WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
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
        // phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- taxonomy filtering is required
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
        // phpcs:enable WordPress.DB.SlowDBQuery.slow_db_query_tax_query
        return $query_args;
    }
    private function query_args_add_search($query_args, $source_args) {
        $search_phrase = isset($source_args['s']) ? trim(sanitize_text_field($source_args['s'])) : '';
        if ($search_phrase === '') {
            return $query_args;
        }
        // hard cap - rejects oversized phrases sent directly to the AJAX endpoint
        $query_args['s'] = mb_substr($search_phrase, 0, 200);
        // search post titles only - matching content is not part of this feature
        $query_args['search_columns'] = ['post_title'];
        // a search phrase replaces taxonomy filtering - results come from the whole post type
        unset($query_args['tax_query']);
        return $query_args;
    }
    public function generate_tax_query($base_args, $taxonomy, $term_id) {
        unset($base_args['paged'], $base_args['offset']);
        // phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- taxonomy filtering is required
        $base_args['tax_query'] = [
            [
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $term_id,
                'operator' => 'IN',
            ],
        ];
        // phpcs:enable WordPress.DB.SlowDBQuery.slow_db_query_tax_query
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
            $this->quick_ajax_id = uniqid('c');
        }
    }
}