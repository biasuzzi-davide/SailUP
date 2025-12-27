<?php
// conferma_prenotazione.php - Pagina di conferma dopo la prenotazione

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/session/session.php';

// Verifica che l'utente sia loggato
requireLogin();

$db = new DBConnection();

// Verifica che ci sia una prenotazione completata
if (!isset($_SESSION['prenotazione_temp'])) {
    header('Location: index.php');
    exit;
}

$prenotazione = $_SESSION['prenotazione_temp'];

// Determino i testi in base al metodo di pagamento e stato
$metodoPagamento = $prenotazione['metodo_pagamento'] ?? 'Contanti';
$statoPrenotazione = $prenotazione['stato'] ?? 'In Attesa';
$tipoProdotto = $prenotazione['tipo_prodotto'] ?? 'Noleggio';

// Titolo e sottotitolo
$titoloConferma = '';
$sottotitoloConferma = '';
$istruzioniPagamento = '';

if ($metodoPagamento === 'Carta di Credito' && $statoPrenotazione === 'Confermata') {
    $titoloConferma = 'Pagamento Confermato!';
    $sottotitoloConferma = 'La tua prenotazione è stata confermata con successo.';
} elseif ($metodoPagamento === 'Contanti') {
    $titoloConferma = 'Prenotazione Registrata';
    $sottotitoloConferma = 'La tua prenotazione è in attesa di conferma.';
    $istruzioniPagamento = '<div class="info-box"><p><strong>Modalità di pagamento: Contanti</strong></p><p>Il pagamento dovrà essere effettuato in contanti presso la nostra sede il giorno stesso dell\'inizio della prenotazione. Ti aspettiamo!</p></div>';
} elseif ($metodoPagamento === 'Bonifico') {
    $titoloConferma = 'Prenotazione Registrata';
    $sottotitoloConferma = 'La tua prenotazione è in attesa di conferma del pagamento.';
    $istruzioniPagamento = '<div class="info-box"><p><strong>Modalità di pagamento: Bonifico Bancario</strong></p><p>Effettua il bonifico bancario al seguente <abbr title="International Bank Account Number" lang="en">IBAN</abbr>:</p><p class="iban-code"><strong>IT60 X054 2811 1010 0000 0123 456</strong></p><p>Causale: <em>Prenotazione #' . htmlspecialchars((string) $prenotazione['id_prenotazione'], ENT_QUOTES) . '</em></p><p><strong>Importante:</strong> Il pagamento deve essere accreditato almeno 24 ore prima dell\'inizio della prenotazione.</p></div>';
}

// Label prodotto
$tipoProdottoLabel = $tipoProdotto === 'Experience' ? 'Esperienza' : 'Imbarcazione';

// Formattazione date
$dataInizioIso = '';
$dataInizioFormattata = '';
$dataFineRow = '';

if (isset($prenotazione['data_inizio']) && $prenotazione['data_inizio'] !== null) {
    $dataInizioIso = $prenotazione['data_inizio'];
    $dateObj = DateTime::createFromFormat('Y-m-d', $prenotazione['data_inizio']);
    if ($dateObj) {
        // Formattazione manuale per evitare problemi con locale
        $mesi = ['', 'Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];
        $giorno = $dateObj->format('d');
        $mese = $mesi[(int)$dateObj->format('m')];
        $anno = $dateObj->format('Y');
        $dataInizioFormattata = $giorno . ' ' . $mese . ' ' . $anno;
    } else {
        $dataInizioFormattata = $prenotazione['data_inizio'];
    }
}

// Label data
$dataLabel = $tipoProdotto === 'Experience' ? 'Data <span lang="en">Tour</span>' : '<span lang="en">Check-in</span>';

// Data fine (solo per noleggi)
if ($tipoProdotto === 'Noleggio' && isset($prenotazione['data_fine']) && $prenotazione['data_fine'] !== null) {
    $dataFineIso = $prenotazione['data_fine'];
    $dateFineObj = DateTime::createFromFormat('Y-m-d', $prenotazione['data_fine']);
    if ($dateFineObj) {
        $mesi = ['', 'Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];
        $giorno = $dateFineObj->format('d');
        $mese = $mesi[(int)$dateFineObj->format('m')];
        $anno = $dateFineObj->format('Y');
        $dataFineFormattata = $giorno . ' ' . $mese . ' ' . $anno;
    } else {
        $dataFineFormattata = $prenotazione['data_fine'];
    }
    
    $dataFineRow = '<div>
            <dt><span lang="en">Check-out</span>:</dt>
            <dd><time datetime="' . htmlspecialchars($dataFineIso, ENT_QUOTES) . '">' . htmlspecialchars($dataFineFormattata, ENT_QUOTES) . '</time></dd>
        </div>';
}

// Extra servizio (skipper per noleggio, pickup per esperienza)
$extraServizioRow = '';
if ($tipoProdotto === 'Noleggio' && isset($prenotazione['skipper']) && $prenotazione['skipper']) {
    $extraServizioRow = '<div>
            <dt><span lang="en">Skipper</span>:</dt>
            <dd>Sì <span class="text-accent">(+10%)</span></dd>
        </div>';
} elseif ($tipoProdotto === 'Experience' && isset($prenotazione['pickup']) && $prenotazione['pickup']) {
    $extraServizioRow = '<div>
            <dt>Prelievo <span lang="en">Hotel</span>:</dt>
            <dd>Sì <span class="text-accent">(+10%)</span></dd>
        </div>';
}

// Prezzo
$prezzoTotale = number_format((float) ($prenotazione['prezzo_totale'] ?? 0), 0, ',', '.');

// Label "Pagato" o "Da Pagare"
$pagatoODaPagare = ($metodoPagamento === 'Carta di Credito' && $statoPrenotazione === 'Confermata') ? 'Pagato' : 'Da Pagare';

// Costruisco la pagina
$html = buildPage('../pages/conferma_prenotazione.html', $_SERVER['PHP_SELF']);

$placeholders = [
    '[TITOLO_CONFERMA]' => htmlspecialchars($titoloConferma, ENT_QUOTES),
    '[SOTTOTITOLO_CONFERMA]' => htmlspecialchars($sottotitoloConferma, ENT_QUOTES),
    '[ISTRUZIONI_PAGAMENTO]' => $istruzioniPagamento,
    '[TIPO_PRODOTTO_LABEL]' => $tipoProdottoLabel,
    '[NOME_PRODOTTO]' => htmlspecialchars($prenotazione['nome_prodotto'] ?? '', ENT_QUOTES),
    '[DATA_LABEL]' => $dataLabel,
    '[DATA_INIZIO_ISO]' => htmlspecialchars($dataInizioIso, ENT_QUOTES),
    '[DATA_INIZIO_FORMATTATA]' => htmlspecialchars($dataInizioFormattata, ENT_QUOTES),
    '[DATA_FINE_ROW]' => $dataFineRow,
    '[EXTRA_SERVIZIO_ROW]' => $extraServizioRow,
    '[PREZZO_TOTALE]' => htmlspecialchars($prezzoTotale, ENT_QUOTES),
    '[PAGATO_O_DA_PAGARE]' => htmlspecialchars($pagatoODaPagare, ENT_QUOTES),
];

$html = str_replace(array_keys($placeholders), array_values($placeholders), $html);

echo $html;
?>
