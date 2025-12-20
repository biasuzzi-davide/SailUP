<?php

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

$html = buildPage('../pages/catalogo_esperienze.html', $_SERVER['PHP_SELF']);

$db = new DBConnection();
$prodottiExperience = $db->getProdottiWithMedia('Experience', 50);

$cardsHtml = '';
if ($prodottiExperience && is_array($prodottiExperience) && count($prodottiExperience) > 0) {
    foreach ($prodottiExperience as $prodotto) {
        $idProdotto = $prodotto['IDProdotto'] ?? '';
        if ($idProdotto === '') {
            continue;
        }

        $imageUrl = $prodotto['URL_Media'] ?? '../img/placeholder.png';
        $altText = $prodotto['Testo_Alternativo'] ?? 'Immagine non disponibile';

        $productName = htmlspecialchars($prodotto['Nome_Prodotto'] ?? 'Esperienza', ENT_QUOTES);
        $description = htmlspecialchars($prodotto['Descrizione_Breve'] ?? 'Descrizione non disponibile.', ENT_QUOTES);

        $tipologiaRaw = $prodotto['Tipologia_Prodotto'] ?? 'Tour';
        $badgeText = htmlspecialchars($tipologiaRaw, ENT_QUOTES);
        $badgeSlug = strtolower($tipologiaRaw);
        if (strpos($badgeSlug, 'aperitivo') !== false) {
            $badgeClass = 'badge-aperitivo';
        } elseif (strpos($badgeSlug, 'escursione') !== false) {
            $badgeClass = 'badge-escursione';
        } elseif (strpos($badgeSlug, 'tour') !== false) {
            $badgeClass = 'badge-tour';
        } else {
            $badgeClass = 'badge-tour';
        }

        $postiTotali = isset($prodotto['Posti_Totali']) ? (int) $prodotto['Posti_Totali'] : null;
        $postiDescrizione = $postiTotali !== null ? $postiTotali . ' posti' : '—';

        $accessibile = filter_var($prodotto['Accessibile_Disabili'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $accessibileLabel = $accessibile ? 'Accessibile a tutti' : 'Accessibilità limitata';

        $price = isset($prodotto['Prezzo_Base']) ? number_format((float) $prodotto['Prezzo_Base'], 0, ',', '.') : '—';
        $detailUrl = 'dettaglio_esperienza.php?id=' . rawurlencode($idProdotto);

        $cardsHtml .= '<article class="product-card">
            <img class="product-card-image" src="' . htmlspecialchars($imageUrl, ENT_QUOTES) . '" alt="' . htmlspecialchars($altText, ENT_QUOTES) . '" width="400" height="267" loading="lazy">
            <div class="product-card-content">
              <div class="product-header">
                <h3 class="product-title">
                  ' . $productName . '
                </h3>
                <span class="product-badge ' . $badgeClass . '">' . $badgeText . '</span>
              </div>

              <p class="product-description">
                ' . $description . '
              </p>

              <ul class="product-specs">
                <li class="spec-item">
                  <span class="spec-icon" aria-hidden="true">👥</span>
                  ' . $postiDescrizione . '
                </li>
                <li class="spec-item">
                  <span class="spec-icon" aria-hidden="true">♿</span>
                  ' . $accessibileLabel . '
                </li>
                <li class="spec-item">
                  <span class="spec-icon" aria-hidden="true">🗺️</span>
                  ' . $badgeText . '
                </li>
              </ul>

              <div class="product-footer">
                <div class="product-price">
                  <span class="price-label">da</span>
                  <span class="price-value">' . $price . '€</span>
                  <span class="price-period">/tour</span>
                </div>
                <a href="' . $detailUrl . '" class="product-cta" aria-label="Vedi dettagli ' . $productName . '">
                  Vedi dettagli →
                </a>
              </div>
            </div>
          </article>';
    }
} else {
    $cardsHtml = '<p class="catalog-empty">Non sono presenti esperienze da mostrare al momento. Torna presto.</p>';
}

$html = str_replace('[ESPERIENZE_CARDS]', $cardsHtml, $html);

echo $html;
?>
