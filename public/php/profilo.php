<?php

require_once '../../includes/session/session.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

requireLogin();

$user = $_SESSION['user'] ?? [];
$db = new DBConnection();

$addrPlaceholders = [
    '[ADDR_VIA]' => '',
    '[ADDR_CIVICO]' => '',
    '[ADDR_CAP]' => '',
    '[ADDR_CITTA]' => '',
    '[ADDR_PROVINCIA]' => '',
    '[ADDR_PROVINCIA_FULL]' => '',
    '[ADDR_PAESE]' => '',
];

if (!empty($user['IDIndirizzo'])) {
    $addr = $db->getIndirizzoById((int)$user['IDIndirizzo']);
    if (is_array($addr)) {
        $addrPlaceholders = [
            '[ADDR_VIA]' => htmlspecialchars($addr['Via'] ?? ''),
            '[ADDR_CIVICO]' => htmlspecialchars($addr['N_Civico'] ?? ''),
            '[ADDR_CAP]' => htmlspecialchars($addr['CAP'] ?? ''),
            '[ADDR_CITTA]' => htmlspecialchars($addr['Citta'] ?? ''),
            '[ADDR_PROVINCIA]' => htmlspecialchars($addr['Provincia'] ?? ''),
            '[ADDR_PROVINCIA_FULL]' => htmlspecialchars($addr['Provincia'] ?? ''),
            '[ADDR_PAESE]' => htmlspecialchars($addr['Paese'] ?? ''),
        ];
    }
}

$placeholders = [
    '[USER_NOME]' => htmlspecialchars($user['Nome'] ?? ''),
    '[USER_COGNOME]' => htmlspecialchars($user['Cognome'] ?? ''),
    '[USER_EMAIL]' => htmlspecialchars($user['Email'] ?? ''),
    '[USER_CF]' => htmlspecialchars($user['CF'] ?? ''),
    '[USER_PATENTE]' => htmlspecialchars($user['Numero_Patente_Nautica'] ?? '—'),
];

$statTotPren = '0';
$statAttive = '0';
$pren = $db->getPrenotazioniUtente((int)($user['IDUtente'] ?? 0));
if (is_array($pren)) {
    $statTotPren = (string) count($pren);
    $statAttive = (string) count(array_filter($pren, fn($p) => ($p['Stato_Prenotazione'] ?? '') !== 'Cancellata'));
}
$placeholders['[STAT_TOT_PREN]'] = $statTotPren;
$placeholders['[STAT_ATTIVE]'] = $statAttive;

$html = buildPage('../pages/profilo.html', $_SERVER['PHP_SELF']);
$html = str_replace(array_keys($placeholders + $addrPlaceholders), array_values($placeholders + $addrPlaceholders), $html);

echo $html;
?>
