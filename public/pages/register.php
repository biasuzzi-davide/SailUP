<?php
require_once __DIR__ . '/../../includes/session/session.php';
require_once __DIR__ . '/../../includes/auth/auth.php';
require_once __DIR__ . '/../../includes/utils/validation.php';

$errors = [];

//controllo se il form è stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome      = trim($_POST['nome'] ?? '');
    $cognome   = trim($_POST['cognome'] ?? '');
    $cf        = strtoupper(trim($_POST['cf'] ?? ''));
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $patente   = trim($_POST['patente'] ?? null);
    $indirizzo = trim($_POST['indirizzo'] ?? '');

    //validazioni lato server
    if (!isValidName($nome)) {
        $errors[] = "Nome non valido";
    }
    if (!isSurnameValid($cognome)) {
        $errors[] = "Cognome non valido";
    }
    if (!isValidCF($cf)) {
        $errors[] = "Codice fiscale non valido";
    }
    if (!isValidEmail($email)) {
        $errors[] = "Email non valida";
    }
    if (!validatePassword($password)) {
        $errors[] = "Password non valida (almeno 8 caratteri, 1 numero, 1 lettera, 1 carattere speciale)";
    }
    if (!isValidPatenteNautica($patente)) {
        $errors[] = "Numero patente nautica non valido";
    }
    if (!isValidIndirizzo($indirizzo)) {
        $errors[] = "Indirizzo non valido";
    }

    //no errors, registro l'utente
    if (empty($errors)) {
        if (registerUser($nome, $cognome, $cf, $email, $password, $indirizzo, $patente)) {
            //registrazion okappa, indirizzo verso il login
            header("Location: "); //DA METTERE
            exit();
        } else {
            $errors[] = "Errore durante la registrazione (email o CF già registrati)";
        }
    }
}
/*
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione</title>
</head>
<body>
    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $err): ?>
                <p><?php echo htmlspecialchars($err); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Nome:</label>
        <input type="text" name="nome" required>
        <br>
        <label>Cognome:</label>
        <input type="text" name="cognome" required>
        <br>
        <label>Codice Fiscale:</label>
        <input type="text" name="cf" required>
        <br>
        <label>Email:</label>
        <input type="email" name="email" required>
        <br>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br>
        <label>Patente nautica (opzionale):</label>
        <input type="text" name="patente">
        <br>
        <label>Indirizzo:</label>
        <input type="text" name="indirizzo" required>
        <br>
        <button type="submit">Registrati</button>
    </form>
</body>
</html>
*/
