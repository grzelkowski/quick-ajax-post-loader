<?php 
$accordion_block_title = 'add_action: Lista dostępnych akcji';
$accordion_block_content = '<p><strong>Opis:</strong> Akcje dostępne za pomocą <code class="code-tag">add_action</code> w <strong>Quick Ajax Post Loader</strong> pozwalają na wprowadzenie niestandardowych modyfikacji w kluczowych momentach działania pluginu. Dzięki tym hookom, deweloperzy mogą dostosowywać proces renderowania filtrów, zawartości postów oraz innych elementów, zapewniając większą elastyczność i możliwości rozszerzenia funkcjonalności.</p>';
$accordion_block_content .= '<h4>Dostępne Akcje</h4>';
$accordion_block_content .= '<ul>
<li><code class="no-background"><strong>before_quick_ajax_filter_wrapper</strong></code>: Przed renderowaniem wrappera filtrów AJAX. Idealne do dodawania niestandardowego HTML.</li>
<li><code class="no-background"><strong>quick_ajax_filter_wrapper_start</strong></code>: Na początku renderowania wrappera filtrów. Umożliwia wstawienie zawartości na początku.</li>
<li><code class="no-background"><strong>quick_ajax_filter_wrapper_end</strong></code>: Na końcu renderowania wrappera filtrów. Pozwala na dodanie zawartości przed zamknięciem wrappera.</li>
<li><code class="no-background"><strong>after_quick_ajax_filter_wrapper</strong></code>: Po wyrenderowaniu wrappera filtrów.</li>
<li><code class="no-background"><strong>before_quick_ajax_posts_wrapper</strong></code>: Przed renderowaniem wrappera postów AJAX.</li>
<li><code class="no-background"><strong>quick_ajax_posts_wrapper_start</strong></code>: Zaraz po otwarciu wrappera postów. Idealne do dodawania własnej zawartości na początku sekcji.</li>
<li><code class="no-background"><strong>before_quick_ajax_load_more_button</strong></code>: Przed wyrenderowaniem przycisku "Załaduj więcej". Umożliwia dodanie treści przed przyciskiem.</li>
<li><code class="no-background"><strong>after_quick_ajax_load_more_button</strong></code>: Po wyrenderowaniu przycisku "Załaduj więcej". Pozwala na dodanie treści po przycisku.</li>
<li><code class="no-background"><strong>before_quick_ajax_loader_icon</strong></code>: Przed wyrenderowaniem ikony ładowania. Idealne do dodania treści przed ikoną.</li>
<li><code class="no-background"><strong>after_quick_ajax_loader_icon</strong></code>: Po wyrenderowaniu ikony ładowania. Umożliwia dodanie treści po ikonie.</li>
<li><code class="no-background"><strong>quick_ajax_posts_wrapper_end</strong></code>: Zaraz przed zamknięciem wrappera postów. Pozwala na dodanie zawartości na końcu sekcji postów.</li>
<li><code class="no-background"><strong>after_quick_ajax_posts_wrapper</strong></code>: Po wyrenderowaniu wrappera postów AJAX.</li>
</ul>';
$accordion_block_content .= '<h4>Jak Korzystać</h4>';
$accordion_block_content .= '<p>Dodanie własnych akcji jest proste za pomocą funkcji <code class="code-tag">add_action()</code>. Poniżej przykład, jak dodać niestandardowy tekst przed wrappera filtrów AJAX:</p>';
$accordion_block_content .= '<pre><code class="no-background">
add_action(\'before_quick_ajax_filter_wrapper\', function() {
    echo \'Niestandardowy tekst przed nawigacją filtrów\';
});
</code></pre>';
$accordion_block_content .= '<p>Korzystanie z odpowiednich hooków umożliwia łatwe dostosowanie różnych aspektów działania pluginu, zgodnie z potrzebami Twojej witryny.</p>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
?>
