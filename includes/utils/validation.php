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
