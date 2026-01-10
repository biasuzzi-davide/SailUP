<?php

require_once '../../includes/session/session.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/helpers.php';

requireLogin();

$db = new DBConnection();
$userId = (int) ($_SESSION['user']['IDUtente'] ?? 0);
$profileImageUrl = getProfileImageUrl($_SESSION['user'] ?? []);
$feedbackState = 'hidden';
$feedbackMsg = '';
$csrfToken = getCsrfToken();
$csrfTokenEscaped = htmlspecialchars($csrfToken);

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

$rowsHtml = buildProfileBookingRows(is_array($prenotazioni) ? $prenotazioni : [], $csrfToken);

$html = buildPage('../pages/profilo_prenotazioni.html', $_SERVER['PHP_SELF']);
$html = str_replace('[ADMIN_MENU_ITEM]', buildAdminMenuItem(), $html);
$html = str_replace('[ADMIN_BREADCRUMB]', buildAdminBreadcrumb(), $html);

// Keywords per SEO
$keywords = '<meta name="keywords" content="prenotazioni, gestione, storico, annulla, modifica, utente, SailUP">';

$placeholders = [
    '[BOOKING_ROWS]' => $rowsHtml,
    '[USER_NOME]' => htmlspecialchars($_SESSION['user']['Nome'] ?? ''),
    '[USER_COGNOME]' => htmlspecialchars($_SESSION['user']['Cognome'] ?? ''),
    '[USER_EMAIL]' => htmlspecialchars($_SESSION['user']['Email'] ?? ''),
    '[PROFILE_IMAGE_URL]' => htmlspecialchars($profileImageUrl),
    '[BOOKINGS_SERVER_STATE]' => $feedbackState,
    '[BOOKINGS_SERVER_MESSAGES]' => htmlspecialchars($feedbackMsg),
    '[CSRF_TOKEN]' => $csrfTokenEscaped,
    '[KEYWORDS]' => $keywords,
];
$html = str_replace(array_keys($placeholders), array_values($placeholders), $html);

echo $html;
?>
