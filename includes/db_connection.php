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
     * Restituisce un prodotto per IDProdotto oppure null/false.
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
            SELECT Nome_Extra, Prezzo_Extra, Descrizione_Extra, Opzionale
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
}