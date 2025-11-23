<?php
/**
 * ============================================================================
 * File: includes/utils/db_functions.php
 * Descrizione: Funzioni CRUD con prepared statements per tutte le tabelle
 * Configurazione: Debian 13.1, PHP 8.4.11, MariaDB 11.8.3
 * ============================================================================
 */

require_once __DIR__ . '/../../config/db_connect.php';

// ============================================================================
// SEZIONE: FUNZIONI UTENTE (Tabella: Utente)
// ============================================================================

/**
 * Registra un nuovo utente nel database
 * 
 * @param array $userData - Array contente: Nome, Cognome, CF, Email, Password, IDIndirizzo
 * @return array - ['success' => bool, 'IDUtente' => int, 'error' => string]
 */
function registerUser($userData) {
    try {
        $db = getDB();
        
        // Validazione input lato server
        if (empty($userData['nome']) || empty($userData['cognome']) || 
            empty($userData['cf']) || empty($userData['email']) || 
            empty($userData['password']) || empty($userData['id_indirizzo'])) {
            return [
                'success' => false,
                'error' => 'Tutti i campi obbligatori devono essere compilati'
            ];
        }
        
        // Verifica se email esiste già
        $emailQuery = 'SELECT IDUtente FROM Utente WHERE Email = ?';
        $existingEmail = $db->fetchOne($emailQuery, [$userData['email']]);
        if ($existingEmail) {
            return [
                'success' => false,
                'error' => 'Email già registrata'
            ];
        }
        
        // Verifica se CF esiste già
        $cfQuery = 'SELECT IDUtente FROM Utente WHERE CF = ?';
        $existingCF = $db->fetchOne($cfQuery, [$userData['cf']]);
        if ($existingCF) {
            return [
                'success' => false,
                'error' => 'Codice Fiscale già registrato'
            ];
        }
        
        // Hash della password
        $passwordHash = password_hash($userData['password'], PASSWORD_BCRYPT);
        
        // INSERT nuovo utente
        $insertQuery = '
            INSERT INTO Utente 
            (Nome, Cognome, CF, Email, PasswordHash, Numero_Patente_Nautica, IDIndirizzo, Is_Admin)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ';
        
        $patentNumber = isset($userData['patente']) ? $userData['patente'] : null;
        $params = [
            $userData['nome'],
            $userData['cognome'],
            $userData['cf'],
            $userData['email'],
            $passwordHash,
            $patentNumber,
            $userData['id_indirizzo'],
            0 // Is_Admin = false di default
        ];
        
        $newUserId = $db->insertGetId($insertQuery, $params);
        
        if ($newUserId === false) {
            return [
                'success' => false,
                'error' => 'Errore durante la registrazione'
            ];
        }
        
        return [
            'success' => true,
            'IDUtente' => $newUserId,
            'message' => 'Utente registrato con successo'
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => 'Errore nel sistema: ' . ($GLOBALS['DEBUG_MODE'] ? $e->getMessage() : 'Contattare amministratore')
        ];
    }
}

/**
 * Autentica un utente tramite email e password
 * 
 * @param string $email - Email dell'utente
 * @param string $password - Password in chiaro
 * @return array - ['success' => bool, 'user' => array, 'error' => string]
 */
function authenticateUser($email, $password) {
    try {
        $db = getDB();
        
        // Validazione input
        if (empty($email) || empty($password)) {
            return [
                'success' => false,
                'error' => 'Email e password sono obbligatori'
            ];
        }
        
        // Query per recuperare l'utente
        $query = '
            SELECT IDUtente, Nome, Cognome, Email, PasswordHash, Is_Admin, Attivo
            FROM Utente
            WHERE Email = ? AND Attivo = 1
            LIMIT 1
        ';
        
        $user = $db->fetchOne($query, [$email]);
        
        if (!$user) {
            return [
                'success' => false,
                'error' => 'Email o password non corretti'
            ];
        }
        
        // Verifica password
        if (!password_verify($password, $user['PasswordHash'])) {
            return [
                'success' => false,
                'error' => 'Email o password non corretti'
            ];
        }
        
        // Aggiorna Data_Ultimo_Accesso
        $updateQuery = 'UPDATE Utente SET Data_Ultimo_Accesso = NOW() WHERE IDUtente = ?';
        $db->execute($updateQuery, [$user['IDUtente']]);
        
        // Rimuovi il password hash dal risultato per sicurezza
        unset($user['PasswordHash']);
        
        return [
            'success' => true,
            'user' => $user
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => 'Errore nel sistema di autenticazione'
        ];
    }
}

