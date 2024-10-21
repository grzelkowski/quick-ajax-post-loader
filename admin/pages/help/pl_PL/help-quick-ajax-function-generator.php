<?php 
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = 'Narzędzie do generowania funkcji ajax w php';
$accordion_block_content = '<p><strong>Opis:</strong> <strong>Generator funkcji ajax</strong> w pluginie <strong>Quick Ajax Post Loader</strong> to narzędzie dostępne w menu Quick Ajax > Settings & Features, w zakładce "Generator Funkcji". Umożliwia ono tworzenie kodu PHP, który można bezpośrednio wpleść do ciała strony w WordPressie, takie jak w plikach <code class="code-tag">page.php</code>, <code class="code-tag">single.php</code> lub indywidualnym szablonie strony. Funkcje te działają podobnie do shortcode’ów, ale oferują większą elastyczność w implementacji i możliwość bezpośredniego włączenia do kodu tematu.</p>';
$accordion_block_content .= '<h4>Przykładowe zastosowanie</h4>';
$accordion_block_content .= '<p>Wygenerowany kod umożliwia dynamiczne wyświetlanie postów z wykorzystaniem AJAX, bez konieczności odświeżania strony. Aby go użyć, wystarczy skopiować wygenerowany kod funkcji i wkleić do odpowiedniego miejsca w pliku szablonu tematu, gdzie ma być wyświetlona zawartość AJAX.</p>';
$accordion_block_content .= '<h4>Implementacja kodu</h4>';
$accordion_block_content .= '<p>Otwórz plik szablonu, na przykład <code class="code-tag">page.php</code>, w miejscu, gdzie chcesz, aby posty były wyświetlane i wklej kod funkcji. Kod ten automatycznie zajmie się wyświetlaniem postów zgodnie z zdefiniowanymi parametrami i atrybutami.</p>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
