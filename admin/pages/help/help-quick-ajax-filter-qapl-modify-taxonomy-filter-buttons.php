<?php
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = 'How to Modify Taxonomy Filter Buttons Using the `qapl_modify_taxonomy_filter_buttons` Filter';
$accordion_block_content = '<p><strong>Description:</strong> The <code class="code-tag">qapl_modify_taxonomy_filter_buttons</code> filter allows customization of the properties of buttons used for filtering site content based on taxonomy terms in the <strong>Quick Ajax Post Loader</strong> plugin. With two parameters: an array of buttons and an AJAX identifier, the filter offers the possibility of precisely personalizing these elements.</p>';
$accordion_block_content .= '<h4>Usage Example:</h4>';
$accordion_block_content .= '<p>To modify the properties of the buttons and utilize the AJAX identifier, you can add your own modifying function in the following way:</p>';
$accordion_block_content .= '<pre><code class="no-background">';
$accordion_block_content .= htmlentities("add_filter('qapl_modify_taxonomy_filter_buttons', function(\$buttons, \$quick_ajax_id) {") . "\n";
$accordion_block_content .= htmlentities("    // Modifying button properties considering the AJAX identifier") . "\n";
$accordion_block_content .= htmlentities("    foreach (\$buttons as &\$button) {") . "\n";
$accordion_block_content .= htmlentities("        if (\$quick_ajax_id === 'some_specific_id') {") . "\n";
$accordion_block_content .= htmlentities("            // Customizing the label of the \"Show All\" button") . "\n";
$accordion_block_content .= htmlentities("            if (\$button['term_id'] === 'none') {") . "\n";
$accordion_block_content .= htmlentities("                \$button['button_label'] = 'View All'; // Changing the label to \"View All\"") . "\n";
$accordion_block_content .= htmlentities("            } else {") . "\n";
$accordion_block_content .= htmlentities("                // Converting labels of other buttons to uppercase") . "\n";
$accordion_block_content .= htmlentities("                \$button['button_label'] = strtoupper(\$button['button_label']);") . "\n";
$accordion_block_content .= htmlentities("            }") . "\n";
$accordion_block_content .= htmlentities("        }") . "\n";
$accordion_block_content .= htmlentities("    }") . "\n";
$accordion_block_content .= htmlentities("    return \$buttons;") . "\n";
$accordion_block_content .= htmlentities("}, 10, 2);") . "\n";
$accordion_block_content .= '</code></pre>';
$accordion_block_content .= '<p>This example demonstrates changing the "Show All" button label to "View All" and converting the labels of all other buttons to uppercase, which allows for better visibility and uniformity of the user interface. Using the AJAX identifier enables specific customization for particular AJAX containers.</p>';
$accordion_block_content .= '<p><strong>Finding the quick_ajax_id:</strong> The <code class="code-tag">quick_ajax_id</code> can be found by looking for the <code class="code-tag">id</code> attribute of the outer div containing the AJAX buttons. For example, <code class="code-tag">&lt;div id="quick-ajax-filter-<strong>p9</strong>" class="quick-ajax-filter-container"&gt;</code> indicates that the identifier is <code class="code-tag">"p9"</code>. This is key to identifying the specific container in your customizations.</p>';
$accordion_block_content .= '<p>For debugging purposes, the <code class="code-tag">print_r($quick_ajax_id)</code> function can be used to display the identifier while working on modifications. Remember to avoid exposing debugging to end users in a production environment.</p>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
