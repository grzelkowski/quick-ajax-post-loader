<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Shortcode_Generator {

    public static function generate_shortcode(int $post_id): string {
        // guard clause to check if $post_id is valid
        if ($post_id <= 0) {
            return '';
        }
        // get the title of the post
        $post_title = get_the_title($post_id);
        // initialise variables
        $excluded_post_ids = '';
        // get serialized meta data from the post
        $serialized_data = get_post_meta($post_id, QAPL_Constants::DB_POSTMETA_SHORTCODE_SETTINGS, true);
        // check if serialized data exists and process it
        if ($serialized_data) {
            $form_data = maybe_unserialize($serialized_data);
            // ensure that the unserialized data is a valid array
            if (is_array($form_data)) {
                $excluded_key = QAPL_Constants::QUERY_SETTING_SET_POST_NOT_IN;
                if (isset($form_data[$excluded_key]) && !empty($form_data[$excluded_key])) {
                    $excluded_post_ids = ' excluded_post_ids="' . esc_attr($form_data[$excluded_key]) . '"';
                }
            }
        }
        // generate the shortcode with post ID and title, include excluded post ids if available
        $shortcode = '[qapl-quick-ajax id="' . $post_id . '" title="' . esc_attr($post_title) . '"' . $excluded_post_ids . ']';
        return $shortcode;
    }
}