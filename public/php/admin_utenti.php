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

$usersRes = $db->searchUtenti(
    $search === '' ? null : $search,
    $filterRole === '' ? null : $filterRole
);

$users = [];
if (is_array($usersRes)) {
    $users = $usersRes;
} elseif ($usersRes === false) {
    // Errore database
    error_log('searchUtenti returned false');
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

$html = str_replace(
    [
        '[ADMIN_USERS_ROWS]',
        '[ADMIN_USERS_FEEDBACK]',
        '[ADMIN_USERS_SEARCH]',
        '[IF_RUOLO_ADMIN]',
        '[IF_RUOLO_STANDARD]',
    ],
    [
        $rows,
        $feedbackBlock,
        htmlspecialchars($search),
        $filterRole === 'admin' ? 'selected' : '',
        $filterRole === 'standard' ? 'selected' : '',
    ],
    $html
);

echo str_replace(array_keys($statPlaceholders), array_values($statPlaceholders), $html);
?>
