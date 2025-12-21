<?php

require_once '../../includes/session/session.php';
require_once '../../includes/auth/auth.php';
require_once '../../includes/helpers.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $res = loginUserAuth($email, $password);

    if (is_array($res)) {
        $target = isAdmin() ? 'admin.php' : 'profilo.php';
        header('Location: ' . $target);
        exit;
    } elseif ($res === -1) {
        $errors[] = 'Utente non trovato o disattivato';
    } elseif ($res === 0) {
        $errors[] = 'Password errata';
    } else {
        $errors[] = 'Errore inatteso, riprova';
    }
}

$html = buildPage('../pages/login.html', $_SERVER['PHP_SELF']);

if (!empty($errors)) {
    $msg = '<div role="status" aria-live="polite" class="form-messages error">'.implode('<br>', array_map('htmlspecialchars', $errors)).'</div>';
    $html = str_replace('[ERRORS]', $msg, $html);
} else {
    $html = str_replace('[ERRORS]', '', $html);
}

echo $html;
?>
