<?php

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

$db = new DBConnection();
$productId = trim((string) filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$productDetail = null;

if ($productId !== '') {
	$productDetail = $db->getProdottoWithMediaById($productId);
}

if ($productDetail === false) {
	$productDetail = null;
}

$productName = $productDetail['Nome_Prodotto'] ?? 'Imbarcazione non trovata';
if (!$productDetail) {
	http_response_code(404);
	echo buildPage('../pages/404.html', $_SERVER['PHP_SELF']);
	exit;
}

$heroImage = resolveImageUrl($productDetail['URL_Media'] ?? null);
$heroAlt = $productDetail ? 'Immagine di ' . $productName : 'Immagine in evidenza';

$briefText = $productDetail['Descrizione_Breve'] ?? 'Descrizione breve in arrivo.';
$productType = $productDetail['Tipologia_Prodotto'] ?? $productDetail['Tipo_Prodotto'] ?? '—';
$lengthValue = formatDecimalNumber($productDetail['Lunghezza_Barca_Metri'] ?? null);
$lengthLabel = $lengthValue !== '—' ? $lengthValue . ' m' : '—';
$seatsValue = isset($productDetail['Posti_Totali']) ? (int) $productDetail['Posti_Totali'] : null;
$seatsLabel = $seatsValue !== null ? (string) $seatsValue : '—';
$licenseRaw = filter_var($productDetail['Richiede_Patente'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
$licenseLabel = $licenseRaw === null ? '—' : ($licenseRaw ? 'Sì' : 'No');
$priceLabel = formatPriceValue($productDetail['Prezzo_Base'] ?? null);
$descriptionBlock = buildParagraphsFromText($productDetail['Descrizione'] ?? '', 'Descrizione non disponibile per questa imbarcazione.');

$inclusi = [];
$extra = [];
if ($productDetail && !empty($productDetail['IDProdotto'])) {
	$inclusi = $db->getProdottoInclusi($productDetail['IDProdotto']);
	if ($inclusi === false) {
		$inclusi = [];
	}

	$extra = $db->getProdottoExtra($productDetail['IDProdotto']);
	if ($extra === false) {
		$extra = [];
	}
}

$inclusiHtml = buildItemsList(
	is_array($inclusi) ? $inclusi : [],
	'Nome_Incluso',
	'Nessuna dotazione inclusa al momento.'
);

$extraHtml = buildItemsList(
	is_array($extra) ? $extra : [],
	'Nome_Extra',
	'Nessun extra disponibile al momento.',
	function ($item) {
		$label = htmlspecialchars($item['Nome_Extra'] ?? '', ENT_QUOTES);
		if (!empty($item['Prezzo_Extra'])) {
			$formattedPrice = number_format((float) $item['Prezzo_Extra'], 0, ',', '.');
			if ($formattedPrice !== '') {
				$label .= ' (+ ' . htmlspecialchars($formattedPrice, ENT_QUOTES) . ' €)';
			}
		}

		return $label;
	}
);

// Genera le checkbox per gli extra nel form
$extraCheckboxes = '';
if (is_array($extra) && count($extra) > 0) {
	foreach ($extra as $index => $extraItem) {
		$extraName = htmlspecialchars($extraItem['Nome_Extra'] ?? '', ENT_QUOTES);
		$extraPrice = $extraItem['Prezzo_Extra'] ?? 0;
		$isOptional = isset($extraItem['Opzionale']) ? filter_var($extraItem['Opzionale'], FILTER_VALIDATE_BOOLEAN) : true;
		$formattedPrice = number_format((float) $extraPrice, 0, ',', '.');
		
		$checkboxId = 'extra-' . $index;
		$checkedAttr = !$isOptional ? 'checked' : '';
		$disabledAttr = !$isOptional ? 'disabled' : '';
		$priceText = $formattedPrice !== '' ? '+' . htmlspecialchars($formattedPrice, ENT_QUOTES) . ' €' : '';
		
		$extraCheckboxes .= '<div class="form-check checkbox-highlight">';
		$extraCheckboxes .= '<input type="checkbox" id="' . $checkboxId . '" name="extras[]" value="' . $index . '" ' . $checkedAttr . ' ' . $disabledAttr . '>';
		$extraCheckboxes .= '<label for="' . $checkboxId . '">';
		$extraCheckboxes .= '<span>' . $extraName . '</span>';
		if ($priceText !== '') {
			$extraCheckboxes .= '<span class="text-accent">' . $priceText . '</span>';
		}
		$extraCheckboxes .= '</label>';
		$extraCheckboxes .= '</div>' . "\n";
	}
}

$html = buildPage('../pages/dettaglio_barca.html', $_SERVER['PHP_SELF']);

$placeholders = [
	'[PRODUCT_IMAGE_SRC]' => htmlspecialchars($heroImage, ENT_QUOTES),
	'[PRODUCT_IMAGE_ALT]' => htmlspecialchars($heroAlt, ENT_QUOTES),
	'[PRODUCT_NAME]' => htmlspecialchars($productName, ENT_QUOTES),
	'[PRODUCT_BRIEF]' => htmlspecialchars($briefText, ENT_QUOTES),
	'[PRODUCT_TYPE]' => htmlspecialchars($productType, ENT_QUOTES),
	'[PRODUCT_LENGTH]' => htmlspecialchars($lengthLabel, ENT_QUOTES),
	'[PRODUCT_SEATS]' => htmlspecialchars($seatsLabel, ENT_QUOTES),
	'[PRODUCT_LICENSE]' => htmlspecialchars($licenseLabel, ENT_QUOTES),
	'[PRODUCT_DESCRIPTION_BLOCK]' => $descriptionBlock,
	'[INCLUDED_ITEMS]' => $inclusiHtml,
	'[EXTRA_ITEMS]' => $extraHtml,
	'[PRODUCT_PRICE]' => htmlspecialchars($priceLabel, ENT_QUOTES),
	'[EXTRA_CHECKBOXES]' => $extraCheckboxes,
];

$html = str_replace(array_keys($placeholders), array_values($placeholders), $html);

echo $html;
?>
