<?php

require_once '../../includes/session/session.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/helpers.php';

requireAdmin();

$db = new DBConnection();
$userStats = $db->getUserStats();
$feedbackState = 'hidden';
$feedbackMessage = '';
$currentUserId = $_SESSION['user']['IDUtente'] ?? null;
$csrfToken = htmlspecialchars(getCsrfToken());
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$search = trim($_GET['q'] ?? '');
$filterRole = $_GET['ruolo'] ?? '';
$filterStatus = $_GET['stato'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? null;
    $action = $_POST['action'] ?? '';
    $targetId = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;

    if (!verifyCsrfToken($csrf)) {
        $feedbackState = 'error';
        $feedbackMessage = 'Sessione scaduta, ricarica la pagina.';
    } elseif ($targetId <= 0) {
        $feedbackState = 'error';
        $feedbackMessage = 'Utente non valido.';
    } else {
        $targetUser = $db->getUtenteById($targetId);
        if ($targetUser === null) {
            $feedbackState = 'error';
            $feedbackMessage = 'Utente non trovato.';
        } elseif ($targetUser === false) {
            $feedbackState = 'error';
            $feedbackMessage = 'Errore durante il recupero utente.';
        } elseif ($currentUserId !== null && (int) $currentUserId === $targetId) {
            $feedbackState = 'error';
            $feedbackMessage = 'Non puoi modificare il tuo account da qui.';
        } elseif ($action === 'toggle_status') {
            $newStatus = empty($targetUser['Attivo']);
            $ok = $db->setUserStatus($targetId, $newStatus);
            if ($ok) {
                $feedbackState = 'success';
                $feedbackMessage = $newStatus
                    ? 'Utente riattivato correttamente.'
                    : 'Utente disattivato correttamente.';
            } else {
                $feedbackState = 'error';
                $feedbackMessage = 'Impossibile aggiornare lo stato utente.';
            }
        } elseif ($action === 'toggle_role') {
            $newRole = empty($targetUser['Is_Admin']);
            $ok = $db->setUserRole($targetId, $newRole);
            if ($ok) {
                $feedbackState = 'success';
                $feedbackMessage = $newRole
                    ? 'Utente promosso ad admin.'
                    : 'Utente impostato come standard.';
            } else {
                $feedbackState = 'error';
                $feedbackMessage = 'Impossibile aggiornare il ruolo utente.';
            }
        } else {
            $feedbackState = 'error';
            $feedbackMessage = 'Azione non riconosciuta.';
        }
    }
}

$offset = ($page - 1) * $perPage;
$usersRes = $db->searchUtenti(
    $search === '' ? null : $search,
    $filterRole === '' ? null : $filterRole,
    $filterStatus === '' ? null : $filterStatus,
    $perPage,
    $offset
);

$users = [];
$total = 0;
if (is_array($usersRes)) {
    $users = $usersRes['data'] ?? [];
    $total = (int)($usersRes['total'] ?? 0);
}

