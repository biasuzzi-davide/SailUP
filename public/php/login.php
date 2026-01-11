<?php

require_once '../../includes/session/session.php';
require_once '../../includes/auth/auth.php';
require_once '../../includes/helpers.php';

// Se un utente prova a fare un traversal path alla pagina di login nonostante sia già loggato
requireGuest('../php/profilo.php');

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
        } else {
            $errors[] = 'Email o password non corretti.';
        }
    }
}

$html = buildPage('../pages/login.html', $_SERVER['PHP_SELF']);

$state = empty($errors) ? 'hidden' : 'alert alert-error';

$messageText = '';
if (!empty($errors)) {
    $messageText = htmlspecialchars(implode(' | ', $errors));
}

$html = str_replace(
    ['[SERVER_STATE]', '[SERVER_MESSAGES]', '[CSRF_TOKEN]'],
    [$state, $messageText, htmlspecialchars(getCsrfToken())],
    $html
);

echo $html;
?>