<?php

require_once '../../includes/session/session.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/helpers.php';

requireLogin();

$db = new DBConnection();
$userId = (int) ($_SESSION['user']['IDUtente'] ?? 0);
$prenotazioni = $db->getPrenotazioniUtente($userId);

$rowsHtml = '';
if (is_array($prenotazioni) && !empty($prenotazioni)) {
    foreach ($prenotazioni as $p) {
        $rowsHtml .= '<tr class="booking-row" data-status="active">'
            . '<td>' . htmlspecialchars($p['IDPrenotazione']) . '</td>'
            . '<td>' . htmlspecialchars($p['IDProdotto']) . '</td>'
            . '<td>' . htmlspecialchars($p['Data_Ora_Inizio']) . '</td>'
            . '<td>' . htmlspecialchars($p['Data_Ora_Fine']) . '</td>'
            . '<td>' . htmlspecialchars($p['Stato_Prenotazione']) . '</td>'
            . '</tr>';
    }
} else {
    $rowsHtml = '<tr class="booking-row"><td colspan="5">Nessuna prenotazione trovata.</td></tr>';
}

$html = buildPage('../pages/profilo_prenotazioni.html', $_SERVER['PHP_SELF']);
$placeholders = [
    '[BOOKING_ROWS]' => $rowsHtml,
    '[USER_NOME]' => htmlspecialchars($_SESSION['user']['Nome'] ?? ''),
    '[USER_COGNOME]' => htmlspecialchars($_SESSION['user']['Cognome'] ?? ''),
    '[USER_EMAIL]' => htmlspecialchars($_SESSION['user']['Email'] ?? ''),
];
$html = str_replace(array_keys($placeholders), array_values($placeholders), $html);

echo $html;
?>
