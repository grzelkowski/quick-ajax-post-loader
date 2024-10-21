<?php
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = 'Dostosowywanie przycisku filtru taksonomii';
$accordion_block_content = '<p><strong>Opis:</strong> Plugin <strong>Quick Ajax Post Loader</strong> umożliwia dostosowanie przycisku filtru taksonomii przez modyfikację pliku <code class="code-tag">term-filter-button.php</code>. Ten plik odpowiada za wygląd i działanie przycisków używanych do filtrowania postów według kategorii, tagów lub innych taksonomii.</p>';
$accordion_block_content .= '<h4>Jak dostosować przycisk filtru</h4>';
$accordion_block_content .= '<p>Aby dostosować przycisk filtru, należy nadpisać plik <code class="code-tag">term-filter-button.php</code> w katalogu <code class="code-tag">/quick-ajax-post-loader/templates/term-filter/</code> w Twoim motywie lub motywie potomnym. Modyfikacja tego pliku pozwala na zmianę stylu, klasy i atrybutów przycisku, co umożliwia lepsze dopasowanie do wyglądu i potrzeb Twojej strony.</p>';
$accordion_block_content .= '<h4>Przykład dostosowanego przycisku</h4>';
$accordion_block_content .= '<p>Oto przykładowy kod zmodyfikowanego przycisku filtru, z uwzględnieniem dynamicznej zmiany etykiety za pomocą <code class="code-tag">QUICK_AJAX_LABEL</code> oraz wykorzystaniem atrybutu <code class="code-tag">data-button="quick-ajax-filter-button"</code> do określenia zachowania przycisku w kontekście AJAX:</p>';
$accordion_block_content .= '<pre><code class="no-background">';
$accordion_block_content .= htmlentities('<button type="button" class="filter-button custom-class" data-button="quick-ajax-filter-button">QUICK_AJAX_LABEL</button>') . "\n";
$accordion_block_content .= '</code></pre>';
$accordion_block_content .= '<h4>Najlepsze praktyki</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><p>Stosuj nazewnictwo klas CSS zgodne z konwencjami Twojego motywu lub witryny, aby zachować spójność wizualną.</p></li>';
$accordion_block_content .= '<li><p>Testuj zmiany na różnych urządzeniach i przeglądarkach, aby upewnić się, że przycisk jest odpowiednio wyświetlany i funkcjonalny.</p></li>';
$accordion_block_content .= '<li><p>Używaj atrybutów <code class="code-tag">data-*</code> do przekazywania informacji dla skryptów obsługujących filtrację, zachowując ostrożność przy ich modyfikacji. Atrybut <code class="code-tag">data-button="quick-ajax-filter-button"</code> jest kluczowy dla integracji z logiką AJAX, umożliwiając dynamiczne ładowanie treści bez przeładowywania strony.</p></li>';
$accordion_block_content .= '</ul>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
