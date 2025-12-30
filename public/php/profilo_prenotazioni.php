<?php

require_once '../../includes/session/session.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/helpers.php';

requireLogin();

$db = new DBConnection();
$userId = (int) ($_SESSION['user']['IDUtente'] ?? 0);
$feedbackState = 'hidden';
$feedbackMsg = '';
$csrfToken = htmlspecialchars(getCsrfToken());

//annullamento prenotazione
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? null;
    $action = $_POST['action'] ?? '';
    $bookingId = isset($_POST['booking_id']) ? (int) $_POST['booking_id'] : 0;

    if (!verifyCsrfToken($csrf)) {
        $feedbackState = 'error';
        $feedbackMsg = 'Sessione scaduta, ricarica la pagina.';
    } elseif ($action === 'cancel_booking' && $bookingId > 0) {
        $booking = $db->getPrenotazioneById($bookingId);
        if (!is_array($booking) || (int)($booking['IDUtente'] ?? 0) !== $userId) {
            $feedbackState = 'error';
            $feedbackMsg = 'Prenotazione non trovata.';
        } elseif (($booking['Stato_Prenotazione'] ?? '') === 'Cancellata') {
            $feedbackState = 'success';
            $feedbackMsg = 'Prenotazione già annullata.';
        } else {
            $ok = $db->updatePrenotazioneStato($bookingId, 'Cancellata', $booking['Note_Addizionali'] ?? null);
            if ($ok) {
                $feedbackState = 'success';
                $feedbackMsg = 'Prenotazione annullata correttamente.';
            } else {
                $feedbackState = 'error';
                $feedbackMsg = 'Impossibile annullare la prenotazione.';
            }
        }
    }
}

$prenotazioni = $db->getPrenotazioniUtente($userId);

$rowsHtml = '';
if (is_array($prenotazioni) && !empty($prenotazioni)) {
    foreach ($prenotazioni as $p) {
        $stato = $p['Stato_Prenotazione'] ?? '';
        $statusAttr = 'active';
        if ($stato === 'Cancellata') $statusAttr = 'cancelled';
        if ($stato === 'Completata' || $stato === 'Conclusa') $statusAttr = 'completed';

        $dataInizio = !empty($p['Data_Ora_Inizio']) ? date('d/m/Y', strtotime($p['Data_Ora_Inizio'])) : '—';
        $dataFine = !empty($p['Data_Ora_Fine']) ? date('d/m/Y', strtotime($p['Data_Ora_Fine'])) : '—';
        $prezzo = number_format((float)($p['Prezzo_Totale'] ?? 0), 2, ',', '.');

        $isCancellable = in_array($statusAttr, ['active', 'pending'], true);

        $rowsHtml .= '<tr class="booking-row" data-status="' . htmlspecialchars($statusAttr) . '">'
            . '<td>' . htmlspecialchars($p['IDPrenotazione']) . '</td>'
            . '<td>' . htmlspecialchars($p['IDProdotto']) . '</td>'
            . '<td>' . htmlspecialchars($dataInizio . ' — ' . $dataFine) . '</td>'
            . '<td>€ ' . htmlspecialchars($prezzo) . '</td>'
            . '<td>' . htmlspecialchars($stato) . '</td>'
            . '<td>'
            . ($isCancellable
                ? '<form method="post" class="inline-form" onsubmit="return confirm(\'Annullare questa prenotazione?\');">'
                    . '<input type="hidden" name="csrf_token" value="' . $csrfToken . '">'
                    . '<input type="hidden" name="action" value="cancel_booking">'
                    . '<input type="hidden" name="booking_id" value="' . htmlspecialchars($p['IDPrenotazione']) . '">'
                    . '<button type="submit" class="btn-text">Annulla</button>'
                . '</form>'
                : '—'
            )
            . '</td>'
            . '</tr>';
    }
} else {
    // Riga segnaposto senza classi usate per i contatori JS, così resta a 0
    $rowsHtml = '<tr><td colspan="6">Nessuna prenotazione trovata.</td></tr>';
}

$html = buildPage('../pages/profilo_prenotazioni.html', $_SERVER['PHP_SELF']);

// Keywords per SEO
$keywords = '<meta name="keywords" content="prenotazioni SailUP, gestione prenotazioni, storico prenotazioni, annulla prenotazione">';

$placeholders = [
    '[BOOKING_ROWS]' => $rowsHtml,
    '[USER_NOME]' => htmlspecialchars($_SESSION['user']['Nome'] ?? ''),
    '[USER_COGNOME]' => htmlspecialchars($_SESSION['user']['Cognome'] ?? ''),
    '[USER_EMAIL]' => htmlspecialchars($_SESSION['user']['Email'] ?? ''),
    '[BOOKINGS_SERVER_STATE]' => $feedbackState,
    '[BOOKINGS_SERVER_MESSAGES]' => htmlspecialchars($feedbackMsg),
    '[CSRF_TOKEN]' => $csrfToken,
    '[KEYWORDS]' => $keywords,
];
$html = str_replace(array_keys($placeholders), array_values($placeholders), $html);

echo $html;
?>
