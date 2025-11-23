<?php
/**
 * ============================================================================
 * Test Rapido Connessione Database - SailUP
 * ============================================================================
 * File: test_connection.php
 * Descrizione: Script di test per verificare la connessione al database
 * Esecuzione: php test_connection.php (da linea di comando)
 *             oppure http://localhost/test_connection.php (da browser)
 * ============================================================================
 */

// Configurazione teste a colori (per output da linea di comando)
$colors = [
    'reset' => "\033[0m",
    'bold' => "\033[1m",
    'green' => "\033[32m",
    'red' => "\033[31m",
    'yellow' => "\033[33m",
    'blue' => "\033[34m"
];

$isTerminal = php_sapi_name() === 'cli';

function printTest($name, $status, $message = '') {
    global $isTerminal, $colors;
    
    if ($isTerminal) {
        $statusColor = $status ? $colors['green'] : $colors['red'];
        $statusText = $status ? '✅ PASS' : '❌ FAIL';
        echo $statusColor . $statusText . $colors['reset'] . " - $name";
        if ($message) {
            echo " ($message)";
        }
        echo "\n";
    } else {
        $statusText = $status ? '✅ PASS' : '❌ FAIL';
        echo "<p><strong>$statusText</strong> - $name";
        if ($message) {
            echo " ($message)";
        }
        echo "</p>";
    }
}

function printSection($title) {
    global $isTerminal, $colors;
    
    if ($isTerminal) {
        echo "\n" . $colors['bold'] . $colors['blue'] . "=== $title ===" . $colors['reset'] . "\n";
    } else {
        echo "<h3>$title</h3>";
    }
}

// ============================================================================
// TEST 1: Verifica ambiente PHP
// ============================================================================

printSection('TEST 1: Ambiente PHP');

// Versione PHP
$phpVersion = phpversion();
$phpVersionOk = version_compare($phpVersion, '8.4.0', '>=');
printTest('Versione PHP >= 8.4', $phpVersionOk, $phpVersion);

// Estensione PDO
$pdoLoaded = extension_loaded('pdo');
printTest('Estensione PDO caricata', $pdoLoaded);

// Estensione PDO MySQL
$pdoMysqlLoaded = extension_loaded('pdo_mysql');
printTest('Estensione PDO MySQL caricata', $pdoMysqlLoaded);

// Funzione password_hash
$passwordHashExists = function_exists('password_hash');
printTest('Funzione password_hash disponibile', $passwordHashExists);

// ============================================================================
// TEST 2: Verifica file di configurazione
// ============================================================================

printSection('TEST 2: File di Configurazione');

// Verifica db_connect.php
$dbConnectPath = dirname(__FILE__) . '/config/db_connect.php';
$dbConnectExists = file_exists($dbConnectPath);
printTest('File db_connect.php esiste', $dbConnectExists, $dbConnectPath);

if ($dbConnectExists) {
    // Verifica che non sia corrotto
    $dbConnectReadable = is_readable($dbConnectPath);
    printTest('File db_connect.php è leggibile', $dbConnectReadable);
}

// Verifica db_functions.php
$dbFunctionsPath = dirname(__FILE__) . '/includes/utils/db_functions.php';
$dbFunctionsExists = file_exists($dbFunctionsPath);
printTest('File db_functions.php esiste', $dbFunctionsExists, $dbFunctionsPath);

// ============================================================================
// TEST 3: Caricamento file
// ============================================================================

printSection('TEST 3: Caricamento File');

if ($dbConnectExists && $pdoLoaded) {
    try {
        require_once $dbConnectPath;
        printTest('File db_connect.php caricato', true);
    } catch (Exception $e) {
        printTest('File db_connect.php caricato', false, $e->getMessage());
    }
} else {
    printTest('File db_connect.php caricato', false, 'Prerequisiti non soddisfatti');
}

if ($dbFunctionsExists && $dbConnectExists) {
    try {
        require_once $dbFunctionsPath;
        printTest('File db_functions.php caricato', true);
    } catch (Exception $e) {
        printTest('File db_functions.php caricato', false, $e->getMessage());
    }
} else {
    printTest('File db_functions.php caricato', false, 'Prerequisiti non soddisfatti');
}

// ============================================================================
// TEST 4: Connessione Database
// ============================================================================

printSection('TEST 4: Connessione Database');

try {
    $db = getDB();
    $dbConnected = true;
    printTest('Connessione database ottenuta', true);
} catch (Exception $e) {
    $dbConnected = false;
    printTest('Connessione database ottenuta', false, $e->getMessage());
}

// ============================================================================
// TEST 5: Test Query
// ============================================================================

