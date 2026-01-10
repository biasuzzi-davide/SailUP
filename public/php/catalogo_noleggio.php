<?php

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

$html = buildPage('../pages/catalogo_noleggio.html', $_SERVER['PHP_SELF']);

// Keywords per SEO
$keywords = '<meta name="keywords" content="noleggio, barche, vela, motore, gommoni, patente, catalogo, flotta, Napoli, golfo">';
$html = str_replace('[KEYWORDS]', $keywords, $html);

$html = str_replace('[ACTION_URL]', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES), $html);

$db = new DBConnection();

$tipologieDisponibili = $db->getTipologieByTipo('Noleggio');

$rawTipologie = isset($_GET['tipo']) ? (array) $_GET['tipo'] : [];
$tipologieSelezionate = array_values(array_unique(array_filter(
	array_map('trim', $rawTipologie),
	fn($value) => $value !== ''
)));

$patenteParam = $_GET['patente'] ?? 'indifferente';
if (!in_array($patenteParam, ['richiesta', 'non_richiesta', 'indifferente'], true)) {
	$patenteParam = 'indifferente';
}
$patenteFiltro = null;
if ($patenteParam === 'richiesta') {
	$patenteFiltro = true;
} elseif ($patenteParam === 'non_richiesta') {
	$patenteFiltro = false;
}

$postiMin = null;
if (isset($_GET['posti'])) {
	$postiMin = filter_var($_GET['posti'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: null;
}

$maxPrice = null;
if (isset($_GET['maxPrice'])) {
	$validatedPrice = filter_var($_GET['maxPrice'], FILTER_VALIDATE_FLOAT);
	if ($validatedPrice !== false && $validatedPrice > 0) {
		$maxPrice = $validatedPrice;
	}
}

$datePattern = '/^\d{4}-\d{2}-\d{2}$/';
$dataInizio = (isset($_GET['data_inizio']) && preg_match($datePattern, (string) $_GET['data_inizio'])) ? $_GET['data_inizio'] : '';
$dataFine = (isset($_GET['data_fine']) && preg_match($datePattern, (string) $_GET['data_fine'])) ? $_GET['data_fine'] : '';

$dataRichiestaInizio = null;
$dataRichiestaFine = null;
if ($dataInizio !== '' && $dataFine !== '') {
	$dataInizioObj = DateTime::createFromFormat('Y-m-d', $dataInizio);
	$dataFineObj = DateTime::createFromFormat('Y-m-d', $dataFine);
	if ($dataInizioObj && $dataFineObj) {
		if ($dataInizioObj > $dataFineObj) {
			[$dataInizioObj, $dataFineObj] = [$dataFineObj, $dataInizioObj];
		}
		$dataRichiestaInizio = $dataInizioObj->format('Y-m-d') . ' 00:00:00';
		$dataRichiestaFine = $dataFineObj->format('Y-m-d') . ' 23:59:59';
	}
}

$sortParam = $_GET['sort'] ?? 'price-asc';
$allowedSort = ['price-asc', 'price-desc', 'size'];
$sortChoice = in_array($sortParam, $allowedSort, true) ? $sortParam : 'price-asc';

$filters = [
	'tipologie' => $tipologieSelezionate,
	'patente' => $patenteFiltro,
	'postiMin' => $postiMin,
	'prezzoMax' => $maxPrice,
];
if ($dataRichiestaInizio !== null && $dataRichiestaFine !== null) {
	$filters['dataRichiestaInizio'] = $dataRichiestaInizio;
	$filters['dataRichiestaFine'] = $dataRichiestaFine;
}

$prodottiNoleggio = $db->getProdottiWithMedia('Noleggio', 200, $filters, $sortChoice);

$cardsHtml = '';
if ($prodottiNoleggio && is_array($prodottiNoleggio) && count($prodottiNoleggio) > 0) {
	foreach ($prodottiNoleggio as $prodotto) {
		$cardsHtml .= buildNoleggioCatalogCard($prodotto);
	}
} else {
	$cardsHtml = '<p class="catalog-empty">Al momento non ci sono imbarcazioni disponibili in noleggio. Torna più tardi.</p>';
}

$tipologiaOptionsHtml = buildTipologieCheckboxes($tipologieDisponibili, $tipologieSelezionate);

$html = str_replace('[TIPOLOGIA_OPTIONS]', $tipologiaOptionsHtml, $html);

$html = str_replace('[CHECK_PATENTE_RICHIESTA]', $patenteParam === 'richiesta' ? 'checked' : '', $html);
$html = str_replace('[CHECK_PATENTE_NON_RICHIESTA]', $patenteParam === 'non_richiesta' ? 'checked' : '', $html);
$html = str_replace('[CHECK_PATENTE_IND]', ($patenteParam === 'indifferente' || $patenteParam === '') ? 'checked' : '', $html);

$html = str_replace('[VAL_POSTI]', $postiMin !== null ? htmlspecialchars((string) $postiMin, ENT_QUOTES) : '', $html);
$html = str_replace('[VAL_DATA_INIZIO]', htmlspecialchars($dataInizio, ENT_QUOTES), $html);
$html = str_replace('[VAL_DATA_FINE]', htmlspecialchars($dataFine, ENT_QUOTES), $html);
$html = str_replace('[VAL_MAX_PRICE]', $maxPrice !== null ? htmlspecialchars((string) $maxPrice, ENT_QUOTES) : '', $html);
$html = str_replace('[MIN_DATE]', date('Y-m-d'), $html);

$html = str_replace('[SELECTED_PRICE_ASC]', $sortChoice === 'price-asc' ? 'selected' : '', $html);
$html = str_replace('[SELECTED_PRICE_DESC]', $sortChoice === 'price-desc' ? 'selected' : '', $html);
$html = str_replace('[SELECTED_SIZE]', $sortChoice === 'size' ? 'selected' : '', $html);

$html = str_replace('[NOLEGGIO_CARDS]', $cardsHtml, $html);

echo $html;
?>