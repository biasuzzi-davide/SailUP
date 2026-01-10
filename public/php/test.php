<?php


/** ATTENZIONE QUESTA E UNA PAGINA CHE SI USA SOLO IN FASE DI SVILUPPO DEL SITO
 *  SERVE SOLO PER TESTARE LE PAGINE PHP E COPIARE IL CODICE HTML GENERATO
 *  IN MANIERA VELOCE SULLA CLIPBOARD IN MODO DA INCOLLARLO NEI TEST AUTOMATICI
 */ 
require_once '../../includes/helpers.php';

$page = isset($_GET['page']) ? $_GET['page'] : null;
if ($page) {
    $templatePath = '../pages/' . str_replace('.php', '.html', $page);
    $html = buildPage($templatePath, $page);
    echo '<!DOCTYPE html><html><head><style>body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; } #message { background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 5px; }</style><script>navigator.clipboard.writeText(' . json_encode($html) . ').then(() => { document.getElementById("message").style.display = "block"; setTimeout(() => { window.location = "test.php"; }, 2000); }).catch(err => { document.getElementById("message").innerText = "Errore nella copia: " + err; document.getElementById("message").style.backgroundColor = "#f8d7da"; document.getElementById("message").style.color = "#721c24"; document.getElementById("message").style.display = "block"; setTimeout(() => { window.location = "test.php"; }, 2000); });</script></head><body><div id="message" style="display: none;">Codice HTML copiato per ' . htmlspecialchars($page) . '!</div></body></html>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Pagine PHP - SailUP</title>
</head>
<body>
    <h1>Test delle Pagine PHP</h1>
    <p>Clicca sui collegamenti per copiare il codice HTML compilato sulla clipboard:</p>
    <ul>
        <li><a href="?page=404.php">404.php</a></li>
        <li><a href="?page=admin.php">admin.php</a></li>
        <li><a href="?page=admin_blog.php">admin_blog.php</a></li>
        <li><a href="?page=admin_blog_nuovo.php">admin_blog_nuovo.php</a></li>
        <li><a href="?page=admin_prenotazioni.php">admin_prenotazioni.php</a></li>
        <li><a href="?page=admin_prodotti.php">admin_prodotti.php</a></li>
        <li><a href="?page=admin_prodotti_nuovo.php">admin_prodotti_nuovo.php</a></li>
        <li><a href="?page=admin_utenti.php">admin_utenti.php</a></li>
        <li><a href="?page=blog.php">blog.php</a></li>
        <li><a href="?page=blog_articolo.php">blog_articolo.php</a></li>
        <li><a href="?page=catalogo_esperienze.php">catalogo_esperienze.php</a></li>
        <li><a href="?page=catalogo_noleggio.php">catalogo_noleggio.php</a></li>
        <li><a href="?page=chi_siamo.php">chi_siamo.php</a></li>
        <li><a href="?page=conferma_prenotazione.php">conferma_prenotazione.php</a></li>
        <li><a href="?page=cookie.php">cookie.php</a></li>
        <li><a href="?page=dettaglio_barca.php">dettaglio_barca.php</a></li>
        <li><a href="?page=dettaglio_esperienza.php">dettaglio_esperienza.php</a></li>
        <li><a href="?page=dettaglio_prenotazione.php">dettaglio_prenotazione.php</a></li>
        <li><a href="?page=faq.php">faq.php</a></li>
        <li><a href="?page=index.php">index.php</a></li>
        <li><a href="?page=login.php">login.php</a></li>
        <li><a href="?page=pagamento.php">pagamento.php</a></li>
        <li><a href="?page=privacy.php">privacy.php</a></li>
        <li><a href="?page=profilo.php">profilo.php</a></li>
        <li><a href="?page=profilo_prenotazioni.php">profilo_prenotazioni.php</a></li>
        <li><a href="?page=profilo_sicurezza.php">profilo_sicurezza.php</a></li>
        <li><a href="?page=registrazione.php">registrazione.php</a></li>
    </ul>
</body>
</html>