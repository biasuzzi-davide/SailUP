<?php

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/session/session.php';

// Verifica che l'utente sia loggato
requireLogin();

$db = new DBConnection();

// Verifica che ci sia una prenotazione in corso
if (!isset($_SESSION['prenotazione_temp'])) {
    header('Location: index.php');
    exit;
}

$prenotazione = $_SESSION['prenotazione_temp'];

// Gestione POST - Elaborazione pagamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupero i dati del form (validazione base, in un sistema reale si interfaccerebbero con un gateway)
    $cardHolder = trim((string) filter_input(INPUT_POST, 'card_holder', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $cardNumber = trim((string) filter_input(INPUT_POST, 'card_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $expiryDate = trim((string) filter_input(INPUT_POST, 'expiry_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $cvv = trim((string) filter_input(INPUT_POST, 'cvv', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $terms = isset($_POST['terms']);
    
    // Validazione base
    if (empty($cardHolder) || empty($cardNumber) || empty($expiryDate) || empty($cvv) || !$terms) {
        // Se mancano dati, ricarica la pagina con errore (in realtà questo viene gestito da JS)
        // Per semplicità torniamo alla pagina
        $html = buildPage('../pages/pagamento.html', $_SERVER['PHP_SELF']);
        echo $html;
        exit;
    }
    
    // Simulo un pagamento riuscito
    // In un sistema reale qui si chiamerebbe il gateway di pagamento
    
    // Creo la prenotazione con stato "Confermata" (solo per pagamenti con carta)
    $idPrenotazione = $db->insertPrenotazione(
        $prenotazione['id_utente'],
        $prenotazione['id_prodotto'],
        $prenotazione['data_inizio'],
        $prenotazione['data_fine'],
        $prenotazione['skipper'] ?? $prenotazione['pickup'] ?? false,
        $prenotazione['prezzo_totale'],
        $prenotazione['metodo_pagamento'],
        'Confermata', // Stato confermato direttamente
        null
    );
    
    if ($idPrenotazione) {
        // Aggiorno i dati in sessione con l'ID della prenotazione creata
        $_SESSION['prenotazione_temp']['id_prenotazione'] = $idPrenotazione;
        $_SESSION['prenotazione_temp']['stato'] = 'Confermata';
        
        // Redirect alla conferma
        header('Location: conferma_prenotazione.php');
        exit;
    } else {
        // Errore nella creazione della prenotazione
        $html = buildPage('../pages/pagamento.html', $_SERVER['PHP_SELF']);
        echo $html;
        exit;
    }
}

// Visualizzazione pagina (GET)
$html = buildPage('../pages/pagamento.html', $_SERVER['PHP_SELF']);

// Creo il link al prodotto
$linkProdotto = '';
$nomeProdotto = $prenotazione['nome_prodotto'] ?? 'Prodotto';

if ($prenotazione['tipo_prodotto'] === 'Noleggio') {
    $linkProdotto = 'dettaglio_barca.php?id=' . urlencode($prenotazione['id_prodotto']);
} else {
    $linkProdotto = 'dettaglio_esperienza.php?id=' . urlencode($prenotazione['id_prodotto']);
}

// Formatto il prezzo totale
$prezzoTotaleFormattato = number_format((float) ($prenotazione['prezzo_totale'] ?? 0), 2, ',', '.');

$placeholders = [
    '[LINK PRODOTTO]' => htmlspecialchars($linkProdotto, ENT_QUOTES),
    '[NOME-PRODOTTO]' => htmlspecialchars($nomeProdotto, ENT_QUOTES),
    '[LINK_INDIETRO]' => htmlspecialchars($linkProdotto, ENT_QUOTES),
    '[PREZZO_TOTALE]' => htmlspecialchars($prezzoTotaleFormattato, ENT_QUOTES),
];

// Keywords per SEO
$keywords = '<meta name="keywords" content="pagamento, prenotazione, checkout, sicuro, carta, credito, bonifico, contanti, SailUP">';
$placeholders['[KEYWORDS]'] = $keywords;

$html = str_replace(array_keys($placeholders), array_values($placeholders), $html);

echo $html;
?>