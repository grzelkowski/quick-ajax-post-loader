<?php 
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = 'Creating and Using Custom Post Templates';
$accordion_block_content = '<p><strong>Description:</strong> The <strong>Quick Ajax Post Loader</strong> plugin allows users to create and apply their own post templates, offering the ability to personalize the appearance and behavior of dynamically loaded content on a WordPress site. You can override the default post template by creating a <code class="code-tag">post-item.php</code> file in the appropriate directory, or add any number of your own templates, which will then be available for selection in the select field when configuring the shortcode.</p>';
$accordion_block_content .= '<h4>How to Create Your Own Templates</h4>';
$accordion_block_content .= '<p>To override the default post template, create a file named <code class="code-tag">post-item.php</code> and place it in the directory <code class="code-tag">wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/post-items/</code> in your theme or child theme. This file will replace the default template used by the plugin. To add more templates, name files according to the format <code class="code-tag">post-item-custom-name.php</code> and place them in the same directory. At the top line of each template file, add a comment with the template name, which will be visible in the plugin administration panel.</p>';
$accordion_block_content .= '<h4>Template File Naming Rules</h4>';
$accordion_block_content .= '<p>For a template file to be detected by the plugin, it must meet certain naming rules:</p>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><p>For the default post template: the file must be named <code class="code-tag">post-item.php</code> and placed in the directory <code class="code-tag">wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/post-items/</code>. This file will automatically override the plugin\'s built-in default template.</p></li>';
$accordion_block_content .= '<li><p>For custom templates: files must be named starting with <code class="code-tag">\'post-item\'</code>, e.g., <code class="code-tag">post-item-custom-name.php</code>. This naming format allows the plugin to identify the files as additional post templates.</p></li>';
$accordion_block_content .= '<li><p>If a template file contains a comment with the template name in the format <code class="code-tag">/* Post Item Name: Template Name */</code>, this name will be used in the plugin administration panel as the template name. If there is no such comment, the file name (without the .php extension) will be displayed as the template name in the administration panel.</p></li>';
$accordion_block_content .= '</ul>';
$accordion_block_content .= '<h4>Template Example</h4>';
$accordion_block_content .= '<p>Below is an example code for a post template:</p>';
$accordion_block_content .= '<pre><code class="no-background">';
$accordion_block_content .= htmlentities('<'.'?php ') . "\n";
$accordion_block_content .= htmlentities('/* Post Item Name: My Custom Template Name */') . "\n";
$accordion_block_content .= htmlentities('?'.'>') . "\n";
$accordion_block_content .= htmlentities('<div class="quick-ajax-post-item">') . "\n";
$accordion_block_content .= htmlentities('    <a href="<?php echo get_permalink(); ?>">') . "\n";
$accordion_block_content .= htmlentities('        <!-- Here, add code to display the post, e.g., thumbnail, title -->') . "\n";
$accordion_block_content .= htmlentities('    </a>') . "\n";
$accordion_block_content .= htmlentities('</div>') . "\n";
$accordion_block_content .= '</code></pre>';
$accordion_block_content .= '<h4>"No Posts" Message</h4>';
$accordion_block_content .= '<p>You can also customize the message displayed when there are no posts to show by creating a <code class="code-tag">no-posts.php</code> file in the directory <code class="code-tag">wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/post-items/</code>, to override the default message.</p>';
$accordion_block_content .= '<h4>Template Selection</h4>';
$accordion_block_content .= '<p>Created templates, including the <code class="code-tag">post-item.php</code> file, will be automatically detected by the plugin and available for selection in the select field when configuring the shortcode. This allows for easy change of post appearance without the need to edit source code.</p>';
$accordion_block_content .= '<h4>Template Overriding and Loading Rules</h4>';
$accordion_block_content .= '<p>The plugin respects the WordPress hierarchy, loading templates in the following order: child theme, theme, plugin. This means that if a template exists in the child theme, it will have priority over the theme and the plugin\'s built-in template. This rule allows for safe modifications without the risk of losing changes during theme or plugin updates.</p>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><p>By placing a template in the <strong>child theme</strong>, you ensure its persistence through theme updates.</p></li>';
$accordion_block_content .= '<li><p>By placing a template directly in the <strong>theme</strong>, you gain the ability for quick customization, but with the risk of losing changes during theme updates.</p></li>';
$accordion_block_content .= '<li><p>The plugin will automatically detect available templates in these locations and allow their selection in the shortcode configuration.</p></li>';
$accordion_block_content .= '</ul>';
$accordion_block_content .= '<h4>Best Practices</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><p>Name your template files clearly to easily identify them.</p></li>';
$accordion_block_content .= '<li><p>Test templates in different environments and configurations to ensure they look good on all devices and in various browsers.</p></li>';
$accordion_block_content .= '<li><p>Use appropriate CSS classes to ensure style consistency with the rest of your site.</p></li>';
$accordion_block_content .= '</ul>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
