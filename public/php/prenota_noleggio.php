<?php
// prenota_noleggio.php - Gestisce la prenotazione di un noleggio (barca)

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/session/session.php';

$db = new DBConnection();
$productId = trim((string) filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

// Se arrivo con GET (visualizzazione form), mostro la pagina dettaglio
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    include 'dettaglio_barca.php';
    exit;
}

// Gestione POST - elaborazione prenotazione

// Prendo l'ID prodotto dal form se non presente in GET
if (empty($productId)) {
    $productId = trim((string) filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
}

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

// Variabili per i messaggi
$serverState = '';
$serverMessage = '';

// Controllo se l'utente è loggato
if (!isLogged()) {
    $serverState = 'visible';
    $serverMessage = 'Per prenotare devi effettuare il <a href="login.php">login</a> o la <a href="registrazione.php">registrazione</a>.';
    
    // Ricarico la pagina con il messaggio di errore
    $html = buildPage('../pages/dettaglio_barca.html', $_SERVER['PHP_SELF']);
    
    // Recupero tutti i dati per popolare la pagina
    $heroImage = resolveImageUrl($productDetail['URL_Media'] ?? null);
    $heroAlt = 'Immagine di ' . ($productDetail['Nome_Prodotto'] ?? '');
    $productName = $productDetail['Nome_Prodotto'] ?? 'Imbarcazione non trovata';
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
    
    $inclusi = $db->getProdottoInclusi($productDetail['IDProdotto']);
    if ($inclusi === false) $inclusi = [];
    
    $extra = $db->getProdottoExtra($productDetail['IDProdotto']);
    if ($extra === false) $extra = [];
    
    $inclusiHtml = buildItemsList(is_array($inclusi) ? $inclusi : [], 'Nome_Incluso', 'Nessuna dotazione inclusa al momento.');
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
    
    $extraCheckboxes = '';
    if (is_array($extra) && count($extra) > 0) {
        foreach ($extra as $index => $extraItem) {
            $extraName = htmlspecialchars($extraItem['Nome_Extra'] ?? '', ENT_QUOTES);
            $extraPrice = $extraItem['Prezzo_Extra'] ?? 0;
            $extraId = $extraItem['IDExtra'] ?? $index;
            $isOptional = isset($extraItem['Opzionale']) ? filter_var($extraItem['Opzionale'], FILTER_VALIDATE_BOOLEAN) : true;
            $formattedPrice = number_format((float) $extraPrice, 0, ',', '.');
            
            $checkboxId = 'extra-' . $extraId;
            $checkedAttr = !$isOptional ? 'checked' : '';
            $disabledAttr = !$isOptional ? 'disabled' : '';
            $priceText = $formattedPrice !== '' ? '+' . htmlspecialchars($formattedPrice, ENT_QUOTES) . ' €' : '';
            
            $extraCheckboxes .= '<div class="form-check checkbox-highlight">';
            $extraCheckboxes .= '<input type="checkbox" id="' . $checkboxId . '" name="extras[]" value="' . $extraId . '" ' . $checkedAttr . ' ' . $disabledAttr . '>';
            // Aggiungi hidden input per extra obbligatori (disabled non viene inviato)
            if (!$isOptional) {
                $extraCheckboxes .= '<input type="hidden" name="extras[]" value="' . $extraId . '">';
            }
            $extraCheckboxes .= '<label for="' . $checkboxId . '">';
            $extraCheckboxes .= '<span>' . $extraName . '</span>';
            if ($priceText !== '') {
                $extraCheckboxes .= '<span class="text-accent">' . $priceText . '</span>';
            }
            $extraCheckboxes .= '</label>';
            $extraCheckboxes .= '</div>' . "\n";
        }
    }
    
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
        '[SERVER_STATE]' => $serverState,
        '[SERVER_MESSAGES]' => $serverMessage,
    ];
    
    // Keywords dinamiche
    $keywordsContent = 'noleggio ' . strtolower($productName) . ', ' . strtolower($productType) . ' Napoli, prenota barca Napoli';
    $placeholders['[KEYWORDS]'] = '<meta name="keywords" content="' . htmlspecialchars($keywordsContent, ENT_QUOTES) . '">';
    $placeholders['[PRODUCT_ID]'] = htmlspecialchars($productId, ENT_QUOTES);
    $placeholders['[MIN_DATE]'] = date('Y-m-d');
    
    $html = str_replace(array_keys($placeholders), array_values($placeholders), $html);
    echo $html;
    exit;
}

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
    include 'dettaglio_barca.php';
    exit;
}

