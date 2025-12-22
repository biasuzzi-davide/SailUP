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
INSERT INTO Prodotto (IDProdotto, Tipo_Prodotto, Tipologia_Prodotto, Nome_Prodotto, Descrizione_Breve, Durata_Ore, Prezzo_Base, Posti_Totali, Accessibile_Disabili, Lunghezza_Barca_Metri, Richiede_Patente) VALUES
('BARCA-GOZZO-01', 'Noleggio', 'Gozzo', 'Gozzo Sorrentino Classico', 'Ideale per giornate di relax.', NULL, 250.00, 6, 0, 7.50, 0),
('GOMMONE-SPORT-05', 'Noleggio', 'Gommone', 'Gommone Sport 200cv', 'Velocità e divertimento.', NULL, 400.00, 8, 0, 8.20, 1),
('EXP-TRAMONTO-01', 'Experience', 'Romantico', 'Aperitivo al Tramonto', 'Tour della costa con prosecco.', 8, 50.00, 10, 1, NULL, NULL);

-- 4. LINGUE DISPONIBILI
INSERT INTO Lingua (Codice, Nome) VALUES
('IT', 'Italiano'),
('EN', 'Inglese'),
('FR', 'Francese'),
('ES', 'Spagnolo');

-- 5. LINGUE PER LE EXPERIENCE
INSERT INTO Prodotto_Lingua (IDProdotto, IDLingua) VALUES
('EXP-TRAMONTO-01', (SELECT IDLingua FROM Lingua WHERE Codice = 'IT')),
('EXP-TRAMONTO-01', (SELECT IDLingua FROM Lingua WHERE Codice = 'EN'));

-- 6. PRODOTTO EXTRA (Upselling)
INSERT INTO Prodotto_Extra (IDProdotto, Nome_Extra, Prezzo_Extra, Opzionale) VALUES
('BARCA-GOZZO-01', 'Skipper Professionista', 100.00, 1),
('BARCA-GOZZO-01', 'Attrezzatura Snorkeling', 15.00, 1),
('GOMMONE-SPORT-05', 'Ciambella Trainabile', 30.00, 1),
('EXP-TRAMONTO-01', 'Bottiglia Champagne', 80.00, 1);

-- 7. PRODOTTO INCLUSO (Cosa è compreso)
INSERT INTO Prodotto_Incluso (IDProdotto, Nome_Incluso) VALUES
('BARCA-GOZZO-01', 'Assicurazione Kasko'),
('BARCA-GOZZO-01', 'Pulizia Finale'),
('EXP-TRAMONTO-01', 'Guida Turistica'),
('EXP-TRAMONTO-01', 'Snack e Bevande');

-- 8. ARTICOLI BLOG
INSERT INTO Articolo_Blog (IDAutore, Titolo, Descrizione_Breve, Contenuto, Data_Pubblicazione, Tempo_Lettura, Pubblicato) VALUES
(1, '5 Cale nascoste da vedere', 'Scopri le spiagge segrete.', 'Contenuto lungo dell\'articolo sulle cale...', NOW(), 6, 1),
(1, 'Come ormeggiare in sicurezza', 'Guida pratica per principianti.', 'Contenuto tecnico sull\'ormeggio...', NOW(), 4, 1);

-- 9. ARTICOLO BLOG EXTRA (Consigli pratici)
INSERT INTO Articolo_Blog_Extra (IDArticolo, Titolo, Elemento, Ordine) VALUES
(1, '🎒 Cosa non dimenticare', 'Maschera e boccaglio per lo snorkel del giorno.', 1),
(1, '🎒 Cosa non dimenticare', 'Acqua in abbondanza e uno spuntino leggero.', 2),
(1, '🎒 Cosa non dimenticare', 'Crema solare ad alta protezione resistente all\'acqua.', 3),
(1, '🎒 Cosa non dimenticare', 'Una macchina fotografica impermeabile o una custodia stagna.', 4),
(2, '🛟 Prima di salpare', 'Verifica sempre il livello di carburante e il piano di sicurezza.', 1),
(2, '🛟 Prima di salpare', 'Consegna un itinerario di massima al personale di terra.', 2);

-- 10. MEDIA (Foto)
INSERT INTO Media (URL_Media, Testo_Alternativo, Tipo_Media, IDUtente, IDProdotto, IDArticolo) VALUES
('../img/Azimut_55_fly_2.webp', 'Avatar Admin', 'Immagine', 1, NULL, NULL),
('../img/Azimut_55_fly_2.webp', 'Foto Gozzo in mare', 'Immagine', NULL, 'BARCA-GOZZO-01', NULL),
('../img/Azimut_55_fly_2.webp', 'Coppia al tramonto', 'Immagine', NULL, 'EXP-TRAMONTO-01', NULL),
('../img/Azimut_55_fly_2.webp', 'Cala splendida', 'Immagine', NULL, NULL, 1);

-- 11. PRENOTAZIONI
INSERT INTO Prenotazione (IDUtente, IDProdotto, Data_Ora_Inizio, Data_Ora_Fine, Prezzo_Totale, Stato_Prenotazione) VALUES
-- Davide prenota il Gozzo (Confermato)
(2, 'BARCA-GOZZO-01', '2025-06-15 09:00:00', '2025-06-15 18:00:00', 250.00, 'Confermata'),
-- Giulia prenota l'Experience (In Attesa)
(3, 'EXP-TRAMONTO-01', '2025-07-20 19:00:00', '2025-07-20 21:00:00', 100.00, 'In Attesa');

-- 12. INDISPONIBILITA (Manutenzione)
INSERT INTO Indisponibilita (IDProdotto, Data_Inizio, Data_Fine, Motivo, Creato_Da) VALUES
('GOMMONE-SPORT-05', '2025-05-01 00:00:00', '2025-05-03 23:59:59', 'Manutenzione Motore Ordinaria', 1);