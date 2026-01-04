<?php

require_once '../../includes/session/session.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/helpers.php';

requireAdmin();

$db = new DBConnection();
$csrfToken = htmlspecialchars(getCsrfToken());
$feedback = '';
$feedbackClass = '';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
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
    $offset = ($page - 1) * $perPage;
    $pren = array_slice(array_values($filtered), $offset, $perPage);
}

$rows = '';
if (is_array($pren) && !empty($pren)) {
    foreach ($pren as $p) {
        $stato = $p['Stato_Prenotazione'] ?? '';
        $badgeClass = 'pending';
        if ($stato === 'Confermata') $badgeClass = 'active';
        if ($stato === 'Cancellata') $badgeClass = 'cancelled';
        $rows .= '<tr>'
            . '<td data-label="ID">' . htmlspecialchars($p['IDPrenotazione']) . '</td>'
            . '<td data-label="Prodotto">' . htmlspecialchars($p['IDProdotto']) . '</td>'
            . '<td data-label="Cliente">' . htmlspecialchars(($p['Utente_Nome'] ?? '') . ' ' . ($p['Utente_Cognome'] ?? '')) . '</td>'
            . '<td data-label="Data"><time datetime="' . htmlspecialchars($p['Data_Ora_Inizio'] ?? '') . '">' . htmlspecialchars($p['Data_Ora_Inizio'] ?? '') . '</time></td>'
            . '<td data-label="Totale">€ ' . htmlspecialchars($p['Prezzo_Totale'] ?? '') . '</td>'
            . '<td data-label="Stato"><span class="status-badge ' . $badgeClass . '">' . htmlspecialchars($stato) . '</span></td>'
            . '<td data-label="Azioni" class="actions-cell">'
            . '<a href="dettaglio_prenotazione.php?id=' . htmlspecialchars($p['IDPrenotazione']) . '" class="btn-text" aria-label="Vedi dettagli prenotazione ' . htmlspecialchars($p['IDPrenotazione']) . '">Dettagli</a>'
            . '<form method="post" class="inline-form">'
            . '<input type="hidden" name="csrf_token" value="' . $csrfToken . '">'
            . '<input type="hidden" name="id_prenotazione" value="' . htmlspecialchars($p['IDPrenotazione']) . '">'
            . '<input type="hidden" name="action" value="confirm">'
            . '<button type="submit" class="btn-text" aria-label="Conferma prenotazione ' . htmlspecialchars($p['IDPrenotazione']) . '">Conferma</button>'
            . '</form>'
            . '<form method="post" class="inline-form" onsubmit="return confirm(\'Cancellare questa prenotazione?\');">'
            . '<input type="hidden" name="csrf_token" value="' . $csrfToken . '">'
            . '<input type="hidden" name="id_prenotazione" value="' . htmlspecialchars($p['IDPrenotazione']) . '">'
            . '<input type="hidden" name="action" value="cancel">'
            . '<button type="submit" class="btn-text danger" aria-label="Cancella prenotazione ' . htmlspecialchars($p['IDPrenotazione']) . '">Cancella</button>'
            . '</form>'
            . '</td>'
            . '</tr>';
    }
} else {
    $rows = '<tr><td colspan="7">Nessuna prenotazione trovata.</td></tr>';
}

$html = buildPage('../pages/admin_prenotazioni.html', $_SERVER['PHP_SELF']);
$pages = $total > 0 ? (int)ceil($total / $perPage) : 1;
$pagination = '';
if ($pages > 1) {
    $pagination .= '<nav class="pagination" aria-label="Paginazione prenotazioni"><ul>';
    for ($i = 1; $i <= $pages; $i++) {
        $currentClass = $i === $page ? ' class="current-page"' : '';
        $query = http_build_query([
            'page' => $i,
            'q' => $search,
            'stato' => $filterStato,
        ]);
        $pagination .= '<li' . $currentClass . '><a href="admin_prenotazioni.php?' . htmlspecialchars($query) . '">' . $i . '</a></li>';
    }
    $pagination .= '</ul></nav>';
}

$feedbackBlock = '';
if ($feedback !== '' && $feedbackClass !== '') {
    $feedbackBlock = '<div class="' . $feedbackClass . '" role="status" aria-live="polite">' . htmlspecialchars($feedback) . '</div>';
}

$html = str_replace(
    [
        '[ADMIN_BOOKINGS_ROWS]',
        '[ADMIN_BOOKINGS_FEEDBACK]',
        '[ADMIN_BOOKINGS_PAGINATION]',
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
        $pagination,
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

// Keywords per SEO (pagine admin sono noindex)
$keywords = '<meta name="keywords" content="gestione prenotazioni, admin booking, dashboard prenotazioni SailUP">';
$html = str_replace('[KEYWORDS]', $keywords, $html);

echo $html;
?>
