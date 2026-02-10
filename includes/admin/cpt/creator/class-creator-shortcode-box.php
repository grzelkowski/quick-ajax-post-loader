<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Creator_Shortcode_Box {
    public static function init() {
        add_action('edit_form_after_title', [__CLASS__, 'render']);
    }
    public static function render($post) {
    if ($post && $post->post_type === QAPL_Constants::CPT_SHORTCODE_SLUG) {
        $shortcode = QAPL_Shortcode_Generator::generate_shortcode($post->ID);
        ?>
        <div id="shortcode-box-wrap">
            <p class="shortcode-description"><?php esc_html_e('Copy and paste this shortcode on the page to display the posts list', 'quick-ajax-post-loader'); ?></p>
            <div class="qapl-shortcode-display">
                <input type="text" id="qapl-shortcode-input" class="click-and-select-input" value="<?php echo esc_attr($shortcode); ?>" readonly />
                <button type="button" class="copy-button-input button button-primary button-large"
                    data-copy="qapl-shortcode-input" 
                    data-label-copied="<?php esc_html_e('Copied', 'quick-ajax-post-loader'); ?>">
                    <?php esc_html_e('Copy', 'quick-ajax-post-loader'); ?>
                </button>
            </div>
        </div>
        <?php
    }
}
}
QAPL_Creator_Shortcode_Box::init();