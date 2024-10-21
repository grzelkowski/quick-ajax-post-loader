<?php 
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = '`$quick_ajax_args` Parameter for `qapl_quick_ajax_post_grid` and `qapl_quick_ajax_term_filter` Functions';
$accordion_block_content = '<p><strong>Description:</strong> The <code class="code-tag">$quick_ajax_args</code> parameter is crucial for configuring AJAX queries in the <strong>Quick Ajax Post Loader</strong> plugin. It allows for detailed specification of which posts to load and display in a post grid or using taxonomic filters, providing a dynamic and interactive user experience on the site.</p>';
$accordion_block_content .= '<h4>Application</h4>';
$accordion_block_content .= '<p>The <code class="code-tag">$quick_ajax_args</code> is utilized in functions such as <code class="code-tag">qapl_quick_ajax_post_grid</code> and <code class="code-tag">qapl_quick_ajax_term_filter</code>, enabling flexible and advanced content management without the need for page reloads.</p>';
$accordion_block_content .= '<h4>Parameters</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><code class="no-background"><strong>post_type</strong></code> (string): Type of posts to load, e.g., \'post\', \'page\', custom post types.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>post_status</strong></code> (string): Status of posts to display, e.g., \'publish\'.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>posts_per_page</strong></code> (int): Number of posts to display per page.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>orderby</strong></code> (string): Criterion for sorting posts, e.g., \'date\', \'title\'.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>order</strong></code> (string): Order of post sorting, e.g., \'ASC\', \'DESC\'.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>post__not_in</strong></code> (array): Array of post IDs to exclude.</li>';
$accordion_block_content .= '</ul>';
$accordion_block_content .= '<h4>Best Practices</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><p>Adjust the <code class="code-tag">$quick_ajax_args</code> parameters precisely to the needs of the site to ensure efficient and purposeful content loading.</p></li>';
$accordion_block_content .= '<li><p>Regularly test <code class="code-tag">$quick_ajax_args</code> configurations to ensure they are optimal both in terms of performance and usability.</p></li>';
$accordion_block_content .= '<li><p>Remember to tailor the <code class="code-tag">$quick_ajax_args</code> parameters according to the goals and needs of the specific project or function on the site.</p></li>';
$accordion_block_content .= '</ul>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
