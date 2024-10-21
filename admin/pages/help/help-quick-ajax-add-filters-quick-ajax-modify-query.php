<?php
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = 'add_filter: How to Use `qapl_modify_query`';
$accordion_block_content = '<p><strong>Description:</strong> The <code class="code-tag">qapl_modify_query</code> filter allows for the customization of WP_Query arguments used in the <strong>Quick Ajax Post Loader</strong> plugin. This enables detailed control over AJAX query results, tailoring them to the unique needs of your site.</p>';
$accordion_block_content .= '<h4>Usage Example:</h4>';
$accordion_block_content .= '<p>By adding your own modifying function, you can precisely adjust the query arguments, as shown below. The example demonstrates the use of the identifier <code class="code-tag">$quick_ajax_id</code> to modify queries for a specific AJAX container:</p>';
$accordion_block_content .= '<pre><code class="no-background">';
$accordion_block_content .= htmlentities("add_filter('qapl_modify_query', function(\$args, \$quick_ajax_id) {") . "\n";
$accordion_block_content .= htmlentities("    // Using the AJAX identifier to modify query arguments") . "\n";
$accordion_block_content .= htmlentities("    if (\$quick_ajax_id === 'some_specific_id') {") . "\n";
$accordion_block_content .= htmlentities("        \$args['posts_per_page'] = 5; // Change the number of posts per page to 5") . "\n";
$accordion_block_content .= htmlentities("    }") . "\n";
$accordion_block_content .= htmlentities("    return \$args;") . "\n";
$accordion_block_content .= htmlentities("}, 10, 2);") . "\n";
$accordion_block_content .= '</code></pre>';
$accordion_block_content .= '<p>This example illustrates how to change the number of posts per page to 5, using a specific AJAX identifier. This allows for more targeted and flexible query management.</p>';
$accordion_block_content .= '<p><strong>Finding the quick_ajax_id:</strong> To find the <code class="code-tag">quick_ajax_id</code>, look for the <code class="code-tag">id</code> attribute of the outer div containing the AJAX buttons. For example, <code class="code-tag">&lt;div id="quick-ajax-<strong>p9</strong>" class="quick-ajax-posts-wrapper"&gt;</code> indicates that the <code class="code-tag">quick_ajax_id</code> is <code class="code-tag">"p9"</code>. This is crucial for identifying the container in your filters.</p>';
$accordion_block_content .= '<p>For debugging, you can use <code class="code-tag">print_r($quick_ajax_id)</code> in your modifying function to see the identifier. Remember that in a production environment, exposing such information to users should be avoided.</p>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
