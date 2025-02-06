<?php 
if (!defined('ABSPATH')) {
    exit;
}
/* Post Item Name: Full Background Image Post Template */
?>

<div class="quick-ajax-post-item qapl-full-background-image-post-item">
    <a class="quick-ajax-post-link" href="<?php echo esc_url(get_permalink()); ?>">
        <?php qapl_output_template_post_image(); ?>
        <div class="post-content">
        <?php qapl_output_template_post_date(); ?>
        <?php qapl_output_template_post_title(); ?>
        <?php qapl_output_template_post_excerpt(); ?>
        <?php qapl_output_template_post_read_more(); ?>
        </div>
    </a>
</div>
