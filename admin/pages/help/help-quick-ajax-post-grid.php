<?php 
$accordion_block_title = '`wpg_quick_ajax_post_grid` Function';
$accordion_block_content = '<p><strong>Description:</strong> The <code class="code-tag">wpg_quick_ajax_post_grid</code> function is designed for rendering a dynamic post grid in WordPress using AJAX technology. This function allows for the display of posts of a specific type without needing to reload the page, significantly improving user experience and page performance.</p>';
$accordion_block_content .= '<h4>Parameters</h4>';
$accordion_block_content .= '<ul>
<li><code class="no-background"><strong>$quick_ajax_args</strong></code> (array): An array of AJAX query parameters for posts, enabling the configuration of displayed content.</li>
<li><code class="no-background"><strong>$quick_ajax_attributes</strong></code> (array): An array of AJAX attributes, including CSS styles and other configuration options, allowing for customization of the appearance and behavior of the post grid.</li>
</ul>';
$accordion_block_content .= '<h4>Example Usage</h4>';
$accordion_block_content .= <<<HTML
<pre><code class="no-background">\$quick_ajax_args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 6,
    'orderby' => 'date',
    'order' => 'DESC',
    'post__not_in' => array(3, 66, 100),
);

\$quick_ajax_attributes = array(
    'quick_ajax_id' => 12056,
    'quick_ajax_css_style' => 1,
    'grid_num_columns' => 3,
    'post_item_template' => 'post-item-custom-name',
    'taxonomy_filter_class' => 'class-one class-two',
    'container_class' => 'class-one class-two',
    'load_more_posts' => 4,
    'loader_icon' => 'loader-icon-quick-ajax-dot'
);

if(function_exists('wpg_quick_ajax_post_grid')):
    wpg_quick_ajax_post_grid(
        \$quick_ajax_args,
        \$quick_ajax_attributes
    );
endif;
</code></pre>
HTML;

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
?>
