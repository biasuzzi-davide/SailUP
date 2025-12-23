-- ============================================================================
-- NOLEGGIO BARCHE NAPOLI - DATI COMPLETI E REALISTICI
-- Database: dbiasuzz
-- ATTENZIONE, FILE VECCHIO, NON UTILIZZARE ATTUALMENTE, UTILIZZARE IL FILE dbiasuzz.sql presente nella stessa cartella
-- ============================================================================

USE dbiasuzz;

-- Disabilita controlli chiavi esterne durante l'inserimento
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================================
-- 1. INDIRIZZI (15 indirizzi: Napoli, Provincia, e altre città)
-- ============================================================================

INSERT INTO Indirizzo (Via, N_Civico, CAP, Citta, Provincia, Paese) VALUES
('Spaccanapoli', '45', '80138', 'Napoli', 'NA', 'IT'),              -- 1: Centro Napoli
('Via Partenope', '22', '80121', 'Napoli', 'NA', 'IT'),              -- 2: Lungomare
('Vico Equense', '8', '80069', 'Vico Equense', 'NA', 'IT'),          -- 3: Sorrento area
('Via Positano', '15', '84017', 'Positano', 'SA', 'IT'),             -- 4: Costiera Amalfitana
('Piazza Tasso', '33', '80067', 'Sorrento', 'NA', 'IT'),             -- 5: Sorrento centro
('Via Marina Grande', '12', '80073', 'Capri', 'NA', 'IT'),           -- 6: Capri
('Lungolago Salvo d\'Acquisto', '50', '80011', 'Bacoli', 'NA', 'IT'), -- 7: Flegrei
('Via Vittorio Emanuele', '200', '80013', 'Caserta', 'CE', 'IT'),    -- 8: Caserta
('Via Toledo', '334', '80134', 'Napoli', 'NA', 'IT'),                -- 9: Napoli centro
('Corso Italia', '88', '84010', 'Praiano', 'SA', 'IT'),              -- 10: Amalfi coast
('Via Sant\'Antonio', '19', '80010', 'Pozzuoli', 'NA', 'IT'),        -- 11: Pozzuoli
('Viale Europa', '75', '80062', 'Meta di Sorrento', 'NA', 'IT'),     -- 12: Meta
('Via Orientale', '120', '80078', 'Anacapri', 'NA', 'IT'),           -- 13: Anacapri
('Piazza Municipio', '1', '80133', 'Napoli', 'NA', 'IT'),            -- 14: Napoli Porto
('Via Marina Piccola', '5', '80071', 'Anacapri', 'NA', 'IT');        -- 15: Marina Piccola

-- ============================================================================
-- 2. UTENTI (8 utenti: 2 admin + 6 clienti)
-- ============================================================================

