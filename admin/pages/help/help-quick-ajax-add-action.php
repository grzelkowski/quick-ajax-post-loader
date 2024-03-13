<?php 
$accordion_block_title = 'add_action: List of Available Actions';
$accordion_block_content = '<p><strong>Description:</strong> The actions available through <code class="code-tag">add_action</code> in <strong>Quick Ajax Post Loader</strong> allow for custom modifications at key moments of the plugin\'s operation. These hooks enable developers to customize the rendering process of filters, post content, and other elements, providing greater flexibility and possibilities for extending functionality.</p>';
$accordion_block_content .= '<h4>Available Actions</h4>';
$accordion_block_content .= '<ul>
<li><code class="no-background"><strong>before_quick_ajax_filter_wrapper</strong></code>: Before rendering the AJAX filter wrapper. Perfect for adding custom HTML.</li>
<li><code class="no-background"><strong>quick_ajax_filter_wrapper_start</strong></code>: At the beginning of the AJAX filter wrapper rendering. Allows for inserting content at the start.</li>
<li><code class="no-background"><strong>quick_ajax_filter_wrapper_end</strong></code>: At the end of the AJAX filter wrapper rendering. Enables adding content before closing the wrapper.</li>
<li><code class="no-background"><strong>after_quick_ajax_filter_wrapper</strong></code>: After rendering the AJAX filter wrapper.</li>
<li><code class="no-background"><strong>before_quick_ajax_posts_wrapper</strong></code>: Before rendering the AJAX posts wrapper.</li>
<li><code class="no-background"><strong>quick_ajax_posts_wrapper_start</strong></code>: Right after opening the posts wrapper. Ideal for adding your own content at the beginning of the section.</li>
<li><code class="no-background"><strong>before_quick_ajax_load_more_button</strong></code>: Before rendering the "Load More" button. Allows for adding content before the button.</li>
<li><code class="no-background"><strong>after_quick_ajax_load_more_button</strong></code>: After rendering the "Load More" button. Allows for adding content after the button.</li>
<li><code class="no-background"><strong>before_quick_ajax_loader_icon</strong></code>: Before rendering the loading icon. Perfect for adding content before the icon.</li>
<li><code class="no-background"><strong>after_quick_ajax_loader_icon</strong></code>: After rendering the loading icon. Enables adding content after the icon.</li>
<li><code class="no-background"><strong>quick_ajax_posts_wrapper_end</strong></code>: Just before closing the posts wrapper. Allows for adding content at the end of the posts section.</li>
<li><code class="no-background"><strong>after_quick_ajax_posts_wrapper</strong></code>: After rendering the AJAX posts wrapper.</li>
</ul>';
$accordion_block_content .= '<h4>How to Use</h4>';
$accordion_block_content .= '<p>Adding your own actions is straightforward using the <code class="code-tag">add_action()</code> function. Below is an example of how to add custom text before the AJAX filter wrapper:</p>';
$accordion_block_content .= '<pre><code class="no-background">
add_action(\'before_quick_ajax_filter_wrapper\', function() {
    echo \'Custom text before the filter navigation\';
});
</code></pre>';
$accordion_block_content .= '<p>Using the appropriate hooks makes it easy to customize different aspects of the plugin\'s operation according to the needs of your site.</p>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
?>
