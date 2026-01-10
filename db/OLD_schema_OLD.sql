    -- ============================================================================
    -- SailUP Database Schema
    -- Database: dbiasuzz
    -- ============================================================================

    USE dbiasuzz;

    -- Disabilita i controlli delle chiavi esterne per evitare errori di vincolo
    SET FOREIGN_KEY_CHECKS = 0;

    -- Elimina tutte le tabelle in un colpo solo
    DROP TABLE IF EXISTS 
        `Articolo_Blog_Extra`, 
        `Articolo_Blog`, 
        `Indirizzo`, 
        `Indisponibilita`, 
        `Lingua`, 
        `Prodotto_Lingua`, 
        `Media`, 
        `Prenotazione`, 
        `Prodotto`, 
        `Prodotto_Extra`, 
        `Prodotto_Incluso`, 
        `Utente`;

    -- Riabilita i controlli delle chiavi esterne
    SET FOREIGN_KEY_CHECKS = 1;

    -- 1. INDIRIZZO
    CREATE TABLE IF NOT EXISTS Indirizzo (
        IDIndirizzo INT AUTO_INCREMENT PRIMARY KEY,
        Via VARCHAR(255) NOT NULL,
        N_Civico VARCHAR(10) NOT NULL,
        CAP VARCHAR(5) NOT NULL,
        Citta VARCHAR(100) NOT NULL,
        Provincia VARCHAR(2) NOT NULL,
        Paese VARCHAR(2) DEFAULT 'IT' NOT NULL,
        Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_citta (Citta)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    -- 2. UTENTE
    CREATE TABLE IF NOT EXISTS Utente (
        IDUtente INT AUTO_INCREMENT PRIMARY KEY,
        Nome VARCHAR(100) NOT NULL,
        Cognome VARCHAR(100) NOT NULL,
        CF VARCHAR(16) NOT NULL UNIQUE,
        Email VARCHAR(255) NOT NULL UNIQUE,
        PasswordHash VARCHAR(255) NOT NULL,
        Numero_Patente_Nautica VARCHAR(50) NULL,
        IDIndirizzo INT NOT NULL, -- Vincolo reintrodotto
        Is_Admin BOOLEAN DEFAULT 0 NOT NULL,
        Data_Registrazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        Data_Ultimo_Accesso TIMESTAMP NULL,
        Attivo BOOLEAN DEFAULT 1,
        FOREIGN KEY (IDIndirizzo) REFERENCES Indirizzo(IDIndirizzo) ON DELETE RESTRICT,
        INDEX idx_email (Email),
        INDEX idx_attivo (Attivo)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    -- 3. PRODOTTO
    CREATE TABLE IF NOT EXISTS Prodotto (
        IDProdotto VARCHAR(50) PRIMARY KEY,
        Tipo_Prodotto ENUM('Noleggio', 'Experience') NOT NULL,
        Tipologia_Prodotto VARCHAR(30) NULL, 
        Durata_Ore INT UNSIGNED NULL,
        Nome_Prodotto VARCHAR(255) NOT NULL,
        Descrizione_Breve VARCHAR(500) NULL,
        Descrizione TEXT NULL,
        Prezzo_Base DECIMAL(10, 2) NOT NULL,
        Posti_Totali INT NOT NULL,
        Accessibile_Disabili BOOLEAN DEFAULT 0,
        Lunghezza_Barca_Metri DECIMAL(6, 2) NULL,
        Richiede_Patente BOOLEAN NULL,
        Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        Data_Modifica TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        Attivo BOOLEAN DEFAULT 1,
        INDEX idx_tipo (Tipo_Prodotto),
        INDEX idx_attivo (Attivo)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    -- 4. LINGUA
    CREATE TABLE IF NOT EXISTS Lingua (
        IDLingua INT AUTO_INCREMENT PRIMARY KEY,
        Codice VARCHAR(5) NOT NULL UNIQUE,
        Nome VARCHAR(50) NOT NULL,
        Attivo BOOLEAN DEFAULT 1 NOT NULL,
        Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        Data_Modifica TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_codice (Codice)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    -- 5. PRODOTTO_LINGUA
    CREATE TABLE IF NOT EXISTS Prodotto_Lingua (
        IDProdotto VARCHAR(50) NOT NULL,
        IDLingua INT NOT NULL,
        PRIMARY KEY (IDProdotto, IDLingua),
        FOREIGN KEY (IDProdotto) REFERENCES Prodotto(IDProdotto) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (IDLingua) REFERENCES Lingua(IDLingua) ON DELETE RESTRICT ON UPDATE CASCADE,
        INDEX idx_prodottolingua_lingua (IDLingua)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    -- 6. PRODOTTO EXTRA
    CREATE TABLE IF NOT EXISTS Prodotto_Extra (
        IDExtra INT AUTO_INCREMENT PRIMARY KEY,
        IDProdotto VARCHAR(50) NOT NULL,
        Nome_Extra VARCHAR(255) NOT NULL,
        Descrizione_Extra TEXT NULL,
        Prezzo_Extra DECIMAL(10, 2) NOT NULL,
        Opzionale BOOLEAN DEFAULT 1 NOT NULL,
        FOREIGN KEY (IDProdotto) REFERENCES Prodotto(IDProdotto) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    -- 7. PRODOTTO INCLUSO
    CREATE TABLE IF NOT EXISTS Prodotto_Incluso (
        IDIncluso INT AUTO_INCREMENT PRIMARY KEY,
        IDProdotto VARCHAR(50) NOT NULL,
        Nome_Incluso VARCHAR(255) NOT NULL,
        FOREIGN KEY (IDProdotto) REFERENCES Prodotto(IDProdotto) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    -- 8. ARTICOLO BLOG
    CREATE TABLE IF NOT EXISTS Articolo_Blog (
        IDArticolo INT AUTO_INCREMENT PRIMARY KEY,
        IDAutore INT NOT NULL,
        Titolo VARCHAR(255) NOT NULL,
        Descrizione_Breve VARCHAR(500) NOT NULL,
        Contenuto LONGTEXT NOT NULL,
        Data_Pubblicazione DATETIME NOT NULL,
        Tempo_Lettura INT UNSIGNED DEFAULT 0 NOT NULL,
        Pubblicato BOOLEAN DEFAULT 0,
        Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (IDAutore) REFERENCES Utente(IDUtente) ON DELETE RESTRICT,
        INDEX idx_pubblicato (Pubblicato)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    -- 9. ARTICOLO BLOG EXTRA
    CREATE TABLE IF NOT EXISTS Articolo_Blog_Extra (
        IDExtra INT AUTO_INCREMENT PRIMARY KEY,
        IDArticolo INT NOT NULL,
        Titolo VARCHAR(255) NOT NULL,
        Elemento TEXT NOT NULL,
        Ordine INT NOT NULL DEFAULT 0,
        Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (IDArticolo) REFERENCES Articolo_Blog(IDArticolo) ON DELETE CASCADE,
        INDEX idx_articolo_extra (IDArticolo)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    -- 10. MEDIA
    CREATE TABLE IF NOT EXISTS Media (
        IDMedia INT AUTO_INCREMENT PRIMARY KEY,
        URL_Media VARCHAR(500) NOT NULL,
        Testo_Alternativo VARCHAR(255) NOT NULL,
        Tipo_Media ENUM('Immagine', 'Video') DEFAULT 'Immagine' NOT NULL,
        IDUtente INT NULL UNIQUE,
        IDProdotto VARCHAR(50) NULL UNIQUE,
        IDArticolo INT NULL UNIQUE,
        Data_Caricamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (IDUtente) REFERENCES Utente(IDUtente) ON DELETE CASCADE,
        FOREIGN KEY (IDProdotto) REFERENCES Prodotto(IDProdotto) ON DELETE CASCADE,
        FOREIGN KEY (IDArticolo) REFERENCES Articolo_Blog(IDArticolo) ON DELETE CASCADE,
        CHECK (
            (IDUtente IS NOT NULL AND IDProdotto IS NULL AND IDArticolo IS NULL) OR
            (IDUtente IS NULL AND IDProdotto IS NOT NULL AND IDArticolo IS NULL) OR
            (IDUtente IS NULL AND IDProdotto IS NULL AND IDArticolo IS NOT NULL)
        )
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    -- 11. PRENOTAZIONE
    CREATE TABLE IF NOT EXISTS Prenotazione (
        IDPrenotazione INT AUTO_INCREMENT PRIMARY KEY,
        IDUtente INT NOT NULL,
        IDProdotto VARCHAR(50) NOT NULL,
        Data_Ora_Inizio DATETIME NOT NULL,
        Data_Ora_Fine DATETIME NOT NULL,
        Skipper_Richiesto BOOLEAN DEFAULT 0,
        Prezzo_Totale DECIMAL(10, 2) NOT NULL,
        Metodo_Pagamento ENUM('Contanti', 'Bonifico', 'Carta di Credito') DEFAULT 'Contanti',
        Stato_Prenotazione ENUM('In Attesa', 'Confermata', 'Cancellata') DEFAULT 'In Attesa',
        Note_Addizionali TEXT NULL,
        Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (IDUtente) REFERENCES Utente(IDUtente) ON DELETE RESTRICT,
        FOREIGN KEY (IDProdotto) REFERENCES Prodotto(IDProdotto) ON DELETE RESTRICT,
        INDEX idx_data_inizio (Data_Ora_Inizio),
        INDEX idx_stato (Stato_Prenotazione)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    -- 12. INDISPONIBILITA
    CREATE TABLE IF NOT EXISTS Indisponibilita (
        IDIndisponibilita INT AUTO_INCREMENT PRIMARY KEY,
        IDProdotto VARCHAR(50) NOT NULL,
        Data_Inizio DATETIME NOT NULL,
        Data_Fine DATETIME NOT NULL,
        Motivo VARCHAR(255) NULL,
        Creato_Da INT NOT NULL,
        Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (IDProdotto) REFERENCES Prodotto(IDProdotto) ON DELETE CASCADE,
        FOREIGN KEY (Creato_Da) REFERENCES Utente(IDUtente) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;