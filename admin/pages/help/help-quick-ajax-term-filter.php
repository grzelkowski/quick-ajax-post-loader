<?php 
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = '`qapl_quick_ajax_term_filter` Function';
$accordion_block_content = '<p><strong>Description:</strong> The <code class="code-tag">qapl_quick_ajax_term_filter</code> function enables dynamic loading and updating of posts based on selected taxonomy without reloading the entire page. It is a key tool for creating interactive, filterable post lists in WordPress, utilizing AJAX.</p>';
$accordion_block_content .= '<h4>Parameters</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><code class="no-background"><strong>$quick_ajax_args</strong></code> (array): An array of AJAX query parameters for posts, allowing for detailed configuration of the content displayed.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>$quick_ajax_attributes</strong></code> (array): An array of AJAX attributes, including CSS styles and other configuration options, enabling customization of the appearance and behavior of the post grid and filters.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>$quick_ajax_taxonomy</strong></code> (string): The name of the taxonomy used to filter posts, e.g., \'category\' or \'tag\'.</li>';
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
$accordion_block_content .= htmlentities(');') . "\n";
$accordion_block_content .= htmlentities('$quick_ajax_attributes = array(') . "\n";
$accordion_block_content .= htmlentities("    'quick_ajax_id' => 12056,") . "\n";
$accordion_block_content .= htmlentities("    'quick_ajax_css_style' => 1,") . "\n";
$accordion_block_content .= htmlentities("    'grid_num_columns' => 3,") . "\n";
$accordion_block_content .= htmlentities("    'post_item_template' => 'post-item-custom-name',") . "\n";
$accordion_block_content .= htmlentities("    'taxonomy_filter_class' => 'class-one class-two',") . "\n";
$accordion_block_content .= htmlentities("    'container_class' => 'class-one class-two',") . "\n";
$accordion_block_content .= htmlentities("    'load_more_posts' => 4,") . "\n";
$accordion_block_content .= htmlentities("    'loader_icon' => 'loader-icon-quick-ajax-dot'") . "\n";
$accordion_block_content .= htmlentities(');') . "\n";
$accordion_block_content .= htmlentities('$quick_ajax_taxonomy = \'category\';') . "\n\n";
$accordion_block_content .= htmlentities('if (function_exists(\'qapl_quick_ajax_term_filter\')):') . "\n";
$accordion_block_content .= htmlentities('    qapl_quick_ajax_term_filter(') . "\n";
$accordion_block_content .= htmlentities('        $quick_ajax_args,') . "\n";
$accordion_block_content .= htmlentities('        $quick_ajax_attributes,') . "\n";
$accordion_block_content .= htmlentities('        $quick_ajax_taxonomy') . "\n";
$accordion_block_content .= htmlentities('    );') . "\n";
$accordion_block_content .= htmlentities('endif;') . "\n";
$accordion_block_content .= '</code></pre>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
