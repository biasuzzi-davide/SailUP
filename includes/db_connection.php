<?php
// db_connection.php
// Classe per la gestione centralizzata del DB (schema: dbiasuzz)

require_once __DIR__ . '/../config/conf.php';

class DBConnection {
    private $connection;
    private string $host = DB_HOST;
    private string $user = DB_USER;
    private string $password = DB_PASS;
    private string $database = DB_NAME;

    /**
     * Costruttore: imposta il reporting degli errori.
     */
    public function __construct() {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    }

    /**
     * Apre la connessione al database.
     */
    private function openConnection(): void {
        try {
            $this->connection = new mysqli(
                $this->host,
                $this->user,
                $this->password,
                $this->database
            );

            if ($this->connection->connect_errno) {
                throw new Exception("Errore di connessione: " . $this->connection->connect_error);
            }

            if (!$this->connection->set_charset('utf8mb4')) {
                throw new Exception("Impossibile impostare charset UTF-8: " . $this->connection->error);
            }
        } catch (Throwable $t) {
            // Comportamento: niente dettagli tecnici verso l’utente
            die("Errore di connessione al database.");
        }
    }

    /**
     * Chiude la connessione.
     */
    private function closeConnection(): void {
        if ($this->connection && !$this->connection->connect_errno) {
            $this->connection->close();
        }
    }

    /* ============================================================
       METODI INDIRIZZO
       ============================================================ */

