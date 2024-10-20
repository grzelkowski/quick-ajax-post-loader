<?php
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = 'Customizing the Taxonomy Filter Button';
$accordion_block_content = '<p><strong>Description:</strong> The <strong>Quick Ajax Post Loader</strong> plugin allows for customization of the taxonomy filter button by modifying the <code class="code-tag">term-filter-button.php</code> file. This file is responsible for the appearance and functionality of the buttons used for filtering posts by category, tags, or other taxonomies.</p>';
$accordion_block_content .= '<h4>How to Customize the Filter Button</h4>';
$accordion_block_content .= '<p>To customize the filter button, you need to override the <code class="code-tag">term-filter-button.php</code> file in the directory <code class="code-tag">/qapl-quick-ajax-post-loader/templates/term-filter/</code> in your theme or child theme. Modifying this file allows for changes in the style, class, and attributes of the button, enabling better alignment with the look and needs of your site.</p>';
$accordion_block_content .= '<h4>Example of a Customized Button</h4>';
$accordion_block_content .= '<p>Here is an example code of a modified filter button, incorporating dynamic label changes with <code class="code-tag">QUICK_AJAX_LABEL</code> and using the attribute <code class="code-tag">data-button="quick-ajax-filter-button"</code> to specify the button\'s behavior in an AJAX context:</p>';
$accordion_block_content .= '<pre><code class="no-background">';
$accordion_block_content .= htmlentities('<button type="button" class="filter-button custom-class" data-button="quick-ajax-filter-button">QUICK_AJAX_LABEL</button>') . "\n";
$accordion_block_content .= '</code></pre>';
$accordion_block_content .= '<h4>Best Practices</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><p>Use CSS class naming conventions consistent with your theme or site to maintain visual consistency.</p></li>';
$accordion_block_content .= '<li><p>Test changes across different devices and browsers to ensure the button is displayed and functions properly.</p></li>';
$accordion_block_content .= '<li><p>Use <code class="code-tag">data-*</code> attributes to pass information for filtering scripts, being cautious when modifying them. The <code class="code-tag">data-button="quick-ajax-filter-button"</code> attribute is crucial for integration with AJAX logic, enabling dynamic content loading without page reloads.</p></li>';
$accordion_block_content .= '</ul>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
?>
