<?php

require_once '../../includes/session/session.php';
require_once '../../includes/auth/auth.php';
require_once '../../includes/helpers.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Sessione scaduta, ricarica la pagina.';
    } else {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $res = loginUserAuth($email, $password);

    if (is_array($res)) {
        $target = isAdmin() ? 'admin.php' : 'profilo.php';
        header('Location: ' . $target);
        exit;
    } elseif ($res === -1) {
        $errors[] = 'Utente non trovato';
    } elseif ($res === 0) {
        $errors[] = 'Password errata';
    } else {
        $errors[] = 'Errore inatteso, riprova';
    }
    }
}

$html = buildPage('../pages/login.html', $_SERVER['PHP_SELF']);

$state = empty($errors) ? 'hidden' : 'error';
$messageText = '';
if (!empty($errors)) {
    $messageText = htmlspecialchars(implode(' | ', $errors));
}

// Keywords per SEO
$keywords = '<meta name="keywords" content="login, accesso, account, utente, prenotazioni, area, riservata, SailUP">';

$html = str_replace(
    ['[SERVER_STATE]', '[SERVER_MESSAGES]', '[CSRF_TOKEN]', '[KEYWORDS]'],
    [$state, $messageText, htmlspecialchars(getCsrfToken()), $keywords],
    $html
);

echo $html;
?>
