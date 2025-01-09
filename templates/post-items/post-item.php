<?php 
if (!defined('ABSPATH')) {
    exit;
}
/* Post Item Name: Default Post Item Template */
?>
<div class="quick-ajax-post-item">
    <a href="<?php echo esc_url(get_permalink()); ?>">
        <div class="post-date">
            <span><?php echo esc_html(get_the_date()); ?></span>
        </div>
        <?php 
        if (has_post_thumbnail()) {
            $thumbnail_id = get_post_thumbnail_id();
            echo '<div class="post-image">';
            echo wp_get_attachment_image($thumbnail_id, 'full');
            echo '</div>';
        }
        ?>
        <div class="post-title <?php echo has_post_thumbnail() ? '' : 'no-image'; ?>">
            <h3><?php echo esc_html(get_the_title()); ?></h3>
        </div>
        <div class="post-desc">
            <p><?php echo esc_html(wp_trim_excerpt()); ?></p>
            <p>[[QAPL::READ_MORE_LABEL]]</p>
        </div>
    </a>
</div>
