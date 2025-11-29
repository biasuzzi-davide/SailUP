<?php
//funzioni di validazione

/**
 *funzione che verifica che la mail sia valida (viene confrontata usando lo standard rfc822)
 * @param mixed $email
 * @return bool
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * validazione password
 */
function validatePassword(string $password):bool{
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
 * checka se il codice fiscale inserito è ok
 * @param string $cf
 * @return bool
 */
function isValidCF(string $cf){
    //converto in maiuscolo per poter usare le tabelle
    $cf = strtoupper($cf);

    //controllo il formato, solo lettere e numeri e length 16
    if (!preg_match('/^[A-Z0-9]{16}$/', $cf)) {
        return false;
    }

    //tabella dei valori in posizione dispari secondo il codice fiscale italiano
    $odd = [
        '0'=>1,'1'=>0,'2'=>5,'3'=>7,'4'=>9,'5'=>13,'6'=>15,'7'=>17,'8'=>19,'9'=>21,
        'A'=>1,'B'=>0,'C'=>5,'D'=>7,'E'=>9,'F'=>13,'G'=>15,'H'=>17,'I'=>19,'J'=>21,
        'K'=>2,'L'=>4,'M'=>18,'N'=>20,'O'=>11,'P'=>3,'Q'=>6,'R'=>8,'S'=>12,'T'=>14,
        'U'=>16,'V'=>10,'W'=>22,'X'=>25,'Y'=>24,'Z'=>23
    ];

    //tabella dei valori in posizione pari
    $even = [
        '0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9,
        'A'=>0,'B'=>1,'C'=>2,'D'=>3,'E'=>4,'F'=>5,'G'=>6,'H'=>7,'I'=>8,'J'=>9,
        'K'=>10,'L'=>11,'M'=>12,'N'=>13,'O'=>14,'P'=>15,'Q'=>16,'R'=>17,'S'=>18,'T'=>19,
        'U'=>20,'V'=>21,'W'=>22,'X'=>23,'Y'=>24,'Z'=>25
    ];

    //somma dei valori calcolati per ciascun carattere
    $sum = 0;

    for ($i = 0; $i < 15; $i++) {
        $char = $cf[$i];
        $sum += ($i % 2 === 0) ? $odd[$char] : $even[$char];
    }

    return $cf[15] === chr(($sum % 26) + ord('A'));
}

//validazione nome
function isValidName(string $nome):bool{
    //controlla che ci siano solo lettere e spazi, inoltre verifico che l 'utente non inserisca un nome composto solo da spazi
    return preg_match("/^[A-Za-z ]+$/", $nome) === 1
        && trim($nome) !== "";
}

/**
 * validazione cognome
 */
function isSurnameValid(string $cognome):bool{
     //controlla che ci siano solo lettere e spazi, inoltre verifico che l 'utente non inserisca un nome composto solo da spazi
    return preg_match("/^[A-Za-z ]+$/", $cognome) === 1
        && trim($cognome) !== "";
}

/**
 * validazione patente nautica
 */

function isValidPatenteNautica(?string $patente){
    //se è vuota o nulla, va bene perhce nel db si salvera come null oppure ""
    if (is_null($patente) || trim($patente) === "") {
        return true;
    }

    //solo numeri, lunghezza 5-10
    return preg_match("/^[0-9]{5,10}$/", $patente) === 1;
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

    // massimo 30 caratteri
    if (strlen($indirizzo) > 30) {
        return false;
    }

    //accetto lettere, numeri,spazi e alcuni caratteri comuni negli indirizzi (, . - /)
    return preg_match("/^[A-Za-z0-9\s,.\-\/]+$/", $indirizzo) === 1;
}