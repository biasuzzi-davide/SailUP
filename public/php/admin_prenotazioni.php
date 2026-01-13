<?php

require_once '../../includes/session/session.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/helpers.php';

requireAdmin();

$db = new DBConnection();
$csrfToken = getCsrfToken();
$feedback = '';
$feedbackClass = '';
$search = trim($_GET['q'] ?? '');
$filterStato = $_GET['stato'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? null;
    $action = $_POST['action'] ?? '';
    $idPren = isset($_POST['id_prenotazione']) ? (int)$_POST['id_prenotazione'] : 0;
    if (!verifyCsrfToken($csrf)) {
        $feedbackClass = 'alert alert-error';
        $feedback = 'Sessione scaduta, ricarica la pagina.';
    } elseif ($idPren <= 0) {
        $feedbackClass = 'alert alert-error';
        $feedback = 'Prenotazione non valida.';
    } else {
        $booking = $db->getPrenotazioneById($idPren);
        if ($booking === null) {
            $feedbackClass = 'alert alert-error';
            $feedback = 'Prenotazione non trovata.';
        } elseif ($booking === false) {
            $feedbackClass = 'alert alert-error';
            $feedback = 'Errore durante il recupero della prenotazione.';
        } else {
            $newState = null;
            if ($action === 'confirm') $newState = 'Confermata';
            if ($action === 'cancel') $newState = 'Cancellata';

            if ($newState === null) {
                $feedbackClass = 'alert alert-error';
                $feedback = 'Azione non valida.';
            } else {
                $canConfirm = true;
                if ($newState === 'Confermata') {
                    $canConfirm = $db->checkDateAvailability(
                        (string)$booking['IDProdotto'],
                        (string)$booking['Data_Ora_Inizio'],
                        (string)$booking['Data_Ora_Fine'],
                        (int)$booking['IDPrenotazione']
                    );
                }

                if (!$canConfirm) {
                    $feedbackClass = 'alert alert-error';
                    $feedback = 'La barca risulta già occupata per quelle date.';
                } else {
                    $ok = $db->updatePrenotazioneStato($idPren, $newState);
                    if ($ok) {
                        $feedbackClass = 'alert alert-success';
                        $feedback = 'Stato prenotazione aggiornato.';
                    } else {
                        $feedbackClass = 'alert alert-error';
                        $feedback = 'Impossibile aggiornare la prenotazione.';
                    }
                }
            }
        }
    }
}

$prenAll = $db->getPrenotazioni();
$finStats = $db->getBookingFinanceStats();
$pren = [];
$total = 0;
$stats = [
    'attive' => 0,
    'attesa' => 0,
    'confermate' => 0,
    'cancellate' => 0,
];
if (is_array($prenAll)) {
    $now = new DateTimeImmutable('now');
    $filtered = array_filter($prenAll, function ($row) use ($search, $filterStato) {
        $match = true;
        if ($search !== '') {
            $hay = strtolower(($row['Utente_Nome'] ?? '') . ' ' . ($row['Utente_Cognome'] ?? '') . ' ' . ($row['IDPrenotazione'] ?? ''));
            $match = $match && str_contains($hay, strtolower($search));
        }
        if ($filterStato !== '' && isset($row['Stato_Prenotazione'])) {
            $match = $match && $row['Stato_Prenotazione'] === $filterStato;
        }
        return $match;
    });
    foreach ($prenAll as $row) {
        $st = $row['Stato_Prenotazione'] ?? '';
        $startStr = $row['Data_Ora_Inizio'] ?? null;
        $startDate = null;
        if ($startStr) {
            try {
                $startDate = new DateTimeImmutable($startStr);
            } catch (Throwable $t) {
                $startDate = null;
            }
        }

        if ($st === 'Confermata') {
            $stats['confermate']++; // tutte le confermate
        } elseif ($st === 'Cancellata') {
            $stats['cancellate']++;
        } elseif ($st === 'In Attesa') {
            $stats['attesa']++;
        }
    }
    $total = count($filtered);
    $pren = array_values($filtered);
}

$rows = buildAdminBookingRows($pren, $csrfToken);

$html = buildPage('../pages/admin_prenotazioni.html', $_SERVER['PHP_SELF']);

$feedbackBlock = buildFeedbackBlock($feedback, $feedbackClass);

$html = str_replace(
    [
        '[ADMIN_BOOKINGS_ROWS]',
        '[ADMIN_BOOKINGS_FEEDBACK]',
        '[ADMIN_BOOKINGS_SEARCH]',
        '[IF_BOOKING_STATO_ATTESA]',
        '[IF_BOOKING_STATO_CONF]',
        '[IF_BOOKING_STATO_CANC]',
        '[STAT_ATTIVE]',
        '[STAT_ATTESA]',
        '[STAT_COMPLETATE]',
        '[STAT_CANCELLATE]',
        '[STAT_REVENUE_MONTH]',
        '[STAT_REVENUE_PENDING]',
        '[STAT_REVENUE_YEAR]',
    ],
    [
        $rows,
        $feedbackBlock,
        htmlspecialchars($search),
        $filterStato === 'In Attesa' ? 'selected' : '',
        $filterStato === 'Confermata' ? 'selected' : '',
        $filterStato === 'Cancellata' ? 'selected' : '',
        $stats['attive'],
        $stats['attesa'],
        $stats['confermate'],
        $stats['cancellate'],
        htmlspecialchars(number_format((float)($finStats['revenue_month'] ?? 0), 2, ',', '.')),
        htmlspecialchars(number_format((float)($finStats['revenue_pending'] ?? 0), 2, ',', '.')),
        htmlspecialchars(number_format((float)($finStats['revenue_year'] ?? 0), 2, ',', '.')),
    ],
    $html
);

echo $html;
?>
