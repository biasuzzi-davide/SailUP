<?php
//funzioni di autenticazione e registrazione
declare(strict_types=1);

require_once __DIR__ . '/../session/session.php';
require_once __DIR__ . '/../db_connection.php';

/**
 *rèegistra un utente completo di indirizzo
 */
function registerUserFull(array $data): array {
    $db = new DBConnection();

    //innserisco prima l indirizzo
    $idIndirizzo = $db->insertIndirizzo(
        $data['via'],
        $data['civico'],
        $data['cap'],
        $data['citta'],
        $data['provincia'],
        $data['paese'] ?? 'IT'
    );
    if (!$idIndirizzo) {
        return ['ok' => false, 'error' => 'indirizzo'];
    }

    //registro utente
    $result = $db->registerUser(
        $data['nome'],
        $data['cognome'],
        $data['cf'],
        $data['email'],
        $data['password_hash'],
        $data['patente'] ?? null,
        $idIndirizzo,
        $data['is_admin'] ?? false
    );

    if ($result === -1) return ['ok' => false, 'error' => 'email_duplicata'];
    if ($result === -2) return ['ok' => false, 'error' => 'cf_duplicato'];
    if (!$result)       return ['ok' => false, 'error' => 'generic'];

    return ['ok' => true, 'user_id' => $result];
}

/**
 *effettua login e popola la sessione
 *ritorna array utente se ok, -1 se utente non trovato,0 se password errata, false se errore
 */
function loginUserAuth(string $email, string $password) {
    $db = new DBConnection();
    $user = $db->loginUser($email, $password); // già usa password_verify e attivo=1

    if (is_array($user)) {
        $_SESSION['user'] = $user;
        //aggiorno ultimo accesso
        $db->aggiornaUltimoAccesso(idUtente: (int)$user['IDUtente']);
    }

    return $user;
}

/**
 *esegue il logout usando l'helper di sessione
 */
function logoutUser(string $redirect = '../php/login.php'): void {
    logout($redirect);
}
