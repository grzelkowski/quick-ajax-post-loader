<?php 
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

$accordion_block_title = 'Available Template Filters in Quick Ajax Post Loader';
$accordion_block_content = '<p><strong>Description:</strong> The template filters available through <code class="code-tag">apply_filters</code> in <strong>Quick Ajax Post Loader</strong> allow you to customize the HTML output of various template components. These filters give you the flexibility to modify the rendered output for date, image, title, excerpt, read more, and load more button elements.</p>';

$accordion_block_content .= '<h4>Available Template Filters</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><code class="no-background"><strong>qapl_template_post_item_date</strong></code>: Filter the HTML output for the post date element.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>qapl_template_post_item_image</strong></code>: Filter the HTML output for the post image element.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>qapl_template_post_item_title</strong></code>: Filter the HTML output for the post title element.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>qapl_template_post_item_excerpt</strong></code>: Filter the HTML output for the post excerpt element.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>qapl_template_post_item_read_more</strong></code>: Filter the HTML output for the "read more" element.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>qapl_template_load_more_button</strong></code>: Filter the HTML output for the load more button element.</li>';
$accordion_block_content .= '</ul>';

$accordion_block_content .= '<h4>How to Use</h4>';
$accordion_block_content .= '<p>You can modify the template output by hooking into these filters using the <code class="code-tag">add_filter()</code> function. For example, to change the post title markup, add the following code:</p>';

// Example for modifying the date
$accordion_block_content .= '<h4>Example: Customizing the Post Date Format</h4>';
$accordion_block_content .= '<pre><code class="no-background">';
$accordion_block_content .= htmlentities("function custom_qapl_date(\$output, \$template, \$quick_ajax_id) {") . "\n";
$accordion_block_content .= htmlentities("    if (\$template === 'post-item') { // Apply only to the default 'post-item' template") . "\n";
$accordion_block_content .= htmlentities("        \$new_date = get_the_date('d-m-Y'); // Change the date format to 'd-m-Y'") . "\n";
$accordion_block_content .= htmlentities("        \$output = '<div class=\"qapl-post-date\"><span> Date: ' . esc_html(\$new_date) . '</span></div>';") . "\n";
$accordion_block_content .= htmlentities("    }") . "\n";
$accordion_block_content .= htmlentities("    return \$output;") . "\n";
$accordion_block_content .= htmlentities("}") . "\n";
$accordion_block_content .= htmlentities("add_filter('qapl_template_post_item_date', 'custom_qapl_date', 10, 3);") . "\n";
$accordion_block_content .= '</code></pre>';

// Example for modifying the title
$accordion_block_content .= '<h4>Example: Customizing the Post Title for a Specific Container</h4>';
$accordion_block_content .= '<pre><code class="no-background">';
$accordion_block_content .= htmlentities("function custom_qapl_title(\$output, \$template, \$quick_ajax_id) {") . "\n";
$accordion_block_content .= htmlentities("    if (\$quick_ajax_id === 'quick-ajax-p100') { // Apply only to the container with ID 'quick-ajax-p100'") . "\n";
$accordion_block_content .= htmlentities("        \$output = '<div class=\"qapl-post-title\"><h5> Title: ' . esc_html(get_the_title()) . '</h5></div>';") . "\n";
$accordion_block_content .= htmlentities("    }") . "\n";
$accordion_block_content .= htmlentities("    return \$output;") . "\n";
$accordion_block_content .= htmlentities("}") . "\n";
$accordion_block_content .= htmlentities("add_filter('qapl_template_post_item_title', 'custom_qapl_title', 10, 3);") . "\n";
$accordion_block_content .= '</code></pre>';

$accordion_block_content .= '<p>Using the appropriate filters makes it easy to customize different aspects of the plugin\'s template rendering process.</p>';

return [
    'title'   => $accordion_block_title,
    'content' => $accordion_block_content,
];
