<?php 
if (!defined('ABSPATH')) {
    exit;
}
$accordion_block_title = 'Generowanie shortcodów do wyświetlania postów';
$accordion_block_content = '<p><strong>Opis:</strong> Plugin <strong>Quick Ajax Post Loader</strong> pozwala na tworzenie shortcodów, które umożliwiają dynamiczne wyświetlanie postów w WordPressie z wykorzystaniem AJAX. Zawartość może być ładowana bez konieczności odświeżania strony, dzięki czemu użytkownicy mogą cieszyć się płynniejszym przeglądaniem treści. Aby utworzyć shortcode, wystarczy przejść do sekcji Quick Ajax -> Shortcodes lub Add New w panelu administracyjnym WordPressa.</p>';
$accordion_block_content .= '<h4>Przykład użycia</h4>';
$accordion_block_content .= '<p>Po stworzeniu nowego shortcode, na przykład <code class="code-tag">[qapl-quick-ajax id="1" title="My Ajax"]</code>, można go umieścić w dowolnym miejscu na stronie, aby zainicjować ładowanie treści przez AJAX. Proces ten eliminuje potrzebę ręcznego odświeżania strony, umożliwiając szybki i efektywny dostęp do aktualizowanych treści.</p>';
$accordion_block_content .= '<h4>Tworzenie shortcodu</h4>';
$accordion_block_content .= '<p>Aby stworzyć nowy shortcode, odwiedź panel administracyjny WordPressa i wybierz opcję Quick Ajax > Shortcodes lub Add New. W tej sekcji można skonfigurować typ posta do wyświetlenia, opcje wyświetlania i inne ustawienia, które pomogą dostosować działanie shortcode do potrzeb Twojej strony. Po konfiguracji, shortcode jest gotowy do użycia i może być umieszczony na stronie.</p>';

return [
    'title' => $accordion_block_title,
    'content' => $accordion_block_content,
];
?>
