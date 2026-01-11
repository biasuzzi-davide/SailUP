<?php
// Test page per inserire un utente nel database
// ATTENZIONE: Questa pagina è solo per test - rimuovere in produzione

require_once __DIR__ . '/../../includes/db_connection.php';

// Funzione per generare un codice fiscale casuale (solo per test)
function generaCFCasuale() {
    $consonanti = 'BCDFGHJKLMNPQRSTVWXYZ';
    $vocali = 'AEIOU';
    $numeri = '0123456789';
    
    $cf = '';
    // 6 caratteri consonanti/vocali
    for($i = 0; $i < 3; $i++) {
        $cf .= $consonanti[rand(0, strlen($consonanti)-1)];
    }
    for($i = 0; $i < 3; $i++) {
        $cf .= $vocali[rand(0, strlen($vocali)-1)];
    }
    // 2 cifre anno
    $cf .= sprintf('%02d', rand(80, 99));
    // 1 lettera mese
    $cf .= $consonanti[rand(0, strlen($consonanti)-1)];
    // 2 cifre giorno + carattere sesso
    $cf .= sprintf('%02d', rand(1, 31));
    // 4 caratteri comune (1 lettera + 3 cifre)
    $cf .= $consonanti[rand(0, strlen($consonanti)-1)];
    $cf .= sprintf('%03d', rand(100, 999));
    // 1 carattere controllo
    $cf .= $consonanti[rand(0, strlen($consonanti)-1)];
    
    return $cf;
}

echo "<!DOCTYPE html>\n";
echo "<html lang=\"it\">\n";
echo "<head>\n";
echo "    <meta charset=\"UTF-8\">\n";
echo "    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
echo "    <title>Test Inserimento Utente</title>\n";
echo "    <style>\n";
echo "        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }\n";
echo "        .success { color: green; padding: 10px; background: #e8f5e9; border: 1px solid green; margin: 10px 0; border-radius: 5px; }\n";
echo "        .error { color: red; padding: 10px; background: #ffebee; border: 1px solid red; margin: 10px 0; border-radius: 5px; }\n";
echo "        .info { color: blue; padding: 10px; background: #e3f2fd; border: 1px solid blue; margin: 10px 0; border-radius: 5px; }\n";
echo "        h1 { color: #333; }\n";
echo "        .data { background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 10px 0; }\n";
echo "        .data strong { display: inline-block; width: 180px; }\n";
echo "    </style>\n";
echo "</head>\n";
echo "<body>\n";
echo "    <h1>Test Inserimento Utente</h1>\n";

