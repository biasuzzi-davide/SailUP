<?php

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

$html = buildPage('../pages/catalogo_esperienze.html', $_SERVER['PHP_SELF']);
$html = str_replace('[ACTION_URL]', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES), $html);

$db = new DBConnection();

$lingueDisponibili = $db->getLingueDisponibiliPerTipo('Experience');

$postiMin = null;
if (isset($_GET['posti_disponibili'])) {
    $postiMin = filter_var($_GET['posti_disponibili'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: null;
}

$datePattern = '/^\d{4}-\d{2}-\d{2}$/';
$dataEsperienza = (isset($_GET['data_esperienza']) && preg_match($datePattern, (string) $_GET['data_esperienza']))
    ? $_GET['data_esperienza']
    : '';
$dataRichiestaInizio = null;
$dataRichiestaFine = null;
if ($dataEsperienza !== '') {
    $parsedDate = DateTime::createFromFormat('Y-m-d', $dataEsperienza);
    if ($parsedDate) {
        $formattedDate = $parsedDate->format('Y-m-d');
        $dataRichiestaInizio = $formattedDate . ' 00:00:00';
        $dataRichiestaFine = $formattedDate . ' 23:59:59';
    }
}

$linguaSelezionata = '';
if (isset($_GET['lingua_guida'])) {
    $rawLingua = trim((string) $_GET['lingua_guida']);
    if ($rawLingua !== '' && strtolower($rawLingua) !== 'indifferente') {
        $codiciDisponibili = is_array($lingueDisponibili) ? array_column($lingueDisponibili, 'Codice') : [];
        if (in_array($rawLingua, $codiciDisponibili, true)) {
            $linguaSelezionata = $rawLingua;
        }
    }
}

$maxPrice = null;
if (isset($_GET['maxPriceExperience'])) {
    $validatedPrice = filter_var($_GET['maxPriceExperience'], FILTER_VALIDATE_FLOAT);
    if ($validatedPrice !== false && $validatedPrice > 0) {
        $maxPrice = $validatedPrice;
    }
}

$accessibileFiltro = null;
if (isset($_GET['accessibile'])) {
    $accessibileFiltro = true;
}

$sortParam = $_GET['sort'] ?? 'price-asc';
$allowedSort = ['price-asc', 'price-desc', 'duration'];
$sortChoice = in_array($sortParam, $allowedSort, true) ? $sortParam : 'price-asc';

$filters = [
    'postiMin' => $postiMin,
    'prezzoMax' => $maxPrice,
    'accessibile' => $accessibileFiltro,
    'lingua' => $linguaSelezionata,
];
if ($dataRichiestaInizio !== null && $dataRichiestaFine !== null) {
    $filters['dataRichiestaInizio'] = $dataRichiestaInizio;
    $filters['dataRichiestaFine'] = $dataRichiestaFine;
}

$prodottiExperience = $db->getProdottiWithMedia('Experience', 200, $filters, $sortChoice);

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

        $lingueDisponibiliProdotto = [];
        $lingueAssoc = $db->getLinguePerProdotto($idProdotto);
        if (is_array($lingueAssoc)) {
            foreach ($lingueAssoc as $lingua) {
                if (!empty($lingua['Nome'])) {
                    $lingueDisponibiliProdotto[] = htmlspecialchars($lingua['Nome'], ENT_QUOTES);
                }
            }
        }
        $lingueDescrizione = !empty($lingueDisponibiliProdotto) ? implode(', ', $lingueDisponibiliProdotto) : 'Lingue in definizione';

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
                  <span class="spec-icon" aria-hidden="true">🌐</span>
                  Lingue: ' . $lingueDescrizione . '
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

$linguaOptions = '';
$indifferenteSelected = $linguaSelezionata === '' ? ' selected' : '';
$linguaOptions .= '<option value=""' . $indifferenteSelected . '>Indifferente</option>';
if ($lingueDisponibili && is_array($lingueDisponibili) && count($lingueDisponibili) > 0) {
    foreach ($lingueDisponibili as $lingua) {
        $codice = htmlspecialchars($lingua['Codice'] ?? '', ENT_QUOTES);
        $nome = htmlspecialchars($lingua['Nome'] ?? '', ENT_QUOTES);
        if ($codice === '') {
            continue;
        }
        $selected = ($linguaSelezionata === $lingua['Codice']) ? ' selected' : '';
        $linguaOptions .= '<option value="' . $codice . '"' . $selected . '>' . $nome . '</option>';
    }
} else {
    $linguaOptions .= '<option value="" disabled>Nessuna lingua disponibile</option>';
}

$html = str_replace('[LINGUA_OPTIONS]', $linguaOptions, $html);

$html = str_replace('[VAL_POSTI]', $postiMin !== null ? htmlspecialchars((string) $postiMin, ENT_QUOTES) : '', $html);
$html = str_replace('[VAL_DATA]', htmlspecialchars($dataEsperienza, ENT_QUOTES), $html);
$html = str_replace('[VAL_MAX_PRICE]', $maxPrice !== null ? htmlspecialchars((string) $maxPrice, ENT_QUOTES) : '', $html);
$html = str_replace('[MIN_DATE]', date('Y-m-d'), $html);
$html = str_replace('[CHECK_ACCESSIBILE]', $accessibileFiltro ? 'checked' : '', $html);

$html = str_replace('[SELECTED_PRICE_ASC]', $sortChoice === 'price-asc' ? 'selected' : '', $html);
$html = str_replace('[SELECTED_PRICE_DESC]', $sortChoice === 'price-desc' ? 'selected' : '', $html);
$html = str_replace('[SELECTED_DURATION]', $sortChoice === 'duration' ? 'selected' : '', $html);

$html = str_replace('[ESPERIENZE_CARDS]', $cardsHtml, $html);

echo $html;
?>
