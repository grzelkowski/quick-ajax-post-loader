<?php
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = 'Jak modyfikować przyciski filtrowania taksonomii za pomocą filtra `qapl_modify_taxonomy_filter_buttons`';
$accordion_block_content = '<p><strong>Opis:</strong> Filtr <code class="code-tag">qapl_modify_taxonomy_filter_buttons</code> umożliwia dostosowanie właściwości przycisków używanych do filtrowania zawartości strony na podstawie terminów taksonomii w pluginie <strong>Quick Ajax Post Loader</strong>. Dzięki przyjęciu dwóch parametrów: tablicy przycisków i identyfikatora AJAX, filtr oferuje możliwość dokładnej personalizacji tych elementów.</p>';
$accordion_block_content .= '<h4>Przykład użycia:</h4>';
$accordion_block_content .= '<p>Do modyfikacji właściwości przycisków i wykorzystania identyfikatora AJAX, możesz dodać własną funkcję modyfikującą w poniższy sposób:</p>';
$accordion_block_content .= '<pre><code class="no-background">';
$accordion_block_content .= htmlentities("add_filter('qapl_modify_taxonomy_filter_buttons', function(\$buttons, \$quick_ajax_id) {") . "\n";
$accordion_block_content .= htmlentities("    // Modyfikacja właściwości przycisków z uwzględnieniem identyfikatora AJAX") . "\n";
$accordion_block_content .= htmlentities("    foreach (\$buttons as &\$button) {") . "\n";
$accordion_block_content .= htmlentities("        if (\$quick_ajax_id === 'some_specific_id') {") . "\n";
$accordion_block_content .= htmlentities("            // Dostosowanie etykiety przycisku \"Pokaż wszystkie\"") . "\n";
$accordion_block_content .= htmlentities("            if (\$button['term_id'] === 'none') {") . "\n";
$accordion_block_content .= htmlentities("                \$button['button_label'] = 'View All'; // Zmiana etykiety na \"View All\"") . "\n";
$accordion_block_content .= htmlentities("            } else {") . "\n";
$accordion_block_content .= htmlentities("                // Konwersja etykiet pozostałych przycisków na wielkie litery") . "\n";
$accordion_block_content .= htmlentities("                \$button['button_label'] = strtoupper(\$button['button_label']);") . "\n";
$accordion_block_content .= htmlentities("            }") . "\n";
$accordion_block_content .= htmlentities("        }") . "\n";
$accordion_block_content .= htmlentities("    }") . "\n";
$accordion_block_content .= htmlentities("    return \$buttons;") . "\n";
$accordion_block_content .= htmlentities("}, 10, 2);") . "\n";
$accordion_block_content .= '</code></pre>';
$accordion_block_content .= '<p>Przykład ten demonstruje zmianę etykiety przycisku "Show All" na "View All" oraz konwersję etykiet wszystkich innych przycisków na wielkie litery, co pozwala na lepszą widoczność i jednolitość interfejsu użytkownika. Wykorzystanie identyfikatora AJAX umożliwia specyficzną personalizację dla konkretnych kontenerów AJAX.</p>';
$accordion_block_content .= '<p><strong>Znajdowanie quick_ajax_id:</strong> Identyfikator <code class="code-tag">quick_ajax_id</code> można znaleźć, szukając atrybutu <code class="code-tag">id</code> zewnętrznego diva zawierającego przyciski AJAX. Na przykład, <code class="code-tag">&lt;div id="quick-ajax-filter-<strong>p9</strong>" class="quick-ajax-filter-container"&gt;</code> wskazuje, że identyfikator to <code class="code-tag">"p9"</code>. Jest to klucz do identyfikacji specyficznego kontenera w Twoich personalizacjach.</p>';
$accordion_block_content .= '<p>W celach debugowania, funkcja <code class="code-tag">print_r($quick_ajax_id)</code> może zostać użyta do wyświetlenia identyfikatora w trakcie pracy nad modyfikacjami. Pamiętaj, by w środowisku produkcyjnym unikać eksponowania debugowania użytkownikom końcowym.</p>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
