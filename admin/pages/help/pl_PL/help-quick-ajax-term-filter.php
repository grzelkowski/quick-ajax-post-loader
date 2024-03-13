<?php 
$accordion_block_title = 'Funkcja `wpg_quick_ajax_term_filter`';
$accordion_block_content = '<p><strong>Opis:</strong> Funkcja <code class="code-tag">wpg_quick_ajax_term_filter</code> umożliwia dynamiczne ładowanie i aktualizację postów na podstawie wybranej taksonomii, bez przeładowywania całej strony. Jest to kluczowe narzędzie do tworzenia interaktywnych, filtrowanych list postów w WordPressie, wykorzystujące AJAX.</p>';
$accordion_block_content .= '<h4>Parametry</h4>';
$accordion_block_content .= '<ul>
<li><code class="no-background"><strong>$quick_ajax_args</strong></code> (array): Tablica parametrów zapytania AJAX dla postów, pozwalająca na szczegółową konfigurację wyświetlanych treści.</li>
<li><code class="no-background"><strong>$quick_ajax_attributes</strong></code> (array): Tablica atrybutów AJAX, zawierająca style CSS i inne opcje konfiguracji, umożliwiająca dostosowanie wyglądu i zachowania siatki postów oraz filtrów.</li>
<li><code class="no-background"><strong>$quick_ajax_taxonomy</strong></code> (string): Nazwa taksonomii używana do filtrowania postów, np. \'category\' lub \'tag\'.</li>
</ul>';
$accordion_block_content .= '<h4>Przykład Użycia</h4>';
$accordion_block_content .= <<<HTML
<pre><code class="no-background">
\$quick_ajax_args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 6,
    'orderby' => 'date',
    'order' => 'DESC',
    'post__not_in' => array(3, 66, 100),
);
\$quick_ajax_attributes = array(
    'quick_ajax_id' => 12056,
    'quick_ajax_css_style' => 1,
    'grid_num_columns' => 3,
    'post_item_template' => 'post-item-custom-name',
    'taxonomy_filter_class' => 'class-one class-two',
    'container_class' => 'class-one class-two',
    'load_more_posts' => 4,
    'loader_icon' => 'loader-icon-quick-ajax-dot'
);    
\$quick_ajax_taxonomy = 'category';

if (function_exists('wpg_quick_ajax_term_filter')):
    wpg_quick_ajax_term_filter(
        \$quick_ajax_args,
        \$quick_ajax_attributes,
        \$quick_ajax_taxonomy
    );
endif;
</code></pre>
HTML;

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
?>
