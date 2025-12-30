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
        $idArticolo = $articolo['IDArticolo'] ?? '';
        if ($idArticolo === '') {
            continue;
        }

        $imageUrl = $articolo['URL_Media'] ?? '../img/placeholder.png';
        $altText = $articolo['Testo_Alternativo'] ?? 'Immagine articolo non disponibile';

        $titolo = htmlspecialchars($articolo['Titolo'] ?? 'Articolo SailUP', ENT_QUOTES);
        $descrizione = htmlspecialchars($articolo['Descrizione_Breve'] ?? 'Nessuna descrizione disponibile.', ENT_QUOTES);

        $detailUrl = 'blog_articolo.php?id=' . rawurlencode($idArticolo);

        $cardsHtml .= '<article class="product-card">
            <img class="product-card-image" src="' . htmlspecialchars($imageUrl, ENT_QUOTES) . '" alt="' . htmlspecialchars($altText, ENT_QUOTES) . '" width="400" height="267" loading="lazy">
            <div class="product-card-content">
              <div class="product-header">
                <h3 class="product-title">
                  ' . $titolo . '
                </h3>
              </div>

              <p class="product-description">
                ' . $descrizione . '
              </p>

              <div class="product-footer">
                <a href="' . $detailUrl . '" class="product-cta" aria-label="Leggi l\'articolo: ' . $titolo . '">
                  Leggi tutto →
                </a>
              </div>
            </div>
          </article>';
    }
} else {
    $cardsHtml = '<p class="catalog-empty">Non ci sono articoli da mostrare al momento. Torna presto.</p>';
}

$html = str_replace('[BLOG_CARDS]', $cardsHtml, $html);

echo $html;
?>
