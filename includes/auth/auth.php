<?php
require_once __DIR__ . '/../session/session.php';
require_once __DIR__ . '/../../config/db_connect.php';


/**
 * registrazione nuovo utente
 * @return bool
 */
function registerUser($nome, $cognome, $cf, $email, $password, $indirizzo, $patente = null) {
    global $conn;

    //inserisc l’indirizzo
    $sqlInd = "INSERT INTO Indirizzo (Indirizzo) VALUES (:indirizzo) RETURNING IDIndirizzo";
    $stmtInd = $conn->prepare($sqlInd);
    $stmtInd->execute([":indirizzo" => $indirizzo]);
    $idIndirizzo = $stmtInd->fetchColumn();

    //inserisco l’utente
    $sql = "INSERT INTO Utente
            (Nome, Cognome, CF, Email, PasswordHash, Numero_Patente_Nautica, IDIndirizzo)
            VALUES (:nome, :cognome, :cf, :email, :password, :patente, :idIndirizzo)";

    $stmt = $conn->prepare($sql);

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        return $stmt->execute([
            ":nome"        => $nome,
            ":cognome"     => $cognome,
            ":cf"          => $cf,
            ":email"       => $email,
            ":password"    => $hashedPassword,
            ":patente"     => $patente,
            ":idIndirizzo" => $idIndirizzo
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * login utente
 * @return bool
 */
function loginUser($email, $password){
    //rendo visbile la variabile conn presente nel file db_connect (config)
    global $conn;

    //recupero utente tramite email
    $sql = "SELECT * FROM Utente WHERE Email = :email LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([":email" => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false; // email non trovata
    }

    //verifica password hashata
    if (!password_verify($password, $user["passwordhash"])) {
        return false; // password sbagliata
    }

    //login okappa , salvo nella sessione
    $_SESSION["user_id"] = $user["idutente"];
    $_SESSION["email"]   = $user["email"];
    $_SESSION["role"]    = ($user["is_admin"] == true) ? "admin" : "user";

    return true;

}