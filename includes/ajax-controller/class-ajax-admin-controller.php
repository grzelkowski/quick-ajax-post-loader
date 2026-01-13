<?php 
if (!defined('ABSPATH')) {
    exit;
}
// phpcs:disable WordPress.Security.NonceVerification.Missing -- nonce verified in verify_request
final class QAPL_Ajax_Admin_Controller {
    use QAPL_Ajax_Request_Verifier;

    public static function register(): void {
        // get taxonomies
        add_action('wp_ajax_qapl_action_get_taxonomies_by_post_type', [self::class, 'get_taxonomies_by_post_type']);
        add_action('wp_ajax_nopriv_qapl_action_get_taxonomies_by_post_type', [self::class, 'get_taxonomies_by_post_type']);

        // get terms
        add_action('wp_ajax_qapl_action_get_terms_by_taxonomy', [self::class, 'get_terms_by_taxonomy']);
        add_action('wp_ajax_nopriv_qapl_action_get_terms_by_taxonomy', [self::class, 'get_terms_by_taxonomy']);
    }
    public static function get_taxonomies_by_post_type(): void {
        self::verify_request();
        if (empty($_POST['post_type'])) {
            wp_send_json_error(['message' => 'Quick Ajax Post Loader: Invalid request, missing post type.']);
        }
        $post_type = sanitize_text_field(wp_unslash($_POST['post_type']));
        $taxonomies = get_object_taxonomies($post_type, 'objects');

        ob_start();
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                echo '<option value="' . esc_attr($taxonomy->name) . '">' . esc_html($taxonomy->label) . '</option>';
            }
        } else {
            echo '<option value="0">' . esc_html__('No taxonomy found', 'quick-ajax-post-loader') . '</option>';
        }
        $output = ob_get_clean();

        wp_send_json_success($output);
        wp_die();
    }
    public static function get_terms_by_taxonomy(): void {
        self::verify_request();
        //return info if No taxonomy
        if (empty($_POST['taxonomy']) || $_POST['taxonomy'] === '0') {
            ob_start();
            echo '<div class="quick-ajax-multiselect-option"><span class="no-options">' . esc_html__('No taxonomy available', 'quick-ajax-post-loader') . '</span></div>';
            $output = ob_get_clean();
            wp_send_json_success($output);
            wp_die();
        }
        $taxonomy = sanitize_text_field(wp_unslash($_POST['taxonomy']));
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]);
        //get checked terms if saved in post_meta
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $saved_terms = [];
        if ($post_id > 0) {
            // get saved terms from post meta
            $post_meta = get_post_meta($post_id, QAPL_Constants::DB_POSTMETA_SHORTCODE_SETTINGS, true);
            $post_meta_values = maybe_unserialize($post_meta);
            // if terms exist in post meta
            if (is_array($post_meta_values) && isset($post_meta_values['qapl_manual_selected_terms'])) {
                $saved_terms = array_map('intval', (array) $post_meta_values['qapl_manual_selected_terms']);
            }
        }
        ob_start();
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                ?>
                <div class="quick-ajax-multiselect-option">
                    <label>
                        <input type="checkbox" name="qapl_manual_selected_terms[]" value="<?php echo esc_attr($term->term_id); ?>" <?php checked(in_array($term->term_id, $saved_terms)); ?>>
                        <?php echo esc_html($term->name); ?>
                    </label>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="quick-ajax-multiselect-option">
                <span class="no-options"><?php echo esc_html__('No terms found', 'quick-ajax-post-loader'); ?></span>
            </div>
            <?php
        }
        $output = ob_get_clean();
        wp_send_json_success($output);
        wp_die();
    }
}
// phpcs:enable WordPress.Security.NonceVerification.Missing
if (is_admin()){
    QAPL_Ajax_Admin_Controller::register();
}