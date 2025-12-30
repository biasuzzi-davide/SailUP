<?php

require_once '../../includes/session/session.php';
require_once '../../includes/auth/auth.php';
require_once '../../includes/utils/validation.php';
require_once '../../includes/helpers.php';

$errors = [];
$old = [
    'nome' => '',
    'cognome' => '',
    'cf' => '',
    'email' => '',
    'via' => 'Via Roma',
    'civico' => '123',
    'cap' => '80100',
    'citta' => 'Napoli',
    'provincia' => 'NA',
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
    $patente   = trim($_POST['patente'] ?? '');
    $privacy   = isset($_POST['privacy']);

    // conserva i valori inseriti per ripopolare il form
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
        //validazioni
        if (!isValidName($nome)) $errors[] = 'Nome non valido';
        if (!isSurnameValid($cognome)) $errors[] = 'Cognome non valido';
        if (!isValidCF($cf)) $errors[] = 'Codice fiscale non valido';
        if (!isValidEmail($email)) $errors[] = 'Email non valida';
        if (!validatePassword($password)) $errors[] = 'Password non valida';
        if ($password !== $confirm) $errors[] = 'Le password non coincidono';
        if (!isValidIndirizzo($via)) $errors[] = 'Via non valida';
        if (!isValidCivico($civico)) $errors[] = 'Civico non valido';
        if (!isValidCAP($cap)) $errors[] = 'CAP non valido';
        if (!isValidCitta($citta)) $errors[] = 'Città non valida';
        if (!isValidProvincia($provincia)) $errors[] = 'Provincia non valida';
        if (!isValidPatenteNautica($patente)) $errors[] = 'Patente nautica non valida';

        if (empty($errors)) {
            $res = registerUserFull([
                'nome' => $nome,
                'cognome' => $cognome,
                'cf' => $cf,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'patente' => $patente === '' ? null : $patente,
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
            else $errors[] = 'Errore durante la registrazione, riprova';
        }
    }
}

$html = buildPage('../pages/registrazione.html', $_SERVER['PHP_SELF']);

//inserisc stato e messaggi server nel template tramite placeholder
$state = empty($errors) ? 'hidden' : 'error';
$messageText = '';
if (!empty($errors)) {
    $messageText = htmlspecialchars(implode(' | ', $errors));
}

// Keywords per SEO
$keywords = '<meta name="keywords" content="registrazione SailUP, crea account, iscriviti SailUP, nuovo utente, prenotazioni barche Napoli">';

$html = str_replace(
    ['[SERVER_STATE]', '[SERVER_MESSAGES]', '[CSRF_TOKEN]',
     '[OLD_NOME]', '[OLD_COGNOME]', '[OLD_CF]', '[OLD_EMAIL]',
     '[OLD_VIA]', '[OLD_CIVICO]', '[OLD_CAP]', '[OLD_CITTA]', '[OLD_PROVINCIA]',
     '[PRIVACY_CHECKED]', '[KEYWORDS]'],
    [$state, $messageText, htmlspecialchars(getCsrfToken()),
     htmlspecialchars($old['nome'] ?? ''), htmlspecialchars($old['cognome'] ?? ''), htmlspecialchars($old['cf'] ?? ''), htmlspecialchars($old['email'] ?? ''),
     htmlspecialchars($old['via'] ?? ''), htmlspecialchars($old['civico'] ?? ''), htmlspecialchars($old['cap'] ?? ''), htmlspecialchars($old['citta'] ?? ''), htmlspecialchars($old['provincia'] ?? ''),
     !empty($old['privacy']) ? 'checked' : '', $keywords],
    $html
);

echo $html;
?>
