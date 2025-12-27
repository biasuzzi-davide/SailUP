<?php
// prenota_esperienza.php - Gestisce la prenotazione di un'esperienza

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/session/session.php';

$db = new DBConnection();
$experienceId = trim((string) filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

// Se arrivo con GET (visualizzazione form), mostro la pagina dettaglio
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    include 'dettaglio_esperienza.php';
    exit;
}

// Gestione POST - elaborazione prenotazione

// Prendo l'ID esperienza dal form se non presente in GET
if (empty($experienceId)) {
    $experienceId = trim((string) filter_input(INPUT_POST, 'experience_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
}

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

// Variabili per i messaggi
$serverState = '';
$serverMessage = '';

// Controllo se l'utente è loggato
if (!isLogged()) {
    $serverState = 'visible';
    $serverMessage = 'Per prenotare devi effettuare il <a href="login.php">login</a> o la <a href="registrazione.php">registrazione</a>.';
    
    // Ricarico la pagina con il messaggio di errore
    $html = buildPage('../pages/dettaglio_esperienza.html', $_SERVER['PHP_SELF']);
    
    // Recupero tutti i dati per popolare la pagina
    $experienceName = $experience['Nome_Prodotto'] ?? 'Esperienza SailUP';
    $experienceTagline = $experience['Descrizione_Breve'] ?? 'Dettagli in arrivo...';
    $heroImage = resolveImageUrl($experience['URL_Media'] ?? null);
    $heroAlt = 'Vista di ' . $experienceName;
    $durationRaw = $experience['Durata_Ore'];
    $duration = ($durationRaw !== null && $durationRaw !== '') ? ((int) $durationRaw) . ' h' : '—';
    $participants = isset($experience['Posti_Totali']) ? $experience['Posti_Totali'] . ' persone' : '—';
    $access = isset($experience['Accessibile_Disabili']) && filter_var($experience['Accessibile_Disabili'], FILTER_VALIDATE_BOOLEAN) ? 'Accessibile' : 'Limitato';
    $descriptionBlock = buildParagraphsFromText($experience['Descrizione'] ?? '', 'Descrizione non disponibile per questa esperienza.');
    $price = formatPriceValue($experience['Prezzo_Base'] ?? null);
    
    $inclusi = $db->getProdottoInclusi($experience['IDProdotto']);
    if ($inclusi === false) $inclusi = [];
    
    $extra = $db->getProdottoExtra($experience['IDProdotto']);
    if ($extra === false) $extra = [];
    
    $includedHtml = buildItemsList(is_array($inclusi) ? $inclusi : [], 'Nome_Incluso', 'Non ci sono inclusi al momento.');
    $extraHtml = buildItemsList(
        is_array($extra) ? $extra : [],
        'Nome_Extra',
        'Non ci sono extra al momento.',
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
        '[EXTRA_CHECKBOXES]' => $extraCheckboxes,
        '[SERVER_STATE]' => $serverState,
        '[SERVER_MESSAGES]' => $serverMessage,
    ];
    
    $html = str_replace(array_keys($placeholders), array_values($placeholders), $html);
    echo $html;
    exit;
}

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
    include 'dettaglio_esperienza.php';
    exit;
}

// Controllo che la data non sia nel passato
$oggi = date('Y-m-d');
if ($bookingDate < $oggi) {
    $serverState = 'visible';
    $serverMessage = 'Non è possibile prenotare date nel passato.';
    include 'dettaglio_esperienza.php';
    exit;
}

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
    include 'dettaglio_esperienza.php';
    exit;
}

// Calcolo del prezzo
$prezzoBase = (float) ($experience['Prezzo_Base'] ?? 0);
$extra = $db->getProdottoExtra($experience['IDProdotto']);
if ($extra === false) $extra = [];

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

// Calcolo +10% pickup (solo sul prezzo base)
$prezzoPickup = 0;
if ($pickupChecked) {
    $prezzoPickup = $prezzoBase * 0.10;
}

$prezzoTotale = $prezzoBase + $prezzoExtra + $prezzoPickup;

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

// Inserisco la prenotazione (skipper_richiesto = pickup per le esperienze)
$idPrenotazione = $db->insertPrenotazione(
    $idUtente,
    $experienceId,
    $dataInizio,
    $dataFine,
    $pickupChecked, // Uso lo stesso campo per pickup
    $prezzoTotale,
    $metodoPagamento,
    $statoPrenotazione,
    null
);

if (!$idPrenotazione) {
    $serverState = 'visible';
    $serverMessage = 'Errore durante la creazione della prenotazione. Riprova.';
    include 'dettaglio_esperienza.php';
    exit;
}

// Salvo i dati della prenotazione in sessione per usarli nelle pagine successive
$_SESSION['prenotazione_temp'] = [
    'id_prenotazione' => $idPrenotazione,
    'id_prodotto' => $experienceId,
    'nome_prodotto' => $experience['Nome_Prodotto'],
    'tipo_prodotto' => 'Experience',
    'data_inizio' => $bookingDate,
    'data_fine' => null, // Per esperienze non mostro fine
    'pickup' => $pickupChecked,
    'prezzo_totale' => $prezzoTotale,
    'metodo_pagamento' => $metodoPagamento,
    'stato' => $statoPrenotazione,
];

// Redirect in base al metodo di pagamento
if ($selectPayment === 'cc') {
    // Vai alla pagina di pagamento
    header('Location: pagamento.php');
    exit;
} else {
    // Vai direttamente alla conferma
    header('Location: conferma_prenotazione.php');
    exit;
}
?>
