<?php

if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Taxonomy_Options_Provider {
    public function get_taxonomy_options_for_post_type(string $post_type): array {
        $taxonomy_options = [];
        $post_type_object = get_post_type_object($post_type);
        if ($post_type_object) {
            $taxonomies = get_object_taxonomies($post_type);
            foreach ($taxonomies as $taxonomy) {
                $taxonomy_object = get_taxonomy($taxonomy);
                if ($taxonomy_object) {
                    $taxonomy_options[] = [
                        'label' => esc_html($taxonomy_object->label),
                        'value' => $taxonomy,
                    ];
                }
            }
        }
        if (empty($taxonomy_options)) {
            return [
                [
                    'label' => esc_html__('No taxonomy found', 'quick-ajax-post-loader'),
                    'value' => '',
                ],
            ];
        }
        return $taxonomy_options;
    }

    public function get_term_options_for_taxonomy(string $taxonomy): array {
        $terms = get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
        ]);
        if (empty($terms) || is_wp_error($terms)) {
            return [
                [
                    'label' => esc_html__('No terms found', 'quick-ajax-post-loader'),
                    'value' => '',
                ],
            ];
        }
        $options = [];
        foreach ($terms as $term) {
            $options[] = [
                'label' => esc_html($term->name),
                'value' => $term->term_id,
            ];
        }
        return $options;
    }
}