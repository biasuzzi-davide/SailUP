<?php

require_once '../../includes/session/session.php';
require_once '../../includes/auth/auth.php';
require_once '../../includes/utils/validation.php';
require_once '../../includes/helpers.php';

requireGuest('../php/profilo.php');

$errors = [];
$old = [
    'nome' => '',
    'cognome' => '',
    'cf' => '',
    'email' => '',
    'via' => '',
    'civico' => '',
    'cap' => '',
    'citta' => '',
    'provincia' => '',
    'privacy' => false,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfOk = verifyCsrfToken($_POST['csrf_token'] ?? null);
    if (!$csrfOk) {
        $errors[] = 'Sessione non valida, ricarica la pagina.';
    }

    $nome      = trim($_POST['nome'] ?? '');
    $cognome   = trim($_POST['cognome'] ?? '');
    $cf        = strtoupper(trim($_POST['cf'] ?? ''));
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $confirm   = $_POST['confirm-password'] ?? '';
    $via       = trim($_POST['indirizzo_via'] ?? '');
    $civico    = trim($_POST['indirizzo_civico'] ?? '');
    $cap       = trim($_POST['indirizzo_cap'] ?? '');
    $citta     = trim($_POST['indirizzo_citta'] ?? '');
    $provincia = strtoupper(trim($_POST['indirizzo_provincia'] ?? ''));
    $privacy   = isset($_POST['privacy']);

    $old = [
        'nome' => $nome,
        'cognome' => $cognome,
        'cf' => $cf,
        'email' => $email,
        'via' => $via === '' ? '' : $via,
        'civico' => $civico === '' ? '' : $civico,
        'cap' => $cap === '' ? '' : $cap,
        'citta' => $citta === '' ? '' : $citta,
        'provincia' => $provincia === '' ? '' : $provincia,
        'privacy' => $privacy,
    ];

    if ($csrfOk) {
        if (!isValidName($nome)) $errors[] = 'Nome non valido';
        if (!isSurnameValid($cognome)) $errors[] = 'Cognome non valido';
        if (!isValidCF($cf)) $errors[] = 'Codice fiscale non valido: deve essere 16 caratteri alfanumerici';
        if (!isValidEmail($email)) $errors[] = 'Email non valida: controlla il formato';
        if (!validatePassword($password)) $errors[] = 'Password non valida: minimo 8 caratteri, con lettere, numeri e simboli';
        if ($password !== $confirm) $errors[] = 'Le password non coincidono';
        if (!isValidIndirizzo($via)) $errors[] = 'Via non valida: massimo 30 caratteri, solo lettere, numeri e caratteri comuni';
        if (!isValidCivico($civico)) $errors[] = 'Civico non valido: inserisci solo numeri (1-5 cifre)';
        if (!isValidCAP($cap)) $errors[] = 'CAP non valido: deve essere di 5 cifre';
        if (!isValidCitta($citta)) $errors[] = 'Città non valida: almeno 2 caratteri, solo lettere, spazi, apostrofi e trattini';
        if (!isValidProvincia($provincia)) $errors[] = 'Provincia non valida: usa 2 lettere maiuscole (es. NA, RM)';
        
        if (empty($errors)) {
            $res = registerUserFull([
                'nome' => $nome,
                'cognome' => $cognome,
                'cf' => $cf,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'via' => $via,
                'civico' => $civico,
                'cap' => $cap,
                'citta' => $citta,
                'provincia' => $provincia,
            ]);

            if ($res['ok']) {
                header('Location: login.php?registered=1');
                exit;
            }
            if ($res['error'] === 'email_duplicata') $errors[] = 'Email già registrata';
            elseif ($res['error'] === 'cf_duplicato') $errors[] = 'Codice fiscale già registrato';
            else $errors[] = 'Si è verificato un problema durante la registrazione. Riprova o contatta l\'assistenza all\'indirizzo it@sailup.it';
        }
    }
}

$html = buildPage('../pages/registrazione.html', $_SERVER['PHP_SELF']);

$state = empty($errors) ? 'hidden' : 'error-message';
$messageText = '';

if (!empty($errors)) {
    $messageText = '<ul>';
    foreach ($errors as $err) {
        $messageText .= '<li>' . htmlspecialchars($err) . '</li>';
    }
    $messageText .= '</ul>';
}

$html = str_replace(
    ['[SERVER_STATE]', '[SERVER_MESSAGES]', '[CSRF_TOKEN]',
     '[OLD_NOME]', '[OLD_COGNOME]', '[OLD_CF]', '[OLD_EMAIL]',
     '[OLD_VIA]', '[OLD_CIVICO]', '[OLD_CAP]', '[OLD_CITTA]', '[OLD_PROVINCIA]',
     '[PRIVACY_CHECKED]'],
    [$state, $messageText, htmlspecialchars(getCsrfToken()),
     htmlspecialchars($old['nome'] ?? ''), htmlspecialchars($old['cognome'] ?? ''), htmlspecialchars($old['cf'] ?? ''), htmlspecialchars($old['email'] ?? ''),
     htmlspecialchars($old['via'] ?? ''), htmlspecialchars($old['civico'] ?? ''), htmlspecialchars($old['cap'] ?? ''), htmlspecialchars($old['citta'] ?? ''), htmlspecialchars($old['provincia'] ?? ''),
     !empty($old['privacy']) ? 'checked' : ''],
    $html
);

echo $html;
?>