<?php 
/* Post Item Name: Default Post Item Template */
?>
<div class="quick-ajax-post-item">
    <a href="<?php echo get_permalink() ?>">
        <?php 
        if (has_post_thumbnail()) {
            $thumbnail_id = get_post_thumbnail_id();
            $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'full');
            $thumbnail_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
            echo '<div class="post-image">';
            echo '<img src="' . $thumbnail_url[0] . '" alt="' . $thumbnail_alt . '">';
            echo '</div>';
        }
        ?>    
        <div class="post-title">
            <h3><?php the_title(); ?></h3>
        </div>
        <div class="post-desc">
            <p><?php echo wp_trim_excerpt(); ?></p>
        </div>
    </a>
</div>
