<?php 
$accordion_block_title = 'Generating Shortcodes for Displaying Posts';
$accordion_block_content = '<p><strong>Description:</strong> The <strong>Quick Ajax Post Loader</strong> plugin enables the creation of shortcodes that allow for the dynamic display of posts in WordPress using AJAX. Content can be loaded without the need to refresh the page, providing users with a smoother content browsing experience. To create a shortcode, simply go to the Quick Ajax -> Shortcodes or Add New section in the WordPress admin panel.</p>';
$accordion_block_content .= '<h4>Example of Use</h4>';
$accordion_block_content .= '<p>After creating a new shortcode, for example, <code class="code-tag">[quick-ajax id="1" title="My Ajax"]</code>, it can be placed anywhere on the page to initiate content loading through AJAX. This process eliminates the need for manual page refreshes, enabling quick and efficient access to updated content.</p>';
$accordion_block_content .= '<h4>Creating a Shortcode</h4>';
$accordion_block_content .= '<p>To create a new shortcode, visit the WordPress admin panel and select the Quick Ajax > Shortcodes or Add New option. In this section, you can configure the post type to display, display options, and other settings that will help customize the shortcode to your site\'s needs. After configuration, the shortcode is ready for use and can be placed on the page.</p>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
?>
