<?php 
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = '`qapl_quick_ajax_post_grid` Function';
$accordion_block_content = '<p><strong>Description:</strong> The <code class="code-tag">qapl_quick_ajax_post_grid</code> function is designed for rendering a dynamic post grid in WordPress using AJAX technology. This function allows for the display of posts of a specific type without needing to reload the page, significantly improving user experience and page performance.</p>';
$accordion_block_content .= '<h4>Parameters</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><code class="no-background"><strong>$quick_ajax_args</strong></code> (array): An array of AJAX query parameters for posts, enabling the configuration of displayed content.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>$quick_ajax_attributes</strong></code> (array): An array of AJAX attributes, including CSS styles and other configuration options, allowing for customization of the appearance and behavior of the post grid.</li>';
$accordion_block_content .= '</ul>';
$accordion_block_content .= '<h4>Example Usage</h4>';
$accordion_block_content .= '<pre><code class="no-background">';
$accordion_block_content .= htmlentities('$quick_ajax_args = array(') . "\n";
$accordion_block_content .= htmlentities("    'post_type' => 'post',") . "\n";
$accordion_block_content .= htmlentities("    'post_status' => 'publish',") . "\n";
$accordion_block_content .= htmlentities("    'posts_per_page' => 6,") . "\n";
$accordion_block_content .= htmlentities("    'orderby' => 'date',") . "\n";
$accordion_block_content .= htmlentities("    'order' => 'DESC',") . "\n";
$accordion_block_content .= htmlentities("    'post__not_in' => array(3, 66, 100),") . "\n";
$accordion_block_content .= htmlentities(');') . "\n\n";
$accordion_block_content .= htmlentities('$quick_ajax_attributes = array(') . "\n";
$accordion_block_content .= htmlentities("    'quick_ajax_id' => 12056,") . "\n";
$accordion_block_content .= htmlentities("    'quick_ajax_css_style' => 1,") . "\n";
$accordion_block_content .= htmlentities("    'grid_num_columns' => 3,") . "\n";
$accordion_block_content .= htmlentities("    'post_item_template' => 'post-item-custom-name',") . "\n";
$accordion_block_content .= htmlentities("    'taxonomy_filter_class' => 'class-one class-two',") . "\n";
$accordion_block_content .= htmlentities("    'container_class' => 'class-one class-two',") . "\n";
$accordion_block_content .= htmlentities("    'load_more_posts' => 4,") . "\n";
$accordion_block_content .= htmlentities("    'loader_icon' => 'loader-icon-quick-ajax-dot'") . "\n";
$accordion_block_content .= htmlentities(');') . "\n\n";
$accordion_block_content .= htmlentities('if(function_exists(\'qapl_quick_ajax_post_grid\')):') . "\n";
$accordion_block_content .= htmlentities('    qapl_quick_ajax_post_grid(') . "\n";
$accordion_block_content .= htmlentities('        $quick_ajax_args,') . "\n";
$accordion_block_content .= htmlentities('        $quick_ajax_attributes') . "\n";
$accordion_block_content .= htmlentities('    );') . "\n";
$accordion_block_content .= htmlentities('endif;') . "\n";
$accordion_block_content .= '</code></pre>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