    /**
     * Inserisce un indirizzo e ritorna l'ID generato oppure false.
     */
    public function insertIndirizzo(
        string $via,
        string $nCivico,
        string $cap,
        string $citta,
        string $provincia,
        string $paese = 'IT'
    ) {
        $this->openConnection();
        $query = "INSERT INTO Indirizzo (Via, N_Civico, CAP, Citta, Provincia, Paese)
                  VALUES (?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param(
                "ssssss",
                $via,
                $nCivico,
                $cap,
                $citta,
                $provincia,
                $paese
            );

            if (!$stmt->execute()) {
                $stmt->close();
                $this->closeConnection();
                return false;
            }

            $id = $stmt->insert_id;
            $stmt->close();
            $this->closeConnection();
            return $id;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Restituisce un indirizzo per ID oppure null/false.
     */
    public function getIndirizzoById(int $idIndirizzo) {
        $this->openConnection();
        $query = "SELECT * FROM Indirizzo WHERE IDIndirizzo = ?";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param("i", $idIndirizzo);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $stmt->close();
                $this->closeConnection();
                return null;
            }

            $row = $result->fetch_assoc();
            $stmt->close();
            $this->closeConnection();
            return $row;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * update di un indirizzo esistente
     */
    public function updateIndirizzo(
        int $idIndirizzo,
        string $via,
        string $nCivico,
        string $cap,
        string $citta,
        string $provincia,
        string $paese = 'IT'
    ): bool {
        $this->openConnection();
        $query = "UPDATE Indirizzo
                  SET Via = ?, N_Civico = ?, CAP = ?, Citta = ?, Provincia = ?, Paese = ?
                  WHERE IDIndirizzo = ?";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param(
                "ssssssi",
                $via,
                $nCivico,
                $cap,
                $citta,
                $provincia,
                $paese,
                $idIndirizzo
            );

            $ok = $stmt->execute();
            $stmt->close();
            $this->closeConnection();
            return $ok;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /* ============================================================
       METODI UTENTE
       ============================================================ */

    /**
     * Registra un nuovo utente.
     * Ritorna:
     *  - -1 se email già presente
     *  - -2 se CF già presente
     *  - IDUtente se inserimento ok
     *  -  0 / false in caso di errore generico
     */
    public function registerUser(
        string $nome,
        string $cognome,
        string $cf,
        string $email,
        string $passwordHash,
        ?string $numeroPatente,
        int $idIndirizzo,
        bool $isAdmin = false
    ) {
        $this->openConnection();
        try {
            // Controllo email duplicata
            $qEmail = "SELECT IDUtente FROM Utente WHERE Email = ?";
            $stmt = $this->connection->prepare($qEmail);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows > 0) {
                $stmt->close();
                $this->closeConnection();
                return -1;
            }
            $stmt->close();

            // Controllo CF duplicato
            $qCF = "SELECT IDUtente FROM Utente WHERE CF = ?";
            $stmt = $this->connection->prepare($qCF);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }
            $stmt->bind_param("s", $cf);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows > 0) {
                $stmt->close();
                $this->closeConnection();
                return -2;
            }
            $stmt->close();

            // Insert utente
            $query = "INSERT INTO Utente 
                        (Nome, Cognome, CF, Email, PasswordHash, Numero_Patente_Nautica, IDIndirizzo, Is_Admin)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $isAdminInt = $isAdmin ? 1 : 0;

            $stmt->bind_param(
                "ssssssii",
                $nome,
                $cognome,
                $cf,
                $email,
                $passwordHash,
                $numeroPatente,
                $idIndirizzo,
                $isAdminInt
            );

            if (!$stmt->execute()) {
                $stmt->close();
                $this->closeConnection();
                return 0;
            }

            $id = $stmt->insert_id;
            $stmt->close();
            $this->closeConnection();
            return $id;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Login utente tramite email.
     * Ritorna:
     *  - array associativo utente se password ok
     *  - -1 se utente non trovato
     *  -  0 se password errata
     *  - false in caso di errore
     */
    public function loginUser(string $email, string $password) {
        $this->openConnection();
        $query = "SELECT * FROM Utente WHERE Email = ? AND Attivo = 1";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            if (!$user) {
                $this->closeConnection();
                return -1;
            }

            if (password_verify($password, $user['PasswordHash'])) {
                $this->closeConnection();
                return $user;
            }

            $this->closeConnection();
            return 0;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     *recupera un utente per id
     *ritorna array se trovato, null se mancante,false in caso di errore db
     */
    public function getUtenteById(int $idUtente): array|null|bool {
        $this->openConnection();
        $query = "
            SELECT IDUtente, Nome, Cognome, Email, Is_Admin, Attivo, Data_Registrazione
            FROM Utente
            WHERE IDUtente = ?
            LIMIT 1
        ";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param('i', $idUtente);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            $this->closeConnection();

            if (!$row) {
                return null;
            }

            return $row;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * stats da visualizzare relative agli utenti nella pagina admin_utenti
     */
    public function getUserStats(): array|bool {
        $this->openConnection();
        $query = "
            SELECT
                (SELECT COUNT(*) FROM Utente) AS total_users,
                (SELECT COUNT(*) FROM Utente WHERE Is_Admin = 1) AS admin_users,
                (SELECT COUNT(*) FROM Utente WHERE Is_Admin = 0) AS standard_users,
                (SELECT COUNT(*) FROM Utente
                 WHERE YEAR(Data_Registrazione) = YEAR(CURRENT_DATE())
                   AND MONTH(Data_Registrazione) = MONTH(CURRENT_DATE())
                ) AS new_this_month
        ";

        try {
            $result = $this->connection->query($query);
            if (!$result) {
                $this->closeConnection();
                return false;
            }
            $row = $result->fetch_assoc();
            $result->free();
            $this->closeConnection();
            return $row ?: [];
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Aggiorna i dati base utente (nome, cognome, CF, email).
     * Ritorna true se ok, -1 se email già usata da altro utente, -2 se CF già usato, false in caso di errore.
     */
    public function updateUserProfile(
        int $idUtente,
        string $nome,
        string $cognome,
        string $cf,
        string $email
    ) {
        $this->openConnection();
        try {
            // Controllo email duplicata su altri utenti
            $qEmail = "SELECT IDUtente FROM Utente WHERE Email = ? AND IDUtente <> ?";
            $stmt = $this->connection->prepare($qEmail);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }
            $stmt->bind_param("si", $email, $idUtente);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows > 0) {
                $stmt->close();
                $this->closeConnection();
                return -1;
            }
            $stmt->close();

            // Controllo CF duplicato su altri utenti
            $qCF = "SELECT IDUtente FROM Utente WHERE CF = ? AND IDUtente <> ?";
            $stmt = $this->connection->prepare($qCF);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }
            $stmt->bind_param("si", $cf, $idUtente);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows > 0) {
                $stmt->close();
                $this->closeConnection();
                return -2;
            }
            $stmt->close();

            // Update utente
            $query = "UPDATE Utente
                      SET Nome = ?, Cognome = ?, CF = ?, Email = ?
                      WHERE IDUtente = ?";
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param("ssssi", $nome, $cognome, $cf, $email, $idUtente);
            $ok = $stmt->execute();
            $stmt->close();
            $this->closeConnection();
            return $ok;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * aggiorna la password di un utente
     */
    public function updateUserPassword(int $idUtente, string $newPasswordHash): bool {
        $this->openConnection();
        $query = "UPDATE Utente SET PasswordHash = ? WHERE IDUtente = ?";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param('si', $newPasswordHash, $idUtente);
            $ok = $stmt->execute();
            $stmt->close();
            $this->closeConnection();
            return $ok;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * update del ruolo di un user
     */
    public function setUserRole(int $idUtente, bool $isAdmin): bool {
        $this->openConnection();
        $query = "UPDATE Utente SET Is_Admin = ? WHERE IDUtente = ?";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $flag = $isAdmin ? 1 : 0;
            $stmt->bind_param('ii', $flag, $idUtente);
            $ok = $stmt->execute();
            $stmt->close();
            $this->closeConnection();
            return $ok;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Attiva/disattiva un uten
     */
    public function setUserStatus(int $idUtente, bool $attivo): bool {
        $this->openConnection();
        $query = "UPDATE Utente SET Attivo = ? WHERE IDUtente = ?";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $flag = $attivo ? 1 : 0;
            $stmt->bind_param('ii', $flag, $idUtente);
            $ok = $stmt->execute();
            $stmt->close();
            $this->closeConnection();
            return $ok;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * restituisce tutti gli utenti (serve per l admin)
     */
    public function getUtenti(): array|bool {
        $this->openConnection();
        $query = "SELECT IDUtente, Nome, Cognome, Email, Data_Registrazione, Is_Admin, Attivo FROM Utente ORDER BY Data_Registrazione DESC";

        try {
            $result = $this->connection->query($query);
            if (!$result) {
                $this->closeConnection();
                return false;
            }
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $result->free();
            $this->closeConnection();
            return $rows;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * ricerca utentie con filtro e paginazione
     */
    public function searchUtenti(
        ?string $term = null,
        ?string $ruolo = null,
        ?string $stato = null,
        int $limit = 20,
        int $offset = 0
    ): array|bool {
        $this->openConnection();

        $conditions = [];
        $params = [];
        $types = '';

        if ($term !== null && trim($term) !== '') {
            $like = '%' . trim($term) . '%';
            $conditions[] = '(Nome LIKE ? OR Cognome LIKE ? OR Email LIKE ?)';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $types .= 'sss';
        }

        if ($ruolo === 'admin') {
            $conditions[] = 'Is_Admin = 1';
        } elseif ($ruolo === 'standard') {
            $conditions[] = 'Is_Admin = 0';
        }

        if ($stato === 'attivi') {
            $conditions[] = 'Attivo = 1';
        } elseif ($stato === 'disattivi') {
            $conditions[] = 'Attivo = 0';
        }

        $query = "SELECT SQL_CALC_FOUND_ROWS IDUtente, Nome, Cognome, Email, Data_Registrazione, Is_Admin, Attivo
                  FROM Utente";

        if (!empty($conditions)) {
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $query .= ' ORDER BY Data_Registrazione DESC LIMIT ? OFFSET ?';
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            $stmt->close();

            $countResult = $this->connection->query("SELECT FOUND_ROWS() AS total");
            $total = 0;
            if ($countResult) {
                $totalRow = $countResult->fetch_assoc();
                $total = (int) ($totalRow['total'] ?? 0);
                $countResult->free();
            }

            $this->closeConnection();
            return ['data' => $rows, 'total' => $total];
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Aggiorna la data dell'ultimo accesso.
     */
    public function aggiornaUltimoAccesso(int $idUtente): bool {
        $this->openConnection();
        $query = "UPDATE Utente SET Data_Ultimo_Accesso = NOW() WHERE IDUtente = ?";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param("i", $idUtente);
            $ok = $stmt->execute();
            $stmt->close();
            $this->closeConnection();
            return $ok;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /* ============================================================
       METODI PRODOTTO (Noleggio / Experience)
       ============================================================ */

    /**
     * crea nuovo prodotto 
     */
    public function creaProdotto(array $data): bool {
        $this->openConnection();
        $query = "
            INSERT INTO Prodotto (
                IDProdotto, Tipo_Prodotto, Tipologia_Prodotto, Durata_Ore,
                Nome_Prodotto, Descrizione_Breve, Descrizione, Prezzo_Base,
                Posti_Totali, Accessibile_Disabili, Lunghezza_Barca_Metri,
                Richiede_Patente, Attivo
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $durata = $data['Durata_Ore'] ?? null;
            if ($durata !== null) {
                $durata = (int) $durata;
            }

            $stmt->bind_param(
                'sssisssdiidii',
                $data['IDProdotto'],
                $data['Tipo_Prodotto'],
                $data['Tipologia_Prodotto'],
                $durata,
                $data['Nome_Prodotto'],
                $data['Descrizione_Breve'],
                $data['Descrizione'],
                $data['Prezzo_Base'],
                $data['Posti_Totali'],
                $data['Accessibile_Disabili'],
                $data['Lunghezza_Barca_Metri'],
                $data['Richiede_Patente'],
                $data['Attivo']
            );

            $ok = $stmt->execute();
            $stmt->close();
            $this->closeConnection();
            return $ok;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * aggiorna un prodotto esistente
     */
    public function updateProdotto(string $idProdotto, array $data): bool {
        $this->openConnection();
        $query = "
            UPDATE Prodotto
            SET Tipo_Prodotto = ?, Tipologia_Prodotto = ?, Durata_Ore = ?, Nome_Prodotto = ?,
                Descrizione_Breve = ?, Descrizione = ?, Prezzo_Base = ?, Posti_Totali = ?,
                Accessibile_Disabili = ?, Lunghezza_Barca_Metri = ?, Richiede_Patente = ?, Attivo = ?
            WHERE IDProdotto = ?
        ";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $durata = $data['Durata_Ore'] ?? null;
            if ($durata !== null) {
                $durata = (int) $durata;
            }

            $stmt->bind_param(
                'ssisssdiidiis',
                $data['Tipo_Prodotto'],
                $data['Tipologia_Prodotto'],
                $durata,
                $data['Nome_Prodotto'],
                $data['Descrizione_Breve'],
                $data['Descrizione'],
                $data['Prezzo_Base'],
                $data['Posti_Totali'],
                $data['Accessibile_Disabili'],
                $data['Lunghezza_Barca_Metri'],
                $data['Richiede_Patente'],
                $data['Attivo'],
                $idProdotto
            );

            $ok = $stmt->execute();
            $stmt->close();
            $this->closeConnection();
            return $ok;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * aggiorna stato prodotto
     */
    public function setProdottoStatus(string $idProdotto, bool $attivo): bool {
        $this->openConnection();
        $query = "UPDATE Prodotto SET Attivo = ? WHERE IDProdotto = ?";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $flag = $attivo ? 1 : 0;
            $stmt->bind_param('is', $flag, $idProdotto);
            $ok = $stmt->execute();
            $stmt->close();
            $this->closeConnection();
            return $ok;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * recupera prodotti anche non attivi, con filtri base e paginazione
     */
    public function getProdottiAdmin(
        ?string $term = null,
        ?string $tipo = null,
        ?string $stato = null,
        int $limit = 20,
        int $offset = 0
    ): array|bool {
        $this->openConnection();

        $conditions = [];
        $params = [];
        $types = '';

        if ($term !== null && trim($term) !== '') {
            $like = '%' . trim($term) . '%';
            $conditions[] = '(Nome_Prodotto LIKE ? OR IDProdotto LIKE ?)';
            $params[] = $like;
            $params[] = $like;
            $types .= 'ss';
        }

        if ($tipo !== null && $tipo !== '') {
            $conditions[] = 'Tipo_Prodotto = ?';
            $params[] = $tipo;
            $types .= 's';
        }

        if ($stato === 'attivi') {
            $conditions[] = 'Attivo = 1';
        } elseif ($stato === 'disattivi') {
            $conditions[] = 'Attivo = 0';
        }

        $query = "SELECT SQL_CALC_FOUND_ROWS *
                  FROM Prodotto";

        if (!empty($conditions)) {
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $query .= ' ORDER BY Data_Creazione DESC LIMIT ? OFFSET ?';
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $stmt->close();

            $countResult = $this->connection->query("SELECT FOUND_ROWS() AS total");
            $total = 0;
            if ($countResult) {
                $totalRow = $countResult->fetch_assoc();
                $total = (int) ($totalRow['total'] ?? 0);
                $countResult->free();
            }

            $this->closeConnection();
            return ['data' => $rows, 'total' => $total];
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * modifica/setta lingue prodotto
     */
    public function setLingueProdotto(string $idProdotto, array $codici): bool {
        $this->openConnection();
        $this->connection->begin_transaction();

        try {
            $del = $this->connection->prepare("DELETE FROM Prodotto_Lingua WHERE IDProdotto = ?");
            if (!$del) {
                $this->connection->rollback();
                $this->closeConnection();
                return false;
            }
            $del->bind_param('s', $idProdotto);
            $del->execute();
            $del->close();

            if (!empty($codici)) {
                $stmt = $this->connection->prepare("
                    INSERT INTO Prodotto_Lingua (IDProdotto, IDLingua)
                    SELECT ?, IDLingua FROM Lingua WHERE Codice = ? AND Attivo = 1
                ");
                if (!$stmt) {
                    $this->connection->rollback();
                    $this->closeConnection();
                    return false;
                }

                foreach ($codici as $codice) {
                    $code = trim($codice);
                    if ($code === '') continue;
                    $stmt->bind_param('ss', $idProdotto, $code);
                    $stmt->execute();
                }
                $stmt->close();
            }

            $this->connection->commit();
            $this->closeConnection();
            return true;
        } catch (Throwable $t) {
            $this->connection->rollback();
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Sostituisce la media principale del prodotto
     */
    public function upsertMediaProdotto(string $idProdotto, string $url, string $alt): bool {
        $this->openConnection();
        $this->connection->begin_transaction();

        try {
            $del = $this->connection->prepare("DELETE FROM Media WHERE IDProdotto = ?");
            if (!$del) {
                $this->connection->rollback();
                $this->closeConnection();
                return false;
            }
            $del->bind_param('s', $idProdotto);
            $del->execute();
            $del->close();

            $stmt = $this->connection->prepare("
                INSERT INTO Media (URL_Media, Testo_Alternativo, Tipo_Media, IDProdotto)
                VALUES (?, ?, 'Immagine', ?)
            ");
            if (!$stmt) {
                $this->connection->rollback();
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param('sss', $url, $alt, $idProdotto);
            $ok = $stmt->execute();
            $stmt->close();

            if ($ok) {
                $this->connection->commit();
            } else {
                $this->connection->rollback();
            }

            $this->closeConnection();
            return $ok;
        } catch (Throwable $t) {
            $this->connection->rollback();
            $this->closeConnection();
            return false;
        }
    }

    /**
     * sostituisce gli inclusi del prodotto
     */
    public function setInclusiProdotto(string $idProdotto, array $inclusi): bool {
        $this->openConnection();
        $this->connection->begin_transaction();

        try {
            $del = $this->connection->prepare("DELETE FROM Prodotto_Incluso WHERE IDProdotto = ?");
            if (!$del) {
                $this->connection->rollback();
                $this->closeConnection();
                return false;
            }
            $del->bind_param('s', $idProdotto);
            $del->execute();
            $del->close();

            if (!empty($inclusi)) {
                $stmt = $this->connection->prepare("
                    INSERT INTO Prodotto_Incluso (IDProdotto, Nome_Incluso)
                    VALUES (?, ?)
                ");
                if (!$stmt) {
                    $this->connection->rollback();
                    $this->closeConnection();
                    return false;
                }

                foreach ($inclusi as $inc) {
                    $val = trim($inc);
                    if ($val === '') continue;
                    $stmt->bind_param('ss', $idProdotto, $val);
                    $stmt->execute();
                }
                $stmt->close();
            }

            $this->connection->commit();
            $this->closeConnection();
            return true;
        } catch (Throwable $t) {
            $this->connection->rollback();
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Rrestituisce un prodotto per per id
     */
    public function getProdottoById(string $idProdotto) {
        $this->openConnection();
        $query = "SELECT * FROM Prodotto WHERE IDProdotto = ? AND Attivo = 1";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param("s", $idProdotto);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $stmt->close();
                $this->closeConnection();
                return null;
            }

            $row = $result->fetch_assoc();
            $stmt->close();
            $this->closeConnection();
            return $row;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Recupera un singolo prodotto con la prima immagine disponibile.
     */
    public function getProdottoWithMediaById(string $idProdotto): array|bool|null {
        $this->openConnection();
        $query = "
            SELECT p.*, m.URL_Media, m.Testo_Alternativo
            FROM Prodotto p
            LEFT JOIN Media m ON p.IDProdotto = m.IDProdotto
            WHERE p.IDProdotto = ? AND p.Attivo = 1
            ORDER BY m.IDMedia ASC
            LIMIT 1
        ";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param('s', $idProdotto);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if (!$result) {
                $this->closeConnection();
                return false;
            }

            if ($result->num_rows === 0) {
                $this->closeConnection();
                return null;
            }

            $row = $result->fetch_assoc();
            $result->free();
            $this->closeConnection();
            return $row;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * recupera prodotto per l'area admin (anche non attivo)
     */
    public function getProdottoAdminById(string $idProdotto): array|bool|null {
        $this->openConnection();
        $query = "
            SELECT p.*, m.URL_Media, m.Testo_Alternativo
            FROM Prodotto p
            LEFT JOIN Media m ON p.IDProdotto = m.IDProdotto
            WHERE p.IDProdotto = ?
            LIMIT 1
        ";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param('s', $idProdotto);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if (!$result) {
                $this->closeConnection();
                return false;
            }

            if ($result->num_rows === 0) {
                $this->closeConnection();
                return null;
            }

            $row = $result->fetch_assoc();
            $result->free();
            $this->closeConnection();
            return $row;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Recupera i nomi degli elementi inclusi per un prodotto.
     */
    public function getProdottoInclusi(string $idProdotto): array|bool {
        $this->openConnection();
        $query = "
            SELECT Nome_Incluso
            FROM Prodotto_Incluso
            WHERE IDProdotto = ?
            ORDER BY Nome_Incluso ASC
        ";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param('s', $idProdotto);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if (!$result) {
                $this->closeConnection();
                return false;
            }

            if ($result->num_rows === 0) {
                $this->closeConnection();
                return [];
            }

            $inclusi = [];
            while ($row = $result->fetch_assoc()) {
                $inclusi[] = $row;
            }

            $result->free();
            $this->closeConnection();
            return $inclusi;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Recupera gli extra associati a un prodotto.
     */
    public function getProdottoExtra(string $idProdotto): array|bool {
        $this->openConnection();
        $query = "
            SELECT IDExtra, Nome_Extra, Prezzo_Extra, Descrizione_Extra, Opzionale
            FROM Prodotto_Extra
            WHERE IDProdotto = ?
            ORDER BY Opzionale DESC, Nome_Extra ASC
        ";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param('s', $idProdotto);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if (!$result) {
                $this->closeConnection();
                return false;
            }

            if ($result->num_rows === 0) {
                $this->closeConnection();
                return [];
            }

            $extras = [];
            while ($row = $result->fetch_assoc()) {
                $extras[] = $row;
            }

            $result->free();
            $this->closeConnection();
            return $extras;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Restituisce tutti i prodotti attivi, eventualmente filtrati per tipo.
     */
    public function getProdotti(?string $tipoProdotto = null): array|bool {
        $this->openConnection();
        try {
            if ($tipoProdotto === null) {
                $query = "SELECT * FROM Prodotto WHERE Attivo = 1";
                $result = $this->connection->query($query);
            } else {
                $query = "SELECT * FROM Prodotto WHERE Attivo = 1 AND Tipo_Prodotto = ?";
                $stmt = $this->connection->prepare($query);
                if (!$stmt) {
                    $this->closeConnection();
                    return false;
                }
                $stmt->bind_param("s", $tipoProdotto);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
            }

            if (!$result) {
                $this->closeConnection();
                return false;
            }

            if ($result->num_rows === 0) {
                $this->closeConnection();
                return [];
            }

            $prodotti = [];
            while ($row = $result->fetch_assoc()) {
                $prodotti[] = $row;
            }

            $result->free();
            $this->closeConnection();
            return $prodotti;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Restituisce le tipologie disponibili per un determinato tipo di prodotto (es. Noleggio/Experience).
     */
    public function getTipologieByTipo(string $tipoProdotto): array|bool {
        $this->openConnection();
        $query = "
            SELECT DISTINCT Tipologia_Prodotto
            FROM Prodotto
            WHERE Attivo = 1 AND Tipo_Prodotto = ? AND Tipologia_Prodotto IS NOT NULL AND TRIM(Tipologia_Prodotto) <> ''
            ORDER BY Tipologia_Prodotto ASC
        ";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param('s', $tipoProdotto);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if (!$result) {
                $this->closeConnection();
                return false;
            }

            if ($result->num_rows === 0) {
                $this->closeConnection();
                return [];
            }

            $tipologie = [];
            while ($row = $result->fetch_assoc()) {
                $tipologie[] = $row['Tipologia_Prodotto'];
            }

            $result->free();
            $this->closeConnection();
            return $tipologie;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Recupera le lingue attivate associate a un prodotto specifico.
     */
    public function getLinguePerProdotto(string $idProdotto): array|bool {
        $this->openConnection();
        $query = "
            SELECT l.Codice, l.Nome
            FROM Lingua l
            INNER JOIN Prodotto_Lingua pl ON pl.IDLingua = l.IDLingua
            WHERE pl.IDProdotto = ? AND l.Attivo = 1
            ORDER BY l.Nome ASC
        ";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param('s', $idProdotto);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if (!$result) {
                $this->closeConnection();
                return false;
            }

            if ($result->num_rows === 0) {
                $this->closeConnection();
                return [];
            }

            $lingue = [];
            while ($row = $result->fetch_assoc()) {
                $lingue[] = $row;
            }

            $result->free();
            $this->closeConnection();
            return $lingue;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Restituisce le lingue attive collegate a prodotti, filtrando opzionalmente per tipo.
     */
    public function getLingueDisponibiliPerTipo(?string $tipoProdotto = null): array|bool {
        $this->openConnection();
        $query = "
            SELECT DISTINCT l.Codice, l.Nome
            FROM Lingua l
            INNER JOIN Prodotto_Lingua pl ON pl.IDLingua = l.IDLingua
            INNER JOIN Prodotto p ON p.IDProdotto = pl.IDProdotto
            WHERE l.Attivo = 1 AND p.Attivo = 1
        ";

        $params = [];
        $types = '';

        if ($tipoProdotto !== null) {
            $query .= " AND p.Tipo_Prodotto = ?";
            $params[] = $tipoProdotto;
            $types .= 's';
        }

        $query .= ' ORDER BY l.Nome ASC';

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if (!$result) {
                $this->closeConnection();
                return false;
            }

            if ($result->num_rows === 0) {
                $this->closeConnection();
                return [];
            }

            $lingue = [];
            while ($row = $result->fetch_assoc()) {
                $lingue[] = $row;
            }

            $result->free();
            $this->closeConnection();
            return $lingue;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /* ============================================================
       METODI PRENOTAZIONE
       ============================================================ */

    /**
     * Crea una prenotazione (senza logica di disponibilità).
     * Ritorna IDPrenotazione oppure false.
     */
    public function creaPrenotazione(
        int $idUtente,
        string $idProdotto,
        string $dataOraInizio,
        string $dataOraFine,
        bool $skipperRichiesto,
        float $prezzoTotale,
        string $metodoPagamento,
        ?string $note
    ) {
        $this->openConnection();
        $query = "INSERT INTO Prenotazione 
                    (IDUtente, IDProdotto, Data_Ora_Inizio, Data_Ora_Fine, 
                     Skipper_Richiesto, Prezzo_Totale, Metodo_Pagamento, Note_Addizionali)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $skipperInt = $skipperRichiesto ? 1 : 0;

            $stmt->bind_param(
                "isssidss",
                $idUtente,
                $idProdotto,
                $dataOraInizio,
                $dataOraFine,
                $skipperInt,
                $prezzoTotale,
                $metodoPagamento,
                $note
            );

            if (!$stmt->execute()) {
                $stmt->close();
                $this->closeConnection();
                return false;
            }

            $id = $stmt->insert_id;
            $stmt->close();
            $this->closeConnection();
            return $id;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Restituisce le prenotazioni di un utente ordinate per data.
     */
    public function getPrenotazioniUtente(int $idUtente): array|bool {
        $this->openConnection();
        $query = "SELECT * 
                  FROM Prenotazione 
                  WHERE IDUtente = ?
                  ORDER BY Data_Ora_Inizio DESC";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param("i", $idUtente);
            $stmt->execute();
            $result = $stmt->get_result();

            $prenotazioni = [];
            while ($row = $result->fetch_assoc()) {
                $prenotazioni[] = $row;
            }

            $stmt->close();
            $this->closeConnection();
            return $prenotazioni;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * recupera tutte le prenotazioni con dati utente 
     */
    public function getPrenotazioni(): array|bool {
        $this->openConnection();
        $query = "
            SELECT pr.*, u.Nome AS Utente_Nome, u.Cognome AS Utente_Cognome, u.Email AS Utente_Email
            FROM Prenotazione pr
            JOIN Utente u ON u.IDUtente = pr.IDUtente
            ORDER BY pr.Data_Ora_Inizio DESC
        ";

        try {
            $result = $this->connection->query($query);
            if (!$result) {
                $this->closeConnection();
                return false;
            }

            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            $result->free();
            $this->closeConnection();
            return $rows;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * aggiorna stato e note prenotazione
     */
    public function updatePrenotazioneStato(int $idPrenotazione, string $stato, ?string $note = null): bool {
        $this->openConnection();

        $allowed = ['In Attesa', 'Confermata', 'Cancellata'];
        if (!in_array($stato, $allowed, true)) {
            $this->closeConnection();
            return false;
        }

        $query = "UPDATE Prenotazione SET Stato_Prenotazione = ?, Note_Addizionali = ? WHERE IDPrenotazione = ?";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param('ssi', $stato, $note, $idPrenotazione);
            $ok = $stmt->execute();
            $stmt->close();
            $this->closeConnection();
            return $ok;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * recupera  prenotazione con dati utente e prodotto
     */
    public function getPrenotazioneById(int $idPrenotazione): array|bool|null {
        $this->openConnection();
        $query = "
            SELECT pr.*,
                   u.Nome AS Utente_Nome, u.Cognome AS Utente_Cognome, u.Email AS Utente_Email,
                   p.Nome_Prodotto, p.Tipo_Prodotto, p.Tipologia_Prodotto, p.Prezzo_Base,
                   m.URL_Media, m.Testo_Alternativo
            FROM Prenotazione pr
            JOIN Utente u ON u.IDUtente = pr.IDUtente
            JOIN Prodotto p ON p.IDProdotto = pr.IDProdotto
            LEFT JOIN Media m ON m.IDProdotto = p.IDProdotto
            WHERE pr.IDPrenotazione = ?
            LIMIT 1
        ";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }
            $stmt->bind_param('i', $idPrenotazione);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            $this->closeConnection();
            if (!$row) {
                return null;
            }
            return $row;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Statistiche rapide per dashboard admin.
     */
    public function getAdminStats(): array|bool {
        $this->openConnection();
        $query = "
            SELECT
                (SELECT COUNT(*) FROM Utente) AS total_users,
                (SELECT COUNT(*) FROM Prodotto WHERE Attivo = 1) AS active_products,
                (SELECT COUNT(*) FROM Prenotazione WHERE Stato_Prenotazione IN ('In Attesa', 'Confermata')) AS active_bookings,
                (SELECT COALESCE(SUM(Prezzo_Totale), 0)
                 FROM Prenotazione
                 WHERE Stato_Prenotazione = 'Confermata'
                   AND YEAR(Data_Creazione) = YEAR(CURRENT_DATE())
                   AND MONTH(Data_Creazione) = MONTH(CURRENT_DATE())
                ) AS monthly_revenue
        ";

        try {
            $result = $this->connection->query($query);
            if (!$result) {
                $this->closeConnection();
                return false;
            }

            $row = $result->fetch_assoc();
            $result->free();
            $this->closeConnection();
            return $row ?: [];
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * stats finanziarie prenotazioni (incassi e pending)
     */
    public function getBookingFinanceStats(): array|bool {
        $this->openConnection();
        $query = "
            SELECT
                (SELECT COALESCE(SUM(Prezzo_Totale), 0)
                 FROM Prenotazione
                 WHERE Stato_Prenotazione = 'Confermata'
                   AND YEAR(Data_Creazione) = YEAR(CURRENT_DATE())
                   AND MONTH(Data_Creazione) = MONTH(CURRENT_DATE())
                ) AS revenue_month,
                (SELECT COALESCE(SUM(Prezzo_Totale), 0)
                 FROM Prenotazione
                 WHERE Stato_Prenotazione = 'In Attesa'
                ) AS revenue_pending,
                (SELECT COALESCE(SUM(Prezzo_Totale), 0)
                 FROM Prenotazione
                 WHERE Stato_Prenotazione = 'Confermata'
                   AND YEAR(Data_Creazione) = YEAR(CURRENT_DATE())
                ) AS revenue_year
        ";

        try {
            $result = $this->connection->query($query);
            if (!$result) {
                $this->closeConnection();
                return false;
            }
            $row = $result->fetch_assoc();
            $result->free();
            $this->closeConnection();
            return $row ?: [];
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Recupera prodotti con media associata, limitati per tipo e numero.
     */
    public function getProdottiWithMedia(
        ?string $tipoProdotto = null,
        int $limit = 100,
        array $filters = [],
        ?string $sort = null
    ): array|bool {
        $this->openConnection();
        try {
            if ($limit <= 0) {
                $limit = 100;
            }

            $query = "
                SELECT p.*, m.URL_Media, m.Testo_Alternativo
                FROM Prodotto p
                LEFT JOIN Media m ON p.IDProdotto = m.IDProdotto
            ";

            $conditions = ['p.Attivo = 1'];
            $params = [];
            $types = '';

            if ($tipoProdotto !== null) {
                $conditions[] = 'p.Tipo_Prodotto = ?';
                $params[] = $tipoProdotto;
                $types .= 's';
            }

            if (!empty($filters['tipologie']) && is_array($filters['tipologie'])) {
                $tipologie = array_values(array_unique(array_filter(
                    array_map('trim', $filters['tipologie']),
                    fn($value) => $value !== ''
                )));

                if (!empty($tipologie)) {
                    $placeholders = implode(',', array_fill(0, count($tipologie), '?'));
                    $conditions[] = "p.Tipologia_Prodotto IN ($placeholders)";
                    foreach ($tipologie as $tipologia) {
                        $params[] = $tipologia;
                        $types .= 's';
                    }
                }
            }

            if (array_key_exists('patente', $filters) && $filters['patente'] !== null) {
                $conditions[] = 'p.Richiede_Patente = ?';
                $params[] = $filters['patente'] ? 1 : 0;
                $types .= 'i';
            }

            if (!empty($filters['postiMin'])) {
                $conditions[] = 'p.Posti_Totali >= ?';
                $params[] = (int) $filters['postiMin'];
                $types .= 'i';
            }

            if (!empty($filters['prezzoMax'])) {
                $conditions[] = 'p.Prezzo_Base <= ?';
                $params[] = (float) $filters['prezzoMax'];
                $types .= 'd';
            }

            if (array_key_exists('accessibile', $filters) && $filters['accessibile'] !== null) {
                $conditions[] = 'p.Accessibile_Disabili = ?';
                $params[] = $filters['accessibile'] ? 1 : 0;
                $types .= 'i';
            }

            if (!empty($filters['lingua'])) {
                $conditions[] = 'EXISTS (
                    SELECT 1
                    FROM Prodotto_Lingua pl
                    INNER JOIN Lingua l ON l.IDLingua = pl.IDLingua AND l.Attivo = 1
                    WHERE pl.IDProdotto = p.IDProdotto AND l.Codice = ?
                )';
                $params[] = $filters['lingua'];
                $types .= 's';
            }

            $dataRichiestaInizio = $filters['dataRichiestaInizio'] ?? null;
            $dataRichiestaFine = $filters['dataRichiestaFine'] ?? null;
            $rangeValido = false;
            if ($dataRichiestaInizio !== null && $dataRichiestaFine !== null) {
                $timestampInizio = strtotime($dataRichiestaInizio);
                $timestampFine = strtotime($dataRichiestaFine);
                if ($timestampInizio !== false && $timestampFine !== false && $timestampInizio <= $timestampFine) {
                    $rangeValido = true;
                }
            }

            if ($rangeValido) {
                $conditions[] = 'NOT EXISTS (
                    SELECT 1
                    FROM Prenotazione pr
                    WHERE pr.IDProdotto = p.IDProdotto
                      AND pr.Stato_Prenotazione != ?
                      AND pr.Data_Ora_Inizio < ?
                      AND pr.Data_Ora_Fine > ?
                )';
                $params[] = 'Cancellata';
                $types .= 's';
                $params[] = $dataRichiestaFine;
                $types .= 's';
                $params[] = $dataRichiestaInizio;
                $types .= 's';

                $conditions[] = 'NOT EXISTS (
                    SELECT 1
                    FROM Indisponibilita ind
                    WHERE ind.IDProdotto = p.IDProdotto
                      AND ind.Data_Inizio < ?
                      AND ind.Data_Fine > ?
                )';
                $params[] = $dataRichiestaFine;
                $types .= 's';
                $params[] = $dataRichiestaInizio;
                $types .= 's';
            }

            $orderClause = 'ORDER BY p.Data_Creazione DESC, p.IDProdotto ASC';
            switch ($sort) {
                case 'price-asc':
                    $orderClause = 'ORDER BY p.Prezzo_Base ASC, p.IDProdotto ASC';
                    break;
                case 'price-desc':
                    $orderClause = 'ORDER BY p.Prezzo_Base DESC, p.IDProdotto ASC';
                    break;
                case 'duration':
                    $orderClause = 'ORDER BY COALESCE(p.Durata_Ore, 0) ASC, p.IDProdotto ASC';
                    break;
                case 'size':
                    $orderClause = 'ORDER BY COALESCE(p.Lunghezza_Barca_Metri, 0) DESC, p.IDProdotto ASC';
                    break;
            }

            if (!empty($conditions)) {
                $query .= ' WHERE ' . implode(' AND ', $conditions);
            }
            $query .= ' ' . $orderClause . ' LIMIT ?';

            $params[] = $limit;
            $types .= 'i';

            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if (!$result) {
                $this->closeConnection();
                return false;
            }

            if ($result->num_rows === 0) {
                $this->closeConnection();
                return [];
            }

            $prodotti = [];
            while ($row = $result->fetch_assoc()) {
                $prodotti[] = $row;
            }

            $result->free();
            $this->closeConnection();
            return $prodotti;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Recupera gli articoli pubblicati con media associata.
     */
    public function getArticoliBlogWithMedia(int $limit = 10): array|bool {
        $this->openConnection();
        try {
            $query = "
                SELECT a.*, m.URL_Media, m.Testo_Alternativo
                FROM Articolo_Blog a
                LEFT JOIN Media m ON a.IDArticolo = m.IDArticolo
                WHERE a.Pubblicato = 1
                ORDER BY a.Data_Pubblicazione DESC
                LIMIT ?
            ";

            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param('i', $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if (!$result) {
                $this->closeConnection();
                return false;
            }

            if ($result->num_rows === 0) {
                $this->closeConnection();
                return [];
            }

            $articoli = [];
            while ($row = $result->fetch_assoc()) {
                $articoli[] = $row;
            }

            $result->free();
            $this->closeConnection();
            return $articoli;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Recupera un articolo pubblicato con i dati dell'autore e le immagini collegate.
     */
    public function getArticoloBlogCompleto(int $idArticolo): array|bool|null {
        $this->openConnection();
        $query = "
            SELECT a.*,
                   u.Nome AS Autore_Nome,
                   u.Cognome AS Autore_Cognome,
                   u.Numero_Patente_Nautica AS Autore_Patente,
                   u.Data_Registrazione AS Autore_Data_Registrazione,
                   u.Is_Admin AS Autore_Is_Admin,
                   (SELECT URL_Media FROM Media WHERE IDArticolo = a.IDArticolo ORDER BY IDMedia ASC LIMIT 1) AS Articolo_URL,
                   (SELECT Testo_Alternativo FROM Media WHERE IDArticolo = a.IDArticolo ORDER BY IDMedia ASC LIMIT 1) AS Articolo_Alt,
                   (SELECT URL_Media FROM Media WHERE IDUtente = u.IDUtente ORDER BY IDMedia ASC LIMIT 1) AS Autore_URL,
                   (SELECT Testo_Alternativo FROM Media WHERE IDUtente = u.IDUtente ORDER BY IDMedia ASC LIMIT 1) AS Autore_Alt
            FROM Articolo_Blog a
            JOIN Utente u ON u.IDUtente = a.IDAutore
            WHERE a.IDArticolo = ? AND a.Pubblicato = 1
            LIMIT 1
        ";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param('i', $idArticolo);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if (!$result) {
                $this->closeConnection();
                return false;
            }

            if ($result->num_rows === 0) {
                $this->closeConnection();
                return null;
            }

            $row = $result->fetch_assoc();
            $result->free();
            $this->closeConnection();
            return $row;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Recupera i suggerimenti extra associati a un articolo.
     */
    public function getArticoloBlogExtra(int $idArticolo): array|bool {
        $this->openConnection();
        $query = "
            SELECT Titolo, Elemento
            FROM Articolo_Blog_Extra
            WHERE IDArticolo = ?
            ORDER BY Titolo ASC, Ordine ASC
        ";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param('i', $idArticolo);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if (!$result) {
                $this->closeConnection();
                return false;
            }

            if ($result->num_rows === 0) {
                $this->closeConnection();
                return [];
            }

            $extras = [];
            while ($row = $result->fetch_assoc()) {
                $extras[] = $row;
            }

            $result->free();
            $this->closeConnection();
            return $extras;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * recupera articoli area admin
     */
    public function getArticoliAdmin(?string $term = null, int $limit = 20, int $offset = 0): array|bool {
        $this->openConnection();

        $conditions = [];
        $params = [];
        $types = '';

        if ($term !== null && trim($term) !== '') {
            $like = '%' . trim($term) . '%';
            $conditions[] = '(Titolo LIKE ? OR Descrizione_Breve LIKE ?)';
            $params[] = $like;
            $params[] = $like;
            $types .= 'ss';
        }

        $query = "
            SELECT SQL_CALC_FOUND_ROWS a.*, u.Nome AS Autore_Nome, u.Cognome AS Autore_Cognome,
                   (SELECT URL_Media FROM Media WHERE IDArticolo = a.IDArticolo LIMIT 1) AS URL_Media
            FROM Articolo_Blog a
            JOIN Utente u ON u.IDUtente = a.IDAutore
        ";

        if (!empty($conditions)) {
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $query .= ' ORDER BY a.Data_Pubblicazione DESC, a.IDArticolo DESC LIMIT ? OFFSET ?';
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $stmt->close();

            $countResult = $this->connection->query("SELECT FOUND_ROWS() AS total");
            $total = 0;
            if ($countResult) {
                $totalRow = $countResult->fetch_assoc();
                $total = (int) ($totalRow['total'] ?? 0);
                $countResult->free();
            }

            $this->closeConnection();
            return ['data' => $rows, 'total' => $total];
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * crea nuovo articolo di blog.
     */
    public function creaArticoloBlog(
        int $idAutore,
        string $titolo,
        string $descrizioneBreve,
        string $contenuto,
        string $dataPubblicazione,
        bool $pubblicato,
        int $tempoLettura = 0
    ) {
        $this->openConnection();
        $query = "
            INSERT INTO Articolo_Blog (IDAutore, Titolo, Descrizione_Breve, Contenuto, Data_Pubblicazione, Tempo_Lettura, Pubblicato)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }

            $pub = $pubblicato ? 1 : 0;
            $stmt->bind_param('issssii', $idAutore, $titolo, $descrizioneBreve, $contenuto, $dataPubblicazione, $tempoLettura, $pub);
            $ok = $stmt->execute();
            $newId = $stmt->insert_id;
            $stmt->close();
            $this->closeConnection();
            return $ok ? $newId : false;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * aggiorna stato pubblicazione di un articolo
     */
    public function setArticoloStato(int $idArticolo, bool $pubblicato): bool {
        $this->openConnection();
        $query = "UPDATE Articolo_Blog SET Pubblicato = ? WHERE IDArticolo = ?";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }
            $flag = $pubblicato ? 1 : 0;
            $stmt->bind_param('ii', $flag, $idArticolo);
            $ok = $stmt->execute();
            $stmt->close();
            $this->closeConnection();
            return $ok;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * elimina articolo 
     */
    public function deleteArticolo(int $idArticolo): bool {
        $this->openConnection();
        $query = "DELETE FROM Articolo_Blog WHERE IDArticolo = ?";

        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }
            $stmt->bind_param('i', $idArticolo);
            $ok = $stmt->execute();
            $stmt->close();
            $this->closeConnection();
            return $ok;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * stats da visualizzare in admin_blog
     */
    public function getBlogStats(): array|bool {
        $this->openConnection();
        $query = "
            SELECT
                (SELECT COUNT(*) FROM Articolo_Blog WHERE Pubblicato = 1) AS published,
                (SELECT COUNT(*) FROM Articolo_Blog WHERE Pubblicato = 0) AS drafts
        ";

        try {
            $result = $this->connection->query($query);
            if (!$result) {
                $this->closeConnection();
                return false;
            }
            $row = $result->fetch_assoc();
            $result->free();
            $this->closeConnection();
            return $row ?: [];
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * sostituisce la media principale di un articolo
     */
    public function upsertMediaArticolo(int $idArticolo, string $url, string $alt): bool {
        $this->openConnection();
        $this->connection->begin_transaction();

        try {
            $this->connection->query("DELETE FROM Media WHERE IDArticolo = " . (int) $idArticolo);

            $stmt = $this->connection->prepare("
                INSERT INTO Media (URL_Media, Testo_Alternativo, Tipo_Media, IDArticolo)
                VALUES (?, ?, 'Immagine', ?)
            ");
            if (!$stmt) {
                $this->connection->rollback();
                $this->closeConnection();
                return false;
            }

            $stmt->bind_param('ssi', $url, $alt, $idArticolo);
            $ok = $stmt->execute();
            $stmt->close();

            if ($ok) {
                $this->connection->commit();
            } else {
                $this->connection->rollback();
            }

            $this->closeConnection();
            return $ok;
        } catch (Throwable $t) {
            $this->connection->rollback();
            $this->closeConnection();
            return false;
        }
    }

    /* ============================================================
       METODI PRENOTAZIONE
       ============================================================ */

    /**
     * Verifica se un prodotto è disponibile in un determinato periodo
     */
    public function checkDateAvailability(string $idProdotto, string $dataInizio, string $dataFine): bool {
        $this->openConnection();
        
        // Controlla prenotazioni esistenti (non cancellate)
        $query = "SELECT COUNT(*) as count FROM Prenotazione 
                  WHERE IDProdotto = ? 
                  AND Stato_Prenotazione != 'Cancellata'
                  AND (
                      (Data_Ora_Inizio < ? AND Data_Ora_Fine > ?) OR
                      (Data_Ora_Inizio < ? AND Data_Ora_Fine > ?) OR
                      (Data_Ora_Inizio >= ? AND Data_Ora_Fine <= ?)
                  )";
        
        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }
            
            $stmt->bind_param('sssssss', $idProdotto, $dataFine, $dataInizio, $dataFine, $dataInizio, $dataInizio, $dataFine);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $count = (int) $row['count'];
            
            $stmt->close();
            
            // Controlla anche indisponibilità manuali
            $query2 = "SELECT COUNT(*) as count FROM Indisponibilita 
                       WHERE IDProdotto = ? 
                       AND (
                           (Data_Inizio < ? AND Data_Fine > ?) OR
                           (Data_Inizio < ? AND Data_Fine > ?) OR
                           (Data_Inizio >= ? AND Data_Fine <= ?)
                       )";
            
            $stmt2 = $this->connection->prepare($query2);
            if (!$stmt2) {
                $this->closeConnection();
                return false;
            }
            
            $stmt2->bind_param('sssssss', $idProdotto, $dataFine, $dataInizio, $dataFine, $dataInizio, $dataInizio, $dataFine);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = $result2->fetch_assoc();
            $count2 = (int) $row2['count'];
            
            $stmt2->close();
            $this->closeConnection();
            
            return ($count === 0 && $count2 === 0);
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }

    /**
     * Inserisce una nuova prenotazione
     * @return int|false ID della prenotazione inserita o false in caso di errore
     */
    public function insertPrenotazione(
        int $idUtente,
        string $idProdotto,
        string $dataInizio,
        string $dataFine,
        bool $skipperRichiesto,
        float $prezzoTotale,
        string $metodoPagamento,
        string $statoPrenotazione = 'In Attesa',
        ?string $noteAddizionali = null
    ) {
        $this->openConnection();
        
        $query = "INSERT INTO Prenotazione 
                  (IDUtente, IDProdotto, Data_Ora_Inizio, Data_Ora_Fine, Skipper_Richiesto, 
                   Prezzo_Totale, Metodo_Pagamento, Stato_Prenotazione, Note_Addizionali)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                $this->closeConnection();
                return false;
            }
            
            $skipperInt = $skipperRichiesto ? 1 : 0;
            $stmt->bind_param(
                'isssidsss',
                $idUtente,
                $idProdotto,
                $dataInizio,
                $dataFine,
                $skipperInt,
                $prezzoTotale,
                $metodoPagamento,
                $statoPrenotazione,
                $noteAddizionali
            );
            
            if (!$stmt->execute()) {
                $stmt->close();
                $this->closeConnection();
                return false;
            }
            
            $id = $stmt->insert_id;
            $stmt->close();
            $this->closeConnection();
            return $id;
        } catch (Throwable $t) {
            $this->closeConnection();
            return false;
        }
    }
}
