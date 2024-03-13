<?php 
$accordion_block_title = 'Parametr `$quick_ajax_args` dla funkcji `wpg_quick_ajax_post_grid` oraz `wpg_quick_ajax_term_filter`';
$accordion_block_content = '<p><strong>Opis:</strong> Parametr <code class="code-tag">$quick_ajax_args</code> jest kluczowy do konfigurowania zapytań AJAX w pluginie <strong>Quick Ajax Post Loader</strong>. Pozwala on na szczegółowe określenie, jakie posty mają być ładowane i wyświetlane w ramach siatki postów (grid) lub przy użyciu filtrów taksonomicznych, zapewniając dynamiczne i interaktywne doświadczenie użytkownika na stronie.</p>';
$accordion_block_content .= '<h4>Zastosowanie</h4>';
$accordion_block_content .= '<p><code class="code-tag">$quick_ajax_args</code> jest wykorzystywany w funkcjach takich jak <code class="code-tag">quick_ajax_post_grid</code> oraz <code class="code-tag">wpg_quick_ajax_term_filter</code>, umożliwiając elastyczne i zaawansowane zarządzanie treścią bez potrzeby przeładowywania strony.</p>';
$accordion_block_content .= '<h4>Parametry</h4>';
$accordion_block_content .= '<ul>
<li><code class="no-background"><strong>post_type</strong></code> (string): Typ postów do załadowania, np. \'post\', \'page\', niestandardowe typy postów.</li>
<li><code class="no-background"><strong>post_status</strong></code> (string): Status postów do wyświetlenia, np. \'publish\'.</li>
<li><code class="no-background"><strong>posts_per_page</strong></code> (int): Liczba wyświetlanych postów na stronę.</li>
<li><code class="no-background"><strong>orderby</strong></code> (string): Kryterium sortowania postów, np. \'date\', \'title\'.</li>
<li><code class="no-background"><strong>order</strong></code> (string): Kolejność sortowania postów, np. \'ASC\', \'DESC\'.</li>
<li><code class="no-background"><strong>post__not_in</strong></code> (array): Tablica ID postów do wykluczenia.</li>
</ul>';
$accordion_block_content .= '<h4>Najlepsze Praktyki</h4>';
$accordion_block_content .= '<ul>
<li><p>Dokładnie dostosowuj parametry w <code class="code-tag">$quick_ajax_args</code> do potrzeb strony, aby zapewnić efektywne i celowe ładowanie treści.</p></li>
<li><p>Regularnie testuj konfiguracje <code class="code-tag">$quick_ajax_args</code>, aby upewnić się, że są one optymalne zarówno pod kątem wydajności, jak i użytkowności.</p></li>
<li><p>Pamiętaj, aby dostosować parametry <code class="code-tag">$quick_ajax_args</code> zgodnie z celami i potrzebami konkretnego projektu lub funkcji na stronie.</p></li>
</ul>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
?>
