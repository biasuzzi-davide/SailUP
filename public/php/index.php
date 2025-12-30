<?php

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

$html = buildPage('../pages/index.html', $_SERVER['PHP_SELF']);

// Keywords per SEO
$keywords = '<meta name="keywords" content="noleggio barche Napoli, esperienze mare Napoli, barche vela Napoli, gommoni Napoli, Golfo di Napoli, tour mare, escursioni barche">';
$html = str_replace('[KEYWORDS]', $keywords, $html);

// Recupera dati dal DB
$db = new DBConnection();
$fleetProdotti = $db->getProdottiWithMedia('Noleggio', 3);
$experienceProdotti = $db->getProdottiWithMedia('Experience', 2);

// Genera HTML per le card delle barche
$fleetCards = '';
if ($fleetProdotti && is_array($fleetProdotti)) {
    foreach ($fleetProdotti as $prodotto) {
        $imageUrl = $prodotto['URL_Media'] ?? '../img/placeholder.png';
        $altText = $prodotto['Testo_Alternativo'] ?? 'Immagine non disponibile';
        $fleetCards .= '
        <article class="product-card">
          <img class="product-card-image" src="' . htmlspecialchars($imageUrl) . '" alt="' . htmlspecialchars($altText) . '">
          <div class="product-card-content">
            <div class="product-header">
              <h3 class="product-title">' . htmlspecialchars($prodotto['Nome_Prodotto']) . '</h3>
            </div>
            <div class="product-footer">
              <a href="dettaglio_barca.php?id=' . htmlspecialchars($prodotto['IDProdotto']) . '" class="product-cta" aria-label="Vedi dettagli ' . htmlspecialchars($prodotto['Nome_Prodotto']) . '">Scopri di più &rarr;</a>
            </div>
          </div>
        </article>';
    }
} else {
    $fleetCards = '<p>Nessuna barca disponibile al momento.</p>';
}

// Genera HTML per le card delle esperienze
$experienceCards = '';
if ($experienceProdotti && is_array($experienceProdotti)) {
    foreach ($experienceProdotti as $prodotto) {
        $imageUrl = $prodotto['URL_Media'] ?? '../img/placeholder.png';
        $altText = $prodotto['Testo_Alternativo'] ?? 'Immagine non disponibile';
        $experienceCards .= '
        <article class="product-card">
          <img class="product-card-image" src="' . htmlspecialchars($imageUrl) . '" alt="' . htmlspecialchars($altText) . '" loading="lazy">
          <div class="product-card-content">
            <div class="product-header">
              <h3 class="product-title">' . htmlspecialchars($prodotto['Nome_Prodotto']) . '</h3>
            </div>
            <div class="product-footer">
              <a href="dettaglio_esperienza.php?id=' . htmlspecialchars($prodotto['IDProdotto']) . '" class="product-cta" aria-label="Prenota ' . htmlspecialchars($prodotto['Nome_Prodotto']) . '">Prenota ora &rarr;</a>
            </div>
          </div>
        </article>';
    }
} else {
    $experienceCards = '<p>Nessuna esperienza disponibile al momento.</p>';
}

// Sostituisci i placeholder nell'HTML
$html = str_replace('[FLEET_CARDS]', $fleetCards, $html);
$html = str_replace('[EXPERIENCE_CARDS]', $experienceCards, $html);

echo $html;
?>