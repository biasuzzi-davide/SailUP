<?php

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

$html = buildPage('../pages/catalogo_noleggio.html', $_SERVER['PHP_SELF']);

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

$sortParam = $_GET['sort'] ?? 'price-asc';
$allowedSort = ['price-asc', 'price-desc', 'size'];
$sortChoice = in_array($sortParam, $allowedSort, true) ? $sortParam : 'price-asc';

$filters = [
	'tipologie' => $tipologieSelezionate,
	'patente' => $patenteFiltro,
	'postiMin' => $postiMin,
	'prezzoMax' => $maxPrice,
];

$prodottiNoleggio = $db->getProdottiWithMedia('Noleggio', 200, $filters, $sortChoice);

$cardsHtml = '';
if ($prodottiNoleggio && is_array($prodottiNoleggio) && count($prodottiNoleggio) > 0) {
		foreach ($prodottiNoleggio as $prodotto) {
				$idProdotto = $prodotto['IDProdotto'] ?? '';
				if ($idProdotto === '') {
						continue;
				}

				$imageUrl = $prodotto['URL_Media'] ?? '../img/placeholder.png';
				$altText = $prodotto['Testo_Alternativo'] ?? 'Immagine non disponibile';

				$productName = htmlspecialchars($prodotto['Nome_Prodotto'] ?? 'Prodotto', ENT_QUOTES);
				$description = htmlspecialchars($prodotto['Descrizione_Breve'] ?? 'Descrizione non disponibile.', ENT_QUOTES);

				$badgeTextRaw = $prodotto['Tipologia_Prodotto'] ?? 'Noleggio';
				$badgeText = htmlspecialchars($badgeTextRaw, ENT_QUOTES);
				$badgeKey = strtolower($badgeTextRaw);
				$badgeClass = 'badge-motore';
				if (strpos($badgeKey, 'vela') !== false) {
						$badgeClass = 'badge-vela';
				} elseif (strpos($badgeKey, 'gommone') !== false) {
						$badgeClass = 'badge-gommone';
				} elseif (strpos($badgeKey, 'motore') !== false) {
						$badgeClass = 'badge-motore';
				}

				$lengthValue = $prodotto['Lunghezza_Barca_Metri'];
				$formattedLength = '—';
				if ($lengthValue !== null && $lengthValue !== '') {
						$formattedLength = number_format((float) $lengthValue, 2, ',', '.');
						$formattedLength = rtrim(rtrim($formattedLength, '0'), ',');
						$formattedLength .= 'm';
				}

				$postiTotali = isset($prodotto['Posti_Totali']) ? (int) $prodotto['Posti_Totali'] : null;
				$postiDescrizione = $postiTotali !== null ? $postiTotali . ' posti' : '—';

				$richiedePatente = filter_var($prodotto['Richiede_Patente'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
				$richiedePatente = $richiedePatente ?? false;
				$patenteIcon = $richiedePatente ? '🎫' : '✅';
				$patenteLabel = $richiedePatente ? 'Patente Richiesta' : 'Patente non Richiesta';

				$prezzoBase = isset($prodotto['Prezzo_Base']) ? number_format((float) $prodotto['Prezzo_Base'], 0, ',', '.') : '—';

				$detailUrl = 'dettaglio_barca.php?id=' . rawurlencode($idProdotto);

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
									<span class="spec-icon" aria-hidden="true">📏</span>
									' . $formattedLength . '
								</li>
								<li class="spec-item">
									<span class="spec-icon" aria-hidden="true">👥</span>
									' . $postiDescrizione . '
								</li>
								<li class="spec-item">
									<span class="spec-icon" aria-hidden="true">' . $patenteIcon . '</span>
									' . $patenteLabel . '
								</li>
							</ul>

							<div class="product-footer">
								<div class="product-price">
									<span class="price-label">da</span>
									<span class="price-value">' . $prezzoBase . '€</span>
									<span class="price-period">/giorno</span>
								</div>
								<a href="' . $detailUrl . '" class="product-cta" aria-label="Vedi dettagli ' . $productName . '">
									Vedi dettagli →
								</a>
							</div>
						</div>
					</article>';
		}
} else {
		$cardsHtml = '<p class="catalog-empty">Al momento non ci sono imbarcazioni disponibili in noleggio. Torna più tardi.</p>';
}

$tipologiaOptionsHtml = '';
if ($tipologieDisponibili && is_array($tipologieDisponibili) && count($tipologieDisponibili) > 0) {
	foreach ($tipologieDisponibili as $index => $tipologia) {
		$label = htmlspecialchars($tipologia, ENT_QUOTES);
		$inputId = 'tipo-' . preg_replace('/[^a-z0-9]+/i', '-', strtolower($tipologia));
		$inputId = trim($inputId, '-');
		if ($inputId === '') {
			$inputId = 'tipo-' . $index;
		}
		$checked = in_array($tipologia, $tipologieSelezionate, true) ? ' checked' : '';
		$tipologiaOptionsHtml .= '<label class="filter-checkbox">
			  <input type="checkbox" id="' . htmlspecialchars($inputId, ENT_QUOTES) . '" name="tipo[]" value="' . $label . '"' . $checked . '>
			  <span>' . $label . '</span>
			</label>';
	}
} else {
	$tipologiaOptionsHtml = '<p class="filter-empty">Nessuna tipologia disponibile.</p>';
}

$html = str_replace('[TIPOLOGIA_OPTIONS]', $tipologiaOptionsHtml, $html);

$html = str_replace('[CHECK_PATENTE_RICHIESTA]', $patenteParam === 'richiesta' ? 'checked' : '', $html);
$html = str_replace('[CHECK_PATENTE_NON_RICHIESTA]', $patenteParam === 'non_richiesta' ? 'checked' : '', $html);
$html = str_replace('[CHECK_PATENTE_IND]', ($patenteParam === 'indifferente' || $patenteParam === '') ? 'checked' : '', $html);

$html = str_replace('[VAL_POSTI]', $postiMin !== null ? htmlspecialchars((string) $postiMin, ENT_QUOTES) : '', $html);
$html = str_replace('[VAL_DATA_INIZIO]', htmlspecialchars($dataInizio, ENT_QUOTES), $html);
$html = str_replace('[VAL_DATA_FINE]', htmlspecialchars($dataFine, ENT_QUOTES), $html);
$html = str_replace('[VAL_MAX_PRICE]', $maxPrice !== null ? htmlspecialchars((string) $maxPrice, ENT_QUOTES) : '', $html);

$html = str_replace('[SELECTED_PRICE_ASC]', $sortChoice === 'price-asc' ? 'selected' : '', $html);
$html = str_replace('[SELECTED_PRICE_DESC]', $sortChoice === 'price-desc' ? 'selected' : '', $html);
$html = str_replace('[SELECTED_SIZE]', $sortChoice === 'size' ? 'selected' : '', $html);

$html = str_replace('[NOLEGGIO_CARDS]', $cardsHtml, $html);

echo $html;
?>