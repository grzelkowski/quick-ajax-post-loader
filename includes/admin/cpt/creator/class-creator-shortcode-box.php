<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Creator_Shortcode_Box {
    public static function init() {
        add_action('edit_form_after_title', [__CLASS__, 'render']);
    }
    public static function render($post) {
        //check the post type
        if ($post && $post->post_type === QAPL_Constants::CPT_SHORTCODE_SLUG) {
            $shortcode = QAPL_Shortcode_Generator::generate_shortcode($post->ID);
            echo '<div id="shortcode-box-wrap">';
            echo '<span class="shortcode-description">' . esc_html__('Copy and paste this shortcode on the page to display the posts list', 'quick-ajax-post-loader') . '</span>';
            echo '<div class="click-and-select-all">';
            echo '<pre><code>' . esc_html($shortcode) . '</code></pre>';
            echo '</div>';
            echo '</div>';
        }
    }
}
QAPL_Creator_Shortcode_Box::init();