<?php 
if (!defined('ABSPATH')) {
    exit;
}
/* Post Item Name: Default Post Item Template */
?>
 <div class="quick-ajax-post-item qapl-post-item">
    <?php do_action('qapl_template_before_post_item'); ?>

    <a href="<?php echo esc_url(get_permalink()); ?>">
        <?php do_action('qapl_template_post_item_date'); ?>
        <?php do_action('qapl_template_post_item_image'); ?>
        <?php do_action('qapl_template_post_item_title'); ?>
        <?php do_action('qapl_template_post_item_excerpt'); ?>
        <?php // do_action('qapl_template_post_item_read_more'); ?>
    </a>

    <?php do_action('qapl_template_after_post_item'); ?>
</div>