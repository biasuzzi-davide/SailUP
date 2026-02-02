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
	// Prendo l'ID esperienza dal form
	$experienceId = trim((string) filter_input(INPUT_POST, 'experience_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	
	// Recupero l'esperienza
	$experience = null;
	if ($experienceId !== '') {
		$experience = $db->getProdottoWithMediaById($experienceId);
	}
	
	if (!$experience || $experience['Tipo_Prodotto'] !== 'Experience') {
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
		$bookingDate = trim((string) filter_input(INPUT_POST, 'booking_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$selectPayment = trim((string) filter_input(INPUT_POST, 'select_payment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$pickupChecked = isset($_POST['pickup']);
		$extrasSelected = isset($_POST['extras']) && is_array($_POST['extras']) ? $_POST['extras'] : [];
		
		// Validazione base
		if (empty($bookingDate) || empty($selectPayment)) {
			$serverState = 'visible';
			$serverMessage = 'Compila tutti i campi obbligatori.';
		} else {
			// Controllo che la data non sia nel passato
			$oggi = date('Y-m-d');
			if ($bookingDate < $oggi) {
				$serverState = 'visible';
				$serverMessage = 'Non è possibile prenotare date nel passato.';
			} else {
				// Per le esperienze, calcolo inizio e fine in base alla durata
				$durataOre = (int) ($experience['Durata_Ore'] ?? 4); // default 4 ore se non specificato
				$dataInizio = $bookingDate . ' 09:00:00'; // Inizio alle 09:00
				$dataFineDateTime = new DateTime($dataInizio);
				$dataFineDateTime->modify("+{$durataOre} hours");
				$dataFine = $dataFineDateTime->format('Y-m-d H:i:s');
				
				// Controllo disponibilità
				$isAvailable = $db->checkDateAvailability($experienceId, $dataInizio, $dataFine);
				
				if (!$isAvailable) {
					$serverState = 'visible';
					$serverMessage = 'La data selezionata non è disponibile. Scegli un\'altra data.';
				} else {
					// Calcolo del prezzo
					$prezzoBase = (float) ($experience['Prezzo_Base'] ?? 0);
					
					// Carico gli extra
					$extraDb = $db->getProdottoExtra($experience['IDProdotto']);
					if ($extraDb === false) $extraDb = [];
					
					// Utilizzo la funzione helper per calcolare il prezzo totale
					$prezzoTotale = calcolaPrezzoEsperienza(
						$prezzoBase,
						$extrasSelected,
						$extraDb,
						$pickupChecked
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
							'id_prodotto' => $experienceId,
							'nome_prodotto' => $experience['Nome_Prodotto'],
							'tipo_prodotto' => 'Experience',
							'data_inizio' => $dataInizio,
							'data_fine' => $dataFine,
							'data_inizio_display' => $bookingDate,
							'data_fine_display' => null,
							'pickup' => $pickupChecked,
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
					
					// Per altri metodi di pagamento, verifico disponibilità e inserisco la prenotazione
					$disponibile = $db->verificaDisponibilitaProdotto(
						$experienceId,
						$dataInizio,
						$dataFine
					);
					
					if (!$disponibile) {
						$serverState = 'visible';
						$serverMessage = 'Spiacenti, questa esperienza non è più disponibile per la data selezionata. Un altro utente ha completato la prenotazione prima di te. Seleziona una data alternativa.';
					} else {
						$idPrenotazione = $db->insertPrenotazione(
							$idUtente,
							$experienceId,
							$dataInizio,
							$dataFine,
							$pickupChecked,
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
							'id_prodotto' => $experienceId,
							'nome_prodotto' => $experience['Nome_Prodotto'],
							'tipo_prodotto' => 'Experience',
							'data_inizio' => $dataInizio,
							'data_fine' => $dataFine,
							'data_inizio_display' => $bookingDate,
							'data_fine_display' => null,
							'pickup' => $pickupChecked,
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
	}
	// Se arrivo qui, c'è stato un errore - continuo per mostrare la pagina con il messaggio
}

// === GESTIONE GET - Visualizzazione dettaglio ===
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
$experienceNameVisual = formatText($experienceName);
$experienceTagline = $experience['Descrizione_Breve'] ?? 'Dettagli in arrivo...';
$heroImage = resolveImageUrl($experience['URL_Media'] ?? null);
$heroAlt = $experience['Testo_Alternativo'] ?? '';

$durationRaw = $experience['Durata_Ore'];
$duration = ($durationRaw !== null && $durationRaw !== '')
	? ((int) $durationRaw) . ' <abbr title="ore">h</abbr>'
	: '—';
$postiTotali = isset($experience['Posti_Totali']) ? (int) $experience['Posti_Totali'] : null;
$participants = $postiTotali !== null 
	? ($postiTotali === 1 ? '1 persona' : $postiTotali . ' persone')
	: '—';
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
	'Non ci sono inclusi al momento.',
	function ($item) {
        return formatText($item['Nome_Incluso'] ?? '');
    }
);

$extraHtml = buildItemsList(
	is_array($extra) ? $extra : [],
	'Nome_Extra',
	'Non ci sono extra al momento.',
	function ($item) {
        $label = formatText($item['Nome_Extra'] ?? '');
        
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

// Genera keywords dinamiche basate sui dati dell'esperienza
$keywordParts = array_filter([strtolower($experienceName)]);
if (!empty($languageNames)) {
    $keywordParts[] = 'guida';
    $languageParts = array_slice($languageNames, 0, 2);
    foreach ($languageParts as $lang) {
        $keywordParts[] = strtolower($lang);
    }
}
$keywordsContent = implode(', ', array_unique($keywordParts));
$keywords = '<meta name="keywords" content="' . htmlspecialchars($keywordsContent, ENT_QUOTES) . '">';

$placeholders = [
	'[EXPERIENCE_ID]' => htmlspecialchars($experienceId, ENT_QUOTES),
	'[EXPERIENCE_NAME]' => htmlspecialchars($experienceName, ENT_QUOTES),
	'[EXPERIENCE_NAME_VISUAL]' => $experienceNameVisual,
	'[EXPERIENCE_DESCRIPTION_SHORT]' => $experienceTagline,
	'[EXPERIENCE_IMAGE_SRC]' => htmlspecialchars($heroImage, ENT_QUOTES),
	'[EXPERIENCE_IMAGE_ALT]' => htmlspecialchars($heroAlt, ENT_QUOTES),
	'[EXPERIENCE_TAGLINE]' => formatText($experienceTagline),
	'[EXPERIENCE_DURATION]' => $duration,
	'[EXPERIENCE_PARTICIPANTS]' => htmlspecialchars($participants, ENT_QUOTES),
	'[EXPERIENCE_ACCESS]' => htmlspecialchars($access, ENT_QUOTES),
	'[EXPERIENCE_DESCRIPTION_BLOCK]' => $descriptionBlock,
	'[INCLUDED_ITEMS]' => $includedHtml,
	'[EXTRA_ITEMS]' => $extraHtml,
	'[EXPERIENCE_LANGUAGES]' => htmlspecialchars($languageList, ENT_QUOTES),
	'[EXPERIENCE_PRICE]' => htmlspecialchars($price, ENT_QUOTES),
	'[EXTRA_CHECKBOXES]' => $extraCheckboxes,
	'[MIN_DATE]' => date('Y-m-d'),
	'[KEYWORDS]' => $keywords,
	'[SERVER_STATE]' => $serverState,
	'[SERVER_MESSAGES]' => $serverMessage,
];

$html = str_replace(array_keys($placeholders), array_values($placeholders), $html);

echo $html;
?>
