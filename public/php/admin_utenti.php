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
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$search = trim($_GET['q'] ?? '');
$filterRole = $_GET['ruolo'] ?? '';
$filterStatus = $_GET['stato'] ?? '';

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
        $dataIscr = !empty($u['Data_Registrazione']) ? htmlspecialchars(date('d/m/Y', strtotime($u['Data_Registrazione']))) : '—';
        $rows .= '<tr>'
            . '<td data-label="ID">' . htmlspecialchars($u['IDUtente']) . '</td>'
            . '<td data-label="Nome">' . htmlspecialchars(($u['Nome'] ?? '') . ' ' . ($u['Cognome'] ?? '')) . '</td>'
            . '<td data-label="Email">' . htmlspecialchars($u['Email'] ?? '') . '</td>'
            . '<td data-label="Data Iscrizione"><time datetime="' . htmlspecialchars($u['Data_Registrazione'] ?? '') . '">' . $dataIscr . '</time></td>'
            . '<td data-label="Ruolo">' . $ruolo . '</td>'
            . '</tr>';
    }
} else {
    $rows = '<tr><td colspan="5">Nessun utente trovato.</td></tr>';
}

$feedbackBlock = '';
if ($feedbackState !== 'hidden' && $feedbackMessage !== '') {
    $alertClass = $feedbackState === 'success' ? 'alert alert-success' : 'alert alert-error';
    $feedbackBlock = '<div class="' . $alertClass . '" role="status" aria-live="polite">' . htmlspecialchars($feedbackMessage) . '</div>';
}

//builda la pagine ed inserisce le stats dinamche al posto dei placeholdersss
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
