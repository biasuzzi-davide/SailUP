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
    public function getProdottiWithMedia(?string $tipoProdotto = null, int $limit = 100): array|bool {
        $this->openConnection();
        try {
            $query = "
                SELECT p.*, m.URL_Media, m.Testo_Alternativo
                FROM Prodotto p
                LEFT JOIN Media m ON p.IDProdotto = m.IDProdotto
                WHERE p.Attivo = 1
            ";
            $params = [];
            $types = '';

            if ($tipoProdotto !== null) {
                $query .= " AND p.Tipo_Prodotto = ?";
                $params[] = $tipoProdotto;
                $types .= 's';
            }

            $query .= " ORDER BY p.Data_Creazione DESC LIMIT ?";
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
}