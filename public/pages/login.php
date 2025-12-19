<?php
require_once __DIR__ . '/../../includes/session/session.php';
require_once __DIR__ . '/../../includes/auth/auth.php';

$errors = [];

//controllo se il form è stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    //loggo
    if (loginUser($email, $password)) {
        //se log riuscito, reindirizzo alla home dell'utente
        header('Location: '); // DA METTERE
        exit();
    } else {
        $errors[] = "Email o password non validi";
    }
}
/*
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
        <label>Email:</label>
        <input type="email" name="email" required>
        <br>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
*/