// Converto le date in formato datetime (con ora 00:00:00)
$dataInizio = $startDate . ' 00:00:00';
$dataFine = $endDate . ' 23:59:59';

// Controllo che le date non siano nel passato
$oggi = date('Y-m-d');
if ($startDate < $oggi || $endDate < $oggi) {
    $serverState = 'visible';
    $serverMessage = 'Non è possibile prenotare date nel passato.';
    include 'dettaglio_barca.php';
    exit;
}

// Controllo che la data fine sia successiva alla data inizio
if ($endDate < $startDate) {
    $serverState = 'visible';
    $serverMessage = 'La data di check-out deve essere successiva al check-in.';
    include 'dettaglio_barca.php';
    exit;
}

// Controllo disponibilità
$isAvailable = $db->checkDateAvailability($productId, $dataInizio, $dataFine);

if (!$isAvailable) {
    $serverState = 'visible';
    $serverMessage = 'Le date selezionate non sono disponibili. Scegli altre date.';
    include 'dettaglio_barca.php';
    exit;
}

// Calcolo del prezzo
$prezzoBase = (float) ($productDetail['Prezzo_Base'] ?? 0);

// Carico gli extra (necessario qui perché il flusso POST non passa attraverso la parte GET sopra)
$extra = $db->getProdottoExtra($productDetail['IDProdotto']);
if ($extra === false) $extra = [];

// Calcolo giorni di noleggio
$dateStart = new DateTime($startDate);
$dateEnd = new DateTime($endDate);
$giorni = $dateStart->diff($dateEnd)->days + 1; // +1 perché include entrambi i giorni

$prezzoBaseGiorni = $prezzoBase * $giorni;

// Calcolo extra - cerco per IDExtra
$prezzoExtra = 0;
foreach ($extrasSelected as $extraId) {
    // Trova l'extra nell'array tramite IDExtra
    foreach ($extra as $extraItem) {
        if (isset($extraItem['IDExtra']) && (int)$extraItem['IDExtra'] === (int)$extraId) {
            $prezzoExtra += (float) ($extraItem['Prezzo_Extra'] ?? 0);
            break;
        }
    }
}

// Calcolo +10% skipper (solo sul prezzo base)
$prezzoSkipper = 0;
if ($skipperChecked) {
    $prezzoSkipper = $prezzoBaseGiorni * 0.10;
}

$prezzoTotale = $prezzoBaseGiorni + $prezzoExtra + $prezzoSkipper;

// Determino metodo e stato in base alla selezione (validazione robusta)
$metodoPagamento = 'Contanti';
$statoPrenotazione = 'In Attesa';

if ($selectPayment === 'cc') {
    $metodoPagamento = 'Carta di Credito';
} elseif ($selectPayment === 'bb') {
    $metodoPagamento = 'Bonifico';
} elseif ($selectPayment === 'contanti') {
    $metodoPagamento = 'Contanti';
} else {
    // Valore non valido, usa default
    $metodoPagamento = 'Contanti';
}

// Se il pagamento è con carta, NON inserisco subito la prenotazione
// La inserirò solo dopo la validazione della carta in pagamento.php
if ($selectPayment === 'cc') {
    // Salvo i dati in sessione senza creare la prenotazione
    $_SESSION['prenotazione_temp'] = [
        'id_prenotazione' => null, // Sarà creata dopo il pagamento
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
        'stato' => 'Confermata', // Sarà confermata dopo il pagamento
    ];
    
    // Vai alla pagina di pagamento
    header('Location: pagamento.php');
    exit;
}

// Per altri metodi di pagamento, inserisco la prenotazione normalmente con stato "In Attesa"
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
    include 'dettaglio_barca.php';
    exit;
}

// Salvo i dati della prenotazione in sessione per usarli nelle pagine successive
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
];

// Vai direttamente alla conferma
header('Location: conferma_prenotazione.php');
exit;
?>
