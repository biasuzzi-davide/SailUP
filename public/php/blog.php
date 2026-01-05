<?php

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

$html = buildPage('../pages/blog.html', $_SERVER['PHP_SELF']);

// Keywords per SEO
$keywords = '<meta name="keywords" content="blog nautico Napoli, guide mare, consigli navigazione, Golfo di Napoli, esperienze barche, diario di bordo, turismo mare Napoli">';
$html = str_replace('[KEYWORDS]', $keywords, $html);

$db = new DBConnection();
$articoli = $db->getArticoliBlogWithMedia(20);

$cardsHtml = '';
if ($articoli && is_array($articoli) && count($articoli) > 0) {
    foreach ($articoli as $articolo) {
        $cardsHtml .= buildBlogArticleCard($articolo);
    }
} else {
    $cardsHtml = '<p class="catalog-empty">Non ci sono articoli da mostrare al momento. Torna presto.</p>';
}

$html = str_replace('[BLOG_CARDS]', $cardsHtml, $html);

echo $html;
?>
