<?php
require_once __DIR__ . '/../session/session.php';
require_once __DIR__ . '/../../config/db_connect.php';


/**
 * registrazione nuovo utente
 * @return bool
 */
function registerUser($nome,$cognome,$cf,$email,$password,$idIndirizzo,$patente=null){
    global $conn;
    $hashedPassword=password_hash($password,PASSWORD_DEFAULT);
    //insert nel database
    $sql= "INSERT INTO Utente
            (Nome, Cognome, CF, Email, PasswordHash, Numero_Patente_Nautica, IDIndirizzo)
            VALUES (:nome, :cognome, :cf, :email, :hashedPassword,)";

    $stmt= $conn->prepare($sql);

    try {
        return $stmt->execute([
            ":nome"        => $nome,
            ":cognome"     => $cognome,
            ":cf"          => $cf,
            ":email"       => $email,
            ":pass"        => $hashedPassword,
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
function login(){
    //rendo visbile la variabile conn presente nel file db_connect (config)
    global $conn;

}