-- ============================================================================
-- SailUP - Script di Creazione Database
-- ============================================================================
-- Configurazione: Debian 13.1, PHP 8.4.11, MariaDB 11.8.3
-- Lingue supportate: Italiano (it), Inglese (en), Francese (fr), Tedesco (de)
-- ============================================================================

-- Creazione Database
USE dbiasuzz;

-- ============================================================================
-- TABELLA: Indirizzo
-- Memorizza gli indirizzi, separati dagli utenti, per migliore normalizzazione
-- ============================================================================
CREATE TABLE Indirizzo (
    IDIndirizzo INT AUTO_INCREMENT PRIMARY KEY,
    Via VARCHAR(255) NOT NULL,
    N_Civico VARCHAR(10) NOT NULL,
    CAP VARCHAR(5) NOT NULL,
    Citta VARCHAR(100) NOT NULL,
    Provincia VARCHAR(2) NOT NULL,
    Paese VARCHAR(2) DEFAULT 'IT' NOT NULL,
    Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_citta (Citta),
    INDEX idx_provincia (Provincia)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABELLA: Utente
-- Contiene tutti gli utenti, sia clienti che admin
-- ============================================================================
CREATE TABLE Utente (
    IDUtente INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Cognome VARCHAR(100) NOT NULL,
    CF VARCHAR(16) NOT NULL UNIQUE,
    Email VARCHAR(255) NOT NULL UNIQUE,
    PasswordHash VARCHAR(255) NOT NULL,
    Numero_Patente_Nautica VARCHAR(50) NULL,
    IDIndirizzo INT NOT NULL,
    Is_Admin BOOLEAN DEFAULT 0 NOT NULL,
    Data_Registrazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Data_Ultimo_Accesso TIMESTAMP NULL,
    Attivo BOOLEAN DEFAULT 1,
    FOREIGN KEY (IDIndirizzo) REFERENCES Indirizzo(IDIndirizzo) ON DELETE RESTRICT,
    UNIQUE KEY unique_email (Email),
    UNIQUE KEY unique_cf (CF),
    INDEX idx_email (Email),
    INDEX idx_admin (Is_Admin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABELLA: Lingua
-- Tabella di supporto per le lingue disponibili
-- ============================================================================
CREATE TABLE Lingua (
    IDLingua INT AUTO_INCREMENT PRIMARY KEY,
    Codice_Lingua VARCHAR(5) NOT NULL UNIQUE,
    Nome_Lingua VARCHAR(100) NOT NULL,
    Nome_Nativo VARCHAR(100) NOT NULL,
    Attivo BOOLEAN DEFAULT 1,
    Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_codice (Codice_Lingua)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserimento lingue supportate
INSERT INTO Lingua (Codice_Lingua, Nome_Lingua, Nome_Nativo) VALUES
('it', 'Italian', 'Italiano'),
('en', 'English', 'English'),
('fr', 'French', 'Français'),
('de', 'German', 'Deutsch');

-- ============================================================================
-- TABELLA: Prodotto
-- Centrale: gestisce sia Noleggi che Experiences con ID unico
-- ============================================================================
CREATE TABLE Prodotto (
    IDProdotto VARCHAR(50) PRIMARY KEY,
    Tipo_Prodotto ENUM('Noleggio', 'Experience') NOT NULL,
    Prezzo_Base DECIMAL(10, 2) NOT NULL,
    Posti_Totali INT NOT NULL,
    Accessibile_Disabili BOOLEAN DEFAULT 0,
    Lunghezza_Barca_Metri DECIMAL(6, 2) NULL,
    Richiede_Patente BOOLEAN NULL,
    Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Data_Modifica TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    Attivo BOOLEAN DEFAULT 1,
    INDEX idx_tipo (Tipo_Prodotto),
    INDEX idx_attivo (Attivo),
    INDEX idx_accessibile (Accessibile_Disabili)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABELLA: Prodotto_Traduzione
-- Contiene le traduzioni di Nome, Descrizione e Specifiche per ogni Prodotto
-- ============================================================================
CREATE TABLE Prodotto_Traduzione (
    IDProdotto_Traduzione INT AUTO_INCREMENT PRIMARY KEY,
    IDProdotto VARCHAR(50) NOT NULL,
    IDLingua INT NOT NULL,
    Nome_Prodotto VARCHAR(255) NOT NULL,
    Descrizione TEXT NULL,
    Specifiche TEXT NULL,
    Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Data_Modifica TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (IDProdotto) REFERENCES Prodotto(IDProdotto) ON DELETE CASCADE,
    FOREIGN KEY (IDLingua) REFERENCES Lingua(IDLingua) ON DELETE RESTRICT,
    UNIQUE KEY unique_prodotto_lingua (IDProdotto, IDLingua),
    INDEX idx_prodotto (IDProdotto),
    INDEX idx_lingua (IDLingua)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABELLA: Media
-- Contiene file multimediali (immagini, video) collegati a Prodotti e Articoli
-- ============================================================================
CREATE TABLE Media (
    IDMedia INT AUTO_INCREMENT PRIMARY KEY,
    URL_Media VARCHAR(500) NOT NULL,
    Testo_Alternativo VARCHAR(255) NOT NULL,
    Tipo_Media ENUM('Immagine', 'Video') DEFAULT 'Immagine' NOT NULL,
    Ordine_Visualizzazione INT DEFAULT 0,
    IDProdotto VARCHAR(50) NULL,
    IDArticolo INT NULL,
    Data_Caricamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IDProdotto) REFERENCES Prodotto(IDProdotto) ON DELETE CASCADE,
    INDEX idx_prodotto (IDProdotto),
    INDEX idx_articolo (IDArticolo),
    INDEX idx_ordine (Ordine_Visualizzazione),
    INDEX idx_tipo_media (Tipo_Media)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABELLA: Prenotazione
-- Cuore del sistema: collega Utente a Prodotto in un intervallo di tempo
-- ============================================================================
CREATE TABLE Prenotazione (
    IDPrenotazione INT AUTO_INCREMENT PRIMARY KEY,
    IDUtente INT NOT NULL,
    IDProdotto VARCHAR(50) NOT NULL,
    Data_Ora_Inizio DATETIME NOT NULL,
    Data_Ora_Fine DATETIME NOT NULL,
    Skipper_Richiesto BOOLEAN DEFAULT 0,
    Lingua_Guida VARCHAR(5) NULL,
    Prezzo_Totale DECIMAL(10, 2) NOT NULL,
    Metodo_Pagamento ENUM('Contanti', 'Bonifico', 'Carta di Credito') NOT NULL DEFAULT 'Contanti',
    Stato_Prenotazione ENUM('In Attesa', 'Confermata', 'Cancellata') DEFAULT 'In Attesa',
    Note_Addizionali TEXT NULL,
    Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Data_Modifica TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (IDUtente) REFERENCES Utente(IDUtente) ON DELETE RESTRICT,
    FOREIGN KEY (IDProdotto) REFERENCES Prodotto(IDProdotto) ON DELETE RESTRICT,
    INDEX idx_utente (IDUtente),
    INDEX idx_prodotto (IDProdotto),
    INDEX idx_data_inizio (Data_Ora_Inizio),
    INDEX idx_stato (Stato_Prenotazione)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABELLA: Indisponibilita
-- Per blocchi admin (manutenzione, periodi di chiusura)
-- ============================================================================
CREATE TABLE Indisponibilita (
    IDIndisponibilita INT AUTO_INCREMENT PRIMARY KEY,
    IDProdotto VARCHAR(50) NOT NULL,
    Data_Inizio DATETIME NOT NULL,
    Data_Fine DATETIME NOT NULL,
    Motivo VARCHAR(255) NULL,
    Creato_Da INT NOT NULL,
    Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IDProdotto) REFERENCES Prodotto(IDProdotto) ON DELETE CASCADE,
    FOREIGN KEY (Creato_Da) REFERENCES Utente(IDUtente) ON DELETE RESTRICT,
    INDEX idx_prodotto (IDProdotto),
    INDEX idx_data_inizio (Data_Inizio),
    INDEX idx_data_fine (Data_Fine)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABELLA: Recensione
-- Collegata alla Prenotazione: solo una recensione per prenotazione
-- ============================================================================
CREATE TABLE Recensione (
    IDRecensione INT AUTO_INCREMENT PRIMARY KEY,
    IDPrenotazione INT NOT NULL UNIQUE,
    Punteggio INT NOT NULL CHECK (Punteggio >= 1 AND Punteggio <= 5),
    Titolo VARCHAR(255) NULL,
    Testo_Recensione TEXT NULL,
    Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Data_Modifica TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (IDPrenotazione) REFERENCES Prenotazione(IDPrenotazione) ON DELETE CASCADE,
    INDEX idx_punteggio (Punteggio),
    INDEX idx_data (Data_Creazione)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABELLA: Articolo_Blog
-- Gestione degli articoli del blog
-- ============================================================================
CREATE TABLE Articolo_Blog (
    IDArticolo INT AUTO_INCREMENT PRIMARY KEY,
    IDAutore INT NOT NULL,
    Data_Pubblicazione DATETIME NOT NULL,
    Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Data_Modifica TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    Pubblicato BOOLEAN DEFAULT 0,
    FOREIGN KEY (IDAutore) REFERENCES Utente(IDUtente) ON DELETE RESTRICT,
    INDEX idx_autore (IDAutore),
    INDEX idx_data_pub (Data_Pubblicazione),
    INDEX idx_pubblicato (Pubblicato)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABELLA: Articolo_Blog_Traduzione
-- Contiene le traduzioni di Titolo e Contenuto per ogni Articolo
-- ============================================================================
CREATE TABLE Articolo_Blog_Traduzione (
    IDArticolo_Traduzione INT AUTO_INCREMENT PRIMARY KEY,
    IDArticolo INT NOT NULL,
    IDLingua INT NOT NULL,
    Titolo VARCHAR(255) NOT NULL,
    Contenuto LONGTEXT NOT NULL,
    Slug VARCHAR(255) NULL,
    Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Data_Modifica TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (IDArticolo) REFERENCES Articolo_Blog(IDArticolo) ON DELETE CASCADE,
    FOREIGN KEY (IDLingua) REFERENCES Lingua(IDLingua) ON DELETE RESTRICT,
    UNIQUE KEY unique_articolo_lingua (IDArticolo, IDLingua),
    INDEX idx_articolo (IDArticolo),
    INDEX idx_lingua (IDLingua),
    INDEX idx_slug (Slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABELLA: Pagina_Statica
-- Per gestire le pagine statiche (Privacy, Cookie Policy, FAQ, Chi Siamo, ecc.)
-- ============================================================================
CREATE TABLE Pagina_Statica (
    IDPagina INT AUTO_INCREMENT PRIMARY KEY,
    Slug_Pagina VARCHAR(100) NOT NULL UNIQUE,
    Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Data_Modifica TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (Slug_Pagina)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABELLA: Pagina_Statica_Traduzione
-- Contiene le traduzioni di Titolo e Contenuto per ogni Pagina Statica
-- ============================================================================
CREATE TABLE Pagina_Statica_Traduzione (
    IDPagina_Traduzione INT AUTO_INCREMENT PRIMARY KEY,
    IDPagina INT NOT NULL,
    IDLingua INT NOT NULL,
    Titolo VARCHAR(255) NOT NULL,
    Contenuto LONGTEXT NOT NULL,
    Data_Creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Data_Modifica TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (IDPagina) REFERENCES Pagina_Statica(IDPagina) ON DELETE CASCADE,
    FOREIGN KEY (IDLingua) REFERENCES Lingua(IDLingua) ON DELETE RESTRICT,
    UNIQUE KEY unique_pagina_lingua (IDPagina, IDLingua),
    INDEX idx_pagina (IDPagina),
    INDEX idx_lingua (IDLingua)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- INSERIMENTO DATI DI TEST
-- ============================================================================

-- Inserimento indirizzi di test
INSERT INTO Indirizzo (Via, N_Civico, CAP, Citta, Provincia, Paese) VALUES
('Via Partenope', '10', '80121', 'Napoli', 'NA', 'IT'),
('Via Caracciolo', '21', '80122', 'Napoli', 'NA', 'IT'),
('Via Marina', '5', '80133', 'Napoli', 'NA', 'IT');

-- Inserimento utenti di test (password: admin123 per admin, user123 per user)
INSERT INTO Utente (Nome, Cognome, CF, Email, PasswordHash, Numero_Patente_Nautica, IDIndirizzo, Is_Admin) VALUES
('Admin', 'SailUp', 'ADMNSL80A01H501J', 'admin@sailup.it', '$2y$10$qCqiPhwuS9h4Y1/P7KqE9.GhIUqzCwVVnGfLJrJfSqVHzgXsBAQDq', NULL, 1, 1),
('Marco', 'Rossi', 'RSSMRC90C15H501T', 'marco.rossi@email.it', '$2y$10$N5X7KqJ8nVmH3pL9zBwU6.8HjKsX5mN2pQ4rT7vY9wZ1xC2dE5fG', 'PATENTE123456', 2, 0),
('Anna', 'Bianchi', 'BNCANR85M45H501M', 'anna.bianchi@email.it', '$2y$10$nLmOkJhYvCdE4fG5h6I7J.8kJpQrStU9vWxY0zA1bC2dE3fG4hI5', NULL, 3, 0);

-- Inserimento prodotti di test
INSERT INTO Prodotto (IDProdotto, Tipo_Prodotto, Prezzo_Base, Posti_Totali, Accessibile_Disabili, Lunghezza_Barca_Metri, Richiede_Patente) VALUES
('NOLEGGIO-GOZZO-01', 'Noleggio', 150.00, 8, 0, 8.5, 1),
('NOLEGGIO-GOMMONE-01', 'Noleggio', 120.00, 6, 0, 6.0, 0),
('NOLEGGIO-BARCA-VELA-01', 'Noleggio', 200.00, 10, 0, 12.0, 1),
('EXPERIENCE-CAPRI-SUNSET', 'Experience', 250.00, 10, 0, NULL, NULL),
('EXPERIENCE-ISCHIA-TOUR', 'Experience', 200.00, 12, 1, NULL, NULL);

-- Inserimento traduzioni prodotti
INSERT INTO Prodotto_Traduzione (IDProdotto, IDLingua, Nome_Prodotto, Descrizione, Specifiche) VALUES
-- NOLEGGIO-GOZZO-01
('NOLEGGIO-GOZZO-01', 1, 'Gozzo a Motore 8.5m', 'Barca robusta e comoda, perfetta per famiglie e gruppi', 'Motore 250cv, GPS, Sonar, Biancheria'),
('NOLEGGIO-GOZZO-01', 2, 'Motor Boat 8.5m', 'Sturdy and comfortable boat, perfect for families and groups', 'Engine 250hp, GPS, Sonar, Bedding'),
('NOLEGGIO-GOZZO-01', 3, 'Bateau à Moteur 8.5m', 'Bateau robuste et confortable, parfait pour les familles et les groupes', 'Moteur 250cv, GPS, Sonar, Draps'),
('NOLEGGIO-GOZZO-01', 4, 'Motorboot 8.5m', 'Robustes und komfortables Boot, perfekt für Familien und Gruppen', 'Motor 250 PS, GPS, Sonar, Bettwäsche'),

-- NOLEGGIO-GOMMONE-01
('NOLEGGIO-GOMMONE-01', 1, 'Gommone 6m (Senza Patente)', 'Agile e veloce, ideale per esplorazioni costiere. NON richiede patente.', 'Motore 115cv, Lettini gonfiabili, Ancora'),
('NOLEGGIO-GOMMONE-01', 2, 'Speedboat 6m (No License Required)', 'Agile and fast, ideal for coastal explorations. NO license required.', 'Engine 115hp, Inflatable loungers, Anchor'),
('NOLEGGIO-GOMMONE-01', 3, 'Bateau Pneumatique 6m (Sans Permis)', 'Agile et rapide, idéal pour les explorations côtières. Pas de permis requis.', 'Moteur 115cv, Transats gonflables, Ancre'),
('NOLEGGIO-GOMMONE-01', 4, 'Schlauchboot 6m (Kein Führerschein Erforderlich)', 'Wendig und schnell, ideal für Küstenerkundungen. Kein Führerschein erforderlich.', 'Motor 115 PS, Aufblasbare Liegestühle, Anker'),

-- EXPERIENCE-CAPRI-SUNSET
('EXPERIENCE-CAPRI-SUNSET', 1, 'Tour Tramonto a Capri', 'Esperienza esclusiva: navigazione privata verso Capri con skipper professionista. Aperitivo incluso.', 'Durata 4 ore, Aperitivo, Snorkeling'),
('EXPERIENCE-CAPRI-SUNSET', 2, 'Capri Sunset Tour', 'Exclusive experience: private sailing to Capri with professional skipper. Aperitif included.', 'Duration 4 hours, Aperitif, Snorkeling'),
('EXPERIENCE-CAPRI-SUNSET', 3, 'Tour Coucher de Soleil à Capri', 'Expérience exclusive : navigation privée vers Capri avec skipper professionnel. Apéritif inclus.', 'Durée 4 heures, Apéritif, Plongée'),
('EXPERIENCE-CAPRI-SUNSET', 4, 'Sonnenuntergang-Tour nach Capri', 'Exklusives Erlebnis: private Fahrt nach Capri mit professionellem Skipper. Aperitif inklusive.', 'Dauer 4 Stunden, Aperitif, Schnorcheln');

-- Inserimento pagine statiche di test
INSERT INTO Pagina_Statica (Slug_Pagina) VALUES ('privacy-policy'), ('cookie-policy'), ('chi-siamo'), ('faq');

-- Inserimento traduzioni pagine statiche
INSERT INTO Pagina_Statica_Traduzione (IDPagina, IDLingua, Titolo, Contenuto) VALUES
(1, 1, 'Politica sulla Privacy', 'Contenuto Privacy Policy italiano...'),
(1, 2, 'Privacy Policy', 'Privacy Policy content in English...'),
(1, 3, 'Politique de Confidentialité', 'Contenu Politique de Confidentialité français...'),
(1, 4, 'Datenschutzrichtlinie', 'Inhalt der Datenschutzrichtlinie auf Deutsch...');

ALTER DATABASE dbiasuzz
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;


-- ============================================================================
-- FINE SCRIPT - Database pronto per l'uso
-- ============================================================================
