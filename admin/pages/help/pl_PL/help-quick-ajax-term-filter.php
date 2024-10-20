<?php 
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = 'Funkcja `qapl_quick_ajax_term_filter`';
$accordion_block_content = '<p><strong>Opis:</strong> Funkcja <code class="code-tag">qapl_quick_ajax_term_filter</code> umożliwia dynamiczne ładowanie i aktualizację postów na podstawie wybranej taksonomii, bez przeładowywania całej strony. Jest to kluczowe narzędzie do tworzenia interaktywnych, filtrowanych list postów w WordPressie, wykorzystujące AJAX.</p>';
$accordion_block_content .= '<h4>Parametry</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><code class="no-background"><strong>$quick_ajax_args</strong></code> (array): Tablica parametrów zapytania AJAX dla postów, pozwalająca na szczegółową konfigurację wyświetlanych treści.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>$quick_ajax_attributes</strong></code> (array): Tablica atrybutów AJAX, zawierająca style CSS i inne opcje konfiguracji, umożliwiająca dostosowanie wyglądu i zachowania siatki postów oraz filtrów.</li>';
$accordion_block_content .= '<li><code class="no-background"><strong>$quick_ajax_taxonomy</strong></code> (string): Nazwa taksonomii używana do filtrowania postów, np. \'category\' lub \'tag\'.</li>';
$accordion_block_content .= '</ul>';
$accordion_block_content .= '<h4>Przykład Użycia</h4>';
$accordion_block_content .= '<pre><code class="no-background">';
$accordion_block_content .= htmlentities('$quick_ajax_args = array(') . "\n";
$accordion_block_content .= htmlentities("    'post_type' => 'post',") . "\n";
$accordion_block_content .= htmlentities("    'post_status' => 'publish',") . "\n";
$accordion_block_content .= htmlentities("    'posts_per_page' => 6,") . "\n";
$accordion_block_content .= htmlentities("    'orderby' => 'date',") . "\n";
$accordion_block_content .= htmlentities("    'order' => 'DESC',") . "\n";
$accordion_block_content .= htmlentities("    'post__not_in' => array(3, 66, 100),") . "\n";
$accordion_block_content .= htmlentities(');') . "\n";
$accordion_block_content .= htmlentities('$quick_ajax_attributes = array(') . "\n";
$accordion_block_content .= htmlentities("    'quick_ajax_id' => 12056,") . "\n";
$accordion_block_content .= htmlentities("    'quick_ajax_css_style' => 1,") . "\n";
$accordion_block_content .= htmlentities("    'grid_num_columns' => 3,") . "\n";
$accordion_block_content .= htmlentities("    'post_item_template' => 'post-item-custom-name',") . "\n";
$accordion_block_content .= htmlentities("    'taxonomy_filter_class' => 'class-one class-two',") . "\n";
$accordion_block_content .= htmlentities("    'container_class' => 'class-one class-two',") . "\n";
$accordion_block_content .= htmlentities("    'load_more_posts' => 4,") . "\n";
$accordion_block_content .= htmlentities("    'loader_icon' => 'loader-icon-quick-ajax-dot'") . "\n";
$accordion_block_content .= htmlentities(');') . "\n";
$accordion_block_content .= htmlentities('$quick_ajax_taxonomy = \'category\';') . "\n";
$accordion_block_content .= "\n";
$accordion_block_content .= htmlentities('if (function_exists(\'qapl_quick_ajax_term_filter\')):') . "\n";
$accordion_block_content .= htmlentities('    qapl_quick_ajax_term_filter(') . "\n";
$accordion_block_content .= htmlentities('        $quick_ajax_args,') . "\n";
$accordion_block_content .= htmlentities('        $quick_ajax_attributes,') . "\n";
$accordion_block_content .= htmlentities('        $quick_ajax_taxonomy') . "\n";
$accordion_block_content .= htmlentities('    );') . "\n";
$accordion_block_content .= htmlentities('endif;') . "\n";
$accordion_block_content .= '</code></pre>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
?>