if ($dbConnected) {
    printSection('TEST 5: Test Query');
    
    // Query 1: SELECT 1 (test base)
    try {
        $result = $db->testConnection();
        printTest('Query SELECT 1 eseguita', $result);
    } catch (Exception $e) {
        printTest('Query SELECT 1 eseguita', false, $e->getMessage());
    }
    
    // Query 2: Conta utenti
    try {
        $result = $db->fetchOne('SELECT COUNT(*) as count FROM Utente');
        $userCount = $result['count'] ?? -1;
        printTest('Conta utenti', $userCount >= 0, "Totale: $userCount");
    } catch (Exception $e) {
        printTest('Conta utenti', false, $e->getMessage());
    }
    
    // Query 3: Conta prodotti
    try {
        $result = $db->fetchOne('SELECT COUNT(*) as count FROM Prodotto');
        $productCount = $result['count'] ?? -1;
        printTest('Conta prodotti', $productCount >= 0, "Totale: $productCount");
    } catch (Exception $e) {
        printTest('Conta prodotti', false, $e->getMessage());
    }
    
    // Query 4: Conta lingue
    try {
        $result = $db->fetchOne('SELECT COUNT(*) as count FROM Lingua');
        $langCount = $result['count'] ?? -1;
        printTest('Conta lingue', $langCount === 4, "Totale: $langCount (atteso: 4)");
    } catch (Exception $e) {
        printTest('Conta lingue', false, $e->getMessage());
    }
    
    // Query 5: Verifica dati di test
    try {
        $admins = $db->fetchOne('SELECT COUNT(*) as count FROM Utente WHERE Is_Admin = 1');
        $adminCount = $admins['count'] ?? -1;
        printTest('Utente Admin presente', $adminCount >= 1, "Admin trovati: $adminCount");
    } catch (Exception $e) {
        printTest('Utente Admin presente', false, $e->getMessage());
    }
}

// ============================================================================
// TEST 6: Test Prepared Statements
// ============================================================================

if ($dbConnected) {
    printSection('TEST 6: Prepared Statements');
    
    try {
        // Query con placeholder
        $email = 'admin@sailup.it';
        $result = $db->fetchOne('SELECT IDUtente, Email FROM Utente WHERE Email = ?', [$email]);
        
        if ($result && $result['Email'] === $email) {
            printTest('Prepared statement con parametri', true, "Email trovata: $email");
        } else {
            printTest('Prepared statement con parametri', false, "Email non trovata");
        }
    } catch (Exception $e) {
        printTest('Prepared statement con parametri', false, $e->getMessage());
    }
}

// ============================================================================
// TEST 7: Test Funzioni CRUD
// ============================================================================

if ($dbConnected) {
    printSection('TEST 7: Funzioni CRUD');
    
    // Test: authenticateUser
    try {
        $result = authenticateUser('admin@sailup.it', 'admin123');
        printTest('Funzione authenticateUser', $result['success'], $result['success'] ? 'Admin loggato' : $result['error']);
    } catch (Exception $e) {
        printTest('Funzione authenticateUser', false, $e->getMessage());
    }
    
    // Test: getUserById
    try {
        $user = getUserById(1);
        $exists = $user && isset($user['IDUtente']);
        printTest('Funzione getUserById', $exists);
    } catch (Exception $e) {
        printTest('Funzione getUserById', false, $e->getMessage());
    }
    
    // Test: getProductsByType
    try {
        $products = getProductsByType('Noleggio', 'it');
        $hasProducts = is_array($products) && count($products) > 0;
        printTest('Funzione getProductsByType', $hasProducts, count($products) . ' noleggi trovati');
    } catch (Exception $e) {
        printTest('Funzione getProductsByType', false, $e->getMessage());
    }
    
    // Test: searchProducts
    try {
        $results = searchProducts(['prezzo_max' => 300], 'it');
        $success = is_array($results);
        printTest('Funzione searchProducts', $success);
    } catch (Exception $e) {
        printTest('Funzione searchProducts', false, $e->getMessage());
    }
}

// ============================================================================
// RIEPILOGO
// ============================================================================

printSection('RIEPILOGO');

if ($isTerminal) {
    echo "\n" . $colors['bold'] . "Tutti i test sono stati completati!" . $colors['reset'] . "\n";
    echo "Se tutti i test sono passati (✅), il database è pronto per l'uso.\n";
    echo "Se alcuni test sono falliti (❌), controlla il troubleshooting.\n\n";
} else {
    echo "<p><strong>Tutti i test sono stati completati!</strong></p>";
    echo "<p>Se tutti i test sono passati (✅), il database è pronto per l'uso.</p>";
    echo "<p>Se alcuni test sono falliti (❌), controlla il troubleshooting.</p>";
}

?>