try {
    $db = new DBConnection();
    
    // Dati utente da inserire
    $nome = "Davide";
    $cognome = "Test";
    $cf = generaCFCasuale();
    $email = "davide@gmail.com";
    $password = "Password1!";
    
    // Dati indirizzo casuali
    $via = "Via Roma";
    $civico = rand(1, 200);
    $cap = "80100";
    $citta = "Napoli";
    $provincia = "NA";
    $paese = "IT";
    
    echo "<div class='info'>";
    echo "    <h3>Dati da inserire:</h3>\n";
    echo "    <div class='data'>\n";
    echo "        <strong>Nome:</strong> $nome<br>\n";
    echo "        <strong>Cognome:</strong> $cognome<br>\n";
    echo "        <strong>Email:</strong> $email<br>\n";
    echo "        <strong>Password:</strong> $password<br>\n";
    echo "        <strong>CF:</strong> $cf<br>\n";
    echo "        <strong>Indirizzo:</strong> $via, $civico - $cap $citta ($provincia)<br>\n";
    echo "    </div>\n";
    echo "</div>\n";
    
    // Step 1: Inserisci indirizzo
    echo "<h3>Step 1: Inserimento indirizzo</h3>\n";
    $idIndirizzo = $db->insertIndirizzo($via, $civico, $cap, $citta, $provincia, $paese);
    
    if (!$idIndirizzo) {
        throw new Exception("Errore nell'inserimento dell'indirizzo");
    }
    
    echo "<div class='success'>✓ Indirizzo inserito con successo (ID: $idIndirizzo)</div>\n";
    
    // Step 2: Hash della password
    echo "<h3>Step 2: Hash della password</h3>\n";
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    echo "<div class='info'>Password hashata: " . substr($passwordHash, 0, 50) . "...</div>\n";
    
    // Step 3: Inserisci utente (registerUser gestisce anche il controllo email/CF duplicati)
    echo "<h3>Step 3: Registrazione utente</h3>\n";
    $idUtente = $db->registerUser($nome, $cognome, $cf, $email, $passwordHash, $idIndirizzo, false);
    
    if ($idUtente === -1) {
        echo "<div class='error'>⚠️ ATTENZIONE: Un utente con email '$email' esiste già nel database!</div>\n";
        echo "</body></html>";
        exit;
    } else if ($idUtente === -2) {
        echo "<div class='error'>⚠️ ATTENZIONE: Un utente con CF '$cf' esiste già nel database!</div>\n";
        echo "</body></html>";
        exit;
    } else if (!$idUtente) {
        throw new Exception("Errore nell'inserimento dell'utente");
    }
    
    echo "<div class='success'>✓ Utente registrato con successo (ID: $idUtente)</div>\n";
    
    // Step 4: Verifica inserimento
    echo "<h3>Step 4: Verifica inserimento</h3>\n";
    $utente = $db->getUtenteById($idUtente);
    
    if ($utente) {
        // Prendi anche i dati dell'indirizzo
        $indirizzo = $db->getIndirizzoById($idIndirizzo);
        
        echo "<div class='success'>\n";
        echo "    <h4>✓ Utente trovato nel database!</h4>\n";
        echo "    <div class='data'>\n";
        echo "        <strong>ID Utente:</strong> {$utente['IDUtente']}<br>\n";
        echo "        <strong>Nome:</strong> {$utente['Nome']}<br>\n";
        echo "        <strong>Cognome:</strong> {$utente['Cognome']}<br>\n";
        echo "        <strong>CF:</strong> {$utente['CF']}<br>\n";
        echo "        <strong>Email:</strong> {$utente['Email']}<br>\n";
        if ($indirizzo) {
            echo "        <strong>Indirizzo:</strong> {$indirizzo['Via']}, {$indirizzo['N_Civico']} - {$indirizzo['CAP']} {$indirizzo['Citta']} ({$indirizzo['Provincia']})<br>\n";
        }
        echo "        <strong>Admin:</strong> " . ($utente['Is_Admin'] ? 'Sì' : 'No') . "<br>\n";
        echo "        <strong>Data Registrazione:</strong> {$utente['Data_Registrazione']}<br>\n";
        echo "    </div>\n";
        echo "</div>\n";
    }
    
    // Step 5: Test login
    echo "<h3>Step 5: Test login</h3>\n";
    $loginResult = $db->loginUser($email, $password);
    
    if (is_array($loginResult)) {
        echo "<div class='success'>✓ Login effettuato correttamente!</div>\n";
        echo "<div class='info'>Dati utente dal login: ID {$loginResult['IDUtente']}, {$loginResult['Nome']} {$loginResult['Cognome']}</div>\n";
    } else if ($loginResult === -1) {
        echo "<div class='error'>✗ Utente non trovato</div>\n";
    } else if ($loginResult === 0) {
        echo "<div class='error'>✗ Password errata</div>\n";
    } else {
        echo "<div class='error'>✗ Errore durante il login</div>\n";
    }
    
    echo "<h2 style='color: green; margin-top: 30px;'>🎉 Inserimento completato con successo!</h2>\n";
    echo "<p><strong>Puoi ora effettuare il login con:</strong></p>\n";
    echo "<div class='data'>\n";
    echo "    <strong>Email:</strong> $email<br>\n";
    echo "    <strong>Password:</strong> $password<br>\n";
    echo "</div>\n";
    
} catch (Exception $e) {
    echo "<div class='error'>\n";
    echo "    <h3>❌ Errore durante l'inserimento</h3>\n";
    echo "    <p>" . htmlspecialchars($e->getMessage()) . "</p>\n";
    echo "</div>\n";
}

echo "</body>\n";
echo "</html>\n";
?>
