<?php 
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = 'Creating and Using Custom Loading Icons';
$accordion_block_content = '<p><strong>Description:</strong> The <strong>Quick Ajax Post Loader</strong> plugin offers the ability to customize loading icons using your own templates. You can create any number of custom loading icons, which will then be available in the plugin configuration.</p>';
$accordion_block_content .= '<h4>How to Create Your Own Loading Icons</h4>';
$accordion_block_content .= '<p>Create a file with any name, e.g., <code class="code-tag">loader-icon-custom-loader.php</code>, and place it in the directory <code class="code-tag">/qapl-quick-ajax-post-loader/templates/loader-icon/</code> in your theme or child theme. The plugin will automatically detect all files in this directory as available loading icons.</p>';
$accordion_block_content .= '<h4>Loading Icon Example</h4>';
$accordion_block_content .= '<p>Below is an example code for a custom loading icon:</p>';
$accordion_block_content .= '<pre><code class="no-background">';
$accordion_block_content .= htmlentities('<'.'?php ') . "\n";
$accordion_block_content .= htmlentities('/* Loader Icon Name: Custom Loader */ ') . "\n";
$accordion_block_content .= htmlentities('?'.'>') . "\n";
$accordion_block_content .= htmlentities('<div class="quick-ajax-loader-custom">') . "\n";
$accordion_block_content .= htmlentities('    <!-- Here, add your HTML code, GIF image, or CSS animation for the loading icon, e.g.: -->') . "\n";
$accordion_block_content .= htmlentities('    <img src="images/loader_image.gif" alt="Loading..." />') . "\n";
$accordion_block_content .= htmlentities('    <!-- Or create a simple CSS animation -->') . "\n";
$accordion_block_content .= htmlentities('    <div class="loader-dot"></div>') . "\n";
$accordion_block_content .= htmlentities('    <div class="loader-dot"></div>') . "\n";
$accordion_block_content .= htmlentities('    <div class="loader-dot"></div>') . "\n";
$accordion_block_content .= htmlentities('</div>') . "\n";
$accordion_block_content .= '</code></pre>';

$accordion_block_content .= '<h4>Rules for Overriding and Loading Icons</h4>';
$accordion_block_content .= '<p>The loading icon can be placed in a child theme or theme to ensure its persistence through updates. The plugin loads loading icons from the child theme, then from the theme, and finally from the plugin\'s built-in templates, allowing for easy customization without the risk of losing changes.</p>';
$accordion_block_content .= '<h4>Best Practices</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><p>Test loading icons in different environments to ensure they are displayed correctly across all devices and in various browsers.</p></li>';
$accordion_block_content .= '<li><p>Use clean and efficient HTML/CSS code to ensure fast loading of icons.</p></li>';
$accordion_block_content .= '</ul>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
?>
