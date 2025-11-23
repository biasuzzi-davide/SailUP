<?php
/**
 * ============================================================================
 * Database Connection Handler - SailUP Project
 * ============================================================================
 * File: config/db_connect.php
 * Descrizione: Gestisce la connessione a MariaDB con PDO per prepared statements
 * Configurazione: Debian 13.1, PHP 8.4.11, MariaDB 11.8.3
 * ============================================================================
 */

// Previeni l'esecuzione diretta di questo file
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    http_response_code(403);
    die('Accesso diretto non consentito');
}

// ============================================================================
// CONFIGURAZIONE DATABASE
// ============================================================================

/**
 * Configurazione del database locale
 * 
 * Host: localhost (o IP del server)
 * Port: 3306 (default MariaDB)
 * Database: dbiasuzz (creato con lo script schema_sailup.sql)
 * Charset: utf8mb4 per supporto completo Unicode (inclusi emoji)
 */
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_NAME', 'dbiasuzz');
define('DB_USER', 'dbiasuzz');  // Cambia con l'utente effettivo
define('DB_PASS', 'Mee8EpahWee5ahth');       // Cambia con la password effettiva

/**
 * Impostazioni di sicurezza e ambiente
 */
define('ENVIRONMENT', 'development'); // 'development' o 'production'
define('DEBUG_MODE', ENVIRONMENT === 'development' ? true : false);

// ============================================================================
// CLASSE: DatabaseConnection
// ============================================================================

class DatabaseConnection {
    private static $instance = null;
    private $connection = null;
    private $error_message = '';
    private $last_query = '';

    /**
     * Singleton: ritorna l'unica istanza della connessione
     * 
     * @return DatabaseConnection
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Costruttore privato: connette a MariaDB tramite PDO
     */
    private function __construct() {
        $this->connect();
    }

    /**
     * Crea la connessione PDO a MariaDB
     * 
     * @return void
     */
    private function connect() {
        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                DB_HOST,
                DB_PORT,
                DB_NAME
            );

            $this->connection = new PDO(
                $dsn,
                DB_USER,
                DB_PASS,
                [
                    // Impostazioni di sicurezza e performance
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                ]
            );

