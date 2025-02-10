<?php 
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

$accordion_block_title = 'Dostępne filtry szablonów w Quick Ajax Post Loader';
$accordion_block_content = '<p><strong>Opis:</strong> Filtry szablonów dostępne za pomocą <code class="code-tag">apply_filters</code> w <strong>Quick Ajax Post Loader</strong> pozwalają dostosować kod HTML różnych komponentów szablonu. Te filtry umożliwiają modyfikację renderowanego kodu dla elementów takich jak data, obrazek, tytuł, fragment, przycisk "czytaj więcej" oraz przycisk ładowania kolejnych postów.</p>';

$accordion_block_content .= '<h4>Dostępne filtry szablonów</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><code class="no-background"><strong>qapl_template_post_item_date</strong></code>: Filtruje kod HTML elementu z datą posta.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>qapl_template_post_item_image</strong></code>: Filtruje kod HTML elementu z obrazkiem posta.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>qapl_template_post_item_title</strong></code>: Filtruje kod HTML elementu z tytułem posta.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>qapl_template_post_item_excerpt</strong></code>: Filtruje kod HTML elementu z fragmentem posta.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>qapl_template_post_item_read_more</strong></code>: Filtruje kod HTML elementu "czytaj więcej".</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>qapl_template_load_more_button</strong></code>: Filtruje kod HTML przycisku "załaduj więcej".</li>';
$accordion_block_content .= '</ul>';

$accordion_block_content .= '<h4>Jak używać</h4>';
$accordion_block_content .= '<p>Możesz modyfikować kod wyjściowy szablonu, podpinając się pod te filtry za pomocą funkcji <code class="code-tag">add_filter()</code>. Na przykład, aby zmienić sposób wyświetlania daty posta, dodaj poniższy kod:</p>';

// Example for modifying the date
$accordion_block_content .= '<h4>Przykład: Dostosowanie formatu daty posta</h4>';
$accordion_block_content .= '<pre><code class="no-background">';
$accordion_block_content .= htmlentities("function custom_qapl_date(\$output, \$template, \$quick_ajax_id) {") . "\n";
$accordion_block_content .= htmlentities("    if (\$template === 'post-item') { // Zastosuj tylko dla domyślnego szablonu 'post-item'") . "\n";
$accordion_block_content .= htmlentities("        \$new_date = get_the_date('d-m-Y'); // Zmień format daty na 'd-m-Y'") . "\n";
$accordion_block_content .= htmlentities("        \$output = '<div class=\"qapl-post-date\"><span> Data: ' . esc_html(\$new_date) . '</span></div>';") . "\n";
$accordion_block_content .= htmlentities("    }") . "\n";
$accordion_block_content .= htmlentities("    return \$output;") . "\n";
$accordion_block_content .= htmlentities("}") . "\n";
$accordion_block_content .= htmlentities("add_filter('qapl_template_post_item_date', 'custom_qapl_date', 10, 3);") . "\n";
$accordion_block_content .= '</code></pre>';

// Example for modifying the title
$accordion_block_content .= '<h4>Przykład: Dostosowanie tytułu posta dla określonego kontenera</h4>';
$accordion_block_content .= '<pre><code class="no-background">';
$accordion_block_content .= htmlentities("function custom_qapl_title(\$output, \$template, \$quick_ajax_id) {") . "\n";
$accordion_block_content .= htmlentities("    if (\$quick_ajax_id === 'quick-ajax-p100') { // Zastosuj tylko dla kontenera o ID 'quick-ajax-p100'") . "\n";
$accordion_block_content .= htmlentities("        \$output = '<div class=\"qapl-post-title\"><h5> Tytuł: ' . esc_html(get_the_title()) . '</h5></div>';") . "\n";
$accordion_block_content .= htmlentities("    }") . "\n";
$accordion_block_content .= htmlentities("    return \$output;") . "\n";
$accordion_block_content .= htmlentities("}") . "\n";
$accordion_block_content .= htmlentities("add_filter('qapl_template_post_item_title', 'custom_qapl_title', 10, 3);") . "\n";
$accordion_block_content .= '</code></pre>';

$accordion_block_content .= '<p>Odpowiednie wykorzystanie filtrów pozwala łatwo dostosować różne aspekty renderowania szablonów wtyczki.</p>';

return [
    'title'   => $accordion_block_title,
    'content' => $accordion_block_content,
];
