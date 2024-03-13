<?php
$accordion_block_title = 'add_filter: How to Use `quick_ajax_modify_term_buttons`';
$accordion_block_content = '<p><strong>Description:</strong> The <code class="code-tag">quick_ajax_modify_term_buttons</code> filter allows customization of the properties of buttons used for filtering site content based on taxonomy terms in the <strong>Quick Ajax Post Loader</strong> plugin. With two parameters: an array of buttons and an AJAX identifier, the filter offers the possibility of precisely personalizing these elements.</p>';
$accordion_block_content .= '<h4>Usage Example:</h4>';
$accordion_block_content .= '<p>To modify the properties of the buttons and utilize the AJAX identifier, you can add your own modifying function in the following way:</p>';
$accordion_block_content .= '<pre><code class="no-background">
add_filter(\'quick_ajax_modify_term_buttons\', function($buttons, $quick_ajax_id) { 
    // Modifying button properties considering the AJAX identifier
    foreach ($buttons as &$button) {
        if ($quick_ajax_id === \'some_specific_id\') {
            // Customizing the label of the "Show All" button
            if ($button[\'term_id\'] === \'none\') {
                $button[\'button_label\'] = \'View All\'; // Changing the label to "View All"
            } else {
                // Converting labels of other buttons to uppercase
                $button[\'button_label\'] = strtoupper($button[\'button_label\']);
            }
        }
    }
    return $buttons;
}, 10, 2);
</code></pre>';
$accordion_block_content .= '<p>This example demonstrates changing the "Show All" button label to "View All" and converting the labels of all other buttons to uppercase, which allows for better visibility and uniformity of the user interface. Using the AJAX identifier enables specific customization for particular AJAX containers.</p>';
$accordion_block_content .= '<p><strong>Finding the quick_ajax_id:</strong> The <code class="code-tag">quick_ajax_id</code> can be found by looking for the <code class="code-tag">id</code> attribute of the outer div containing the AJAX buttons. For example, <code class="code-tag">&lt;div id="quick-ajax-term-filter-<strong>p9</strong>" class="quick-ajax-filter-wrapper"&gt;</code> indicates that the identifier is <code class="code-tag">"p9"</code>. This is key to identifying the specific container in your customizations.</p>';
$accordion_block_content .= '<p>For debugging purposes, the <code class="code-tag">print_r($quick_ajax_id)</code> function can be used to display the identifier while working on modifications. Remember to avoid exposing debugging to end users in a production environment.</p>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
?>
