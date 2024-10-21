<?php 
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = '`$quick_ajax_attributes` Parameter for `qapl_quick_ajax_post_grid` and `qapl_quick_ajax_term_filter` Functions';
$accordion_block_content = '<p><strong>Description:</strong> The <code class="code-tag">quick_ajax_attributes</code> parameter is used to configure the appearance and behavior options of post grids and taxonomic filters in the <strong>Quick Ajax Post Loader</strong> plugin for WordPress. It allows for the customization of styles, number of columns, container classes, and other attributes that affect how dynamically loaded content is displayed and functions.</p>';
$accordion_block_content .= '<h4>Application</h4>';
$accordion_block_content .= '<p>The <code class="code-tag">$quick_ajax_attributes</code> parameter is crucial when using functions such as <code class="code-tag">qapl_quick_ajax_post_grid</code> and <code class="code-tag">qapl_quick_ajax_term_filter</code>, enabling detailed personalization of AJAX loaded content.</p>';
$accordion_block_content .= '<h4>Parameters</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><code class="no-background"><strong>quick_ajax_id</strong></code> (int): A unique identifier for the AJAX instance, allowing multiple independent grids on the same page.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>quick_ajax_css_style</strong></code> (int): Enables or disables built-in Quick Ajax CSS styles.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>grid_num_columns</strong></code> (int): Specifies the number of columns in the post grid.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>post_item_template</strong></code>: Allows for the selection of a post template, e.g., <code class="code-tag">\'post-item-custom-name\'</code> for a custom template. Specify the file name without the .php extension.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>taxonomy_filter_class</strong></code> (string): Adds custom CSS classes to the taxonomy filter.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>container_class</strong></code> (string): Adds custom CSS classes to the post grid container.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>load_more_posts</strong></code> (int): Specifies the number of posts to load upon clicking the "Load More" button.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>loader_icon</strong></code> (int): Allows for the selection of a loading icon.</li>';
$accordion_block_content .= '</ul>';
$accordion_block_content .= '<h4>Best Practices</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><p>Adjust <code class="code-tag">quick_ajax_attributes</code> according to the needs and style of your site to ensure visual consistency.</p></li>';
$accordion_block_content .= '<li><p>Test different combinations of parameters to find the perfect settings for your post grids and filters.</p></li>';
$accordion_block_content .= '<li><p>Use custom CSS classes for maximum customization and to avoid style conflicts.</p></li>';
$accordion_block_content .= '</ul>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
