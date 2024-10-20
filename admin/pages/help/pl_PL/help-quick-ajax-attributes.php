<?php 
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = 'Parametr `$quick_ajax_attributes` dla funkcji `qapl_quick_ajax_post_grid` oraz `qapl_quick_ajax_term_filter`';
$accordion_block_content = '<p><strong>Opis:</strong> Parametr <code class="code-tag">quick_ajax_attributes</code> jest wykorzystywany do konfiguracji opcji wyglądu i zachowania siatki postów oraz filtrów taksonomicznych w pluginie <strong>Quick Ajax Post Loader</strong> dla WordPressa. Pozwala na dostosowanie stylów, liczby kolumn, klasy kontenerów i innych atrybutów, które wpływają na sposób wyświetlania i funkcjonowanie dynamicznie ładowanych treści.</p>';
$accordion_block_content .= '<h4>Zastosowanie</h4>';
$accordion_block_content .= '<p>Parametr <code class="code-tag">$quick_ajax_attributes</code> jest kluczowy przy używaniu funkcji takich jak <code class="code-tag">qapl_quick_ajax_post_grid</code> oraz <code class="code-tag">qapl_quick_ajax_term_filter</code>, umożliwiając szczegółową personalizację ładowanych treści AJAX.</p>';
$accordion_block_content .= '<h4>Parametry</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><code class="no-background"><strong>quick_ajax_id</strong></code> (int): Unikalny identyfikator dla instancji AJAX, umożliwiający wiele niezależnych siatek na tej samej stronie.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>quick_ajax_css_style</strong></code> (int): Włącza lub wyłącza wbudowane style Quick Ajax CSS.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>grid_num_columns</strong></code> (int): Określa liczbę kolumn w siatce postów.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>post_item_template</strong></code>: Pozwala na wybór szablonu postu, np. <code class="code-tag">\'post-item-custom-name\'</code> dla niestandardowego szablonu. Podaj nazwę pliku bez rozszerzenia .php.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>taxonomy_filter_class</strong></code> (string): Dodaje niestandardowe klasy CSS do filtrowania taksonomii.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>container_class</strong></code> (string): Dodaje niestandardowe klasy CSS do kontenera siatki postów.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>load_more_posts</strong></code> (int): Określa liczbę postów do załadowania po kliknięciu przycisku "Załaduj więcej".</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>loader_icon</strong></code> (int): Pozwala na wybór ikony ładowania.</li>';
$accordion_block_content .= '</ul>';
$accordion_block_content .= '<h4>Najlepsze Praktyki</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><p>Dostosuj <code class="code-tag">quick_ajax_attributes</code> zgodnie z potrzebami i stylem swojej strony, aby zapewnić spójność wizualną.</p></li>';
$accordion_block_content .= '<li><p>Testuj różne kombinacje parametrów, aby znaleźć idealne ustawienia dla swoich siatek postów i filtrów.</p></li>';
$accordion_block_content .= '<li><p>Wykorzystaj niestandardowe klasy CSS do maksymalnego dostosowania i uniknięcia konfliktów stylów.</p></li>';
$accordion_block_content .= '</ul>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
?>
