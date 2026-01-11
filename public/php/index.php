<?php

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

$html = buildPage('../pages/index.html', $_SERVER['PHP_SELF']);

// Recupera dati dal DB
$db = new DBConnection();
$fleetProdotti = $db->getProdottiWithMedia('Noleggio', 3);
$experienceProdotti = $db->getProdottiWithMedia('Experience', 2);

// Genera HTML per le card delle barche
$fleetCards = '';
if ($fleetProdotti && is_array($fleetProdotti)) {
    foreach ($fleetProdotti as $prodotto) {
        $fleetCards .= buildSimpleProductCard($prodotto, 'noleggio');
    }
} else {
    $fleetCards = '<p>Nessuna barca disponibile al momento.</p>';
}

// Genera HTML per le card delle esperienze
$experienceCards = '';
if ($experienceProdotti && is_array($experienceProdotti)) {
    foreach ($experienceProdotti as $prodotto) {
        $experienceCards .= buildSimpleProductCard($prodotto, 'experience');
    }
} else {
    $experienceCards = '<p>Nessuna esperienza disponibile al momento.</p>';
}

// Sostituisci i placeholder nell'HTML
$html = str_replace('[FLEET_CARDS]', $fleetCards, $html);
$html = str_replace('[EXPERIENCE_CARDS]', $experienceCards, $html);

echo $html;
?>