<?php 
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = 'Tworzenie i używanie własnych szablonów postów';
$accordion_block_content = '<p><strong>Opis: </strong> Plugin <strong>Quick Ajax Post Loader</strong> umożliwia użytkownikom tworzenie i stosowanie własnych szablonów postów, oferując możliwość personalizacji wyglądu i zachowania dynamicznie ładowanych treści na stronie WordPress. Możesz nadpisać domyślny szablon posta, tworząc plik <code class="code-tag">post-item.php</code> w odpowiednim katalogu, lub dodać dowolną liczbę własnych szablonów, które następnie będą dostępne do wyboru w polu select przy konfiguracji shortcode.</p>';
$accordion_block_content .= '<h4>Jak tworzyć własne szablony </h4>';
$accordion_block_content .= '<p>Aby nadpisać domyślny szablon postów, stwórz plik o nazwie <code class="code-tag">post-item.php</code> i umieść go w katalogu <code class="code-tag">wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/post-items/</code> w Twoim motywie lub child theme. Ten plik zastąpi domyślny szablon używany przez plugin. Aby dodać więcej szablonów, nazwij pliki zgodnie z formatem <code class="code-tag">post-item-custom-name.php</code> i umieść je w tym samym katalogu. W pierwszej linii każdego pliku szablonu dodaj komentarz z nazwą szablonu, który będzie widoczny w panelu administracyjnym pluginu.</p>';
$accordion_block_content .= '<h4>Zasady nazewnictwa plików szablonów</h4>';
$accordion_block_content .= '<p>Aby plik szablonu został wykryty przez plugin, musi on spełniać określone zasady nazewnictwa:</p>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><p>Dla domyślnego szablonu postów: plik musi być nazwany <code class="code-tag">post-item.php</code> i umieszczony w katalogu <code class="code-tag">wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/post-items/</code>. Ten plik automatycznie zastąpi wbudowany domyślny szablon pluginu.</p></li>';
$accordion_block_content .= '<li><p>Dla niestandardowych szablonów: pliki muszą mieć nazwę zaczynającą się od <code class="code-tag">\'post-item\'</code>, np. <code class="code-tag">post-item-custom-name.php</code>. Taki format nazwy pozwala pluginowi na identyfikację plików jako dodatkowych szablonów postów.</p></li>';
$accordion_block_content .= '<li><p>Jeśli w pliku szablonu znajduje się komentarz z nazwą szablonu w formacie <code class="code-tag">/* Post Item Name: Nazwa Szablonu */</code>, ta nazwa zostanie użyta w panelu administracyjnym pluginu jako nazwa szablonu. W przypadku braku takiego komentarza, w panelu administracyjnym jako nazwa szablonu zostanie wyświetlona nazwa pliku (bez rozszerzenia .php).</p></li>';
$accordion_block_content .= '</ul>';
$accordion_block_content .= '<h4>Przykład szablonu </h4>';
$accordion_block_content .= '<p>Poniżej znajduje się przykładowy kod szablonu posta:</p>';
$accordion_block_content .= '<pre><code class="no-background">';
$accordion_block_content .= htmlentities('<?php 
/* Post Item Name: Moja Niestandardowa Nazwa Szablonu */
?>') . "\n";
$accordion_block_content .= htmlentities('<div class="qapl-post-item">') . "\n";
$accordion_block_content .= htmlentities('    <a href="<?php echo get_permalink() ?>">') . "\n";
$accordion_block_content .= htmlentities('        <!-- Tu dodaj kod wyświetlający post, np. miniaturę, tytuł -->') . "\n";
$accordion_block_content .= htmlentities('    </a>') . "\n";
$accordion_block_content .= htmlentities('</div>') . "\n";
$accordion_block_content .= '</code></pre>';
$accordion_block_content .= '<h4>Komunikat "Brak postów"</h4>';
$accordion_block_content .= '<p>Możesz również dostosować komunikat wyświetlany, gdy nie ma żadnych postów do wyświetlenia, tworząc plik <code class="code-tag">no-posts.php</code> w katalogu <code class="code-tag">wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/post-items/</code>, aby nadpisać domyślny komunikat.</p>';
$accordion_block_content .= '<h4>Wybór szablonu</h4>';
$accordion_block_content .= '<p>Stworzone szablony, włącznie z plikiem <code class="code-tag">post-item.php</code>, będą automatycznie wykryte przez plugin i dostępne do wyboru w polu select przy konfiguracji shortcode. To pozwala na łatwą zmianę wyglądu postów bez konieczności edycji kodu źródłowego.</p>';
$accordion_block_content .= '<h4>Zasady nadpisywania i wczytywania szablonów</h4>';
$accordion_block_content .= '<p>Plugin szanuje hierarchię WordPressa, wczytując szablony w następującej kolejności: child theme, theme, plugin. Oznacza to, że jeśli istnieje szablon w child theme, będzie miał on priorytet nad szablonem w theme oraz wbudowanym szablonem pluginu. Ta zasada pozwala na bezpieczne modyfikacje bez ryzyka utraty zmian przy aktualizacji motywu głównego lub pluginu.</p>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><p>Umieszczając szablon w <strong>child theme</strong>, zapewnisz jego trwałość przez aktualizacje motywu głównego.</p></li>';
$accordion_block_content .= '<li><p>Umieszczając szablon bezpośrednio w <strong>theme</strong>, zyskasz możliwość szybkiej personalizacji, ale z ryzykiem utraty zmian przy aktualizacji motywu.</p></li>';
$accordion_block_content .= '<li><p>Plugin automatycznie wykryje dostępne szablony w tych lokalizacjach i umożliwi ich wybór w konfiguracji shortcode.</p></li>';
$accordion_block_content .= '</ul>';
$accordion_block_content .= '<h4>Najlepsze Praktyki</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><p>Nazwij swoje pliki szablonów w sposób jednoznaczny, aby łatwo je identyfikować.</p></li>';
$accordion_block_content .= '<li><p>Testuj szablony w różnych środowiskach i konfiguracjach, aby upewnić się, że wyglądają dobrze na wszystkich urządzeniach i w różnych przeglądarkach.</p></li>';
$accordion_block_content .= '<li><p>Używaj odpowiednich klas CSS, aby zapewnić spójność stylów z resztą Twojej strony.</p></li>';
$accordion_block_content .= '</ul>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
