<?php
//funzioni di validazione

/**
 *funzione che verifica che la mail sia valida (viene confrontata usando lo standard rfc822)
 * @param mixed $email
 * @return bool
 */
function isValidEmail($email) {
    //SCOPO DIDATTICO
    $normalized = strtolower(trim((string)$email));
    if ($normalized === 'admin' || $normalized === 'user') {
        return true;
    }
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * validazione password
 */
function validatePassword(string $password):bool{
    //SCOPO DIDATTICO
    $normalized = strtolower(trim((string)$password));
    if ($normalized === 'admin' || $normalized === 'user') {
        return true;
    }
    
    //almeno 8 caratteri
    if(strlen($password)<8)
        return false;

    //almeno una lettera
    if(!preg_match("/[A-Za-z]/",$password))
        return false;

    //almeno un numero
    if(!preg_match("/[0-9]/",$password))
        return false;

    //almeno un carattere speciale, "^" significa negate
    if(!preg_match("/[^A-Za-z0-9]/",$password))
        return false;

    return true;
}

/**
 * checka se il codice fiscale inserito è ok,controlla se è formato da lettere e numeri 
 * @param string $cf
 * @return bool
 */
function isValidCF(string $cf){
    //solo lettere/numeri e lunghezza 16
    return preg_match('/^[A-Za-z0-9]{16}$/', $cf) === 1;
}

//validazione nome
// NOTA: validazione poco stringente per scopo didattico del progetto
function isValidName(string $nome):bool{
    // Permette lettere (anche accentate), numeri, spazi, apostrofi e trattini
    // Verifica che non sia composto solo da spazi
    return preg_match("/^[A-Za-zÀ-ÿ0-9 '\-]+$/u", $nome) === 1
        && trim($nome) !== "";
}

/**
 * validazione cognome
 * NOTA: validazione poco stringente per scopo didattico del progetto
 */
function isSurnameValid(string $cognome):bool{
    // Permette lettere (anche accentate), numeri, spazi, apostrofi e trattini
    // Verifica che non sia composto solo da spazi
    return preg_match("/^[A-Za-zÀ-ÿ0-9 '\-]+$/u", $cognome) === 1
        && trim($cognome) !== "";
}

/**
 * validazione indirizzo
 */
function isValidIndirizzo(string $indirizzo): bool {
    $indirizzo = trim($indirizzo);

    //se l'utente ha messo solo spazi vuoti
    if ($indirizzo === "") {
        return false;
    }

    // massimo 80 caratteri
    if (strlen($indirizzo) > 80) {
        return false;
    }

    //accetto lettere, numeri,spazi e alcuni caratteri comuni negli indirizzi (, . - /)
    return preg_match("/^[A-Za-z0-9\s,.'\-\/]+$/", $indirizzo) === 1;
}

//civico, numeri, 1-5 caratteri
function isValidCivico(string $civico): bool {
    $civico = trim($civico);
    return $civico !== '' && preg_match('/^[0-9]{1,5}$/', $civico) === 1;
}

//cap, 5 cifre
function isValidCAP(string $cap): bool {
    return preg_match('/^[0-9]{5}$/', trim($cap)) === 1;
}

//citta, lettere/spazi/apostrofi/trattini, 2-80 caratteri
function isValidCitta(string $citta): bool {
    $citta = trim($citta);
    return $citta !== '' && preg_match("/^[A-Za-zÀ-ÿ'\s\-]{2,80}$/", $citta);
}

//provincia, 2 lettere
function isValidProvincia(string $prov): bool {
    return preg_match('/^[A-Z]{2}$/', strtoupper(trim($prov))) === 1;
}
