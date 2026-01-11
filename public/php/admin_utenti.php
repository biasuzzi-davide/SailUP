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
$csrfToken = getCsrfToken();
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$search = trim($_GET['q'] ?? '');
$filterRole = $_GET['ruolo'] ?? '';

// Gestione POST per eliminazione utente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? null;
    $action = $_POST['action'] ?? '';
    $idUtente = (int)($_POST['id_utente'] ?? 0);

    if (!verifyCsrfToken($csrf)) {
        $feedbackState = 'error';
        $feedbackMessage = 'Sessione scaduta, ricarica la pagina.';
    } elseif ($idUtente <= 0) {
        $feedbackState = 'error';
        $feedbackMessage = 'Utente non valido.';
    } elseif ($idUtente === $currentUserId) {
        $feedbackState = 'error';
        $feedbackMessage = 'Non puoi eliminare te stesso.';
    } else {
        if ($action === 'delete') {
            $ok = $db->deleteUser($idUtente);
            if ($ok) {
                $feedbackState = 'success';
                $feedbackMessage = 'Utente eliminato definitivamente.';
                // Aggiorna le statistiche dopo l\'eliminazione
                $userStats = $db->getUserStats();
            } else {
                $feedbackState = 'error';
                $feedbackMessage = 'Impossibile eliminare l\'utente. Riprova.';
            }
        } else {
            $feedbackState = 'error';
            $feedbackMessage = 'Azione non valida.';
        }
    }
}

$offset = ($page - 1) * $perPage;
$usersRes = $db->searchUtenti(
    $search === '' ? null : $search,
    $filterRole === '' ? null : $filterRole,
    $perPage,
    $offset
);

$users = [];
$total = 0;
if (is_array($usersRes)) {
    $users = $usersRes['data'] ?? [];
    $total = (int)($usersRes['total'] ?? 0);
}

$rows = buildAdminUsersRows($users, $csrfToken, $currentUserId);

$alertClass = '';
if ($feedbackState !== 'hidden' && $feedbackMessage !== '') {
    $alertClass = $feedbackState === 'success' ? 'alert alert-success' : 'alert alert-error';
}
$feedbackBlock = buildFeedbackBlock($feedbackMessage, $alertClass);

//builda la pagine ed inserisce le stats dinamche al posto dei placeholdersss
$html = buildPage('../pages/admin_utenti.html', $_SERVER['PHP_SELF']);
$statPlaceholders = [
    '[STAT_USERS_TOTAL]' => htmlspecialchars((string)($userStats['total_users'] ?? 0)),
    '[STAT_USERS_MONTH]' => htmlspecialchars((string)($userStats['new_this_month'] ?? 0)),
    '[STAT_USERS_ADMIN]' => htmlspecialchars((string)($userStats['admin_users'] ?? 0)),
    '[STAT_USERS_STANDARD]' => htmlspecialchars((string)($userStats['standard_users'] ?? 0)),
];

$totalPages = $total > 0 ? (int)ceil($total / $perPage) : 1;
$pagination = buildPaginationNav(
    $page,
    $totalPages,
    'admin_utenti.php',
    ['q' => $search, 'ruolo' => $filterRole],
    'Paginazione utenti'
);

$html = str_replace(
    [
        '[ADMIN_USERS_ROWS]',
        '[ADMIN_USERS_FEEDBACK]',
        '[ADMIN_USERS_PAGINATION]',
        '[ADMIN_USERS_SEARCH]',
        '[IF_RUOLO_ADMIN]',
        '[IF_RUOLO_STANDARD]',
    ],
    [
        $rows,
        $feedbackBlock,
        $pagination,
        htmlspecialchars($search),
        $filterRole === 'admin' ? 'selected' : '',
        $filterRole === 'standard' ? 'selected' : '',
    ],
    $html
);

echo str_replace(array_keys($statPlaceholders), array_values($statPlaceholders), $html);
?>
