<?php 
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = 'AJAX Function Generator Tool in PHP';
$accordion_block_content = '<p><strong>Description:</strong> The <strong>AJAX Function Generator</strong> in the <strong>Quick Ajax Post Loader</strong> plugin is a tool available in the Quick Ajax > Settings & Features menu, under the "Function Generator" tab. It enables the creation of PHP code that can be directly woven into the body of a WordPress page, such as in <code class="code-tag">page.php</code>, <code class="code-tag">single.php</code>, or an individual page template. These functions act similarly to shortcodes but offer greater flexibility in implementation and the ability to be directly included in the theme code.</p>';
$accordion_block_content .= '<h4>Example Application</h4>';
$accordion_block_content .= '<p>The generated code allows for the dynamic display of posts using AJAX, without the need for refreshing the page. To use it, simply copy the generated function code and paste it into the appropriate place in the theme template file where the AJAX content is to be displayed.</p>';
$accordion_block_content .= '<h4>Code Implementation</h4>';
$accordion_block_content .= '<p>Open the template file, for example, <code class="code-tag">page.php</code>, at the location where you want the posts to be displayed, and paste the function code. This code will automatically take care of displaying the posts according to the defined parameters and attributes.</p>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
