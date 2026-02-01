<?php

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/session/session.php';

requireAdmin();

$db = new DBConnection();
$feedback = '';
$feedbackClass = '';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$booking = $id > 0 ? $db->getPrenotazioneById($id) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? null;
    $status = $_POST['status'] ?? 'In Attesa';
    $note = trim($_POST['note'] ?? '');

    if (!verifyCsrfToken($csrf)) {
        $feedbackClass = 'alert alert-error';
        $feedback = 'Sessione scaduta, ricarica la pagina.';
    } elseif ($booking && $id > 0) {
        $ok = $db->updatePrenotazioneStato($id, $status, $note === '' ? null : $note);
        if ($ok) {
            $feedbackClass = 'alert alert-success';
            $feedback = 'Prenotazione aggiornata.';
            $booking = $db->getPrenotazioneById($id);
        } else {
            $feedbackClass = 'alert alert-error';
            $feedback = 'Impossibile aggiornare la prenotazione.';
        }
    }
}

if ($booking === null || $booking === false) {
    header('Location: admin_prenotazioni.php');
    exit;
}

$imgUrl = resolveImageUrl($booking['URL_Media'] ?? null);
$imgAlt = $booking['Testo_Alternativo'] ?? '';
$stato = $booking['Stato_Prenotazione'] ?? 'In Attesa';
$badgeClass = 'pending';
if ($stato === 'Confermata') $badgeClass = 'completed';
if ($stato === 'Cancellata') $badgeClass = 'cancelled';

$dataInizio = !empty($booking['Data_Ora_Inizio']) ? date('d M Y', strtotime($booking['Data_Ora_Inizio'])) : '—';
$dataFine = !empty($booking['Data_Ora_Fine']) ? date('d M Y', strtotime($booking['Data_Ora_Fine'])) : '—';
$creataIl = !empty($booking['Data_Creazione']) ? date('d M Y', strtotime($booking['Data_Creazione'])) : '—';

$dataInizioISO = !empty($booking['Data_Ora_Inizio']) ? date('c', strtotime($booking['Data_Ora_Inizio'])) : '';
$dataFineISO = !empty($booking['Data_Ora_Fine']) ? date('c', strtotime($booking['Data_Ora_Fine'])) : '';

$prodTitle = trim(($booking['Nome_Prodotto'] ?? '') . ' ' . (($booking['Tipologia_Prodotto'] ?? '') !== '' ? '(' . $booking['Tipologia_Prodotto'] . ')' : ''));
$cliente = trim(($booking['Utente_Nome'] ?? '') . ' ' . ($booking['Utente_Cognome'] ?? ''));
$skipper = !empty($booking['Skipper_Richiesto']) ? 'Richiesto' : 'Non richiesto';
$noteVal = $booking['Note_Addizionali'] ?? '';

$ospitiNum = $booking['Ospiti'] ?? '';
$totaleNum = number_format((float)($booking['Prezzo_Totale'] ?? 0), 2, '.', '');

$html = buildPage('../pages/dettaglio_prenotazione.html', $_SERVER['PHP_SELF']);

$feedbackBlock = '';
if ($feedback !== '') {
    $feedbackBlock = '<div class="' . $feedbackClass . '" role="status" aria-live="polite">' . htmlspecialchars($feedback) . '</div>';
}

$html = str_replace(
    [
        '[BOOKING_ID]',
        '[BOOKING_CODE]',
        '[BOOKING_CREATED_AT]',
        '[BOOKING_STATUS_BADGE]',
        '[BOOKING_IMG_URL]',
        '[BOOKING_IMG_ALT]',
        '[BOOKING_PRODUCT]',
        '[BOOKING_TIPO]',
        '[BOOKING_START]',
        '[BOOKING_END]',
        '[BOOKING_START_ISO]',
        '[BOOKING_END_ISO]',
        '[BOOKING_GUESTS]',
        '[BOOKING_GUESTS_NUM]',
        '[BOOKING_SKIPPER]',
        '[BOOKING_TOTAL]',
        '[BOOKING_TOTAL_NUM]',
        '[BOOKING_PAYMENT]',
        '[BOOKING_CLIENTE]',
        '[BOOKING_EMAIL]',
        '[BOOKING_FEEDBACK]',
        '[CSRF_TOKEN]',
        '[SEL_PENDING]',
        '[SEL_CONF]',
        '[SEL_CANC]',
        '[BOOKING_NOTE]',
    ],
    [
        htmlspecialchars((string)$booking['IDPrenotazione']),
        htmlspecialchars('SU-' . str_pad((string)$booking['IDPrenotazione'], 6, '0', STR_PAD_LEFT)),
        htmlspecialchars($creataIl),
        '<span class="status-badge ' . $badgeClass . '">' . htmlspecialchars($stato) . '</span>',
        htmlspecialchars($imgUrl),
        htmlspecialchars($imgAlt),
        htmlspecialchars($prodTitle !== '' ? $prodTitle : ($booking['IDProdotto'] ?? '')),
        htmlspecialchars($booking['Tipo_Prodotto'] ?? ''),
        htmlspecialchars($dataInizio),
        htmlspecialchars($dataFine),
        htmlspecialchars($dataInizioISO),
        htmlspecialchars($dataFineISO),
        htmlspecialchars($booking['Ospiti'] ?? '—'),
        htmlspecialchars($ospitiNum),
        htmlspecialchars($skipper),
        htmlspecialchars(number_format((float)($booking['Prezzo_Totale'] ?? 0), 2, ',', '.')),
        htmlspecialchars($totaleNum),
        htmlspecialchars($booking['Metodo_Pagamento'] ?? '—'),
        htmlspecialchars($cliente !== '' ? $cliente : '—'),
        htmlspecialchars($booking['Utente_Email'] ?? '—'),
        $feedbackBlock,
        htmlspecialchars(getCsrfToken()),
        $stato === 'In Attesa' ? 'selected' : '',
        $stato === 'Confermata' ? 'selected' : '',
        $stato === 'Cancellata' ? 'selected' : '',
        htmlspecialchars($noteVal),
    ],
    $html
);

echo $html;
?>