/**
 * Recupera un utente per ID
 * 
 * @param int $idUtente - ID dell'utente
 * @return array|false
 */
function getUserById($idUtente) {
    try {
        $db = getDB();
        
        $query = '
            SELECT u.*, i.Via, i.N_Civico, i.CAP, i.Citta, i.Provincia, i.Paese
            FROM Utente u
            LEFT JOIN Indirizzo i ON u.IDIndirizzo = i.IDIndirizzo
            WHERE u.IDUtente = ?
            LIMIT 1
        ';
        
        return $db->fetchOne($query, [$idUtente]);
        
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Aggiorna i dati di un utente
 * 
 * @param int $idUtente - ID dell'utente
 * @param array $updateData - Dati da aggiornare
 * @return array - ['success' => bool, 'error' => string]
 */
function updateUser($idUtente, $updateData) {
    try {
        $db = getDB();
        
        // Costruisci dinamicamente la query
        $allowedFields = ['Nome', 'Cognome', 'Email', 'Numero_Patente_Nautica', 'IDIndirizzo'];
        $setClause = [];
        $params = [];
        
        foreach ($updateData as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $setClause[] = "$field = ?";
                $params[] = $value;
            }
        }
        
        if (empty($setClause)) {
            return ['success' => false, 'error' => 'Nessun campo da aggiornare'];
        }
        
        $params[] = $idUtente;
        
        $query = 'UPDATE Utente SET ' . implode(', ', $setClause) . ' WHERE IDUtente = ?';
        
        $rowsAffected = $db->execute($query, $params);
        
        return [
            'success' => $rowsAffected !== false,
            'rowsAffected' => $rowsAffected,
            'error' => $rowsAffected === false ? 'Errore nell\'aggiornamento' : ''
        ];
        
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Errore del sistema'];
    }
}

// ============================================================================
// SEZIONE: FUNZIONI PRODOTTO (Tabella: Prodotto + Prodotto_Traduzione)
// ============================================================================

/**
 * Crea un nuovo prodotto con traduzioni
 * 
 * @param array $productData - Dati prodotto: IDProdotto, Tipo, Prezzo, Posti, ecc.
 * @param array $translations - Array di traduzioni: ['it' => [...], 'en' => [...], ...]
 * @return array - ['success' => bool, 'error' => string]
 */
function createProduct($productData, $translations) {
    try {
        $db = getDB();
        
        // Validazione
        if (empty($productData['IDProdotto']) || empty($productData['Tipo_Prodotto'])) {
            return ['success' => false, 'error' => 'IDProdotto e Tipo_Prodotto obbligatori'];
        }
        
        // Inizio transazione
        $db->beginTransaction();
        
        // Insert prodotto
        $insertQuery = '
            INSERT INTO Prodotto 
            (IDProdotto, Tipo_Prodotto, Prezzo_Base, Posti_Totali, Accessibile_Disabili, 
             Lunghezza_Barca_Metri, Richiede_Patente)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ';
        
        $params = [
            $productData['IDProdotto'],
            $productData['Tipo_Prodotto'],
            $productData['Prezzo_Base'] ?? 0,
            $productData['Posti_Totali'] ?? 1,
            $productData['Accessibile_Disabili'] ?? 0,
            $productData['Lunghezza_Barca_Metri'] ?? null,
            $productData['Richiede_Patente'] ?? null
        ];
        
        $result = $db->execute($insertQuery, $params);
        
        if ($result === false) {
            $db->rollback();
            return ['success' => false, 'error' => 'Errore nella creazione del prodotto'];
        }
        
        // Insert traduzioni
        foreach ($translations as $langCode => $translation) {
            $langQuery = 'SELECT IDLingua FROM Lingua WHERE Codice_Lingua = ?';
            $langResult = $db->fetchOne($langQuery, [$langCode]);
            
            if (!$langResult) {
                $db->rollback();
                return ['success' => false, 'error' => "Lingua $langCode non trovata"];
            }
            
            $transQuery = '
                INSERT INTO Prodotto_Traduzione 
                (IDProdotto, IDLingua, Nome_Prodotto, Descrizione, Specifiche)
                VALUES (?, ?, ?, ?, ?)
            ';
            
            $transParams = [
                $productData['IDProdotto'],
                $langResult['IDLingua'],
                $translation['Nome'] ?? '',
                $translation['Descrizione'] ?? null,
                $translation['Specifiche'] ?? null
            ];
            
            $transResult = $db->execute($transQuery, $transParams);
            
            if ($transResult === false) {
                $db->rollback();
                return ['success' => false, 'error' => "Errore nella traduzione $langCode"];
            }
        }
        
        // Commit transazione
        $db->commit();
        
        return ['success' => true, 'message' => 'Prodotto creato con successo'];
        
    } catch (Exception $e) {
        $db->rollback();
        return ['success' => false, 'error' => 'Errore del sistema'];
    }
}

/**
 * Recupera tutti i prodotti di un tipo specifico
 * 
 * @param string $tipo - 'Noleggio' o 'Experience'
 * @param string $lingua - Codice lingua (es. 'it', 'en')
 * @return array|false
 */
function getProductsByType($tipo, $lingua = 'it') {
    try {
        $db = getDB();
        
        $query = '
            SELECT 
                p.IDProdotto,
                p.Tipo_Prodotto,
                p.Prezzo_Base,
                p.Posti_Totali,
                p.Accessibile_Disabili,
                p.Lunghezza_Barca_Metri,
                p.Richiede_Patente,
                pt.Nome_Prodotto,
                pt.Descrizione
            FROM Prodotto p
            LEFT JOIN Prodotto_Traduzione pt ON p.IDProdotto = pt.IDProdotto
            LEFT JOIN Lingua l ON pt.IDLingua = l.IDLingua
            WHERE p.Tipo_Prodotto = ? AND l.Codice_Lingua = ? AND p.Attivo = 1
            ORDER BY p.Data_Creazione DESC
        ';
        
        return $db->fetchAll($query, [$tipo, $lingua]);
        
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Recupera un prodotto specifico con tutte le traduzioni
 * 
 * @param string $idProdotto - ID del prodotto
 * @return array|false
 */
function getProductById($idProdotto) {
    try {
        $db = getDB();
        
        $query = '
            SELECT 
                p.*,
                pt.Nome_Prodotto,
                pt.Descrizione,
                pt.Specifiche,
                l.Codice_Lingua
            FROM Prodotto p
            LEFT JOIN Prodotto_Traduzione pt ON p.IDProdotto = pt.IDProdotto
            LEFT JOIN Lingua l ON pt.IDLingua = l.IDLingua
            WHERE p.IDProdotto = ? AND p.Attivo = 1
        ';
        
        $results = $db->fetchAll($query, [$idProdotto]);
        
        // Restructura i risultati
        if (empty($results)) {
            return false;
        }
        
        $product = [
            'IDProdotto' => $results[0]['IDProdotto'],
            'Tipo_Prodotto' => $results[0]['Tipo_Prodotto'],
            'Prezzo_Base' => $results[0]['Prezzo_Base'],
            'Posti_Totali' => $results[0]['Posti_Totali'],
            'Accessibile_Disabili' => $results[0]['Accessibile_Disabili'],
            'Lunghezza_Barca_Metri' => $results[0]['Lunghezza_Barca_Metri'],
            'Richiede_Patente' => $results[0]['Richiede_Patente'],
            'traduzioni' => []
        ];
        
        foreach ($results as $row) {
            $product['traduzioni'][$row['Codice_Lingua']] = [
                'Nome' => $row['Nome_Prodotto'],
                'Descrizione' => $row['Descrizione'],
                'Specifiche' => $row['Specifiche']
            ];
        }
        
        return $product;
        
    } catch (Exception $e) {
        return false;
    }
}

// ============================================================================
// SEZIONE: FUNZIONI PRENOTAZIONE (Tabella: Prenotazione)
// ============================================================================

/**
 * Crea una nuova prenotazione
 * 
 * @param array $bookingData - Dati prenotazione
 * @return array - ['success' => bool, 'IDPrenotazione' => int, 'error' => string]
 */
function createBooking($bookingData) {
    try {
        $db = getDB();
        
        // Validazione
        if (empty($bookingData['IDUtente']) || empty($bookingData['IDProdotto']) ||
            empty($bookingData['Data_Ora_Inizio']) || empty($bookingData['Data_Ora_Fine'])) {
            return ['success' => false, 'error' => 'Campi obbligatori mancanti'];
        }
        
        // Verifica disponibilità del prodotto
        $availQuery = '
            SELECT COUNT(*) as count FROM Prenotazione
            WHERE IDProdotto = ? 
            AND Stato_Prenotazione != "Cancellata"
            AND (
                (Data_Ora_Inizio < ? AND Data_Ora_Fine > ?)
            )
        ';
        
        $availability = $db->fetchOne($availQuery, [
            $bookingData['IDProdotto'],
            $bookingData['Data_Ora_Fine'],
            $bookingData['Data_Ora_Inizio']
        ]);
        
        if ($availability['count'] > 0) {
            return ['success' => false, 'error' => 'Prodotto non disponibile in questo periodo'];
        }
        
        // Insert prenotazione
        $insertQuery = '
            INSERT INTO Prenotazione 
            (IDUtente, IDProdotto, Data_Ora_Inizio, Data_Ora_Fine, Skipper_Richiesto,
             Lingua_Guida, Prezzo_Totale, Metodo_Pagamento, Stato_Prenotazione, Note_Addizionali)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ';
        
        $params = [
            $bookingData['IDUtente'],
            $bookingData['IDProdotto'],
            $bookingData['Data_Ora_Inizio'],
            $bookingData['Data_Ora_Fine'],
            $bookingData['Skipper_Richiesto'] ?? 0,
            $bookingData['Lingua_Guida'] ?? null,
            $bookingData['Prezzo_Totale'] ?? 0,
            $bookingData['Metodo_Pagamento'] ?? 'Contanti',
            $bookingData['Stato_Prenotazione'] ?? 'In Attesa',
            $bookingData['Note_Addizionali'] ?? null
        ];
        
        $newBookingId = $db->insertGetId($insertQuery, $params);
        
        if ($newBookingId === false) {
            return ['success' => false, 'error' => 'Errore nella creazione della prenotazione'];
        }
        
        return [
            'success' => true,
            'IDPrenotazione' => $newBookingId,
            'message' => 'Prenotazione creata con successo'
        ];
        
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Errore del sistema'];
    }
}

/**
 * Recupera le prenotazioni di un utente
 * 
 * @param int $idUtente - ID dell'utente
 * @param string $filter - 'future' | 'past' | 'all'
 * @return array|false
 */
function getUserBookings($idUtente, $filter = 'all') {
    try {
        $db = getDB();
        
        $query = '
            SELECT p.*, pt.Nome_Prodotto
            FROM Prenotazione p
            LEFT JOIN Prodotto_Traduzione pt ON p.IDProdotto = pt.IDProdotto
            LEFT JOIN Lingua l ON pt.IDLingua = l.IDLingua
            WHERE p.IDUtente = ? AND l.Codice_Lingua = "it"
        ';
        
        if ($filter === 'future') {
            $query .= ' AND p.Data_Ora_Inizio >= NOW()';
        } elseif ($filter === 'past') {
            $query .= ' AND p.Data_Ora_Fine < NOW()';
        }
        
        $query .= ' ORDER BY p.Data_Ora_Inizio DESC';
        
        return $db->fetchAll($query, [$idUtente]);
        
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Aggiorna lo stato di una prenotazione
 * 
 * @param int $idPrenotazione - ID della prenotazione
 * @param string $newStatus - Nuovo stato
 * @return array - ['success' => bool, 'error' => string]
 */
function updateBookingStatus($idPrenotazione, $newStatus) {
    try {
        $db = getDB();
        
        $validStatus = ['In Attesa', 'Confermata', 'Cancellata'];
        
        if (!in_array($newStatus, $validStatus)) {
            return ['success' => false, 'error' => 'Stato non valido'];
        }
        
        $query = 'UPDATE Prenotazione SET Stato_Prenotazione = ? WHERE IDPrenotazione = ?';
        
        $result = $db->execute($query, [$newStatus, $idPrenotazione]);
        
        return [
            'success' => $result !== false,
            'error' => $result === false ? 'Errore nell\'aggiornamento' : ''
        ];
        
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Errore del sistema'];
    }
}

// ============================================================================
// SEZIONE: FUNZIONI RICERCA E FILTRI
// ============================================================================

/**
 * Ricerca prodotti con filtri
 * 
 * @param array $filters - Filtri di ricerca
 * @param string $lingua - Codice lingua
 * @return array|false
 */
function searchProducts($filters, $lingua = 'it') {
    try {
        $db = getDB();
        
        $query = '
            SELECT DISTINCT
                p.IDProdotto,
                p.Tipo_Prodotto,
                p.Prezzo_Base,
                p.Posti_Totali,
                p.Accessibile_Disabili,
                pt.Nome_Prodotto,
                pt.Descrizione
            FROM Prodotto p
            LEFT JOIN Prodotto_Traduzione pt ON p.IDProdotto = pt.IDProdotto
            LEFT JOIN Lingua l ON pt.IDLingua = l.IDLingua
            WHERE p.Attivo = 1 AND l.Codice_Lingua = ?
        ';
        
        $params = [$lingua];
        
        // Aggiungi filtri dinamicamente
        if (!empty($filters['tipo'])) {
            $query .= ' AND p.Tipo_Prodotto = ?';
            $params[] = $filters['tipo'];
        }
        
        if (!empty($filters['prezzo_max'])) {
            $query .= ' AND p.Prezzo_Base <= ?';
            $params[] = $filters['prezzo_max'];
        }
        
        if (!empty($filters['posti_min'])) {
            $query .= ' AND p.Posti_Totali >= ?';
            $params[] = $filters['posti_min'];
        }
        
        if (!empty($filters['accessibile'])) {
            $query .= ' AND p.Accessibile_Disabili = 1';
        }
        
        $query .= ' ORDER BY p.Prezzo_Base ASC LIMIT 100';
        
        return $db->fetchAll($query, $params);
        
    } catch (Exception $e) {
        return false;
    }
}

// ============================================================================
// SEZIONE: FUNZIONI INDIRIZZI
// ============================================================================

/**
 * Crea un nuovo indirizzo
 * 
 * @param array $addressData - Dati indirizzo
 * @return int|false - ID dell'indirizzo creato
 */
function createAddress($addressData) {
    try {
        $db = getDB();
        
        $query = '
            INSERT INTO Indirizzo (Via, N_Civico, CAP, Citta, Provincia, Paese)
            VALUES (?, ?, ?, ?, ?, ?)
        ';
        
        $params = [
            $addressData['Via'] ?? '',
            $addressData['N_Civico'] ?? '',
            $addressData['CAP'] ?? '',
            $addressData['Citta'] ?? '',
            $addressData['Provincia'] ?? '',
            $addressData['Paese'] ?? 'IT'
        ];
        
        return $db->insertGetId($query, $params);
        
    } catch (Exception $e) {
        return false;
    }
}

// ============================================================================
// FINE FILE db_functions.php
// ============================================================================
?>
