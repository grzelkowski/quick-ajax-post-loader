<?php
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = 'Jak modyfikować argumenty WP_Query za pomocą filtra `qapl_modify_posts_query_args`';
$accordion_block_content = '<p><strong>Opis:</strong> Filtr <code class="code-tag">qapl_modify_posts_query_args</code> pozwala na dostosowanie argumentów zapytania WP_Query używanego w pluginie <strong>Quick Ajax Post Loader</strong>. Umożliwia to szczegółową kontrolę nad wynikami zapytań AJAX, dostosowując je do unikalnych potrzeb Twojej strony.</p>';
$accordion_block_content .= '<h4>Przykład użycia:</h4>';
$accordion_block_content .= '<p>Dodając własną funkcję modyfikującą, możesz precyzyjnie dostosować argumenty zapytania, jak pokazano poniżej. Przykład demonstruje wykorzystanie identyfikatora <code class="code-tag">$quick_ajax_id</code> do modyfikacji zapytań dla konkretnego kontenera AJAX:</p>';
$accordion_block_content .= '<pre><code class="no-background">';
$accordion_block_content .= htmlentities("add_filter('qapl_modify_posts_query_args', function(\$args, \$quick_ajax_id) {") . "\n";
$accordion_block_content .= htmlentities("    // Wykorzystanie identyfikatora AJAX do modyfikacji argumentów zapytania") . "\n";
$accordion_block_content .= htmlentities("    if (\$quick_ajax_id === 'some_specific_id') {") . "\n";
$accordion_block_content .= htmlentities("        \$args['posts_per_page'] = 5; // Zmiana liczby postów na stronę na 5") . "\n";
$accordion_block_content .= htmlentities("    }") . "\n";
$accordion_block_content .= htmlentities("    return \$args;") . "\n";
$accordion_block_content .= htmlentities("}, 10, 2);") . "\n";
$accordion_block_content .= '</code></pre>';
$accordion_block_content .= '<p>Ten przykład ilustruje, jak zmienić liczbę postów na stronie na 5, używając specyficznego identyfikatora AJAX. To umożliwia bardziej celowane i elastyczne zarządzanie zapytaniami.</p>';
$accordion_block_content .= '<p><strong>Znajdowanie quick_ajax_id:</strong> Aby znaleźć identyfikator <code class="code-tag">quick_ajax_id</code>, szukaj atrybutu <code class="code-tag">id</code> zewnętrznego diva, który zawiera przyciski AJAX. Na przykład, <code class="code-tag">&lt;div id="quick-ajax-<strong>p9</strong>" class="quick-ajax-posts-container"&gt;</code> wskazuje, że <code class="code-tag">quick_ajax_id</code> to <code class="code-tag">"p9"</code>. Jest to kluczowe dla identyfikacji kontenera w Twoich filtrach.</p>';
$accordion_block_content .= '<p>Do debugowania, możesz użyć <code class="code-tag">print_r($quick_ajax_id)</code> w Twojej funkcji modyfikującej, aby zobaczyć identyfikator. Pamiętaj, że w środowisku produkcyjnym należy unikać eksponowania takich informacji użytkownikom.</p>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
