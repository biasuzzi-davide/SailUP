<?php
//avvio la sessione solo se non è gia stata inviata
if(session_status()==PHP_SESSION_NONE){
    session_start();
}

/** 
 * controlla se l'utente è loggato o meno, isset dice se quella variabile è null o meno
 * @return bool
*/
function isLogged(){
    return isset($_SESSION['user']);

}

/**
 * reindirizza l'utente alla schermata di login se non è loggato
 * @return void
 */
function requireLogin(string $redirect = '../php/login.php'): void {
    if (!isLogged()) {
        header('Location: ' . $redirect);
        exit;
    }
}

/**
 * controllo se l'utente è admin
 * @return bool
 */
function isAdmin(): bool {
    return isset($_SESSION['user']['Is_Admin']) && (int)$_SESSION['user']['Is_Admin'] === 1;
}

/**
 * indirizzo l'utente ad una schermata di errore "non sei autorizzato" se
 * cerca di accedere ad una pagina riservata ad un admin (anche se non la vede nella schermata
 *  potrebbe accederci modificando l'url)
 *  @return void
 */
function requireAdmin(string $redirect = '../php/login.php', string $forbidden = '../pages/403.html'): void {
    requireLogin($redirect);
    if (!isAdmin()) {
        header('Location: ' . $forbidden);
        exit;
    }
}
/**
 * per effettuare il logout dell'utente
 * @return void
 */
function logout(string $redirect = '../php/login.php'): void {
    //pulisce variabili di sessione
    session_unset();
    //elimina la sessione per liberare lo spazio associato
    session_destroy();
    //indirizzo l'utente verso la schermata di login e blocco l esecuzione dello script
    header('Location: ' . $redirect);
    exit;
}