$rows = '';
if (is_array($users) && !empty($users)) {
    foreach ($users as $u) {
        $ruolo = !empty($u['Is_Admin']) ? '<span class="status-badge active">Admin</span>' : '<span class="status-badge completed">Standard</span>';
        $stato = !empty($u['Attivo']) ? '<span class="status-badge active">Attivo</span>' : '<span class="status-badge cancelled">Disattivo</span>';
        $dataIscr = !empty($u['Data_Registrazione']) ? htmlspecialchars(date('d/m/Y', strtotime($u['Data_Registrazione']))) : '—';
        $isSelf = $currentUserId !== null && (int) $currentUserId === (int) $u['IDUtente'];
        $disableAttr = $isSelf ? ' disabled aria-disabled="true" title="Azione non disponibile sul tuo account"' : '';
        $statusLabel = !empty($u['Attivo']) ? 'Disattiva' : 'Attiva';
        $roleLabel = !empty($u['Is_Admin']) ? 'Rimuovi admin' : 'Rendi admin';
        $rows .= '<tr>'
            . '<td data-label="ID">' . htmlspecialchars($u['IDUtente']) . '</td>'
            . '<td data-label="Nome">' . htmlspecialchars(($u['Nome'] ?? '') . ' ' . ($u['Cognome'] ?? '')) . '</td>'
            . '<td data-label="Email">' . htmlspecialchars($u['Email'] ?? '') . '</td>'
            . '<td data-label="Data Iscrizione"><time datetime="' . htmlspecialchars($u['Data_Registrazione'] ?? '') . '">' . $dataIscr . '</time></td>'
            . '<td data-label="Ruolo">' . $ruolo . '</td>'
            . '<td data-label="Stato">' . $stato . '</td>'
            . '<td data-label="Azioni" class="actions-cell">'
            . '<form method="post" class="inline-form">'
            . '<input type="hidden" name="csrf_token" value="' . $csrfToken . '">'
            . '<input type="hidden" name="user_id" value="' . htmlspecialchars($u['IDUtente']) . '">'
            . '<input type="hidden" name="action" value="toggle_status">'
            . '<button type="submit" class="btn-text" aria-label="Attiva o disattiva utente ' . htmlspecialchars($u['Email'] ?? '') . '"' . $disableAttr . '>'
            . htmlspecialchars($statusLabel)
            . '</button>'
            . '</form>'
            . '<form method="post" class="inline-form">'
            . '<input type="hidden" name="csrf_token" value="' . $csrfToken . '">'
            . '<input type="hidden" name="user_id" value="' . htmlspecialchars($u['IDUtente']) . '">'
            . '<input type="hidden" name="action" value="toggle_role">'
            . '<button type="submit" class="btn-text" aria-label="Cambia ruolo utente ' . htmlspecialchars($u['Email'] ?? '') . '"' . $disableAttr . '>'
            . htmlspecialchars($roleLabel)
            . '</button>'
            . '</form>'
            . '</td>'
            . '</tr>';
    }
} else {
    $rows = '<tr><td colspan="7">Nessun utente trovato.</td></tr>';
}

$feedbackBlock = '';
if ($feedbackState !== 'hidden' && $feedbackMessage !== '') {
    $alertClass = $feedbackState === 'success' ? 'alert alert-success' : 'alert alert-error';
    $feedbackBlock = '<div class="' . $alertClass . '" role="status" aria-live="polite">' . htmlspecialchars($feedbackMessage) . '</div>';
}

//builda la pagine ed inserisce le stats dinamche al posto dei placeholder
$html = buildPage('../pages/admin_utenti.html', $_SERVER['PHP_SELF']);
$statPlaceholders = [
    '[STAT_USERS_TOTAL]' => htmlspecialchars((string)($userStats['total_users'] ?? 0)),
    '[STAT_USERS_MONTH]' => htmlspecialchars((string)($userStats['new_this_month'] ?? 0)),
    '[STAT_USERS_ADMIN]' => htmlspecialchars((string)($userStats['admin_users'] ?? 0)),
    '[STAT_USERS_STANDARD]' => htmlspecialchars((string)($userStats['standard_users'] ?? 0)),
];

$pages = $total > 0 ? (int)ceil($total / $perPage) : 1;
$pagination = '';
if ($pages > 1) {
    $pagination .= '<nav class="pagination" aria-label="Paginazione utenti"><ul>';
    for ($i = 1; $i <= $pages; $i++) {
        $currentClass = $i === $page ? ' class="current-page"' : '';
        $query = http_build_query([
            'page' => $i,
            'q' => $search,
            'ruolo' => $filterRole,
            'stato' => $filterStatus,
        ]);
        $pagination .= '<li' . $currentClass . '><a href="admin_utenti.php?' . htmlspecialchars($query) . '">' . $i . '</a></li>';
    }
    $pagination .= '</ul></nav>';
}

$html = str_replace(
    [
        '[ADMIN_USERS_ROWS]',
        '[ADMIN_USERS_FEEDBACK]',
        '[ADMIN_USERS_PAGINATION]',
        '[ADMIN_USERS_SEARCH]',
        '[IF_RUOLO_ADMIN]',
        '[IF_RUOLO_STANDARD]',
        '[IF_STATO_ATTIVI]',
        '[IF_STATO_DISATTIVI]',
    ],
    [
        $rows,
        $feedbackBlock,
        $pagination,
        htmlspecialchars($search),
        $filterRole === 'admin' ? 'selected' : '',
        $filterRole === 'standard' ? 'selected' : '',
        $filterStatus === 'attivi' ? 'selected' : '',
        $filterStatus === 'disattivi' ? 'selected' : '',
    ],
    $html
);

// Keywords per SEO (pagine admin sono noindex)
$keywords = '<meta name="keywords" content="gestione utenti, admin utenti, dashboard utenti SailUP">';
$html = str_replace('[KEYWORDS]', $keywords, $html);

echo str_replace(array_keys($statPlaceholders), array_values($statPlaceholders), $html);
?>
