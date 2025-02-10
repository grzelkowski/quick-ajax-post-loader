<?php 
if (!defined('ABSPATH')) {
    exit;
}
/* Post Item Name: Default Post Item Template */
?>
<div class="qapl-post-item qapl-post-item-default">
    <a class="qapl-post-link" href="<?php echo esc_url(get_permalink()); ?>">
        <?php qapl_output_template_post_date(); ?>
        <?php qapl_output_template_post_image(); ?>
        <?php qapl_output_template_post_title(); ?>
        <?php qapl_output_template_post_excerpt(); ?>
        <?php qapl_output_template_post_read_more(); ?>
    </a>
</div>