<?php

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

$db = new DBConnection();
$experienceId = trim((string) filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$experience = null;

if ($experienceId !== '') {
	$experience = $db->getProdottoWithMediaById($experienceId);
}

if (!$experience || ($experience['Tipo_Prodotto'] ?? '') !== 'Experience') {
	http_response_code(404);
	echo buildPage('../pages/404.html', $_SERVER['PHP_SELF']);
	exit;
}

$experienceName = $experience['Nome_Prodotto'] ?? 'Esperienza SailUP';
$experienceTagline = $experience['Descrizione_Breve'] ?? 'Dettagli in arrivo...';
$heroImage = resolveImageUrl($experience['URL_Media'] ?? null);
$heroAlt = 'Vista di ' . $experienceName;

$durationRaw = $experience['Durata_Ore'];
$duration = ($durationRaw !== null && $durationRaw !== '')
	? ((int) $durationRaw) . ' h'
	: '—';
$participants = isset($experience['Posti_Totali']) ? $experience['Posti_Totali'] . ' persone' : '—';
$access = isset($experience['Accessibile_Disabili']) && filter_var($experience['Accessibile_Disabili'], FILTER_VALIDATE_BOOLEAN) ? 'Accessibile' : 'Limitato';
$descriptionBlock = buildParagraphsFromText($experience['Descrizione'] ?? '', 'Descrizione non disponibile per questa esperienza.');
$price = formatPriceValue($experience['Prezzo_Base'] ?? null);

$inclusi = $db->getProdottoInclusi($experience['IDProdotto']);
if ($inclusi === false) {
	$inclusi = [];
}

$extra = $db->getProdottoExtra($experience['IDProdotto']);
if ($extra === false) {
	$extra = [];
}

$includedHtml = buildItemsList(
	is_array($inclusi) ? $inclusi : [],
	'Nome_Incluso',
	'Non ci sono inclusi al momento.'
);

$extraHtml = buildItemsList(
	is_array($extra) ? $extra : [],
	'Nome_Extra',
	'Non ci sono extra al momento.',
	function ($item) {
		$label = htmlspecialchars($item['Nome_Extra'] ?? '', ENT_QUOTES);
		if (!empty($item['Prezzo_Extra'])) {
			$formattedPrice = formatCurrencyWithDecimals($item['Prezzo_Extra']);
			if ($formattedPrice !== '') {
				$label .= ' (+ ' . htmlspecialchars($formattedPrice, ENT_QUOTES) . ' €)';
			}
		}

		return $label;
	}
);

$languages = $db->getLinguePerProdotto($experience['IDProdotto']);
$languageNames = [];
if (is_array($languages)) {
	foreach ($languages as $lang) {
		$name = $lang['Nome'] ?? $lang['Codice'] ?? '';
		if ($name !== '') {
			$languageNames[] = htmlspecialchars($name, ENT_QUOTES);
		}
	}
}
$languageList = $languageNames !== [] ? implode(', ', $languageNames) : '—';

$html = buildPage('../pages/dettaglio_esperienza.html', $_SERVER['PHP_SELF']);

$placeholders = [
	'[EXPERIENCE_NAME]' => htmlspecialchars($experienceName, ENT_QUOTES),
	'[EXPERIENCE_DESCRIPTION_SHORT]' => htmlspecialchars($experienceTagline, ENT_QUOTES),
	'[EXPERIENCE_IMAGE_SRC]' => htmlspecialchars($heroImage, ENT_QUOTES),
	'[EXPERIENCE_IMAGE_ALT]' => htmlspecialchars($heroAlt, ENT_QUOTES),
	'[EXPERIENCE_TAGLINE]' => htmlspecialchars($experienceTagline, ENT_QUOTES),
	'[EXPERIENCE_DURATION]' => htmlspecialchars($duration, ENT_QUOTES),
	'[EXPERIENCE_PARTICIPANTS]' => htmlspecialchars($participants, ENT_QUOTES),
	'[EXPERIENCE_ACCESS]' => htmlspecialchars($access, ENT_QUOTES),
	'[EXPERIENCE_DESCRIPTION_BLOCK]' => $descriptionBlock,
	'[INCLUDED_ITEMS]' => $includedHtml,
	'[EXTRA_ITEMS]' => $extraHtml,
	'[EXPERIENCE_LANGUAGES]' => htmlspecialchars($languageList, ENT_QUOTES),
	'[EXPERIENCE_PRICE]' => htmlspecialchars($price, ENT_QUOTES),
];

$html = str_replace(array_keys($placeholders), array_values($placeholders), $html);

echo $html;
?>
