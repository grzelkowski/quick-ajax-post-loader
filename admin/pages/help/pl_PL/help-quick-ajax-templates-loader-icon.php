<?php
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = 'Tworzenie i używanie własnych ikon ładowania';
$accordion_block_content = '<p><strong>Opis:</strong> Plugin <strong>Quick Ajax Post Loader</strong> oferuje możliwość personalizacji ikon ładowania za pomocą własnych szablonów. Możesz stworzyć dowolną liczbę niestandardowych ikon ładowania, które następnie będą dostępne w konfiguracji pluginu.</p>';
$accordion_block_content .= '<h4>Jak tworzyć własne ikony ładowania</h4>';
$accordion_block_content .= '<p>Stwórz plik o dowolnej nazwie, np. <code class="code-tag">loader-icon-custom-loader.php</code>, i umieść go w katalogu <code class="code-tag">wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/loader-icon/</code> w Twoim motywie lub child theme. Plugin automatycznie wykryje wszystkie pliki w tym katalogu jako dostępne ikony ładowania.</p>';
$accordion_block_content .= '<h4>Przykład ikony ładowania</h4>';
$accordion_block_content .= '<p>Poniżej znajduje się przykładowy kod niestandardowej ikony ładowania:</p>';
$accordion_block_content .= '<pre><code class="no-background">';
$accordion_block_content .= htmlentities('<?php 
/* Loader Icon Name: Custom Loader */ 
?>') . "\n";
$accordion_block_content .= htmlentities('<div class="quick-ajax-loader-custom">') . "\n";
$accordion_block_content .= htmlentities('    <!-- Tu dodaj swój kod HTML, obrazek GIF lub animację CSS dla ikony ładowania, np.: -->') . "\n";
$accordion_block_content .= htmlentities('    <img src="images/loader_image.gif" alt="Ładowanie..." />') . "\n";
$accordion_block_content .= htmlentities('    <!-- Lub stwórz prostą animację CSS -->') . "\n";
$accordion_block_content .= htmlentities('    <div class="loader-dot"></div>') . "\n";
$accordion_block_content .= htmlentities('    <div class="loader-dot"></div>') . "\n";
$accordion_block_content .= htmlentities('    <div class="loader-dot"></div>') . "\n";
$accordion_block_content .= htmlentities('</div>') . "\n";
$accordion_block_content .= '</code></pre>';

$accordion_block_content .= '<h4>Zasady nadpisywania i wczytywania ikon ładowania</h4>';
$accordion_block_content .= '<p>Ikonę ładowania można umieścić w child theme lub theme, aby zapewnić jej trwałość przez aktualizacje. Plugin wczytuje ikony ładowania z child theme, następnie z theme, a na końcu z wbudowanych szablonów pluginu, co pozwala na łatwe dostosowanie bez ryzyka utraty zmian.</p>';
$accordion_block_content .= '<h4>Najlepsze Praktyki</h4>';
$accordion_block_content .= '<ul>';
$accordion_block_content .= '<li><p>Testuj ikony ładowania w różnych środowiskach, aby upewnić się, że są one poprawnie wyświetlane na wszystkich urządzeniach i w różnych przeglądarkach.</p></li>';
$accordion_block_content .= '<li><p>Używaj czystego i efektywnego kodu HTML/CSS, aby zapewnić szybkie ładowanie ikon.</p></li>';
$accordion_block_content .= '</ul>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
