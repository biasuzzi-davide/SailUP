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
$allowedSort = ['price-asc', 'price-desc', 'capacity-asc', 'capacity-desc'];
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

        // Recupera le lingue per questo prodotto
        $lingueAssoc = $db->getLinguePerProdotto($idProdotto);
        
        $cardsHtml .= buildExperienceCatalogCard($prodotto, $lingueAssoc);
    }
} else {
    $cardsHtml = '<p class="catalog-empty">Non sono presenti esperienze da mostrare al momento. Torna presto.</p>';
}

$linguaOptions = buildLinguaOptions($lingueDisponibili, $linguaSelezionata);

$html = str_replace('[LINGUA_OPTIONS]', $linguaOptions, $html);

$html = str_replace('[ATTR_VAL_POSTI]', $postiMin !== null ? 'value="' . htmlspecialchars((string) $postiMin, ENT_QUOTES) . '"' : '', $html);
$html = str_replace('[ATTR_VAL_DATA]', $dataEsperienza !== '' ? 'value="' . htmlspecialchars($dataEsperienza, ENT_QUOTES) . '"' : '', $html);
$html = str_replace('[ATTR_VAL_MAX_PRICE]', $maxPrice !== null ? 'value="' . htmlspecialchars((string) $maxPrice, ENT_QUOTES) . '"' : '', $html);
$html = str_replace('[MIN_DATE]', date('Y-m-d'), $html);
$html = str_replace('[CHECK_ACCESSIBILE]', $accessibileFiltro ? 'checked' : '', $html);

$html = str_replace('[SELECTED_PRICE_ASC]', $sortChoice === 'price-asc' ? 'selected' : '', $html);
$html = str_replace('[SELECTED_PRICE_DESC]', $sortChoice === 'price-desc' ? 'selected' : '', $html);
$html = str_replace('[SELECTED_CAPACITY_ASC]', $sortChoice === 'capacity-asc' ? 'selected' : '', $html);
$html = str_replace('[SELECTED_CAPACITY_DESC]', $sortChoice === 'capacity-desc' ? 'selected' : '', $html);

$html = str_replace('[ESPERIENZE_CARDS]', $cardsHtml, $html);

echo $html;
?>
