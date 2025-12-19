-- ============================================================================
-- POPOLAMENTO DATI DI PROVA (SailUP) - POI VERRANNO SOSTITUITI CON DATI REALI
-- ============================================================================

USE dbiasuzz;

-- 1. INSERIMENTO INDIRIZZI (Necessari per creare gli utenti)
INSERT INTO Indirizzo (Via, N_Civico, CAP, Citta, Provincia, Paese) VALUES
('Via Roma', '10', '20100', 'Milano', 'MI', 'IT'),          -- Indirizzo 1 (Admin)
('Via del Piave', '45', '31040', 'Nervesa della Battaglia', 'TV', 'IT'), -- Indirizzo 2 (Davide)
('Calle del Forno', '123', '30100', 'Venezia', 'VE', 'IT');   -- Indirizzo 3 (Cliente Giulia)

-- 2. INSERIMENTO UTENTI
-- PasswordHash è fittizia ('hashed_123').
INSERT INTO Utente (Nome, Cognome, CF, Email, PasswordHash, Numero_Patente_Nautica, IDIndirizzo, Is_Admin) VALUES
('Mario', 'Rossi', 'RSSMRA80A01H501U', 'admin@sailup.it', 'hashed_secret_admin', 'PAT-NAUT-001', 1, 1),
('Davide', 'Biasuzzi', 'BSSDVD04D19L123X', 'davide@email.com', 'hashed_secret_davide', NULL, 2, 0),
('Giulia', 'Bianchi', 'BNCGLI95M55H501Z', 'giulia@email.com', 'hashed_secret_giulia', NULL, 3, 0);

-- 3. INSERIMENTO PRODOTTI
INSERT INTO Prodotto (IDProdotto, Tipo_Prodotto, Tipologia_Experience, Nome_Prodotto, Descrizione_Breve, Prezzo_Base, Posti_Totali, Accessibile_Disabili, Lunghezza_Barca_Metri, Richiede_Patente) VALUES
-- Prodotto 1: Noleggio (Barca) -> Tipologia_Experience deve essere NULL
('BARCA-GOZZO-01', 'Noleggio', NULL, 'Gozzo Sorrentino Classico', 'Ideale per giornate di relax.', 250.00, 6, 0, 7.50, 0),
-- Prodotto 2: Noleggio (Gommone potente) -> Tipologia_Experience deve essere NULL
('GOMMONE-SPORT-05', 'Noleggio', NULL, 'Gommone Sport 200cv', 'Velocità e divertimento.', 400.00, 8, 0, 8.20, 1),
-- Prodotto 3: Experience -> Tipologia_Experience deve essere COMPILATO
('EXP-TRAMONTO-01', 'Experience', 'Romantico', 'Aperitivo al Tramonto', 'Tour della costa con prosecco.', 50.00, 10, 1, NULL, NULL);

-- 4. PRODOTTO EXTRA (Upselling)
INSERT INTO Prodotto_Extra (IDProdotto, Nome_Extra, Prezzo_Extra, Opzionale) VALUES
('BARCA-GOZZO-01', 'Skipper Professionista', 100.00, 1),
('BARCA-GOZZO-01', 'Attrezzatura Snorkeling', 15.00, 1),
('GOMMONE-SPORT-05', 'Ciambella Trainabile', 30.00, 1),
('EXP-TRAMONTO-01', 'Bottiglia Champagne', 80.00, 1);

-- 5. PRODOTTO INCLUSO (Cosa è compreso)
INSERT INTO Prodotto_Incluso (IDProdotto, Nome_Incluso) VALUES
('BARCA-GOZZO-01', 'Assicurazione Kasko'),
('BARCA-GOZZO-01', 'Pulizia Finale'),
('EXP-TRAMONTO-01', 'Guida Turistica'),
('EXP-TRAMONTO-01', 'Snack e Bevande');

-- 6. ARTICOLI BLOG
INSERT INTO Articolo_Blog (IDAutore, Titolo, Descrizione_Breve, Contenuto, Data_Pubblicazione, Pubblicato) VALUES
(1, '5 Cale nascoste da vedere', 'Scopri le spiagge segrete.', 'Contenuto lungo dell\'articolo sulle cale...', NOW(), 1),
(1, 'Come ormeggiare in sicurezza', 'Guida pratica per principianti.', 'Contenuto tecnico sull\'ormeggio...', NOW(), 1);

-- 7. MEDIA (Foto)
INSERT INTO Media (URL_Media, Testo_Alternativo, Tipo_Media, IDUtente, IDProdotto, IDArticolo) VALUES
('/uploads/users/avatar_mario.jpg', 'Avatar Admin', 'Immagine', 1, NULL, NULL),
('/uploads/products/gozzo_main.jpg', 'Foto Gozzo in mare', 'Immagine', NULL, 'BARCA-GOZZO-01', NULL),
('/uploads/products/sunset_exp.jpg', 'Coppia al tramonto', 'Immagine', NULL, 'EXP-TRAMONTO-01', NULL),
('/uploads/blog/cale_nascoste.jpg', 'Cala splendida', 'Immagine', NULL, NULL, 1);

-- 8. PRENOTAZIONI
INSERT INTO Prenotazione (IDUtente, IDProdotto, Data_Ora_Inizio, Data_Ora_Fine, Prezzo_Totale, Stato_Prenotazione) VALUES
-- Davide prenota il Gozzo (Confermato)
(2, 'BARCA-GOZZO-01', '2025-06-15 09:00:00', '2025-06-15 18:00:00', 250.00, 'Confermata'),
-- Giulia prenota l'Experience (In Attesa)
(3, 'EXP-TRAMONTO-01', '2025-07-20 19:00:00', '2025-07-20 21:00:00', 100.00, 'In Attesa');

-- 9. INDISPONIBILITA (Manutenzione)
INSERT INTO Indisponibilita (IDProdotto, Data_Inizio, Data_Fine, Motivo, Creato_Da) VALUES
('GOMMONE-SPORT-05', '2025-05-01 00:00:00', '2025-05-03 23:59:59', 'Manutenzione Motore Ordinaria', 1);