<?php

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/session/session.php';

$db = new DBConnection();

// Variabili per i messaggi (usate sia in GET che in POST)
$serverState = '';
$serverMessage = '';

// === GESTIONE POST - Elaborazione prenotazione ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Prendo l'ID prodotto dal form
	$productId = trim((string) filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	
	// Recupero il prodotto
	$productDetail = null;
	if ($productId !== '') {
		$productDetail = $db->getProdottoWithMediaById($productId);
	}
	
	if (!$productDetail || $productDetail['Tipo_Prodotto'] !== 'Noleggio') {
		http_response_code(404);
		echo buildPage('../pages/404.html', $_SERVER['PHP_SELF']);
		exit;
	}
	
	// Controllo se l'utente è loggato
	if (!isLogged()) {
		$serverState = 'visible';
		$serverMessage = 'Per prenotare devi effettuare il <a href="login.php">login</a> o la <a href="registrazione.php">registrazione</a>.';
		// Non faccio exit, continuo per mostrare la pagina con l'errore
	} else {
		// L'utente è loggato, procedo con la prenotazione
		$idUtente = $_SESSION['user']['IDUtente'];
		
		// Recupero i dati del form
		$startDate = trim((string) filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$endDate = trim((string) filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$selectPayment = trim((string) filter_input(INPUT_POST, 'select_payment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$skipperChecked = isset($_POST['skipper']);
		$extrasSelected = isset($_POST['extras']) && is_array($_POST['extras']) ? $_POST['extras'] : [];
		
		// Validazione base
		if (empty($startDate) || empty($endDate) || empty($selectPayment)) {
			$serverState = 'visible';
			$serverMessage = 'Compila tutti i campi obbligatori.';
		} else {
			// Converto le date in formato datetime
			$dataInizio = $startDate . ' 00:00:00';
			$dataFine = $endDate . ' 23:59:59';
			
			// Controllo che le date non siano nel passato
			$oggi = date('Y-m-d');
			if ($startDate < $oggi || $endDate < $oggi) {
				$serverState = 'visible';
				$serverMessage = 'Non è possibile prenotare date nel passato.';
			} elseif ($endDate < $startDate) {
				$serverState = 'visible';
				$serverMessage = 'La data di check-out deve essere successiva al check-in.';
			} else {
				// Controllo disponibilità
				$isAvailable = $db->checkDateAvailability($productId, $dataInizio, $dataFine);
				
				if (!$isAvailable) {
					$serverState = 'visible';
					$serverMessage = 'Le date selezionate non sono disponibili. Scegli altre date.';
				} else {
					// Calcolo del prezzo
					$prezzoBase = (float) ($productDetail['Prezzo_Base'] ?? 0);
					
					// Carico gli extra
					$extraDb = $db->getProdottoExtra($productDetail['IDProdotto']);
					if ($extraDb === false) $extraDb = [];
					
					// Utilizzo la funzione helper per calcolare il prezzo totale
					$prezzoTotale = calcolaPrezzoNoleggio(
						$prezzoBase,
						$startDate,
						$endDate,
						$extrasSelected,
						$extraDb,
						$skipperChecked
					);
					
					// Determino metodo e stato in base alla selezione
					$metodoPagamento = 'Contanti';
					$statoPrenotazione = 'In Attesa';
					
					if ($selectPayment === 'cc') {
						$metodoPagamento = 'Carta di Credito';
					} elseif ($selectPayment === 'bb') {
						$metodoPagamento = 'Bonifico';
					} elseif ($selectPayment === 'contanti') {
						$metodoPagamento = 'Contanti';
					}
					
					// Se il pagamento è con carta, NON inserisco subito la prenotazione
					if ($selectPayment === 'cc') {
						// Salvo i dati in sessione senza creare la prenotazione
						$_SESSION['prenotazione_temp'] = [
							'id_prenotazione' => null,
							'id_utente' => $idUtente,
							'id_prodotto' => $productId,
							'nome_prodotto' => $productDetail['Nome_Prodotto'],
							'tipo_prodotto' => 'Noleggio',
							'data_inizio' => $dataInizio,
							'data_fine' => $dataFine,
							'data_inizio_display' => $startDate,
							'data_fine_display' => $endDate,
							'skipper' => $skipperChecked,
							'prezzo_totale' => $prezzoTotale,
							'metodo_pagamento' => $metodoPagamento,
							'stato' => 'Confermata',
							'extras_selezionati' => $extrasSelected,
							'extras_disponibili' => $extraDb,
						];
						
						// Vai alla pagina di pagamento
						header('Location: pagamento.php');
						exit;
					}
					
					// Per altri metodi di pagamento, inserisco la prenotazione normalmente
					$idPrenotazione = $db->insertPrenotazione(
						$idUtente,
						$productId,
						$dataInizio,
						$dataFine,
						$skipperChecked,
						$prezzoTotale,
						$metodoPagamento,
						$statoPrenotazione,
						null
					);
					
					if (!$idPrenotazione) {
						$serverState = 'visible';
						$serverMessage = 'Errore durante la creazione della prenotazione. Riprova.';
					} else {
						// Salvo i dati della prenotazione in sessione
						$_SESSION['prenotazione_temp'] = [
							'id_prenotazione' => $idPrenotazione,
							'id_prodotto' => $productId,
							'nome_prodotto' => $productDetail['Nome_Prodotto'],
							'tipo_prodotto' => 'Noleggio',
							'data_inizio' => $dataInizio,
							'data_fine' => $dataFine,
							'data_inizio_display' => $startDate,
							'data_fine_display' => $endDate,
							'skipper' => $skipperChecked,
							'prezzo_totale' => $prezzoTotale,
							'metodo_pagamento' => $metodoPagamento,
							'stato' => $statoPrenotazione,
							'extras_selezionati' => $extrasSelected,
							'extras_disponibili' => $extraDb,
						];
						
						// Vai alla conferma
						header('Location: conferma_prenotazione.php');
						exit;
					}
				}
			}
		}
	}
	// Se arrivo qui, c'è stato un errore - continuo per mostrare la pagina con il messaggio
}

// === GESTIONE GET - Visualizzazione dettaglio ===
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
$extraCheckboxes = buildExtraCheckboxes($extra);

$html = buildPage('../pages/dettaglio_barca.html', $_SERVER['PHP_SELF']);

// Genera keywords dinamiche basate sui dati della barca
$keywordParts = ['noleggio', strtolower($productName), strtolower($productType), 'barche', 'Napoli', 'golfo'];
if ($licenseRaw === false) {
    $keywordParts[] = 'senza';
    $keywordParts[] = 'patente';
} else if ($licenseRaw === true) {
    $keywordParts[] = 'patente';
    $keywordParts[] = 'richiesta';
}
$keywordParts[] = 'mare';
$keywordsContent = implode(', ', array_unique($keywordParts));
$keywords = '<meta name="keywords" content="' . htmlspecialchars($keywordsContent, ENT_QUOTES) . '">';

$placeholders = [
	'[PRODUCT_ID]' => htmlspecialchars($productId, ENT_QUOTES),
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
	'[MIN_DATE]' => date('Y-m-d'),
	'[KEYWORDS]' => $keywords,
	'[SERVER_STATE]' => $serverState,
	'[SERVER_MESSAGES]' => $serverMessage,
];

$html = str_replace(array_keys($placeholders), array_values($placeholders), $html);

echo $html;
?>