            // Log di connessione riuscita (solo in debug)
            if (DEBUG_MODE) {
                error_log('[DB] Connessione a MariaDB riuscita: ' . DB_HOST);
            }

        } catch (PDOException $e) {
            $this->error_message = 'Errore di connessione al database: ' . $e->getMessage();
            
            if (DEBUG_MODE) {
                error_log('[DB ERROR] ' . $this->error_message);
                die($this->error_message);
            } else {
                // In produzione, mostra messaggio generico
                die('Errore di connessione al database. Contattare l\'amministratore.');
            }
        }
    }

    /**
     * Ritorna la connessione PDO
     * 
     * @return PDO
     */
    public function getConnection() {
        if ($this->connection === null) {
            $this->connect();
        }
        return $this->connection;
    }

    /**
     * Esegue una prepared statement con parametri
     * 
     * @param string $query - Query SQL con placeholder (?)
     * @param array $params - Array di parametri da bindare
     * @return PDOStatement|false
     */
    public function prepare($query, $params = []) {
        try {
            $this->last_query = $query;
            
            $stmt = $this->connection->prepare($query);
            
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    // Determina il tipo di parametro
                    $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
                    // I placeholder in PDO partono da 1, non da 0
                    $stmt->bindValue($key + 1, $value, $type);
                }
            }
            
            $stmt->execute();
            return $stmt;
            
        } catch (PDOException $e) {
            $this->handleQueryError($e, $query);
            return false;
        }
    }

    /**
     * Esegue una query e ritorna tutte le righe
     * 
     * @param string $query - Query SQL
     * @param array $params - Parametri
     * @return array|false
     */
    public function fetchAll($query, $params = []) {
        $stmt = $this->prepare($query, $params);
        
        if ($stmt === false) {
            return false;
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Esegue una query e ritorna una singola riga
     * 
     * @param string $query - Query SQL
     * @param array $params - Parametri
     * @return array|false
     */
    public function fetchOne($query, $params = []) {
        $stmt = $this->prepare($query, $params);
        
        if ($stmt === false) {
            return false;
        }
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Esegue una query e ritorna un singolo valore
     * 
     * @param string $query - Query SQL
     * @param array $params - Parametri
     * @return mixed|false
     */
    public function fetchValue($query, $params = []) {
        $result = $this->fetchOne($query, $params);
        
        if ($result === false || empty($result)) {
            return false;
        }
        
        // Ritorna il primo valore della prima colonna
        return reset($result);
    }

    /**
     * Esegue un INSERT e ritorna l'ID generato
     * 
     * @param string $query - Query INSERT
     * @param array $params - Parametri
     * @return int|false
     */
    public function insertGetId($query, $params = []) {
        $stmt = $this->prepare($query, $params);
        
        if ($stmt === false) {
            return false;
        }
        
        return (int)$this->connection->lastInsertId();
    }

    /**
     * Esegue una query di UPDATE o DELETE
     * 
     * @param string $query - Query SQL
     * @param array $params - Parametri
     * @return int|false - Numero di righe affette
     */
    public function execute($query, $params = []) {
        $stmt = $this->prepare($query, $params);
        
        if ($stmt === false) {
            return false;
        }
        
        return $stmt->rowCount();
    }

    /**
     * Inizia una transazione
     * 
     * @return bool
     */
    public function beginTransaction() {
        try {
            return $this->connection->beginTransaction();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log('[DB ERROR] Errore nell\'iniziare la transazione: ' . $e->getMessage());
            }
            return false;
        }
    }

    /**
     * Conferma una transazione (COMMIT)
     * 
     * @return bool
     */
    public function commit() {
        try {
            return $this->connection->commit();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log('[DB ERROR] Errore nel commit: ' . $e->getMessage());
            }
            return false;
        }
    }

    /**
     * Annulla una transazione (ROLLBACK)
     * 
     * @return bool
     */
    public function rollback() {
        try {
            return $this->connection->rollBack();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log('[DB ERROR] Errore nel rollback: ' . $e->getMessage());
            }
            return false;
        }
    }

    /**
     * Chiude la connessione
     * 
     * @return void
     */
    public function disconnect() {
        $this->connection = null;
    }

    /**
     * Gestisce gli errori di query
     * 
     * @param PDOException $e
     * @param string $query
     * @return void
     */
    private function handleQueryError(PDOException $e, $query = '') {
        $this->error_message = $e->getMessage();
        
        if (DEBUG_MODE) {
            error_log('[DB ERROR] Query: ' . $query);
            error_log('[DB ERROR] ' . $this->error_message);
        }
    }

    /**
     * Ritorna l'ultimo messaggio di errore
     * 
     * @return string
     */
    public function getErrorMessage() {
        return $this->error_message;
    }

    /**
     * Ritorna l'ultima query eseguita
     * 
     * @return string
     */
    public function getLastQuery() {
        return $this->last_query;
    }

    /**
     * Test della connessione
     * 
     * @return bool
     */
    public function testConnection() {
        try {
            $result = $this->fetchOne('SELECT 1');
            return $result !== false;
        } catch (Exception $e) {
            if (DEBUG_MODE) {
                error_log('[DB ERROR] Test di connessione fallito: ' . $e->getMessage());
            }
            return false;
        }
    }
}
// ============================================================================
// HELPER GLOBALE: Accesso facile al database
// ============================================================================

/**
 * Funzione helper per ottenere l'istanza del database
 * 
 * @return DatabaseConnection
 */
function getDB() {
    return DatabaseConnection::getInstance();
}



// ============================================================================
// FINE FILE db_connect.php
// ============================================================================
?>