INSERT INTO Utente (Nome, Cognome, CF, Email, PasswordHash, Numero_Patente_Nautica, IDIndirizzo, Is_Admin, Attivo) VALUES
('Marco', 'Rossi', 'RSSMRC80A01H501U', 'admin@noleggio-napoli.it', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', 'PAT-NAUT-ADMIN-001', 1, 1, 1),
('Lucia', 'De Luca', 'DLCLCU85M41H501L', 'lucia.admin@noleggio-napoli.it', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', 'PAT-NAUT-ADMIN-002', 9, 1, 1),
('Giovanni', 'Ferraro', 'FRRGVN75D15H501K', 'giovanni.ferraro@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', 'PAT-NAUT-001', 2, 0, 1),
('Sofia', 'Esposito', 'ESPSFN92F45H501J', 'sofia.esposito@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', NULL, 3, 0, 1),
('Andrea', 'Moretti', 'MRTAND88H67H501I', 'andrea.moretti@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', 'PAT-NAUT-002', 4, 0, 1),
('Francesca', 'Marino', 'MRNFRC90S55H501H', 'francesca.marino@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', NULL, 5, 0, 1),
('Riccardo', 'Colombo', 'CLMRCR84L22H501G', 'riccardo.colombo@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', 'PAT-NAUT-003', 6, 0, 1),
('Elena', 'Gallo', 'GLLELM87C35H501F', 'elena.gallo@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', NULL, 7, 0, 1);

-- ============================================================================
-- 3. LINGUE
-- ============================================================================

INSERT INTO Lingua (Codice, Nome, Attivo) VALUES
('IT', 'Italiano', 1),
('EN', 'Inglese', 1),
('FR', 'Francese', 1),
('ES', 'Spagnolo', 1);

-- ============================================================================
-- 4. PRODOTTI (12 barche + 3 experiences = 15 prodotti)
-- ============================================================================

-- BARCHE PER NOLEGGIO (12)
INSERT INTO Prodotto (IDProdotto, Tipo_Prodotto, Tipologia_Prodotto, Nome_Prodotto, Descrizione_Breve, Durata_Ore, Prezzo_Base, Posti_Totali, Accessibile_Disabili, Lunghezza_Barca_Metri, Richiede_Patente, Attivo) VALUES

-- Gozzi
('BARCA-GOZZO-001', 'Noleggio', 'Gozzo', 'Gozzo Sorrentino Classico', 'Barca tradizionale perfetta per famiglie. Ideale per giornate di relax e snorkeling.', NULL, 280.00, 6, 1, 8.50, 0, 1),
('BARCA-GOZZO-002', 'Noleggio', 'Gozzo', 'Gozzo Blu Marino', 'Comfort e tradizione con motore affidabile. Perfetto per gite lunghe.', NULL, 300.00, 8, 0, 9.20, 0, 1),

-- Gommoni sportivi
('BARCA-GOMMONE-001', 'Noleggio', 'Gommone', 'Gommone Speed 250cv', 'Adrenalina e velocità. Ideale per chi ama l\'avventura sul mare.', NULL, 450.00, 8, 0, 9.50, 1, 1),
('BARCA-GOMMONE-002', 'Noleggio', 'Gommone', 'Gommone Comfort 200cv', 'Gommone versatile con cabina confortevole.', NULL, 380.00, 10, 0, 8.80, 1, 1),
('BARCA-GOMMONE-003', 'Noleggio', 'Gommone', 'Gommone Luxury 300cv', 'Esclusività e potenza. Per chi non vuole compromessi.', NULL, 550.00, 12, 1, 10.50, 1, 1),

-- Yacht
('BARCA-YACHT-001', 'Noleggio', 'Yacht', 'Azimut 55 Fly', 'Yacht di lusso con salotto interno, cucina e 4 cabine. Esperienza premium.', NULL, 1200.00, 12, 1, 16.80, 1, 1),
('BARCA-YACHT-002', 'Noleggio', 'Yacht', 'Cranchi Endurance 41', 'Eleganza e navigabilità. Perfetto per crociere di una o più giornate.', NULL, 950.00, 10, 0, 12.50, 1, 1),

-- Barche a vela
('BARCA-VELA-001', 'Noleggio', 'Vela', 'Beneteau First 35', 'Vela sportiva con prestazioni eccellenti. Per velisti esperti.', NULL, 400.00, 6, 0, 10.65, 1, 1),
('BARCA-VELA-002', 'Noleggio', 'Vela', 'Jeanneau Sun Odyssey 45', 'Vela con comfort abitativo. Ideale per crociere tranquille.', NULL, 550.00, 8, 0, 13.80, 1, 1),

-- Piccole barche
('BARCA-PICCOLA-001', 'Noleggio', 'Barca Piccola', 'Barca Aperta 6m', 'Perfetta per principianti e famiglie con bambini.', NULL, 150.00, 4, 1, 6.00, 0, 1),
('BARCA-PICCOLA-002', 'Noleggio', 'Barca Piccola', 'Barca Aperta 7.5m', 'Ideale per gite mezzagiornata. Facile da manovrare.', NULL, 180.00, 5, 0, 7.50, 0, 1),

-- EXPERIENCES (3)
('EXP-TRAMONTO-001', 'Experience', 'Romantico', 'Cena al Tramonto con Prosecco', 'Tour della costa con cena leggera e prosecco. Per coppie romantiche.', 4, 95.00, 2, 1, NULL, NULL, 1),
('EXP-SNORKEL-001', 'Experience', 'Avventura', 'Escursione Snorkeling Capri e Anacapri', 'Mezza giornata alla scoperta dei fondali cristallini di Capri.', 5, 75.00, 8, 0, NULL, NULL, 1),
('EXP-ESCURSIONE-001', 'Experience', 'Escursione', 'Tour Costiera Amalfitana Completo', 'Giornata intera tra Positano, Amalfi e Praiano. Include pranzo.', 8, 120.00, 10, 1, NULL, NULL, 1);

-- ============================================================================
-- 5. PRODOTTO_LINGUA (Associazione prodotti a lingue - tutte le barche in IT/EN)
-- ============================================================================

INSERT INTO Prodotto_Lingua (IDProdotto, IDLingua) VALUES
-- Tutti i noleggi in italiano e inglese
('BARCA-GOZZO-001', 1), ('BARCA-GOZZO-001', 2),
('BARCA-GOZZO-002', 1), ('BARCA-GOZZO-002', 2),
('BARCA-GOMMONE-001', 1), ('BARCA-GOMMONE-001', 2),
('BARCA-GOMMONE-002', 1), ('BARCA-GOMMONE-002', 2),
('BARCA-GOMMONE-003', 1), ('BARCA-GOMMONE-003', 2),
('BARCA-YACHT-001', 1), ('BARCA-YACHT-001', 2),
('BARCA-YACHT-002', 1), ('BARCA-YACHT-002', 2),
('BARCA-VELA-001', 1), ('BARCA-VELA-001', 2),
('BARCA-VELA-002', 1), ('BARCA-VELA-002', 2),
('BARCA-PICCOLA-001', 1), ('BARCA-PICCOLA-001', 2),
('BARCA-PICCOLA-002', 1), ('BARCA-PICCOLA-002', 2),

-- Experiences in tutte le lingue
('EXP-TRAMONTO-001', 1), ('EXP-TRAMONTO-001', 2), ('EXP-TRAMONTO-001', 3), ('EXP-TRAMONTO-001', 4),
('EXP-SNORKEL-001', 1), ('EXP-SNORKEL-001', 2), ('EXP-SNORKEL-001', 3), ('EXP-SNORKEL-001', 4),
('EXP-ESCURSIONE-001', 1), ('EXP-ESCURSIONE-001', 2), ('EXP-ESCURSIONE-001', 3), ('EXP-ESCURSIONE-001', 4);

-- ============================================================================
-- 6. PRODOTTO_EXTRA (Servizi aggiuntivi opzionali)
-- ============================================================================

INSERT INTO Prodotto_Extra (IDProdotto, Nome_Extra, Descrizione_Extra, Prezzo_Extra, Opzionale) VALUES

-- Gozzo 001
('BARCA-GOZZO-001', 'Skipper Professionista', 'Skipper esperto per navigazione sicura', 100.00, 1),
('BARCA-GOZZO-001', 'Attrezzatura Snorkeling Completa', 'Maschera, pinne, tubo per 6 persone', 45.00, 1),
('BARCA-GOZZO-001', 'Assicurazione Danni Aggiuntiva', 'Copertura totale danni alla barca', 60.00, 1),

-- Gozzo 002
('BARCA-GOZZO-002', 'Skipper Professionista', 'Skipper esperto per navigazione sicura', 120.00, 1),
('BARCA-GOZZO-002', 'Cuoco a Bordo', 'Cuoco per preparare pranzo a bordo', 150.00, 1),
('BARCA-GOZZO-002', 'Attrezzatura Snorkeling Completa', 'Maschera, pinne, tubo per 8 persone', 60.00, 1),

-- Gommone 001
('BARCA-GOMMONE-001', 'Skipper Professionista', 'Skipper esperienza fuori strada', 130.00, 1),
('BARCA-GOMMONE-001', 'Ciambella Trainabile', 'Divertimento a velocità in acqua', 50.00, 1),
('BARCA-GOMMONE-001', 'GoPro Subacquea Noleggio', 'Registra i tuoi momenti migliori', 25.00, 1),

-- Gommone 002
('BARCA-GOMMONE-002', 'Skipper Professionista', 'Skipper esperto per navigazione sicura', 140.00, 1),
('BARCA-GOMMONE-002', 'Ciambella Trainabile', 'Divertimento a velocità in acqua', 50.00, 1),
('BARCA-GOMMONE-002', 'Attrezzatura Snorkeling', 'Maschera e pinne per 10 persone', 70.00, 1),

-- Gommone 003 (Luxury)
('BARCA-GOMMONE-003', 'Skipper Professionista Luxury', 'Skipper VIP con esperienza internazionale', 200.00, 1),
('BARCA-GOMMONE-003', 'Champagne Dom Pérignon', 'Bottiglia esclusiva a bordo', 120.00, 1),
('BARCA-GOMMONE-003', 'Ciambella Trainabile Gold', 'Ciambella premium con comfort massimo', 80.00, 1),

-- Yacht 001
('BARCA-YACHT-001', 'Skipper Capitano', 'Capitano con patente internazionale', 250.00, 1),
('BARCA-YACHT-001', 'Hostess Bordo', 'Hostess per servizio premium', 200.00, 1),
('BARCA-YACHT-001', 'Cena Gourmet Privata', 'Menu personalizzato chef', 400.00, 1),
('BARCA-YACHT-001', 'Drone Riprese Aeree', 'Fotografie e video drone professionali', 300.00, 1),

-- Yacht 002
('BARCA-YACHT-002', 'Skipper Capitano', 'Capitano con patente internazionale', 200.00, 1),
('BARCA-YACHT-002', 'Hostess Bordo', 'Hostess per servizio premium', 180.00, 1),
('BARCA-YACHT-002', 'Attrezzatura Snorkeling Luxury', 'Attrezzatura premium per snorkeling', 100.00, 1),

-- Vela 001
('BARCA-VELA-001', 'Skipper Professionista', 'Skipper vela con esperienza', 140.00, 1),
('BARCA-VELA-001', 'Attrezzatura Snorkeling', 'Completa per 6 persone', 50.00, 1),

-- Vela 002
('BARCA-VELA-002', 'Skipper Professionista Vela', 'Skipper esperto vela', 150.00, 1),
('BARCA-VELA-002', 'Cuoco a Bordo', 'Cuoco per crociera di più giorni', 200.00, 1),
('BARCA-VELA-002', 'Attrezzatura Snorkeling Luxury', 'Attrezzatura premium', 80.00, 1),

-- Piccole barche
('BARCA-PICCOLA-001', 'Giubbotto Salvagente Bimbi', 'Giubbotto per bambini (4 pezzi)', 20.00, 1),
('BARCA-PICCOLA-002', 'Giubbotto Salvagente Bimbi', 'Giubbotto per bambini (5 pezzi)', 25.00, 1),
('BARCA-PICCOLA-002', 'Attrezzatura Snorkeling', 'Per 5 persone', 40.00, 1),

-- Experiences
('EXP-TRAMONTO-001', 'Bottiglia Champagne Premium', 'Bollicine di qualità superiore', 80.00, 1),
('EXP-TRAMONTO-001', 'Rose per la Sorpresa', 'Mazzo di rose rosse incluso', 35.00, 1),
('EXP-SNORKEL-001', 'Fotografia Subacquea Professionale', 'Foto underwater del vostro momento', 60.00, 1),
('EXP-SNORKEL-001', 'Snack Gourmet Aggiuntivo', 'Salatini e formaggi premium', 30.00, 1),
('EXP-ESCURSIONE-001', 'Fotografia Professionale Costiera', 'Foto ricordo della giornata', 100.00, 1),
('EXP-ESCURSIONE-001', 'Cena Sotto le Stelle Aggiunta', 'Cena a bordo con vista notturna', 150.00, 1);

-- ============================================================================
-- 7. PRODOTTO_INCLUSO (Cosa è incluso nel prezzo)
-- ============================================================================

INSERT INTO Prodotto_Incluso (IDProdotto, Nome_Incluso) VALUES

-- Gozzi
('BARCA-GOZZO-001', 'Assicurazione Responsabilità Civile'),
('BARCA-GOZZO-001', 'Carburante'),
('BARCA-GOZZO-001', 'Giubbotti Salvagente'),
('BARCA-GOZZO-001', 'Pulizia Finale'),
('BARCA-GOZZO-001', 'Mappa Cartacea Costa Sorrentina'),

('BARCA-GOZZO-002', 'Assicurazione Responsabilità Civile'),
('BARCA-GOZZO-002', 'Carburante'),
('BARCA-GOZZO-002', 'Giubbotti Salvagente'),
('BARCA-GOZZO-002', 'Pulizia Finale'),
('BARCA-GOZZO-002', 'GPS Marino Professionale'),
('BARCA-GOZZO-002', 'VHF Ricetrasmettitore'),

-- Gommoni
('BARCA-GOMMONE-001', 'Assicurazione Responsabilità Civile'),
('BARCA-GOMMONE-001', 'Carburante'),
('BARCA-GOMMONE-001', 'Giubbotti Salvagente'),
('BARCA-GOMMONE-001', 'Kit Primo Soccorso'),

('BARCA-GOMMONE-002', 'Assicurazione Responsabilità Civile'),
('BARCA-GOMMONE-002', 'Carburante'),
('BARCA-GOMMONE-002', 'Giubbotti Salvagente Numero 10'),
('BARCA-GOMMONE-002', 'Cabina Climatizzata'),
('BARCA-GOMMONE-002', 'Toilette Marino'),

('BARCA-GOMMONE-003', 'Assicurazione Kasko Completa'),
('BARCA-GOMMONE-003', 'Carburante Premium'),
('BARCA-GOMMONE-003', 'Giubbotti Salvagente Luxury'),
('BARCA-GOMMONE-003', 'Sistema Audio Premium Bose'),
('BARCA-GOMMONE-003', 'Frigo a Bordo'),

-- Yacht
('BARCA-YACHT-001', 'Assicurazione Kasko Completa'),
('BARCA-YACHT-001', 'Carburante Premium'),
('BARCA-YACHT-001', 'Giubbotti Salvagente'),
('BARCA-YACHT-001', '4 Cabine Esclusive'),
('BARCA-YACHT-001', 'Cucina Attrezzata'),
('BARCA-YACHT-001', 'Salotto interno con TV'),
('BARCA-YACHT-001', 'Sistema Navigazione GPS Avanzato'),

('BARCA-YACHT-002', 'Assicurazione Kasko'),
('BARCA-YACHT-002', 'Carburante'),
('BARCA-YACHT-002', 'Giubbotti Salvagente'),
('BARCA-YACHT-002', '3 Cabine Confortevoli'),
('BARCA-YACHT-002', 'Cucina Attrezzata'),
('BARCA-YACHT-002', 'Sala Principale'),

-- Vele
('BARCA-VELA-001', 'Assicurazione Responsabilità Civile'),
('BARCA-VELA-001', 'Giubbotti Salvagente'),
('BARCA-VELA-001', 'Attrezzatura Vela Base'),
('BARCA-VELA-001', 'Mappa Nautica'),

('BARCA-VELA-002', 'Assicurazione Responsabilità Civile'),
('BARCA-VELA-002', 'Giubbotti Salvagente'),
('BARCA-VELA-002', 'Attrezzatura Vela Completa'),
('BARCA-VELA-002', '2 Cabine'),
('BARCA-VELA-002', 'Cucina'),
('BARCA-VELA-002', 'Sistema Autopilota'),

-- Piccole barche
('BARCA-PICCOLA-001', 'Assicurazione Responsabilità Civile'),
('BARCA-PICCOLA-001', 'Giubbotti Salvagente'),
('BARCA-PICCOLA-001', 'Mappa Cartacea'),

('BARCA-PICCOLA-002', 'Assicurazione Responsabilità Civile'),
('BARCA-PICCOLA-002', 'Giubbotti Salvagente'),
('BARCA-PICCOLA-002', 'Mappa Digitale'),
('BARCA-PICCOLA-002', 'Ombrellone da Sole'),

-- Experiences
('EXP-TRAMONTO-001', 'Guida Turistica Italiana'),
('EXP-TRAMONTO-001', 'Prosecco di Qualità'),
('EXP-TRAMONTO-001', 'Stuzzichini Gourmet'),
('EXP-TRAMONTO-001', 'Giubbotti Salvagente'),
('EXP-TRAMONTO-001', 'Coperta Elegante'),

('EXP-SNORKEL-001', 'Guida Turistica Specializzata'),
('EXP-SNORKEL-001', 'Attrezzatura Snorkeling Completa'),
('EXP-SNORKEL-001', 'Giubbotti Salvagente'),
('EXP-SNORKEL-001', 'Snack e Bevande Fresche'),
('EXP-SNORKEL-001', 'Asciugamani Premium'),

('EXP-ESCURSIONE-001', 'Guida Turistica Esperta'),
('EXP-ESCURSIONE-001', 'Pranzo Leggero'),
('EXP-ESCURSIONE-001', 'Bevande Fresche Illimitate'),
('EXP-ESCURSIONE-001', 'Giubbotti Salvagente'),
('EXP-ESCURSIONE-001', 'Attrezzatura Snorkeling'),
('EXP-ESCURSIONE-001', 'Ombrellone da Sole');

-- ============================================================================
-- 8. ARTICOLI BLOG (20 articoli ricchi)
-- ============================================================================

INSERT INTO Articolo_Blog (IDAutore, Titolo, Descrizione_Breve, Contenuto, Data_Pubblicazione, Tempo_Lettura, Pubblicato) VALUES

(1, 'Le 10 Cale Più Belle della Costiera Sorrentina', 'Scopri le spiagge nascoste e i paesaggi mozzafiato della costiera.', 'Questo articolo esplora le dieci cale più affascinanti della costiera sorrentina, dalle celebri Tre Sorelle alle meno conosciute ma altrettanto stupende Marina di Puolo e Cala dell\'Infreschi. Ideale per chi vuole scoprire angoli paradisiaci lontano dalla folla turistica. Ogni cala ha caratteristiche uniche: acque cristalline, spiagge di ciottoli o sabbia, e splendidi sentieri costieri per le escursioni.', '2025-01-15 10:30:00', 8, 1),

(1, 'Guida Completa allo Snorkeling a Capri', 'Tutto ciò che devi sapere per un\'indimenticabile giornata di snorkeling.', 'Capri è una delle mete più straordinarie per lo snorkeling nel Tirreno. In questa guida troverai informazioni dettagliate sui migliori spot, la fauna marina che potrai incontrare, consigli pratici per la sicurezza e i periodi migliori per visitare. Scopri le grotte sottomarine, gli scogli pieni di vita e le praterie di Posidonia che rendono Capri un paradiso subacqueo.', '2025-01-12 14:45:00', 10, 1),

(1, 'Come Ormeggiare in Sicurezza: Guida per Principianti', 'Tecniche di ormeggio essenziali per chi inizia a navigare.', 'L\'ormeggio è una delle abilità fondamentali di ogni navigatore. In questo articolo tecnico insegniamo i metodi classici di ormeggio nei porti della costiera, come avvicinarsi alla banchina, come utilizzare i cavi e le manovre di retromarcia. Imparerai a riconoscere i punti d\'ormeggio sicuri e come proteggerti dalle correnti e dai venti forti.', '2025-01-10 09:20:00', 6, 1),

(2, 'Lusso in Mare: Yacht e Charter Esclusivi', 'Vivi l\'esperienza di un noleggio di lusso nel golfo di Napoli.', 'Uno yacht privato rappresenta il massimo dell\'esclusività. Questo articolo vi guida alla scoperta degli yacht di lusso disponibili nel nostro catalogo, dalle camere sontuose alle cucine attrezzate, dai sistemi di navigazione avanzati ai servizi premium. Scopri come trasformare una giornata in mare in un\'esperienza indimenticabile di classe e raffinatezza.', '2025-01-08 16:10:00', 7, 1),

(1, 'Navigazione Tradizionale: Barca a Vela per Crociere', 'L\'arte della vela e il fascino della navigazione classica.', 'Le barche a vela rappresentano l\'essenza della navigazione tradizionale. Questo articolo esplora il fascino della vela, come una crociera a vela unisce sport e relax, e quali sono i vantaggi di scegliere una barca a vela rispetto a un motore. Leggi consigli pratici su come preparare una crociera a vela sulla costiera amalfitana e su come vivere appieno questa esperienza.', '2025-01-05 11:35:00', 9, 1),

(2, 'Sicurezza in Mare: Protocolli e Attrezzature Obbligatorie', 'Tutto ciò che devi sapere per navigare in sicurezza.', 'La sicurezza è la priorità numero uno quando si naviga. Questo articolo dettagliato copre tutti gli obblighi di legge, le attrezzature di sicurezza necessarie, i protocolli di soccorso, come utilizzare i giubbotti salvagente, i segnali di emergenza e le comunicazioni via radio. Scopri come prepararti per affrontare situazioni di emergenza e come proteggere te stesso e i tuoi compagni.', '2025-01-03 13:50:00', 12, 1),

(1, 'Positano e Amalfi: Villaggi Storici della Costiera', 'Un viaggio attraverso i villaggi più affascinanti della costiera amalfitana.', 'La costiera amalfitana è famosa per i suoi villaggi incantevoli. Questo articolo porta il lettore alla scoperta dei dettagli storici di Positano, conosciuto per le case colorate e gli hotel esclusivi, e di Amalfi, città ricca di storia medievale e arte. Scopri cosa fare durante una visita a terra e come integrare il viaggio in barca con escursioni nei villaggi costieri.', '2025-12-28 10:15:00', 8, 1),

(1, 'Fenomeno del Bioluminescenza nel Golfo di Napoli', 'Scopri la magia della luce vivente nei fondali marini.', 'Il golfo di Napoli è uno dei pochi luoghi dove è possibile osservare il fenomeno della bioluminescenza marina. Questo articolo spiega la scienza dietro a questo spettacolo naturale, i periodi migliori per osservarlo, e come organizzare una spedizione notturna in barca per assistere a questo meraviglia della natura. Un\'esperienza che difficilmente dimenticherai.', '2025-12-25 15:40:00', 7, 1),

(2, 'Ricette di Cucina Marinara: Piatti da Preparare a Bordo', 'Delizie culinarie da gustare durante una crociera in mare.', 'La cucina marinara è basata su ingredienti freschi e semplici. Questo articolo condivide ricette autentiche della costiera che puoi preparare nella cucina della tua barca. Dai piatti di pasta ai frutti di mare, dai pesce al forno alle insalate di mare, scopri come trasformare il noleggio di una barca in un\'esperienza culinaria indimenticabile.', '2025-12-20 09:25:00', 6, 1),

(1, 'Flora e Fauna Marina della Costiera Sorrentina', 'Un approfondimento sulla biodiversità sottomarina della costa campana.', 'La costiera sorrentina è ricca di vita marina. Questo articolo illustra le specie ittiche che incontrerai durante lo snorkeling, dai dentici alle cernie, dalle murene alle stelle marine, e il loro comportamento naturale. Scopri anche la flora marina come la Posidonia oceanica e come essa contribuisce all\'ecosistema marino e alla qualità dell\'acqua.', '2025-12-18 11:50:00', 10, 1),

(2, 'Meteo e Stagioni: Quando Navigare nella Costiera', 'Una guida alle condizioni climatiche e ai periodi ideali per salpare.', 'Ogni stagione offre condizioni diverse per la navigazione. Questo articolo analizza i venti, le correnti, e le caratteristiche meteorologiche di primavera, estate, autunno e inverno nel golfo di Napoli. Scopri i periodi ideali per diversi tipi di navigazione e come leggere le previsioni meteo per pianificare la tua gita in mare in sicurezza e comfort.', '2025-12-15 14:20:00', 8, 1),

(1, 'Sorrento: La Perla della Costiera', 'Alla scoperta della affascinante città di Sorrento.', 'Sorrento è una delle destinazioni più romantiche d\'Italia. Questo articolo esplora la storia di questa affascinante città, i suoi monumenti, le piazze pittoresche e gli hotel di lusso. Scopri cosa fare a terra e come le tue gite in barca possono integrarsi con una visita a Sorrento, includendo tours del centro storico e cene in ristoranti tradizionali.', '2025-12-10 10:05:00', 7, 1),

(1, 'Fotografia Subacquea: Cattura i Tuoi Momenti Migliori', 'Consigli pratici per fotografare il mondo sottomarino.', 'Vuoi immortalare i tuoi momenti subacquei? Questo articolo fornisce consigli su fotocamere e videocamere subacquee, tecniche di fotografia sottomarina, illuminazione, composizione, e post-produzione. Scopri come catturare la bellezza dei fondali e condividere le tue avventure sui social media con fotografie straordinarie.', '2025-12-08 13:35:00', 9, 1),

(2, 'Sostenibilità Marina: Come Navigare Responsabilmente', 'Pratiche ecologiche per proteggere l\'ecosistema marino.', 'La navigazione responsabile è essenziale per preservare la bellezza del nostro mare. Questo articolo affrontsa i temi della sostenibilità marina, da come ridurre l\'uso di combustibili fossili a come evitare inquinamento, alla scelta di prodotti eco-friendly a bordo. Scopri come ogni navigatore può contribuire alla protezione dell\'ambiente marino.', '2025-12-05 16:55:00', 8, 1),

(1, 'Isola di Capri: Leggenda e Realtà', 'Mito, storia e meraviglie dell\'isola più celebre del golfo.', 'Capri è circondata da leggende e fascino. Questo articolo racconta la storia affascinante dell\'isola, dalla Grotta Azzurra ai faraglioni, dai siti archeologici alle celebrità che l\'hanno resa famosa. Scopri come organizzare una giornata perfetta a Capri partendo da una barca noleggiata e quali sono i must-see dell\'isola.', '2025-12-03 11:20:00', 10, 1),

(2, 'Noleggio Barca: Come Scegliere il Natante Perfetto', 'Guida pratica alla scelta della barca giusta per le tue esigenze.', 'Con così tante opzioni disponibili, come scegliere la giusta barca da noleggiare? Questo articolo guida il lettore attraverso i diversi tipi di barche, le loro caratteristiche, i vantaggi e gli svantaggi. Dalla barca piccola al gommone sportivo, dallo yacht di lusso alla barca a vela, scopri quale è più adatta ai tuoi piani e alle tue competenze.', '2025-11-30 09:45:00', 11, 1),

(1, 'Ballate di Marinai: Tradizioni Nautiche della Costiera', 'Leggende, canzoni e tradizioni marinare della costiera campana.', 'La costiera sorrentina è ricca di tradizioni marittime millenarie. Questo articolo esplora le leggende locali, le ballate dei marinai, le tradizioni di pesca, e il modo di vivere delle comunità costiere storiche. Scopri come la cultura marina ha plasmato l\'identità della regione e come questi insegnamenti si riflettono nella navigazione moderna.', '2025-11-28 14:10:00', 8, 1),

(2, 'Wellness in Mare: Relax e Benessere a Bordo', 'Come trasformare una gita in barca in una spa galleggiante.', 'Il mare ha proprietà terapeutiche straordinarie. Questo articolo esplora come integrare pratiche di wellness durante una crociera, dalla meditazione al suono delle onde, ai benefici dello yoga sul mare, ai bagni rinfrescanti in acque cristalline. Scopri come una giornata in barca diventa un\'esperienza di rigenerazione fisica e mentale.', '2025-11-25 10:30:00', 7, 1),

(1, 'Isola di Ischia: Vulcani, Terme e Bellezze Naturali', 'Esplorando l\'isola più grande del golfo di Napoli.', 'Ischia, l\'isola verde del golfo, offre paesaggi vulcanici unici e sorgenti termali naturali. Questo articolo presenta i principali siti di interesse come il Monte Epomeo, le spiagge di sabbia scura, le terme naturali di Maronti, e i villaggi pittoreschi. Scopri come organizzare un\'escursione in barca a Ischia integrando bagni in sorgenti calde e explore vulcaniche.', '2025-11-22 15:50:00', 9, 1),

(2, 'Regolamenti Marittimi: Diritti e Doveri del Navigatore', 'Conoscenze legali essenziali per navigare nel Tirreno.', 'Ogni navigatore deve conoscere le leggi e i regolamenti marittimi che disciplinano la navigazione. Questo articolo copre i principali regolamenti, dai limiti di velocità alle aree protette, dalla documentazione necessaria ai doveri verso l\'ambiente. Scopri i tuoi diritti come noleggiatore di barca e le responsabilità che assumi.', '2025-11-20 12:15:00', 10, 1);

-- ============================================================================
-- 9. ARTICOLO_BLOG_EXTRA (Sezioni aggiuntive per gli articoli)
-- ============================================================================

INSERT INTO Articolo_Blog_Extra (IDArticolo, Titolo, Elemento, Ordine) VALUES

-- Articolo 1: Le 10 Cale
(1, '🏖️ Top 5 Cale da Non Perdere', 'Cala dell\'Infreschi - Splendida cala con spiaggia di sabbia bianca', 1),
(1, '🏖️ Top 5 Cale da Non Perdere', 'Marina di Puolo - Villaggio di pescatori pittoresco', 2),
(1, '🏖️ Top 5 Cale da Non Perdere', 'Tre Sorelle - Tre faraglioni iconici', 3),
(1, '🏖️ Top 5 Cale da Non Perdere', 'Cala di Mitigliano - Acque cristalline', 4),
(1, '🏖️ Top 5 Cale da Non Perdere', 'Cala dei Limoni - Piccolo paradiso per lo snorkeling', 5),
(1, '📋 Cosa Portare', 'Maschera e boccaglio in buone condizioni', 1),
(1, '📋 Cosa Portare', 'Crema solare con protezione forte (SPF 50+)', 2),
(1, '📋 Cosa Portare', 'Asciugamani microfibra (secchi subito)', 3),
(1, '📋 Cosa Portare', 'Snack e acqua abbondante', 4),

-- Articolo 2: Snorkeling a Capri
(2, '🐠 Fauna Marina', 'Dentici - Pesci d\'argento e molto diffidenti', 1),
(2, '🐠 Fauna Marina', 'Cernie - Predatori territoriali, affascinanti da osservare', 2),
(2, '🐠 Fauna Marina', 'Stelle Marine e Ricci di Mare - Fauna colorata', 3),
(2, '🐠 Fauna Marina', 'Murene - Creature affascinanti nelle fessure', 4),
(2, '📍 Spot Migliori', 'Grotta Azzurra - Lo spot più famoso', 1),
(2, '📍 Spot Migliori', 'Faraglioni - Pareti rocciose ricche di vita', 2),
(2, '📍 Spot Migliori', 'Punta Carena - Reef naturale', 3),
(2, '⏰ Periodo Migliore', 'Maggio-Settembre per condizioni ottimali', 1),
(2, '⏰ Periodo Migliore', 'Giugno-Agosto per acqua più calda', 2),

-- Articolo 3: Ormeggio
(3, '⚓ Tecniche Base', 'Ormeggio alla banchina con due cavi', 1),
(3, '⚓ Tecniche Base', 'Retromarcia controllata e parallela', 2),
(3, '⚓ Tecniche Base', 'Protezione dei parabordi', 3),
(3, '⚓ Tecniche Base', 'Lettura dei venti e delle correnti', 4),
(3, '🛟 Sicurezza', 'Mantenere sempre contatto radio con la torre di controllo', 1),
(3, '🛟 Sicurezza', 'Verificare la profondità dell\'acqua con scandaglio', 2),
(3, '🛟 Sicurezza', 'Avere a disposizione ancora di riserva', 3),

-- Articolo 4: Lusso in Mare
(4, '💎 Yacht nel Nostro Catalogo', 'Azimut 55 Fly - Lusso e spazio', 1),
(4, '💎 Yacht nel Nostro Catalogo', 'Cranchi Endurance 41 - Eleganza classica', 2),
(4, '✨ Servizi Premium', 'Skipper capitano internazionale', 1),
(4, '✨ Servizi Premium', 'Hostess di bordo per servizio impeccabile', 2),
(4, '✨ Servizi Premium', 'Cucina gourmet con chef a bordo', 3),
(4, '✨ Servizi Premium', 'Sistema audio e home theater a bordo', 4),

-- Articolo 5: Vela
(5, '⛵ Tipi di Barca a Vela', 'Beneteau First 35 - Sportiva e dinamica', 1),
(5, '⛵ Tipi di Barca a Vela', 'Jeanneau Sun Odyssey 45 - Comfort abitativo', 2),
(5, '🌊 Manovre Base', 'Virata - Cambio di ammuzzo alla virata', 1),
(5, '🌊 Manovre Base', 'Strambata - Giro del boma con poppa al vento', 2),
(5, '🌊 Manovre Base', 'Orzata - Salire verso il vento', 3),

-- Articolo 6: Sicurezza
(6, '🆘 Attrezzature Obbligatorie', 'Giubbotti salvagente per tutto l\'equipaggio', 1),
(6, '🆘 Attrezzature Obbligatorie', 'Zattera di salvataggio ispezionata', 2),
(6, '🆘 Attrezzature Obbligatorie', 'Estintori e kit primo soccorso', 3),
(6, '🆘 Attrezzature Obbligatorie', 'Fari e segnali luminosi notturni', 4),
(6, '📞 Numeri di Emergenza', 'Capitaneria di Porto: 1530', 1),
(6, '📞 Numeri di Emergenza', 'Guardia Costiera: VHF Canale 16', 2),
(6, '📞 Numeri di Emergenza', 'Emergenze Mediche: 118', 3),

-- Articoli successivi (more brevemente per risparmiare spazio)
(7, '🏘️ Cosa Vedere', 'Piazza Positano - Centro storico pittoresco', 1),
(7, '🏘️ Cosa Vedere', 'Spiaggia di Positano - Sabbia e bohemien', 2),
(7, '🏘️ Cosa Vedere', 'Duomo di Amalfi - Capolavoro architettonico', 3),

(8, '🌟 Quando Osservare', 'Giugno-Luglio per fenomeno più intenso', 1),
(8, '🌟 Quando Osservare', 'Notti senza luna per visibilità massima', 2),

(9, '👨‍🍳 Ricette Semplici', 'Spaghetti alle Vongole Veraci', 1),
(9, '👨‍🍳 Ricette Semplici', 'Branzino al Forno con Limone', 2),
(9, '👨‍🍳 Ricette Semplici', 'Insalata di Frutti di Mare', 3),

(10, '🐟 Specie Comuni', 'Dentice - Pesce argentato velocissimo', 1),
(10, '🐟 Specie Comuni', 'Cernia - Pesce grande e territoriale', 2),
(10, '🌿 Flora Marina', 'Posidonia Oceanica - Pianta marina protetta', 1),

(11, '🌬️ Venti Principali', 'Maestrale da Nord-Ovest con raffiche forti', 1),
(11, '🌬️ Venti Principali', 'Scirocco da Sud-Est caldo e umido', 2),
(11, '🌬️ Venti Principali', 'Grecale da Nord-Est secco', 3),

(12, '🏛️ Monumenti', 'Duomo di Sorrento - Importante chiesa medievale', 1),
(12, '🏛️ Monumenti', 'Basilica Sant\'Antonino - Patrono della città', 2),

(13, '📷 Attrezzatura', 'GoPro Hero 12 - Compatta e versatile', 1),
(13, '📷 Attrezzatura', 'Custodia stagna professionale - Protezione totale', 2),
(13, '💡 Consigli di Fotografia', 'Evita il flash - Usa la luce naturale', 1),
(13, '💡 Consigli di Fotografia', 'Scatta da angolazioni diverse', 2),

(14, '♻️ Pratiche Ecologiche', 'Usa biodegradabili per pulizia a bordo', 1),
(14, '♻️ Pratiche Ecologiche', 'Non gettare spazzatura in mare', 2),
(14, '♻️ Pratiche Ecologiche', 'Rispetta le aree marine protette', 3),

(15, '🗿 Faraglioni', 'Tre enormi monoliti di roccia calcarea', 1),
(15, '🗿 Faraglioni', 'Grotta Azzurra - Fenomeno ottico straordinario', 2),
(15, '🗿 Faraglioni', 'Villa Jovis di Tiberio - Rovine imperiali', 3),

(16, '🚤 Barca Piccola', 'Perfetta per principianti e famiglie', 1),
(16, '🚤 Barca Piccola', 'Gozzo - Tradizionale e stabile', 2),
(16, '🚤 Barca Piccola', 'Gommone - Veloce e dinamico', 3),
(16, '⛵ Barca a Vela', 'Per navigatori esperti e romantici', 1),
(16, '🛥️ Yacht', 'Per lusso e confort massimi', 1),

(17, '🎵 Ballate Marinare', 'La Leggenda di Parthenope - Mitologia locale', 1),
(17, '🎵 Ballate Marinare', 'Canzoni dei Pescatori - Tradizione orale', 2),

(18, '🧘 Yoga a Bordo', 'Pratiche meditative con vista sul mare', 1),
(18, '🧘 Yoga a Bordo', 'Bagni termici naturali a Ischia', 2),
(18, '💆 Trattamenti Marini', 'Algoterapia con alghe biologiche', 1),

(19, '🌋 Vulcano', 'Monte Epomeo - Escursione escursionistica', 1),
(19, '🌋 Vulcano', 'Crateri Spenti - Geologia affascinante', 2),
(19, '♨️ Terme', 'Maronti - Sorgenti naturali calde', 1),
(19, '♨️ Terme', 'Cavascura - Grotta termale naturale', 2),

(20, '⚖️ Diritti del Navigatore', 'Libertà di navigazione in acque internazionali', 1),
(20, '⚖️ Diritti del Navigatore', 'Protezione dai danni da maltempo', 2),
(20, '⚖️ Diritti del Navigatore', 'Accesso ai porti di soccorso', 3),
(20, '📋 Doveri', 'Rispetto delle zone protette marine', 1),
(20, '📋 Doveri', 'Comunicazione frequente via radio', 2);

-- ============================================================================
-- 10. MEDIA (Foto associate a utenti, prodotti e articoli)
-- ============================================================================

INSERT INTO Media (URL_Media, Testo_Alternativo, Tipo_Media, IDUtente, IDProdotto, IDArticolo) VALUES

-- Utenti (Avatar)
('../img/Azimut_55_fly_2.webp', 'Avatar Marco Rossi Admin', 'Immagine', 1, NULL, NULL),
('../img/Azimut_55_fly_2.webp', 'Avatar Lucia De Luca Admin', 'Immagine', 2, NULL, NULL),
('../img/Azimut_55_fly_2.webp', 'Avatar Giovanni Ferraro', 'Immagine', 3, NULL, NULL),
('../img/Azimut_55_fly_2.webp', 'Avatar Sofia Esposito', 'Immagine', 4, NULL, NULL),

-- Gozzi
('../img/Azimut_55_fly_2.webp', 'Gozzo Sorrentino Classico in mare', 'Immagine', NULL, 'BARCA-GOZZO-001', NULL),
('../img/Azimut_55_fly_2.webp', 'Gozzo Blu Marino al tramonto', 'Immagine', NULL, 'BARCA-GOZZO-002', NULL),

-- Gommoni
('../img/Azimut_55_fly_2.webp', 'Gommone Speed 250cv saltello', 'Immagine', NULL, 'BARCA-GOMMONE-001', NULL),
('../img/Azimut_55_fly_2.webp', 'Gommone Comfort 200cv in navigazione', 'Immagine', NULL, 'BARCA-GOMMONE-002', NULL),
('../img/Azimut_55_fly_2.webp', 'Gommone Luxury 300cv cabina interna', 'Immagine', NULL, 'BARCA-GOMMONE-003', NULL),

-- Yacht
('../img/Azimut_55_fly_2.webp', 'Azimut 55 Fly lussuoso', 'Immagine', NULL, 'BARCA-YACHT-001', NULL),
('../img/Azimut_55_fly_2.webp', 'Cranchi Endurance 41 elegante', 'Immagine', NULL, 'BARCA-YACHT-002', NULL),

-- Vele
('../img/Azimut_55_fly_2.webp', 'Beneteau First 35 a vela', 'Immagine', NULL, 'BARCA-VELA-001', NULL),
('../img/Azimut_55_fly_2.webp', 'Jeanneau Sun Odyssey 45 in navigazione', 'Immagine', NULL, 'BARCA-VELA-002', NULL),

-- Piccole barche
('../img/Azimut_55_fly_2.webp', 'Barca Aperta 6m per principianti', 'Immagine', NULL, 'BARCA-PICCOLA-001', NULL),
('../img/Azimut_55_fly_2.webp', 'Barca Aperta 7.5m gita mezzagiornata', 'Immagine', NULL, 'BARCA-PICCOLA-002', NULL),

-- Experiences
('../img/Azimut_55_fly_2.webp', 'Coppia al tramonto con prosecco', 'Immagine', NULL, 'EXP-TRAMONTO-001', NULL),
('../img/Azimut_55_fly_2.webp', 'Snorkeling a Capri con pesci', 'Immagine', NULL, 'EXP-SNORKEL-001', NULL),
('../img/Azimut_55_fly_2.webp', 'Costiera Amalfitana vista dal mare', 'Immagine', NULL, 'EXP-ESCURSIONE-001', NULL),

-- Articoli
('../img/Azimut_55_fly_2.webp', 'Cala dell\'Infreschi bellissima', 'Immagine', NULL, NULL, 1),
('../img/Azimut_55_fly_2.webp', 'Snorkel a Capri con pesce dorato', 'Immagine', NULL, NULL, 2),
('../img/Azimut_55_fly_2.webp', 'Ormeggio professionale al porto', 'Immagine', NULL, NULL, 3),
('../img/Azimut_55_fly_2.webp', 'Yacht di lusso nel golfo', 'Immagine', NULL, NULL, 4),
('../img/Azimut_55_fly_2.webp', 'Barca a vela al tramonto', 'Immagine', NULL, NULL, 5);

-- ============================================================================
-- 11. PRENOTAZIONI (35 prenotazioni in vari stati)
-- ============================================================================

INSERT INTO Prenotazione (IDUtente, IDProdotto, Data_Ora_Inizio, Data_Ora_Fine, Skipper_Richiesto, Prezzo_Totale, Metodo_Pagamento, Stato_Prenotazione, Note_Addizionali) VALUES

-- Prenotazioni Confermata Giovanni (IDUtente 3)
(3, 'BARCA-GOZZO-001', '2025-06-15 09:00:00', '2025-06-15 18:00:00', 0, 280.00, 'Bonifico', 'Confermata', 'Prenotazione per gita in famiglia'),
(3, 'BARCA-PICCOLA-001', '2025-07-10 10:00:00', '2025-07-10 13:00:00', 0, 150.00, 'Carta di Credito', 'Confermata', 'Lezione per bambini'),

-- Prenotazioni In Attesa Sofia (IDUtente 4)
(4, 'BARCA-GOMMONE-001', '2025-08-20 14:00:00', '2025-08-20 19:00:00', 1, 450.00, 'Contanti', 'In Attesa', 'Chiesta navigazione con Skipper'),
(4, 'EXP-TRAMONTO-001', '2025-07-25 19:00:00', '2025-07-25 23:00:00', 0, 95.00, 'Carta di Credito', 'In Attesa', 'Prenotazione per anniversario'),

-- Prenotazioni Confermata Andrea (IDUtente 5)
(5, 'BARCA-YACHT-001', '2025-09-01 10:00:00', '2025-09-03 10:00:00', 1, 2400.00, 'Bonifico', 'Confermata', 'Crociera di 2 giorni con skipper e hostess'),
(5, 'BARCA-VELA-002', '2025-08-05 08:00:00', '2025-08-05 17:00:00', 1, 550.00, 'Carta di Credito', 'Confermata', 'Navigazione a vela con cuoco'),

-- Prenotazioni In Attesa Francesca (IDUtente 6)
(6, 'EXP-SNORKEL-001', '2025-07-15 09:00:00', '2025-07-15 14:00:00', 0, 75.00, 'Contanti', 'In Attesa', 'Escursione snorkeling Capri'),
(6, 'BARCA-PICCOLA-002', '2025-06-20 15:00:00', '2025-06-20 18:00:00', 0, 180.00, 'Carta di Credito', 'In Attesa', 'Gita al tramonto'),

-- Prenotazioni Confermata Riccardo (IDUtente 7)
(7, 'BARCA-GOMMONE-002', '2025-07-22 11:00:00', '2025-07-22 17:00:00', 0, 380.00, 'Bonifico', 'Confermata', 'Escursione con amici'),
(7, 'BARCA-YACHT-002', '2025-08-10 09:00:00', '2025-08-10 18:00:00', 1, 950.00, 'Carta di Credito', 'Confermata', 'Giornata di lusso con skipper'),

-- Prenotazioni In Attesa Elena (IDUtente 8)
(8, 'EXP-ESCURSIONE-001', '2025-09-15 08:00:00', '2025-09-15 18:00:00', 0, 120.00, 'Contanti', 'In Attesa', 'Escursione costiera completa'),
(8, 'BARCA-VELA-001', '2025-08-28 10:00:00', '2025-08-28 16:00:00', 1, 400.00, 'Carta di Credito', 'In Attesa', 'Vela sportiva con skipper'),

-- Prenotazioni Cancellata Giovanni (IDUtente 3)
(3, 'BARCA-GOMMONE-003', '2025-06-08 13:00:00', '2025-06-08 18:00:00', 1, 550.00, 'Bonifico', 'Cancellata', 'Cancellato per maltempo'),

-- Prenotazioni Confermata Sofia (IDUtente 4)
(4, 'BARCA-PICCOLA-001', '2025-09-05 10:00:00', '2025-09-05 14:00:00', 0, 150.00, 'Carta di Credito', 'Confermata', 'Gita pomeridiana'),

-- Prenotazioni In Attesa Andrea (IDUtente 5)
(5, 'EXP-TRAMONTO-001', '2025-08-25 19:30:00', '2025-08-25 23:30:00', 0, 95.00, 'Contanti', 'In Attesa', 'Cena romantica al tramonto'),

-- Più prenotazioni per completare
(3, 'EXP-SNORKEL-001', '2025-07-30 08:30:00', '2025-07-30 13:30:00', 0, 75.00, 'Carta di Credito', 'Confermata', 'Snorkeling con famiglia'),
(4, 'BARCA-VELA-002', '2025-08-15 09:00:00', '2025-08-15 17:00:00', 1, 550.00, 'Bonifico', 'Confermata', 'Navigazione a vela'),
(5, 'BARCA-GOZZO-002', '2025-09-08 10:00:00', '2025-09-08 18:00:00', 0, 300.00, 'Contanti', 'In Attesa', 'Noleggio per amici'),
(6, 'BARCA-GOMMONE-001', '2025-07-18 14:00:00', '2025-07-18 19:00:00', 1, 450.00, 'Carta di Credito', 'Confermata', 'Adrenalina con skipper'),
(7, 'EXP-ESCURSIONE-001', '2025-08-30 08:00:00', '2025-08-30 18:00:00', 0, 120.00, 'Bonifico', 'In Attesa', 'Tour amalfitano'),
(8, 'BARCA-PICCOLA-002', '2025-09-10 16:00:00', '2025-09-10 19:00:00', 0, 180.00, 'Contanti', 'Confermata', 'Gita tramonto'),
(3, 'BARCA-YACHT-001', '2025-09-20 10:00:00', '2025-09-21 10:00:00', 1, 1200.00, 'Carta di Credito', 'In Attesa', 'Crociera lusso'),
(4, 'BARCA-GOMMONE-002', '2025-07-28 11:00:00', '2025-07-28 17:00:00', 0, 380.00, 'Bonifico', 'Confermata', 'Escursione veloce'),
(5, 'EXP-TRAMONTO-001', '2025-09-12 19:00:00', '2025-09-12 23:00:00', 0, 95.00, 'Contanti', 'Confermata', 'Anniversario'),
(6, 'BARCA-VELA-001', '2025-08-08 10:00:00', '2025-08-08 16:00:00', 1, 400.00, 'Carta di Credito', 'In Attesa', 'Vela sportiva'),
(7, 'BARCA-PICCOLA-001', '2025-07-12 09:00:00', '2025-07-12 12:00:00', 0, 150.00, 'Bonifico', 'Confermata', 'Lezione principianti'),
(8, 'BARCA-GOZZO-001', '2025-08-22 10:00:00', '2025-08-22 18:00:00', 0, 280.00, 'Contanti', 'Confermata', 'Gita classica'),
(3, 'EXP-ESCURSIONE-001', '2025-09-25 08:00:00', '2025-09-25 18:00:00', 0, 120.00, 'Carta di Credito', 'In Attesa', 'Escursione completa'),
(4, 'BARCA-YACHT-002', '2025-08-18 09:00:00', '2025-08-18 18:00:00', 1, 950.00, 'Bonifico', 'Confermata', 'Lusso giornaliero'),
(5, 'BARCA-GOMMONE-003', '2025-07-05 14:00:00', '2025-07-05 19:00:00', 1, 550.00, 'Contanti', 'In Attesa', 'Adrenalina max'),
(6, 'EXP-SNORKEL-001', '2025-08-12 09:00:00', '2025-08-12 14:00:00', 0, 75.00, 'Carta di Credito', 'Confermata', 'Snorkeling avanzato'),
(7, 'BARCA-GOZZO-002', '2025-09-03 10:00:00', '2025-09-03 18:00:00', 0, 300.00, 'Bonifico', 'In Attesa', 'Gita con famiglia'),
(8, 'BARCA-VELA-002', '2025-08-02 08:00:00', '2025-08-02 17:00:00', 1, 550.00, 'Contanti', 'Confermata', 'Navigazione vela'),
(3, 'BARCA-PICCOLA-002', '2025-07-02 15:00:00', '2025-07-02 18:00:00', 0, 180.00, 'Carta di Credito', 'In Attesa', 'Tramonto dal mare'),
(4, 'BARCA-GOZZO-001', '2025-06-25 09:00:00', '2025-06-25 18:00:00', 0, 280.00, 'Bonifico', 'Confermata', 'Weekend marino'),
(5, 'EXP-TRAMONTO-001', '2025-08-08 19:30:00', '2025-08-08 23:30:00', 0, 95.00, 'Contanti', 'In Attesa', 'Data speciale'),
(6, 'BARCA-GOMMONE-001', '2025-09-30 14:00:00', '2025-09-30 19:00:00', 1, 450.00, 'Carta di Credito', 'Confermata', 'Avventura');

-- ============================================================================
-- 12. INDISPONIBILITA (Giorni di manutenzione e indisponibilità)
-- ============================================================================

INSERT INTO Indisponibilita (IDProdotto, Data_Inizio, Data_Fine, Motivo, Creato_Da) VALUES

-- Manutenzione gozzhi
('BARCA-GOZZO-001', '2025-06-01 00:00:00', '2025-06-05 23:59:59', 'Manutenzione motore e vernice', 1),
('BARCA-GOZZO-002', '2025-05-20 00:00:00', '2025-05-25 23:59:59', 'Revisione carburante e filtri', 1),

-- Manutenzione gommoni
('BARCA-GOMMONE-001', '2025-07-01 00:00:00', '2025-07-03 23:59:59', 'Pulizia cambusa e sanitari', 2),
('BARCA-GOMMONE-002', '2025-08-15 00:00:00', '2025-08-18 23:59:59', 'Manutenzione climatizzazione', 1),
('BARCA-GOMMONE-003', '2025-06-10 00:00:00', '2025-06-14 23:59:59', 'Revisione completa motori', 2),

-- Manutenzione yacht
('BARCA-YACHT-001', '2025-05-01 00:00:00', '2025-05-10 23:59:59', 'Manutenzione navigatore GPS e rotte', 1),
('BARCA-YACHT-002', '2025-07-20 00:00:00', '2025-07-25 23:59:59', 'Pulizia interna e cambusa', 2),

-- Manutenzione vele
('BARCA-VELA-001', '2025-06-20 00:00:00', '2025-06-23 23:59:59', 'Ispezione vela e cavi', 1),
('BARCA-VELA-002', '2025-08-25 00:00:00', '2025-08-28 23:59:59', 'Manutenzione autopilota', 2),

-- Manutenzione piccole barche
('BARCA-PICCOLA-001', '2025-07-10 00:00:00', '2025-07-12 23:59:59', 'Verifica sicurezza attrezzatura', 1),
('BARCA-PICCOLA-002', '2025-09-01 00:00:00', '2025-09-02 23:59:59', 'Pulizia e sanificazione', 2),

-- Manutenzione experiences
('EXP-TRAMONTO-001', '2025-06-15 00:00:00', '2025-06-16 23:59:59', 'Preparazione menu e attrezzature', 1),
('EXP-SNORKEL-001', '2025-07-05 00:00:00', '2025-07-06 23:59:59', 'Controllo attrezzatura snorkeling', 2),
('EXP-ESCURSIONE-001', '2025-08-20 00:00:00', '2025-08-21 23:59:59', 'Preparazione guide turistiche', 1);

-- ============================================================================
-- Riabilita controlli chiavi esterne
-- ============================================================================
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- Fine popolamento dati
-- ============================================================================
COMMIT;