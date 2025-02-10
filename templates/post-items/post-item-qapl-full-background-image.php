<?php 
if (!defined('ABSPATH')) {
    exit;
}
/* Post Item Name: Background Image Post Template */
?>

<div class="qapl-post-item qapl-post-item-bg-img">
    <a class="qapl-post-link" href="<?php echo esc_url(get_permalink()); ?>">
        <?php qapl_output_template_post_image(); ?>
        <div class="post-content">
        <?php qapl_output_template_post_date(); ?>
        <?php qapl_output_template_post_title(); ?>
        <?php qapl_output_template_post_excerpt(); ?>
        <?php qapl_output_template_post_read_more(); ?>
        </div>
    </a>
</div>
