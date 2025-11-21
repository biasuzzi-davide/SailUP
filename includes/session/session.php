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
    return isset($_SESSION['user_id']);

}

/**
 * reindirizza l'utente alla schermata di login se non è loggato
 * @return void
 */
function requireLogin(){
    if(!isLogged()){
        header("Location: "); //PERCORSO ALLA SCHERMATA DI LOGIN, AGGIUNGERE LATER
        exit(); //fermo l esecuzione dello script
    }
}

/**
 * controllo se l'utente è admin
 * @return bool
 */
function isAdmin(){
    /*faccio prima isset per evitare errori nel caso cercassi 'admin'
    in $_SESSION e quest'ultima fosse null
    */
    return isset($_SESSION['role']) && $_SESSION['role']=== 'admin';
}

/**
 * indirizzo l'utente ad una schermata di errore "non sei autorizzato" se
 * cerca di accedere ad una pagina riservata ad un admin (anche se non la vede nella schermata
 *  potrebbe accederci modificando l'url)
 *  @return void
 */
function requireAdmin(){
    requireLogin();

    if(!isAdmin()){
        //indirizzo ad una schermata "non sei autorizzato"
        header("Location: "); // DA COMPLETARE
        exit(); //fermo l esecuzione dello script
    }
}

/**
 * per effettuare il logout dell'utente
 * @return void
 */
function logout(){
    //pulisco tutte le variabili di sessione
    session_unset();
    //elimino la sessione per liberare lo spazio associato sul server
    session_destroy();
    //indirizzo l'utente verso la schermata di login e blocco l esecuzione dello script (per sicurezza)
    header("Location: "); //DA COMPLETARE IL PERCORSO
    exit();
}

