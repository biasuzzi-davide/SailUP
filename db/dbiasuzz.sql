-- phpMyAdmin SQL Dump
-- version 5.2.2deb1+deb13u1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Dic 23, 2025 alle 12:39
-- Versione del server: 11.8.3-MariaDB-0+deb13u1 from Debian
-- Versione PHP: 8.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbiasuzz`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `Articolo_Blog`
--

CREATE TABLE `Articolo_Blog` (
  `IDArticolo` int(11) NOT NULL,
  `IDAutore` int(11) NOT NULL,
  `Titolo` varchar(255) NOT NULL,
  `Descrizione_Breve` varchar(500) NOT NULL,
  `Contenuto` longtext NOT NULL,
  `Data_Pubblicazione` datetime NOT NULL,
  `Tempo_Lettura` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `Pubblicato` tinyint(1) DEFAULT 0,
  `Data_Creazione` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Articolo_Blog`
--

INSERT INTO `Articolo_Blog` (`IDArticolo`, `IDAutore`, `Titolo`, `Descrizione_Breve`, `Contenuto`, `Data_Pubblicazione`, `Tempo_Lettura`, `Pubblicato`, `Data_Creazione`) VALUES
(1, 1, 'Le 10 Cale Più Belle della Costiera Sorrentina', 'Scopri le spiagge nascoste e i paesaggi mozzafiato della costiera.', 'Questo articolo esplora le dieci cale più affascinanti della costiera sorrentina, dalle celebri Tre Sorelle alle meno conosciute ma altrettanto stupende Marina di Puolo e Cala dell\'Infreschi. Ideale per chi vuole scoprire angoli paradisiaci lontano dalla folla turistica. Ogni cala ha caratteristiche uniche: acque cristalline, spiagge di ciottoli o sabbia, e splendidi sentieri costieri per le escursioni.', '2025-01-15 10:30:00', 8, 1, '2025-12-23 12:11:10'),
(2, 1, 'Guida Completa allo Snorkeling a Capri', 'Tutto ciò che devi sapere per un\'indimenticabile giornata di snorkeling.', 'Capri è una delle mete più straordinarie per lo snorkeling nel Tirreno. In questa guida troverai informazioni dettagliate sui migliori spot, la fauna marina che potrai incontrare, consigli pratici per la sicurezza e i periodi migliori per visitare. Scopri le grotte sottomarine, gli scogli pieni di vita e le praterie di Posidonia che rendono Capri un paradiso subacqueo.', '2025-01-12 14:45:00', 10, 1, '2025-12-23 12:11:10'),
(3, 1, 'Come Ormeggiare in Sicurezza: Guida per Principianti', 'Tecniche di ormeggio essenziali per chi inizia a navigare.', 'L\'ormeggio è una delle abilità fondamentali di ogni navigatore. In questo articolo tecnico insegniamo i metodi classici di ormeggio nei porti della costiera, come avvicinarsi alla banchina, come utilizzare i cavi e le manovre di retromarcia. Imparerai a riconoscere i punti d\'ormeggio sicuri e come proteggerti dalle correnti e dai venti forti.', '2025-01-10 09:20:00', 6, 1, '2025-12-23 12:11:10'),
(4, 2, 'Lusso in Mare: Yacht e Charter Esclusivi', 'Vivi l\'esperienza di un noleggio di lusso nel golfo di Napoli.', 'Uno yacht privato rappresenta il massimo dell\'esclusività. Questo articolo vi guida alla scoperta degli yacht di lusso disponibili nel nostro catalogo, dalle camere sontuose alle cucine attrezzate, dai sistemi di navigazione avanzati ai servizi premium. Scopri come trasformare una giornata in mare in un\'esperienza indimenticabile di classe e raffinatezza.', '2025-01-08 16:10:00', 7, 1, '2025-12-23 12:11:10'),
(5, 1, 'Navigazione Tradizionale: Barca a Vela per Crociere', 'L\'arte della vela e il fascino della navigazione classica.', 'Le barche a vela rappresentano l\'essenza della navigazione tradizionale. Questo articolo esplora il fascino della vela, come una crociera a vela unisce sport e relax, e quali sono i vantaggi di scegliere una barca a vela rispetto a un motore. Leggi consigli pratici su come preparare una crociera a vela sulla costiera amalfitana e su come vivere appieno questa esperienza.', '2025-01-05 11:35:00', 9, 1, '2025-12-23 12:11:10'),
(6, 2, 'Sicurezza in Mare: Protocolli e Attrezzature Obbligatorie', 'Tutto ciò che devi sapere per navigare in sicurezza.', 'La sicurezza è la priorità numero uno quando si naviga. Questo articolo dettagliato copre tutti gli obblighi di legge, le attrezzature di sicurezza necessarie, i protocolli di soccorso, come utilizzare i giubbotti salvagente, i segnali di emergenza e le comunicazioni via radio. Scopri come prepararti per affrontare situazioni di emergenza e come proteggere te stesso e i tuoi compagni.', '2025-01-03 13:50:00', 12, 1, '2025-12-23 12:11:10'),
(7, 1, 'Positano e Amalfi: Villaggi Storici della Costiera', 'Un viaggio attraverso i villaggi più affascinanti della costiera amalfitana.', 'La costiera amalfitana è famosa per i suoi villaggi incantevoli. Questo articolo porta il lettore alla scoperta dei dettagli storici di Positano, conosciuto per le case colorate e gli hotel esclusivi, e di Amalfi, città ricca di storia medievale e arte. Scopri cosa fare durante una visita a terra e come integrare il viaggio in barca con escursioni nei villaggi costieri.', '2025-12-28 10:15:00', 8, 1, '2025-12-23 12:11:10'),
(8, 1, 'Fenomeno del Bioluminescenza nel Golfo di Napoli', 'Scopri la magia della luce vivente nei fondali marini.', 'Il golfo di Napoli è uno dei pochi luoghi dove è possibile osservare il fenomeno della bioluminescenza marina. Questo articolo spiega la scienza dietro a questo spettacolo naturale, i periodi migliori per osservarlo, e come organizzare una spedizione notturna in barca per assistere a questo meraviglia della natura. Un\'esperienza che difficilmente dimenticherai.', '2025-12-25 15:40:00', 7, 1, '2025-12-23 12:11:10'),
(9, 2, 'Ricette di Cucina Marinara: Piatti da Preparare a Bordo', 'Delizie culinarie da gustare durante una crociera in mare.', 'La cucina marinara è basata su ingredienti freschi e semplici. Questo articolo condivide ricette autentiche della costiera che puoi preparare nella cucina della tua barca. Dai piatti di pasta ai frutti di mare, dai pesce al forno alle insalate di mare, scopri come trasformare il noleggio di una barca in un\'esperienza culinaria indimenticabile.', '2025-12-20 09:25:00', 6, 1, '2025-12-23 12:11:10'),
(10, 1, 'Flora e Fauna Marina della Costiera Sorrentina', 'Un approfondimento sulla biodiversità sottomarina della costa campana.', 'La costiera sorrentina è ricca di vita marina. Questo articolo illustra le specie ittiche che incontrerai durante lo snorkeling, dai dentici alle cernie, dalle murene alle stelle marine, e il loro comportamento naturale. Scopri anche la flora marina come la Posidonia oceanica e come essa contribuisce all\'ecosistema marino e alla qualità dell\'acqua.', '2025-12-18 11:50:00', 10, 1, '2025-12-23 12:11:10'),
(11, 2, 'Meteo e Stagioni: Quando Navigare nella Costiera', 'Una guida alle condizioni climatiche e ai periodi ideali per salpare.', 'Ogni stagione offre condizioni diverse per la navigazione. Questo articolo analizza i venti, le correnti, e le caratteristiche meteorologiche di primavera, estate, autunno e inverno nel golfo di Napoli. Scopri i periodi ideali per diversi tipi di navigazione e come leggere le previsioni meteo per pianificare la tua gita in mare in sicurezza e comfort.', '2025-12-15 14:20:00', 8, 1, '2025-12-23 12:11:10'),
(12, 1, 'Sorrento: La Perla della Costiera', 'Alla scoperta della affascinante città di Sorrento.', 'Sorrento è una delle destinazioni più romantiche d\'Italia. Questo articolo esplora la storia di questa affascinante città, i suoi monumenti, le piazze pittoresche e gli hotel di lusso. Scopri cosa fare a terra e come le tue gite in barca possono integrarsi con una visita a Sorrento, includendo tours del centro storico e cene in ristoranti tradizionali.', '2025-12-10 10:05:00', 7, 1, '2025-12-23 12:11:10'),
(13, 1, 'Fotografia Subacquea: Cattura i Tuoi Momenti Migliori', 'Consigli pratici per fotografare il mondo sottomarino.', 'Vuoi immortalare i tuoi momenti subacquei? Questo articolo fornisce consigli su fotocamere e videocamere subacquee, tecniche di fotografia sottomarina, illuminazione, composizione, e post-produzione. Scopri come catturare la bellezza dei fondali e condividere le tue avventure sui social media con fotografie straordinarie.', '2025-12-08 13:35:00', 9, 1, '2025-12-23 12:11:10'),
(14, 2, 'Sostenibilità Marina: Come Navigare Responsabilmente', 'Pratiche ecologiche per proteggere l\'ecosistema marino.', 'La navigazione responsabile è essenziale per preservare la bellezza del nostro mare. Questo articolo affrontsa i temi della sostenibilità marina, da come ridurre l\'uso di combustibili fossili a come evitare inquinamento, alla scelta di prodotti eco-friendly a bordo. Scopri come ogni navigatore può contribuire alla protezione dell\'ambiente marino.', '2025-12-05 16:55:00', 8, 1, '2025-12-23 12:11:10'),
(15, 1, 'Isola di Capri: Leggenda e Realtà', 'Mito, storia e meraviglie dell\'isola più celebre del golfo.', 'Capri è circondata da leggende e fascino. Questo articolo racconta la storia affascinante dell\'isola, dalla Grotta Azzurra ai faraglioni, dai siti archeologici alle celebrità che l\'hanno resa famosa. Scopri come organizzare una giornata perfetta a Capri partendo da una barca noleggiata e quali sono i must-see dell\'isola.', '2025-12-03 11:20:00', 10, 1, '2025-12-23 12:11:10'),
(16, 2, 'Noleggio Barca: Come Scegliere il Natante Perfetto', 'Guida pratica alla scelta della barca giusta per le tue esigenze.', 'Con così tante opzioni disponibili, come scegliere la giusta barca da noleggiare? Questo articolo guida il lettore attraverso i diversi tipi di barche, le loro caratteristiche, i vantaggi e gli svantaggi. Dalla barca piccola al gommone sportivo, dallo yacht di lusso alla barca a vela, scopri quale è più adatta ai tuoi piani e alle tue competenze.', '2025-11-30 09:45:00', 11, 1, '2025-12-23 12:11:10'),
(17, 1, 'Ballate di Marinai: Tradizioni Nautiche della Costiera', 'Leggende, canzoni e tradizioni marinare della costiera campana.', 'La costiera sorrentina è ricca di tradizioni marittime millenarie. Questo articolo esplora le leggende locali, le ballate dei marinai, le tradizioni di pesca, e il modo di vivere delle comunità costiere storiche. Scopri come la cultura marina ha plasmato l\'identità della regione e come questi insegnamenti si riflettono nella navigazione moderna.', '2025-11-28 14:10:00', 8, 1, '2025-12-23 12:11:10'),
(18, 2, 'Wellness in Mare: Relax e Benessere a Bordo', 'Come trasformare una gita in barca in una spa galleggiante.', 'Il mare ha proprietà terapeutiche straordinarie. Questo articolo esplora come integrare pratiche di wellness durante una crociera, dalla meditazione al suono delle onde, ai benefici dello yoga sul mare, ai bagni rinfrescanti in acque cristalline. Scopri come una giornata in barca diventa un\'esperienza di rigenerazione fisica e mentale.', '2025-11-25 10:30:00', 7, 1, '2025-12-23 12:11:10'),
(19, 1, 'Isola di Ischia: Vulcani, Terme e Bellezze Naturali', 'Esplorando l\'isola più grande del golfo di Napoli.', 'Ischia, l\'isola verde del golfo, offre paesaggi vulcanici unici e sorgenti termali naturali. Questo articolo presenta i principali siti di interesse come il Monte Epomeo, le spiagge di sabbia scura, le terme naturali di Maronti, e i villaggi pittoreschi. Scopri come organizzare un\'escursione in barca a Ischia integrando bagni in sorgenti calde e explore vulcaniche.', '2025-11-22 15:50:00', 9, 1, '2025-12-23 12:11:10'),
(20, 2, 'Regolamenti Marittimi: Diritti e Doveri del Navigatore', 'Conoscenze legali essenziali per navigare nel Tirreno.', 'Ogni navigatore deve conoscere le leggi e i regolamenti marittimi che disciplinano la navigazione. Questo articolo copre i principali regolamenti, dai limiti di velocità alle aree protette, dalla documentazione necessaria ai doveri verso l\'ambiente. Scopri i tuoi diritti come noleggiatore di barca e le responsabilità che assumi.', '2025-11-20 12:15:00', 10, 1, '2025-12-23 12:11:10');

-- --------------------------------------------------------

--
-- Struttura della tabella `Articolo_Blog_Extra`
--

CREATE TABLE `Articolo_Blog_Extra` (
  `IDExtra` int(11) NOT NULL,
  `IDArticolo` int(11) NOT NULL,
  `Titolo` varchar(255) NOT NULL,
  `Elemento` text NOT NULL,
  `Ordine` int(11) NOT NULL DEFAULT 0,
  `Data_Creazione` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Articolo_Blog_Extra`
--

INSERT INTO `Articolo_Blog_Extra` (`IDExtra`, `IDArticolo`, `Titolo`, `Elemento`, `Ordine`, `Data_Creazione`) VALUES
(1, 1, '🏖️ Top 5 Cale da Non Perdere', 'Cala dell\'Infreschi - Splendida cala con spiaggia di sabbia bianca', 1, '2025-12-23 12:11:10'),
(2, 1, '🏖️ Top 5 Cale da Non Perdere', 'Marina di Puolo - Villaggio di pescatori pittoresco', 2, '2025-12-23 12:11:10'),
(3, 1, '🏖️ Top 5 Cale da Non Perdere', 'Tre Sorelle - Tre faraglioni iconici', 3, '2025-12-23 12:11:10'),
(4, 1, '🏖️ Top 5 Cale da Non Perdere', 'Cala di Mitigliano - Acque cristalline', 4, '2025-12-23 12:11:10'),
(5, 1, '🏖️ Top 5 Cale da Non Perdere', 'Cala dei Limoni - Piccolo paradiso per lo snorkeling', 5, '2025-12-23 12:11:10'),
(6, 1, '📋 Cosa Portare', 'Maschera e boccaglio in buone condizioni', 1, '2025-12-23 12:11:10'),
(7, 1, '📋 Cosa Portare', 'Crema solare con protezione forte (SPF 50+)', 2, '2025-12-23 12:11:10'),
(8, 1, '📋 Cosa Portare', 'Asciugamani microfibra (secchi subito)', 3, '2025-12-23 12:11:10'),
(9, 1, '📋 Cosa Portare', 'Snack e acqua abbondante', 4, '2025-12-23 12:11:10'),
(10, 2, '🐠 Fauna Marina', 'Dentici - Pesci d\'argento e molto diffidenti', 1, '2025-12-23 12:11:10'),
(11, 2, '🐠 Fauna Marina', 'Cernie - Predatori territoriali, affascinanti da osservare', 2, '2025-12-23 12:11:10'),
(12, 2, '🐠 Fauna Marina', 'Stelle Marine e Ricci di Mare - Fauna colorata', 3, '2025-12-23 12:11:10'),
(13, 2, '🐠 Fauna Marina', 'Murene - Creature affascinanti nelle fessure', 4, '2025-12-23 12:11:10'),
(14, 2, '📍 Spot Migliori', 'Grotta Azzurra - Lo spot più famoso', 1, '2025-12-23 12:11:10'),
(15, 2, '📍 Spot Migliori', 'Faraglioni - Pareti rocciose ricche di vita', 2, '2025-12-23 12:11:10'),
(16, 2, '📍 Spot Migliori', 'Punta Carena - Reef naturale', 3, '2025-12-23 12:11:10'),
(17, 2, '⏰ Periodo Migliore', 'Maggio-Settembre per condizioni ottimali', 1, '2025-12-23 12:11:10'),
(18, 2, '⏰ Periodo Migliore', 'Giugno-Agosto per acqua più calda', 2, '2025-12-23 12:11:10'),
(19, 3, '⚓ Tecniche Base', 'Ormeggio alla banchina con due cavi', 1, '2025-12-23 12:11:10'),
(20, 3, '⚓ Tecniche Base', 'Retromarcia controllata e parallela', 2, '2025-12-23 12:11:10'),
(21, 3, '⚓ Tecniche Base', 'Protezione dei parabordi', 3, '2025-12-23 12:11:10'),
(22, 3, '⚓ Tecniche Base', 'Lettura dei venti e delle correnti', 4, '2025-12-23 12:11:10'),
(23, 3, '🛟 Sicurezza', 'Mantenere sempre contatto radio con la torre di controllo', 1, '2025-12-23 12:11:10'),
(24, 3, '🛟 Sicurezza', 'Verificare la profondità dell\'acqua con scandaglio', 2, '2025-12-23 12:11:10'),
(25, 3, '🛟 Sicurezza', 'Avere a disposizione ancora di riserva', 3, '2025-12-23 12:11:10'),
(26, 4, '💎 Yacht nel Nostro Catalogo', 'Azimut 55 Fly - Lusso e spazio', 1, '2025-12-23 12:11:10'),
(27, 4, '💎 Yacht nel Nostro Catalogo', 'Cranchi Endurance 41 - Eleganza classica', 2, '2025-12-23 12:11:10'),
(28, 4, '✨ Servizi Premium', 'Skipper capitano internazionale', 1, '2025-12-23 12:11:10'),
(29, 4, '✨ Servizi Premium', 'Hostess di bordo per servizio impeccabile', 2, '2025-12-23 12:11:10'),
(30, 4, '✨ Servizi Premium', 'Cucina gourmet con chef a bordo', 3, '2025-12-23 12:11:10'),
(31, 4, '✨ Servizi Premium', 'Sistema audio e home theater a bordo', 4, '2025-12-23 12:11:10'),
(32, 5, '⛵ Tipi di Barca a Vela', 'Beneteau First 35 - Sportiva e dinamica', 1, '2025-12-23 12:11:10'),
(33, 5, '⛵ Tipi di Barca a Vela', 'Jeanneau Sun Odyssey 45 - Comfort abitativo', 2, '2025-12-23 12:11:10'),
(34, 5, '🌊 Manovre Base', 'Virata - Cambio di ammuzzo alla virata', 1, '2025-12-23 12:11:10'),
(35, 5, '🌊 Manovre Base', 'Strambata - Giro del boma con poppa al vento', 2, '2025-12-23 12:11:10'),
(36, 5, '🌊 Manovre Base', 'Orzata - Salire verso il vento', 3, '2025-12-23 12:11:10'),
(37, 6, '🆘 Attrezzature Obbligatorie', 'Giubbotti salvagente per tutto l\'equipaggio', 1, '2025-12-23 12:11:10'),
(38, 6, '🆘 Attrezzature Obbligatorie', 'Zattera di salvataggio ispezionata', 2, '2025-12-23 12:11:10'),
(39, 6, '🆘 Attrezzature Obbligatorie', 'Estintori e kit primo soccorso', 3, '2025-12-23 12:11:10'),
(40, 6, '🆘 Attrezzature Obbligatorie', 'Fari e segnali luminosi notturni', 4, '2025-12-23 12:11:10'),
(41, 6, '📞 Numeri di Emergenza', 'Capitaneria di Porto: 1530', 1, '2025-12-23 12:11:10'),
(42, 6, '📞 Numeri di Emergenza', 'Guardia Costiera: VHF Canale 16', 2, '2025-12-23 12:11:10'),
(43, 6, '📞 Numeri di Emergenza', 'Emergenze Mediche: 118', 3, '2025-12-23 12:11:10'),
(44, 7, '🏘️ Cosa Vedere', 'Piazza Positano - Centro storico pittoresco', 1, '2025-12-23 12:11:10'),
(45, 7, '🏘️ Cosa Vedere', 'Spiaggia di Positano - Sabbia e bohemien', 2, '2025-12-23 12:11:10'),
(46, 7, '🏘️ Cosa Vedere', 'Duomo di Amalfi - Capolavoro architettonico', 3, '2025-12-23 12:11:10'),
(47, 8, '🌟 Quando Osservare', 'Giugno-Luglio per fenomeno più intenso', 1, '2025-12-23 12:11:10'),
(48, 8, '🌟 Quando Osservare', 'Notti senza luna per visibilità massima', 2, '2025-12-23 12:11:10'),
(49, 9, '👨‍🍳 Ricette Semplici', 'Spaghetti alle Vongole Veraci', 1, '2025-12-23 12:11:10'),
(50, 9, '👨‍🍳 Ricette Semplici', 'Branzino al Forno con Limone', 2, '2025-12-23 12:11:10'),
(51, 9, '👨‍🍳 Ricette Semplici', 'Insalata di Frutti di Mare', 3, '2025-12-23 12:11:10'),
(52, 10, '🐟 Specie Comuni', 'Dentice - Pesce argentato velocissimo', 1, '2025-12-23 12:11:10'),
(53, 10, '🐟 Specie Comuni', 'Cernia - Pesce grande e territoriale', 2, '2025-12-23 12:11:10'),
(54, 10, '🌿 Flora Marina', 'Posidonia Oceanica - Pianta marina protetta', 1, '2025-12-23 12:11:10'),
(55, 11, '🌬️ Venti Principali', 'Maestrale da Nord-Ovest con raffiche forti', 1, '2025-12-23 12:11:10'),
(56, 11, '🌬️ Venti Principali', 'Scirocco da Sud-Est caldo e umido', 2, '2025-12-23 12:11:10'),
(57, 11, '🌬️ Venti Principali', 'Grecale da Nord-Est secco', 3, '2025-12-23 12:11:10'),
(58, 12, '🏛️ Monumenti', 'Duomo di Sorrento - Importante chiesa medievale', 1, '2025-12-23 12:11:10'),
(59, 12, '🏛️ Monumenti', 'Basilica Sant\'Antonino - Patrono della città', 2, '2025-12-23 12:11:10'),
(60, 13, '📷 Attrezzatura', 'GoPro Hero 12 - Compatta e versatile', 1, '2025-12-23 12:11:10'),
(61, 13, '📷 Attrezzatura', 'Custodia stagna professionale - Protezione totale', 2, '2025-12-23 12:11:10'),
(62, 13, '💡 Consigli di Fotografia', 'Evita il flash - Usa la luce naturale', 1, '2025-12-23 12:11:10'),
(63, 13, '💡 Consigli di Fotografia', 'Scatta da angolazioni diverse', 2, '2025-12-23 12:11:10'),
(64, 14, '♻️ Pratiche Ecologiche', 'Usa biodegradabili per pulizia a bordo', 1, '2025-12-23 12:11:10'),
(65, 14, '♻️ Pratiche Ecologiche', 'Non gettare spazzatura in mare', 2, '2025-12-23 12:11:10'),
(66, 14, '♻️ Pratiche Ecologiche', 'Rispetta le aree marine protette', 3, '2025-12-23 12:11:10'),
(67, 15, '🗿 Faraglioni', 'Tre enormi monoliti di roccia calcarea', 1, '2025-12-23 12:11:10'),
(68, 15, '🗿 Faraglioni', 'Grotta Azzurra - Fenomeno ottico straordinario', 2, '2025-12-23 12:11:10'),
(69, 15, '🗿 Faraglioni', 'Villa Jovis di Tiberio - Rovine imperiali', 3, '2025-12-23 12:11:10'),
(70, 16, '🚤 Barca Piccola', 'Perfetta per principianti e famiglie', 1, '2025-12-23 12:11:10'),
(71, 16, '🚤 Barca Piccola', 'Gozzo - Tradizionale e stabile', 2, '2025-12-23 12:11:10'),
(72, 16, '🚤 Barca Piccola', 'Gommone - Veloce e dinamico', 3, '2025-12-23 12:11:10'),
(73, 16, '⛵ Barca a Vela', 'Per navigatori esperti e romantici', 1, '2025-12-23 12:11:10'),
(74, 16, '🛥️ Yacht', 'Per lusso e confort massimi', 1, '2025-12-23 12:11:10'),
(75, 17, '🎵 Ballate Marinare', 'La Leggenda di Parthenope - Mitologia locale', 1, '2025-12-23 12:11:10'),
(76, 17, '🎵 Ballate Marinare', 'Canzoni dei Pescatori - Tradizione orale', 2, '2025-12-23 12:11:10'),
(77, 18, '🧘 Yoga a Bordo', 'Pratiche meditative con vista sul mare', 1, '2025-12-23 12:11:10'),
(78, 18, '🧘 Yoga a Bordo', 'Bagni termici naturali a Ischia', 2, '2025-12-23 12:11:10'),
(79, 18, '💆 Trattamenti Marini', 'Algoterapia con alghe biologiche', 1, '2025-12-23 12:11:10'),
(80, 19, '🌋 Vulcano', 'Monte Epomeo - Escursione escursionistica', 1, '2025-12-23 12:11:10'),
(81, 19, '🌋 Vulcano', 'Crateri Spenti - Geologia affascinante', 2, '2025-12-23 12:11:10'),
(82, 19, '♨️ Terme', 'Maronti - Sorgenti naturali calde', 1, '2025-12-23 12:11:10'),
(83, 19, '♨️ Terme', 'Cavascura - Grotta termale naturale', 2, '2025-12-23 12:11:10'),
(84, 20, '⚖️ Diritti del Navigatore', 'Libertà di navigazione in acque internazionali', 1, '2025-12-23 12:11:10'),
(85, 20, '⚖️ Diritti del Navigatore', 'Protezione dai danni da maltempo', 2, '2025-12-23 12:11:10'),
(86, 20, '⚖️ Diritti del Navigatore', 'Accesso ai porti di soccorso', 3, '2025-12-23 12:11:10'),
(87, 20, '📋 Doveri', 'Rispetto delle zone protette marine', 1, '2025-12-23 12:11:10'),
(88, 20, '📋 Doveri', 'Comunicazione frequente via radio', 2, '2025-12-23 12:11:10');

-- --------------------------------------------------------

--
-- Struttura della tabella `Indirizzo`
--

CREATE TABLE `Indirizzo` (
  `IDIndirizzo` int(11) NOT NULL,
  `Via` varchar(255) NOT NULL,
  `N_Civico` varchar(10) NOT NULL,
  `CAP` varchar(5) NOT NULL,
  `Citta` varchar(100) NOT NULL,
  `Provincia` varchar(2) NOT NULL,
  `Paese` varchar(2) NOT NULL DEFAULT 'IT',
  `Data_Creazione` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Indirizzo`
--

INSERT INTO `Indirizzo` (`IDIndirizzo`, `Via`, `N_Civico`, `CAP`, `Citta`, `Provincia`, `Paese`, `Data_Creazione`) VALUES
(1, 'Spaccanapoli', '45', '80138', 'Napoli', 'NA', 'IT', '2025-12-23 12:11:10'),
(2, 'Via Partenope', '22', '80121', 'Napoli', 'NA', 'IT', '2025-12-23 12:11:10'),
(3, 'Vico Equense', '8', '80069', 'Vico Equense', 'NA', 'IT', '2025-12-23 12:11:10'),
(4, 'Via Positano', '15', '84017', 'Positano', 'SA', 'IT', '2025-12-23 12:11:10'),
(5, 'Piazza Tasso', '33', '80067', 'Sorrento', 'NA', 'IT', '2025-12-23 12:11:10'),
(6, 'Via Marina Grande', '12', '80073', 'Capri', 'NA', 'IT', '2025-12-23 12:11:10'),
(7, 'Lungolago Salvo d\'Acquisto', '50', '80011', 'Bacoli', 'NA', 'IT', '2025-12-23 12:11:10'),
(8, 'Via Vittorio Emanuele', '200', '80013', 'Caserta', 'CE', 'IT', '2025-12-23 12:11:10'),
(9, 'Via Toledo', '334', '80134', 'Napoli', 'NA', 'IT', '2025-12-23 12:11:10'),
(10, 'Corso Italia', '88', '84010', 'Praiano', 'SA', 'IT', '2025-12-23 12:11:10'),
(11, 'Via Sant\'Antonio', '19', '80010', 'Pozzuoli', 'NA', 'IT', '2025-12-23 12:11:10'),
(12, 'Viale Europa', '75', '80062', 'Meta di Sorrento', 'NA', 'IT', '2025-12-23 12:11:10'),
(13, 'Via Orientale', '120', '80078', 'Anacapri', 'NA', 'IT', '2025-12-23 12:11:10'),
(14, 'Piazza Municipio', '1', '80133', 'Napoli', 'NA', 'IT', '2025-12-23 12:11:10'),
(15, 'Via Marina Piccola', '5', '80071', 'Anacapri', 'NA', 'IT', '2025-12-23 12:11:10');

-- --------------------------------------------------------

--
-- Struttura della tabella `Indisponibilita`
--

CREATE TABLE `Indisponibilita` (
  `IDIndisponibilita` int(11) NOT NULL,
  `IDProdotto` varchar(50) NOT NULL,
  `Data_Inizio` datetime NOT NULL,
  `Data_Fine` datetime NOT NULL,
  `Motivo` varchar(255) DEFAULT NULL,
  `Creato_Da` int(11) NOT NULL,
  `Data_Creazione` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Indisponibilita`
--

INSERT INTO `Indisponibilita` (`IDIndisponibilita`, `IDProdotto`, `Data_Inizio`, `Data_Fine`, `Motivo`, `Creato_Da`, `Data_Creazione`) VALUES
(1, 'BARCA-GOZZO-001', '2025-06-01 00:00:00', '2025-06-05 23:59:59', 'Manutenzione motore e vernice', 1, '2025-12-23 12:11:10'),
(2, 'BARCA-GOZZO-002', '2025-05-20 00:00:00', '2025-05-25 23:59:59', 'Revisione carburante e filtri', 1, '2025-12-23 12:11:10'),
(3, 'BARCA-GOMMONE-001', '2025-07-01 00:00:00', '2025-07-03 23:59:59', 'Pulizia cambusa e sanitari', 2, '2025-12-23 12:11:10'),
(4, 'BARCA-GOMMONE-002', '2025-08-15 00:00:00', '2025-08-18 23:59:59', 'Manutenzione climatizzazione', 1, '2025-12-23 12:11:10'),
(5, 'BARCA-GOMMONE-003', '2025-06-10 00:00:00', '2025-06-14 23:59:59', 'Revisione completa motori', 2, '2025-12-23 12:11:10'),
(6, 'BARCA-YACHT-001', '2025-05-01 00:00:00', '2025-05-10 23:59:59', 'Manutenzione navigatore GPS e rotte', 1, '2025-12-23 12:11:10'),
(7, 'BARCA-YACHT-002', '2025-07-20 00:00:00', '2025-07-25 23:59:59', 'Pulizia interna e cambusa', 2, '2025-12-23 12:11:10'),
(8, 'BARCA-VELA-001', '2025-06-20 00:00:00', '2025-06-23 23:59:59', 'Ispezione vela e cavi', 1, '2025-12-23 12:11:10'),
(9, 'BARCA-VELA-002', '2025-08-25 00:00:00', '2025-08-28 23:59:59', 'Manutenzione autopilota', 2, '2025-12-23 12:11:10'),
(10, 'BARCA-PICCOLA-001', '2025-07-10 00:00:00', '2025-07-12 23:59:59', 'Verifica sicurezza attrezzatura', 1, '2025-12-23 12:11:10'),
(11, 'BARCA-PICCOLA-002', '2025-09-01 00:00:00', '2025-09-02 23:59:59', 'Pulizia e sanificazione', 2, '2025-12-23 12:11:10'),
(12, 'EXP-TRAMONTO-001', '2025-06-15 00:00:00', '2025-06-16 23:59:59', 'Preparazione menu e attrezzature', 1, '2025-12-23 12:11:10'),
(13, 'EXP-SNORKEL-001', '2025-07-05 00:00:00', '2025-07-06 23:59:59', 'Controllo attrezzatura snorkeling', 2, '2025-12-23 12:11:10'),
(14, 'EXP-ESCURSIONE-001', '2025-08-20 00:00:00', '2025-08-21 23:59:59', 'Preparazione guide turistiche', 1, '2025-12-23 12:11:10');

-- --------------------------------------------------------

--
-- Struttura della tabella `Lingua`
--

CREATE TABLE `Lingua` (
  `IDLingua` int(11) NOT NULL,
  `Codice` varchar(5) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Attivo` tinyint(1) NOT NULL DEFAULT 1,
  `Data_Creazione` timestamp NULL DEFAULT current_timestamp(),
  `Data_Modifica` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Lingua`
--

INSERT INTO `Lingua` (`IDLingua`, `Codice`, `Nome`, `Attivo`, `Data_Creazione`, `Data_Modifica`) VALUES
(1, 'IT', 'Italiano', 1, '2025-12-23 12:11:10', '2025-12-23 12:11:10'),
(2, 'EN', 'Inglese', 1, '2025-12-23 12:11:10', '2025-12-23 12:11:10'),
(3, 'FR', 'Francese', 1, '2025-12-23 12:11:10', '2025-12-23 12:11:10'),
(4, 'ES', 'Spagnolo', 1, '2025-12-23 12:11:10', '2025-12-23 12:11:10');

-- --------------------------------------------------------

--
-- Struttura della tabella `Media`
--

CREATE TABLE `Media` (
  `IDMedia` int(11) NOT NULL,
  `URL_Media` varchar(500) NOT NULL,
  `Testo_Alternativo` varchar(255) NOT NULL,
  `Tipo_Media` enum('Immagine','Video') NOT NULL DEFAULT 'Immagine',
  `IDUtente` int(11) DEFAULT NULL,
  `IDProdotto` varchar(50) DEFAULT NULL,
  `IDArticolo` int(11) DEFAULT NULL,
  `Data_Caricamento` timestamp NULL DEFAULT current_timestamp()
) ;

--
-- Dump dei dati per la tabella `Media`
--

INSERT INTO `Media` (`IDMedia`, `URL_Media`, `Testo_Alternativo`, `Tipo_Media`, `IDUtente`, `IDProdotto`, `IDArticolo`, `Data_Caricamento`) VALUES
(1, '../img/Azimut_55_fly_2.webp', 'Avatar Marco Rossi Admin', 'Immagine', 1, NULL, NULL, '2025-12-23 12:11:10'),
(2, '../img/Azimut_55_fly_2.webp', 'Avatar Lucia De Luca Admin', 'Immagine', 2, NULL, NULL, '2025-12-23 12:11:10'),
(3, '../img/Azimut_55_fly_2.webp', 'Avatar Giovanni Ferraro', 'Immagine', 3, NULL, NULL, '2025-12-23 12:11:10'),
(4, '../img/Azimut_55_fly_2.webp', 'Avatar Sofia Esposito', 'Immagine', 4, NULL, NULL, '2025-12-23 12:11:10'),
(5, '../img/prodotti/gozzo-sorrentino-classico.webp', 'Gozzo Sorrentino Classico in mare', 'Immagine', NULL, 'BARCA-GOZZO-001', NULL, '2025-12-23 12:11:10'),
(6, '../img/prodotti/gozzoblu.jpg', 'Gozzo Blu Marino', 'Immagine', NULL, 'BARCA-GOZZO-002', NULL, '2025-12-23 12:11:10'),
(7, '../img/prodotti/gommone250.jpeg', 'Gommone Speed 250cv saltello', 'Immagine', NULL, 'BARCA-GOMMONE-001', NULL, '2025-12-23 12:11:10'),
(8, '../img/prodotti/gommonecomfort.jpeg', 'Gommone Comfort 200cv in navigazione', 'Immagine', NULL, 'BARCA-GOMMONE-002', NULL, '2025-12-23 12:11:10'),
(9, '../img/prodotti/gommonelux.jpg', 'Gommone Luxury 300cv cabina interna', 'Immagine', NULL, 'BARCA-GOMMONE-003', NULL, '2025-12-23 12:11:10'),
(10, '../img/prodotti/azimut55fly.jpeg', 'Azimut 55 Fly lussuoso', 'Immagine', NULL, 'BARCA-YACHT-001', NULL, '2025-12-23 12:11:10'),
(11, '../img/prodotti/cranchiendu.jpg', 'Cranchi Endurance 41 elegante', 'Immagine', NULL, 'BARCA-YACHT-002', NULL, '2025-12-23 12:11:10'),
(12, '../img/prodotti/first35.jpeg', 'Beneteau First 35 a vela', 'Immagine', NULL, 'BARCA-VELA-001', NULL, '2025-12-23 12:11:10'),
(13, '../img/prodotti/odyssey-45.jpg', 'Jeanneau Sun Odyssey 45 in navigazione', 'Immagine', NULL, 'BARCA-VELA-002', NULL, '2025-12-23 12:11:10'),
(14, '../img/prodotti/aperta6m.jpg', 'Barca Aperta 6m per principianti', 'Immagine', NULL, 'BARCA-PICCOLA-001', NULL, '2025-12-23 12:11:10'),
(15, '../img/prodotti/aperta8m.jpg', 'Barca Aperta 7.5m gita mezzagiornata', 'Immagine', NULL, 'BARCA-PICCOLA-002', NULL, '2025-12-23 12:11:10'),
(16, '../img/prodotti/tramontonapoli.jpg', 'Coppia al tramonto con prosecco', 'Immagine', NULL, 'EXP-TRAMONTO-001', NULL, '2025-12-23 12:11:10'),
(17, '../img/prodotti/snork.jpg', 'Snorkeling a Capri con pesci', 'Immagine', NULL, 'EXP-SNORKEL-001', NULL, '2025-12-23 12:11:10'),
(18, '../img/prodotti/costieraamalfitana.jpg', 'Costiera Amalfitana vista dal mare', 'Immagine', NULL, 'EXP-ESCURSIONE-001', NULL, '2025-12-23 12:11:10'),
(19, '../img/Azimut_55_fly_2.webp', 'Cala dell\'Infreschi bellissima', 'Immagine', NULL, NULL, 1, '2025-12-23 12:11:10'),
(20, '../img/Azimut_55_fly_2.webp', 'Snorkel a Capri con pesce dorato', 'Immagine', NULL, NULL, 2, '2025-12-23 12:11:10'),
(21, '../img/Azimut_55_fly_2.webp', 'Ormeggio professionale al porto', 'Immagine', NULL, NULL, 3, '2025-12-23 12:11:10'),
(22, '../img/Azimut_55_fly_2.webp', 'Yacht di lusso nel golfo', 'Immagine', NULL, NULL, 4, '2025-12-23 12:11:10'),
(23, '../img/Azimut_55_fly_2.webp', 'Barca a vela al tramonto', 'Immagine', NULL, NULL, 5, '2025-12-23 12:11:10');

-- --------------------------------------------------------

--
-- Struttura della tabella `Prenotazione`
--

CREATE TABLE `Prenotazione` (
  `IDPrenotazione` int(11) NOT NULL,
  `IDUtente` int(11) NOT NULL,
  `IDProdotto` varchar(50) NOT NULL,
  `Data_Ora_Inizio` datetime NOT NULL,
  `Data_Ora_Fine` datetime NOT NULL,
  `Skipper_Richiesto` tinyint(1) DEFAULT 0,
  `Prezzo_Totale` decimal(10,2) NOT NULL,
  `Metodo_Pagamento` enum('Contanti','Bonifico','Carta di Credito') DEFAULT 'Contanti',
  `Stato_Prenotazione` enum('In Attesa','Confermata','Cancellata') DEFAULT 'In Attesa',
  `Note_Addizionali` text DEFAULT NULL,
  `Data_Creazione` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Prenotazione`
--

INSERT INTO `Prenotazione` (`IDPrenotazione`, `IDUtente`, `IDProdotto`, `Data_Ora_Inizio`, `Data_Ora_Fine`, `Skipper_Richiesto`, `Prezzo_Totale`, `Metodo_Pagamento`, `Stato_Prenotazione`, `Note_Addizionali`, `Data_Creazione`) VALUES
(1, 3, 'BARCA-GOZZO-001', '2025-06-15 09:00:00', '2025-06-15 18:00:00', 0, 280.00, 'Bonifico', 'Confermata', 'Prenotazione per gita in famiglia', '2025-12-23 12:11:10'),
(2, 3, 'BARCA-PICCOLA-001', '2025-07-10 10:00:00', '2025-07-10 13:00:00', 0, 150.00, 'Carta di Credito', 'Confermata', 'Lezione per bambini', '2025-12-23 12:11:10'),
(3, 4, 'BARCA-GOMMONE-001', '2025-08-20 14:00:00', '2025-08-20 19:00:00', 1, 450.00, 'Contanti', 'In Attesa', 'Chiesta navigazione con Skipper', '2025-12-23 12:11:10'),
(4, 4, 'EXP-TRAMONTO-001', '2025-07-25 19:00:00', '2025-07-25 23:00:00', 0, 95.00, 'Carta di Credito', 'In Attesa', 'Prenotazione per anniversario', '2025-12-23 12:11:10'),
(5, 5, 'BARCA-YACHT-001', '2025-09-01 10:00:00', '2025-09-03 10:00:00', 1, 2400.00, 'Bonifico', 'Confermata', 'Crociera di 2 giorni con skipper e hostess', '2025-12-23 12:11:10'),
(6, 5, 'BARCA-VELA-002', '2025-08-05 08:00:00', '2025-08-05 17:00:00', 1, 550.00, 'Carta di Credito', 'Confermata', 'Navigazione a vela con cuoco', '2025-12-23 12:11:10'),
(7, 6, 'EXP-SNORKEL-001', '2025-07-15 09:00:00', '2025-07-15 14:00:00', 0, 75.00, 'Contanti', 'In Attesa', 'Escursione snorkeling Capri', '2025-12-23 12:11:10'),
(8, 6, 'BARCA-PICCOLA-002', '2025-06-20 15:00:00', '2025-06-20 18:00:00', 0, 180.00, 'Carta di Credito', 'In Attesa', 'Gita al tramonto', '2025-12-23 12:11:10'),
(9, 7, 'BARCA-GOMMONE-002', '2025-07-22 11:00:00', '2025-07-22 17:00:00', 0, 380.00, 'Bonifico', 'Confermata', 'Escursione con amici', '2025-12-23 12:11:10'),
(10, 7, 'BARCA-YACHT-002', '2025-08-10 09:00:00', '2025-08-10 18:00:00', 1, 950.00, 'Carta di Credito', 'Confermata', 'Giornata di lusso con skipper', '2025-12-23 12:11:10'),
(11, 8, 'EXP-ESCURSIONE-001', '2025-09-15 08:00:00', '2025-09-15 18:00:00', 0, 120.00, 'Contanti', 'In Attesa', 'Escursione costiera completa', '2025-12-23 12:11:10'),
(12, 8, 'BARCA-VELA-001', '2025-08-28 10:00:00', '2025-08-28 16:00:00', 1, 400.00, 'Carta di Credito', 'In Attesa', 'Vela sportiva con skipper', '2025-12-23 12:11:10'),
(13, 3, 'BARCA-GOMMONE-003', '2025-06-08 13:00:00', '2025-06-08 18:00:00', 1, 550.00, 'Bonifico', 'Cancellata', 'Cancellato per maltempo', '2025-12-23 12:11:10'),
(14, 4, 'BARCA-PICCOLA-001', '2025-09-05 10:00:00', '2025-09-05 14:00:00', 0, 150.00, 'Carta di Credito', 'Confermata', 'Gita pomeridiana', '2025-12-23 12:11:10'),
(15, 5, 'EXP-TRAMONTO-001', '2025-08-25 19:30:00', '2025-08-25 23:30:00', 0, 95.00, 'Contanti', 'In Attesa', 'Cena romantica al tramonto', '2025-12-23 12:11:10'),
(16, 3, 'EXP-SNORKEL-001', '2025-07-30 08:30:00', '2025-07-30 13:30:00', 0, 75.00, 'Carta di Credito', 'Confermata', 'Snorkeling con famiglia', '2025-12-23 12:11:10'),
(17, 4, 'BARCA-VELA-002', '2025-08-15 09:00:00', '2025-08-15 17:00:00', 1, 550.00, 'Bonifico', 'Confermata', 'Navigazione a vela', '2025-12-23 12:11:10'),
(18, 5, 'BARCA-GOZZO-002', '2025-09-08 10:00:00', '2025-09-08 18:00:00', 0, 300.00, 'Contanti', 'In Attesa', 'Noleggio per amici', '2025-12-23 12:11:10'),
(19, 6, 'BARCA-GOMMONE-001', '2025-07-18 14:00:00', '2025-07-18 19:00:00', 1, 450.00, 'Carta di Credito', 'Confermata', 'Adrenalina con skipper', '2025-12-23 12:11:10'),
(20, 7, 'EXP-ESCURSIONE-001', '2025-08-30 08:00:00', '2025-08-30 18:00:00', 0, 120.00, 'Bonifico', 'In Attesa', 'Tour amalfitano', '2025-12-23 12:11:10'),
(21, 8, 'BARCA-PICCOLA-002', '2025-09-10 16:00:00', '2025-09-10 19:00:00', 0, 180.00, 'Contanti', 'Confermata', 'Gita tramonto', '2025-12-23 12:11:10'),
(22, 3, 'BARCA-YACHT-001', '2025-09-20 10:00:00', '2025-09-21 10:00:00', 1, 1200.00, 'Carta di Credito', 'In Attesa', 'Crociera lusso', '2025-12-23 12:11:10'),
(23, 4, 'BARCA-GOMMONE-002', '2025-07-28 11:00:00', '2025-07-28 17:00:00', 0, 380.00, 'Bonifico', 'Confermata', 'Escursione veloce', '2025-12-23 12:11:10'),
(24, 5, 'EXP-TRAMONTO-001', '2025-09-12 19:00:00', '2025-09-12 23:00:00', 0, 95.00, 'Contanti', 'Confermata', 'Anniversario', '2025-12-23 12:11:10'),
(25, 6, 'BARCA-VELA-001', '2025-08-08 10:00:00', '2025-08-08 16:00:00', 1, 400.00, 'Carta di Credito', 'In Attesa', 'Vela sportiva', '2025-12-23 12:11:10'),
(26, 7, 'BARCA-PICCOLA-001', '2025-07-12 09:00:00', '2025-07-12 12:00:00', 0, 150.00, 'Bonifico', 'Confermata', 'Lezione principianti', '2025-12-23 12:11:10'),
(27, 8, 'BARCA-GOZZO-001', '2025-08-22 10:00:00', '2025-08-22 18:00:00', 0, 280.00, 'Contanti', 'Confermata', 'Gita classica', '2025-12-23 12:11:10'),
(28, 3, 'EXP-ESCURSIONE-001', '2025-09-25 08:00:00', '2025-09-25 18:00:00', 0, 120.00, 'Carta di Credito', 'In Attesa', 'Escursione completa', '2025-12-23 12:11:10'),
(29, 4, 'BARCA-YACHT-002', '2025-08-18 09:00:00', '2025-08-18 18:00:00', 1, 950.00, 'Bonifico', 'Confermata', 'Lusso giornaliero', '2025-12-23 12:11:10'),
(30, 5, 'BARCA-GOMMONE-003', '2025-07-05 14:00:00', '2025-07-05 19:00:00', 1, 550.00, 'Contanti', 'In Attesa', 'Adrenalina max', '2025-12-23 12:11:10'),
(31, 6, 'EXP-SNORKEL-001', '2025-08-12 09:00:00', '2025-08-12 14:00:00', 0, 75.00, 'Carta di Credito', 'Confermata', 'Snorkeling avanzato', '2025-12-23 12:11:10'),
(32, 7, 'BARCA-GOZZO-002', '2025-09-03 10:00:00', '2025-09-03 18:00:00', 0, 300.00, 'Bonifico', 'In Attesa', 'Gita con famiglia', '2025-12-23 12:11:10'),
(33, 8, 'BARCA-VELA-002', '2025-08-02 08:00:00', '2025-08-02 17:00:00', 1, 550.00, 'Contanti', 'Confermata', 'Navigazione vela', '2025-12-23 12:11:10'),
(34, 3, 'BARCA-PICCOLA-002', '2025-07-02 15:00:00', '2025-07-02 18:00:00', 0, 180.00, 'Carta di Credito', 'In Attesa', 'Tramonto dal mare', '2025-12-23 12:11:10'),
(35, 4, 'BARCA-GOZZO-001', '2025-06-25 09:00:00', '2025-06-25 18:00:00', 0, 280.00, 'Bonifico', 'Confermata', 'Weekend marino', '2025-12-23 12:11:10'),
(36, 5, 'EXP-TRAMONTO-001', '2025-08-08 19:30:00', '2025-08-08 23:30:00', 0, 95.00, 'Contanti', 'In Attesa', 'Data speciale', '2025-12-23 12:11:10'),
(37, 6, 'BARCA-GOMMONE-001', '2025-09-30 14:00:00', '2025-09-30 19:00:00', 1, 450.00, 'Carta di Credito', 'Confermata', 'Avventura', '2025-12-23 12:11:10');

-- --------------------------------------------------------

--
-- Struttura della tabella `Prodotto`
--

CREATE TABLE `Prodotto` (
  `IDProdotto` varchar(50) NOT NULL,
  `Tipo_Prodotto` enum('Noleggio','Experience') NOT NULL,
  `Tipologia_Prodotto` varchar(30) DEFAULT NULL,
  `Durata_Ore` int(10) UNSIGNED DEFAULT NULL,
  `Nome_Prodotto` varchar(255) NOT NULL,
  `Descrizione_Breve` varchar(500) DEFAULT NULL,
  `Descrizione` text DEFAULT NULL,
  `Prezzo_Base` decimal(10,2) NOT NULL,
  `Posti_Totali` int(11) NOT NULL,
  `Accessibile_Disabili` tinyint(1) DEFAULT 0,
  `Lunghezza_Barca_Metri` decimal(6,2) DEFAULT NULL,
  `Richiede_Patente` tinyint(1) DEFAULT NULL,
  `Data_Creazione` timestamp NULL DEFAULT current_timestamp(),
  `Data_Modifica` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Attivo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Prodotto`
--

INSERT INTO `Prodotto` (`IDProdotto`, `Tipo_Prodotto`, `Tipologia_Prodotto`, `Durata_Ore`, `Nome_Prodotto`, `Descrizione_Breve`, `Descrizione`, `Prezzo_Base`, `Posti_Totali`, `Accessibile_Disabili`, `Lunghezza_Barca_Metri`, `Richiede_Patente`, `Data_Creazione`, `Data_Modifica`, `Attivo`) VALUES
('BARCA-GOMMONE-001', 'Noleggio', 'Gommone', NULL, 'Gommone Speed 250cv', 'Adrenalina e velocità. Ideale per chi ama l\'avventura sul mare.', 'Un gommone potente e veloce, ideale per chi cerca adrenalina e avventura in mare. Perfetto per escursioni dinamiche e divertenti.', 450.00, 8, 0, 9.50, 1, '2025-12-23 12:11:10', '2025-12-23 12:11:10', 1),
('BARCA-GOMMONE-002', 'Noleggio', 'Gommone', NULL, 'Gommone Comfort 200cv', 'Gommone versatile con cabina confortevole.', 'Un gommone confortevole con cabina spaziosa, ideale per famiglie o gruppi che desiderano esplorare il mare con comodità.', 380.00, 10, 0, 8.80, 1, '2025-12-23 12:11:10', '2025-12-23 12:11:10', 1),
('BARCA-GOMMONE-003', 'Noleggio', 'Gommone', NULL, 'Gommone Luxury 300cv', 'Esclusività e potenza. Per chi non vuole compromessi.', 'Un gommone di lusso con prestazioni eccezionali e comfort esclusivo. Perfetto per chi cerca un’esperienza premium in mare.', 550.00, 12, 1, 10.50, 1, '2025-12-23 12:11:10', '2025-12-23 12:11:10', 1),
('BARCA-GOZZO-001', 'Noleggio', 'Motore', NULL, 'Gozzo Sorrentino Classico', 'Barca tradizionale perfetta per famiglie. Ideale per giornate di relax e snorkeling.', 'Un gozzo tradizionale sorrentino, perfetto per giornate di relax e snorkeling. Ideale per famiglie e piccoli gruppi.', 280.00, 6, 1, 8.50, 0, '2025-12-23 12:11:10', '2025-12-23 12:36:19', 1),
('BARCA-GOZZO-002', 'Noleggio', 'Motore', NULL, 'Gozzo Blu Marino', 'Comfort e tradizione con motore affidabile. Perfetto per gite lunghe.', 'Un gozzo elegante e affidabile, ideale per lunghe escursioni e giornate di esplorazione costiera.', 300.00, 8, 0, 9.20, 0, '2025-12-23 12:11:10', '2025-12-23 12:36:24', 1),
('BARCA-PICCOLA-001', 'Noleggio', 'Motore', NULL, 'Barca Aperta 6m', 'Perfetta per principianti e famiglie con bambini.', 'Una barca aperta compatta, perfetta per principianti e famiglie con bambini. Facile da manovrare e sicura.', 150.00, 4, 1, 6.00, 0, '2025-12-23 12:11:10', '2025-12-23 12:35:51', 1),
('BARCA-PICCOLA-002', 'Noleggio', 'Motore', NULL, 'Barca Aperta 7.5m', 'Ideale per gite mezzagiornata. Facile da manovrare.', 'Una barca aperta spaziosa, ideale per gite di mezza giornata. Offre stabilità e facilità di utilizzo.', 180.00, 5, 0, 7.50, 0, '2025-12-23 12:11:10', '2025-12-23 12:35:46', 1),
('BARCA-VELA-001', 'Noleggio', 'Vela', NULL, 'Beneteau First 35', 'Vela sportiva con prestazioni eccellenti. Per velisti esperti.', 'Una barca a vela sportiva con prestazioni eccellenti, perfetta per velisti esperti che cercano avventura e sfida.', 400.00, 6, 0, 10.65, 1, '2025-12-23 12:11:10', '2025-12-23 12:11:10', 1),
('BARCA-VELA-002', 'Noleggio', 'Vela', NULL, 'Jeanneau Sun Odyssey 45', 'Vela con comfort abitativo. Ideale per crociere tranquille.', 'Una barca a vela confortevole e spaziosa, ideale per crociere rilassanti e tranquille lungo la costa.', 550.00, 8, 0, 13.80, 1, '2025-12-23 12:11:10', '2025-12-23 12:11:10', 1),
('BARCA-YACHT-001', 'Noleggio', 'Motore', NULL, 'Azimut 55 Fly', 'Yacht di lusso con salotto interno, cucina e 4 cabine. Esperienza premium.', 'Uno yacht di lusso con interni eleganti, cucina attrezzata e cabine esclusive. Perfetto per un’esperienza premium in mare.', 1200.00, 12, 1, 16.80, 1, '2025-12-23 12:11:10', '2025-12-23 12:36:02', 1),
('BARCA-YACHT-002', 'Noleggio', 'Motore', NULL, 'Cranchi Endurance 41', 'Eleganza e navigabilità. Perfetto per crociere di una o più giornate.', 'Uno yacht elegante e versatile, ideale per crociere di una o più giornate con massimo comfort.', 950.00, 10, 0, 12.50, 1, '2025-12-23 12:11:10', '2025-12-23 12:36:06', 1),
('EXP-ESCURSIONE-001', 'Experience', 'Tour', 8, 'Tour Costiera Amalfitana Completo', 'Giornata intera tra Positano, Amalfi e Praiano. Include pranzo.', 'Un tour completo della Costiera Amalfitana, con visite a Positano, Amalfi e Praiano. Include pranzo e guida esperta.', 120.00, 10, 1, NULL, NULL, '2025-12-23 12:11:10', '2025-12-23 12:34:56', 1),
('EXP-SNORKEL-001', 'Experience', 'Escursione', 5, 'Escursione Snorkeling Capri e Anacapri', 'Mezza giornata alla scoperta dei fondali cristallini di Capri.', 'Un’escursione di snorkeling a Capri e Anacapri, ideale per esplorare i fondali cristallini e la fauna marina.', 75.00, 8, 0, NULL, NULL, '2025-12-23 12:11:10', '2025-12-23 12:35:02', 1),
('EXP-TRAMONTO-001', 'Experience', 'Aperitivo', 4, 'Cena al Tramonto con Prosecco', 'Tour della costa con cena leggera e prosecco. Per coppie romantiche.', 'Un’esperienza romantica al tramonto con cena leggera e prosecco. Perfetta per coppie che cercano un momento speciale.', 95.00, 2, 1, NULL, NULL, '2025-12-23 12:11:10', '2025-12-23 12:34:42', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `Prodotto_Extra`
--

CREATE TABLE `Prodotto_Extra` (
  `IDExtra` int(11) NOT NULL,
  `IDProdotto` varchar(50) NOT NULL,
  `Nome_Extra` varchar(255) NOT NULL,
  `Descrizione_Extra` text DEFAULT NULL,
  `Prezzo_Extra` decimal(10,2) NOT NULL,
  `Opzionale` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Prodotto_Extra`
--

INSERT INTO `Prodotto_Extra` (`IDExtra`, `IDProdotto`, `Nome_Extra`, `Descrizione_Extra`, `Prezzo_Extra`, `Opzionale`) VALUES
(1, 'BARCA-GOZZO-001', 'Skipper Professionista', 'Skipper esperto per navigazione sicura', 100.00, 1),
(2, 'BARCA-GOZZO-001', 'Attrezzatura Snorkeling Completa', 'Maschera, pinne, tubo per 6 persone', 45.00, 1),
(3, 'BARCA-GOZZO-001', 'Assicurazione Danni Aggiuntiva', 'Copertura totale danni alla barca', 60.00, 1),
(4, 'BARCA-GOZZO-002', 'Skipper Professionista', 'Skipper esperto per navigazione sicura', 120.00, 1),
(5, 'BARCA-GOZZO-002', 'Cuoco a Bordo', 'Cuoco per preparare pranzo a bordo', 150.00, 1),
(6, 'BARCA-GOZZO-002', 'Attrezzatura Snorkeling Completa', 'Maschera, pinne, tubo per 8 persone', 60.00, 1),
(7, 'BARCA-GOMMONE-001', 'Skipper Professionista', 'Skipper esperienza fuori strada', 130.00, 1),
(8, 'BARCA-GOMMONE-001', 'Ciambella Trainabile', 'Divertimento a velocità in acqua', 50.00, 1),
(9, 'BARCA-GOMMONE-001', 'GoPro Subacquea Noleggio', 'Registra i tuoi momenti migliori', 25.00, 1),
(10, 'BARCA-GOMMONE-002', 'Skipper Professionista', 'Skipper esperto per navigazione sicura', 140.00, 1),
(11, 'BARCA-GOMMONE-002', 'Ciambella Trainabile', 'Divertimento a velocità in acqua', 50.00, 1),
(12, 'BARCA-GOMMONE-002', 'Attrezzatura Snorkeling', 'Maschera e pinne per 10 persone', 70.00, 1),
(13, 'BARCA-GOMMONE-003', 'Skipper Professionista Luxury', 'Skipper VIP con esperienza internazionale', 200.00, 1),
(14, 'BARCA-GOMMONE-003', 'Champagne Dom Pérignon', 'Bottiglia esclusiva a bordo', 120.00, 1),
(15, 'BARCA-GOMMONE-003', 'Ciambella Trainabile Gold', 'Ciambella premium con comfort massimo', 80.00, 1),
(16, 'BARCA-YACHT-001', 'Skipper Capitano', 'Capitano con patente internazionale', 250.00, 1),
(17, 'BARCA-YACHT-001', 'Hostess Bordo', 'Hostess per servizio premium', 200.00, 1),
(18, 'BARCA-YACHT-001', 'Cena Gourmet Privata', 'Menu personalizzato chef', 400.00, 1),
(19, 'BARCA-YACHT-001', 'Drone Riprese Aeree', 'Fotografie e video drone professionali', 300.00, 1),
(20, 'BARCA-YACHT-002', 'Skipper Capitano', 'Capitano con patente internazionale', 200.00, 1),
(21, 'BARCA-YACHT-002', 'Hostess Bordo', 'Hostess per servizio premium', 180.00, 1),
(22, 'BARCA-YACHT-002', 'Attrezzatura Snorkeling Luxury', 'Attrezzatura premium per snorkeling', 100.00, 1),
(23, 'BARCA-VELA-001', 'Skipper Professionista', 'Skipper vela con esperienza', 140.00, 1),
(24, 'BARCA-VELA-001', 'Attrezzatura Snorkeling', 'Completa per 6 persone', 50.00, 1),
(25, 'BARCA-VELA-002', 'Skipper Professionista Vela', 'Skipper esperto vela', 150.00, 1),
(26, 'BARCA-VELA-002', 'Cuoco a Bordo', 'Cuoco per crociera di più giorni', 200.00, 1),
(27, 'BARCA-VELA-002', 'Attrezzatura Snorkeling Luxury', 'Attrezzatura premium', 80.00, 1),
(28, 'BARCA-PICCOLA-001', 'Giubbotto Salvagente Bimbi', 'Giubbotto per bambini (4 pezzi)', 20.00, 1),
(29, 'BARCA-PICCOLA-002', 'Giubbotto Salvagente Bimbi', 'Giubbotto per bambini (5 pezzi)', 25.00, 1),
(30, 'BARCA-PICCOLA-002', 'Attrezzatura Snorkeling', 'Per 5 persone', 40.00, 1),
(31, 'EXP-TRAMONTO-001', 'Bottiglia Champagne Premium', 'Bollicine di qualità superiore', 80.00, 1),
(32, 'EXP-TRAMONTO-001', 'Rose per la Sorpresa', 'Mazzo di rose rosse incluso', 35.00, 1),
(33, 'EXP-SNORKEL-001', 'Fotografia Subacquea Professionale', 'Foto underwater del vostro momento', 60.00, 1),
(34, 'EXP-SNORKEL-001', 'Snack Gourmet Aggiuntivo', 'Salatini e formaggi premium', 30.00, 1),
(35, 'EXP-ESCURSIONE-001', 'Fotografia Professionale Costiera', 'Foto ricordo della giornata', 100.00, 1),
(36, 'EXP-ESCURSIONE-001', 'Cena Sotto le Stelle Aggiunta', 'Cena a bordo con vista notturna', 150.00, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `Prodotto_Incluso`
--

CREATE TABLE `Prodotto_Incluso` (
  `IDIncluso` int(11) NOT NULL,
  `IDProdotto` varchar(50) NOT NULL,
  `Nome_Incluso` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Prodotto_Incluso`
--

INSERT INTO `Prodotto_Incluso` (`IDIncluso`, `IDProdotto`, `Nome_Incluso`) VALUES
(1, 'BARCA-GOZZO-001', 'Assicurazione Responsabilità Civile'),
(2, 'BARCA-GOZZO-001', 'Carburante'),
(3, 'BARCA-GOZZO-001', 'Giubbotti Salvagente'),
(4, 'BARCA-GOZZO-001', 'Pulizia Finale'),
(5, 'BARCA-GOZZO-001', 'Mappa Cartacea Costa Sorrentina'),
(6, 'BARCA-GOZZO-002', 'Assicurazione Responsabilità Civile'),
(7, 'BARCA-GOZZO-002', 'Carburante'),
(8, 'BARCA-GOZZO-002', 'Giubbotti Salvagente'),
(9, 'BARCA-GOZZO-002', 'Pulizia Finale'),
(10, 'BARCA-GOZZO-002', 'GPS Marino Professionale'),
(11, 'BARCA-GOZZO-002', 'VHF Ricetrasmettitore'),
(12, 'BARCA-GOMMONE-001', 'Assicurazione Responsabilità Civile'),
(13, 'BARCA-GOMMONE-001', 'Carburante'),
(14, 'BARCA-GOMMONE-001', 'Giubbotti Salvagente'),
(15, 'BARCA-GOMMONE-001', 'Kit Primo Soccorso'),
(16, 'BARCA-GOMMONE-002', 'Assicurazione Responsabilità Civile'),
(17, 'BARCA-GOMMONE-002', 'Carburante'),
(18, 'BARCA-GOMMONE-002', 'Giubbotti Salvagente Numero 10'),
(19, 'BARCA-GOMMONE-002', 'Cabina Climatizzata'),
(20, 'BARCA-GOMMONE-002', 'Toilette Marino'),
(21, 'BARCA-GOMMONE-003', 'Assicurazione Kasko Completa'),
(22, 'BARCA-GOMMONE-003', 'Carburante Premium'),
(23, 'BARCA-GOMMONE-003', 'Giubbotti Salvagente Luxury'),
(24, 'BARCA-GOMMONE-003', 'Sistema Audio Premium Bose'),
(25, 'BARCA-GOMMONE-003', 'Frigo a Bordo'),
(26, 'BARCA-YACHT-001', 'Assicurazione Kasko Completa'),
(27, 'BARCA-YACHT-001', 'Carburante Premium'),
(28, 'BARCA-YACHT-001', 'Giubbotti Salvagente'),
(29, 'BARCA-YACHT-001', '4 Cabine Esclusive'),
(30, 'BARCA-YACHT-001', 'Cucina Attrezzata'),
(31, 'BARCA-YACHT-001', 'Salotto interno con TV'),
(32, 'BARCA-YACHT-001', 'Sistema Navigazione GPS Avanzato'),
(33, 'BARCA-YACHT-002', 'Assicurazione Kasko'),
(34, 'BARCA-YACHT-002', 'Carburante'),
(35, 'BARCA-YACHT-002', 'Giubbotti Salvagente'),
(36, 'BARCA-YACHT-002', '3 Cabine Confortevoli'),
(37, 'BARCA-YACHT-002', 'Cucina Attrezzata'),
(38, 'BARCA-YACHT-002', 'Sala Principale'),
(39, 'BARCA-VELA-001', 'Assicurazione Responsabilità Civile'),
(40, 'BARCA-VELA-001', 'Giubbotti Salvagente'),
(41, 'BARCA-VELA-001', 'Attrezzatura Vela Base'),
(42, 'BARCA-VELA-001', 'Mappa Nautica'),
(43, 'BARCA-VELA-002', 'Assicurazione Responsabilità Civile'),
(44, 'BARCA-VELA-002', 'Giubbotti Salvagente'),
(45, 'BARCA-VELA-002', 'Attrezzatura Vela Completa'),
(46, 'BARCA-VELA-002', '2 Cabine'),
(47, 'BARCA-VELA-002', 'Cucina'),
(48, 'BARCA-VELA-002', 'Sistema Autopilota'),
(49, 'BARCA-PICCOLA-001', 'Assicurazione Responsabilità Civile'),
(50, 'BARCA-PICCOLA-001', 'Giubbotti Salvagente'),
(51, 'BARCA-PICCOLA-001', 'Mappa Cartacea'),
(52, 'BARCA-PICCOLA-002', 'Assicurazione Responsabilità Civile'),
(53, 'BARCA-PICCOLA-002', 'Giubbotti Salvagente'),
(54, 'BARCA-PICCOLA-002', 'Mappa Digitale'),
(55, 'BARCA-PICCOLA-002', 'Ombrellone da Sole'),
(56, 'EXP-TRAMONTO-001', 'Guida Turistica Italiana'),
(57, 'EXP-TRAMONTO-001', 'Prosecco di Qualità'),
(58, 'EXP-TRAMONTO-001', 'Stuzzichini Gourmet'),
(59, 'EXP-TRAMONTO-001', 'Giubbotti Salvagente'),
(60, 'EXP-TRAMONTO-001', 'Coperta Elegante'),
(61, 'EXP-SNORKEL-001', 'Guida Turistica Specializzata'),
(62, 'EXP-SNORKEL-001', 'Attrezzatura Snorkeling Completa'),
(63, 'EXP-SNORKEL-001', 'Giubbotti Salvagente'),
(64, 'EXP-SNORKEL-001', 'Snack e Bevande Fresche'),
(65, 'EXP-SNORKEL-001', 'Asciugamani Premium'),
(66, 'EXP-ESCURSIONE-001', 'Guida Turistica Esperta'),
(67, 'EXP-ESCURSIONE-001', 'Pranzo Leggero'),
(68, 'EXP-ESCURSIONE-001', 'Bevande Fresche Illimitate'),
(69, 'EXP-ESCURSIONE-001', 'Giubbotti Salvagente'),
(70, 'EXP-ESCURSIONE-001', 'Attrezzatura Snorkeling'),
(71, 'EXP-ESCURSIONE-001', 'Ombrellone da Sole');

-- --------------------------------------------------------

--
-- Struttura della tabella `Prodotto_Lingua`
--

CREATE TABLE `Prodotto_Lingua` (
  `IDProdotto` varchar(50) NOT NULL,
  `IDLingua` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Prodotto_Lingua`
--

INSERT INTO `Prodotto_Lingua` (`IDProdotto`, `IDLingua`) VALUES
('BARCA-GOMMONE-001', 1),
('BARCA-GOMMONE-002', 1),
('BARCA-GOMMONE-003', 1),
('BARCA-GOZZO-001', 1),
('BARCA-GOZZO-002', 1),
('BARCA-PICCOLA-001', 1),
('BARCA-PICCOLA-002', 1),
('BARCA-VELA-001', 1),
('BARCA-VELA-002', 1),
('BARCA-YACHT-001', 1),
('BARCA-YACHT-002', 1),
('EXP-ESCURSIONE-001', 1),
('EXP-SNORKEL-001', 1),
('EXP-TRAMONTO-001', 1),
('BARCA-GOMMONE-001', 2),
('BARCA-GOMMONE-002', 2),
('BARCA-GOMMONE-003', 2),
('BARCA-GOZZO-001', 2),
('BARCA-GOZZO-002', 2),
('BARCA-PICCOLA-001', 2),
('BARCA-PICCOLA-002', 2),
('BARCA-VELA-001', 2),
('BARCA-VELA-002', 2),
('BARCA-YACHT-001', 2),
('BARCA-YACHT-002', 2),
('EXP-ESCURSIONE-001', 2),
('EXP-SNORKEL-001', 2),
('EXP-TRAMONTO-001', 2),
('EXP-ESCURSIONE-001', 3),
('EXP-SNORKEL-001', 3),
('EXP-TRAMONTO-001', 3),
('EXP-ESCURSIONE-001', 4),
('EXP-SNORKEL-001', 4),
('EXP-TRAMONTO-001', 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `Utente`
--

CREATE TABLE `Utente` (
  `IDUtente` int(11) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Cognome` varchar(100) NOT NULL,
  `CF` varchar(16) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Numero_Patente_Nautica` varchar(50) DEFAULT NULL,
  `IDIndirizzo` int(11) NOT NULL,
  `Is_Admin` tinyint(1) NOT NULL DEFAULT 0,
  `Data_Registrazione` timestamp NULL DEFAULT current_timestamp(),
  `Data_Ultimo_Accesso` timestamp NULL DEFAULT NULL,
  `Attivo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Utente`
--

INSERT INTO `Utente` (`IDUtente`, `Nome`, `Cognome`, `CF`, `Email`, `PasswordHash`, `Numero_Patente_Nautica`, `IDIndirizzo`, `Is_Admin`, `Data_Registrazione`, `Data_Ultimo_Accesso`, `Attivo`) VALUES
(1, 'Marco', 'Rossi', 'RSSMRC80A01H501U', 'admin@noleggio-napoli.it', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', 'PAT-NAUT-ADMIN-001', 1, 1, '2025-12-23 12:11:10', NULL, 1),
(2, 'Lucia', 'De Luca', 'DLCLCU85M41H501L', 'lucia.admin@noleggio-napoli.it', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', 'PAT-NAUT-ADMIN-002', 9, 1, '2025-12-23 12:11:10', NULL, 1),
(3, 'Giovanni', 'Ferraro', 'FRRGVN75D15H501K', 'giovanni.ferraro@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', 'PAT-NAUT-001', 2, 0, '2025-12-23 12:11:10', NULL, 1),
(4, 'Sofia', 'Esposito', 'ESPSFN92F45H501J', 'sofia.esposito@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', NULL, 3, 0, '2025-12-23 12:11:10', NULL, 1),
(5, 'Andrea', 'Moretti', 'MRTAND88H67H501I', 'andrea.moretti@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', 'PAT-NAUT-002', 4, 0, '2025-12-23 12:11:10', NULL, 1),
(6, 'Francesca', 'Marino', 'MRNFRC90S55H501H', 'francesca.marino@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', NULL, 5, 0, '2025-12-23 12:11:10', NULL, 1),
(7, 'Riccardo', 'Colombo', 'CLMRCR84L22H501G', 'riccardo.colombo@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', 'PAT-NAUT-003', 6, 0, '2025-12-23 12:11:10', NULL, 1),
(8, 'Elena', 'Gallo', 'GLLELM87C35H501F', 'elena.gallo@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', NULL, 7, 0, '2025-12-23 12:11:10', NULL, 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Articolo_Blog`
--
ALTER TABLE `Articolo_Blog`
  ADD PRIMARY KEY (`IDArticolo`),
  ADD KEY `IDAutore` (`IDAutore`),
  ADD KEY `idx_pubblicato` (`Pubblicato`);

--
-- Indici per le tabelle `Articolo_Blog_Extra`
--
ALTER TABLE `Articolo_Blog_Extra`
  ADD PRIMARY KEY (`IDExtra`),
  ADD KEY `idx_articolo_extra` (`IDArticolo`);

--
-- Indici per le tabelle `Indirizzo`
--
ALTER TABLE `Indirizzo`
  ADD PRIMARY KEY (`IDIndirizzo`),
  ADD KEY `idx_citta` (`Citta`);

--
-- Indici per le tabelle `Indisponibilita`
--
ALTER TABLE `Indisponibilita`
  ADD PRIMARY KEY (`IDIndisponibilita`),
  ADD KEY `IDProdotto` (`IDProdotto`),
  ADD KEY `Creato_Da` (`Creato_Da`);

--
-- Indici per le tabelle `Lingua`
--
ALTER TABLE `Lingua`
  ADD PRIMARY KEY (`IDLingua`),
  ADD UNIQUE KEY `Codice` (`Codice`),
  ADD KEY `idx_codice` (`Codice`);

--
-- Indici per le tabelle `Media`
--
ALTER TABLE `Media`
  ADD PRIMARY KEY (`IDMedia`),
  ADD UNIQUE KEY `IDUtente` (`IDUtente`),
  ADD UNIQUE KEY `IDProdotto` (`IDProdotto`),
  ADD UNIQUE KEY `IDArticolo` (`IDArticolo`);

--
-- Indici per le tabelle `Prenotazione`
--
ALTER TABLE `Prenotazione`
  ADD PRIMARY KEY (`IDPrenotazione`),
  ADD KEY `IDUtente` (`IDUtente`),
  ADD KEY `IDProdotto` (`IDProdotto`),
  ADD KEY `idx_data_inizio` (`Data_Ora_Inizio`),
  ADD KEY `idx_stato` (`Stato_Prenotazione`);

--
-- Indici per le tabelle `Prodotto`
--
ALTER TABLE `Prodotto`
  ADD PRIMARY KEY (`IDProdotto`),
  ADD KEY `idx_tipo` (`Tipo_Prodotto`),
  ADD KEY `idx_attivo` (`Attivo`);

--
-- Indici per le tabelle `Prodotto_Extra`
--
ALTER TABLE `Prodotto_Extra`
  ADD PRIMARY KEY (`IDExtra`),
  ADD KEY `IDProdotto` (`IDProdotto`);

--
-- Indici per le tabelle `Prodotto_Incluso`
--
ALTER TABLE `Prodotto_Incluso`
  ADD PRIMARY KEY (`IDIncluso`),
  ADD KEY `IDProdotto` (`IDProdotto`);

--
-- Indici per le tabelle `Prodotto_Lingua`
--
ALTER TABLE `Prodotto_Lingua`
  ADD PRIMARY KEY (`IDProdotto`,`IDLingua`),
  ADD KEY `idx_prodottolingua_lingua` (`IDLingua`);

--
-- Indici per le tabelle `Utente`
--
ALTER TABLE `Utente`
  ADD PRIMARY KEY (`IDUtente`),
  ADD UNIQUE KEY `CF` (`CF`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `IDIndirizzo` (`IDIndirizzo`),
  ADD KEY `idx_email` (`Email`),
  ADD KEY `idx_attivo` (`Attivo`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `Articolo_Blog`
--
ALTER TABLE `Articolo_Blog`
  MODIFY `IDArticolo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT per la tabella `Articolo_Blog_Extra`
--
ALTER TABLE `Articolo_Blog_Extra`
  MODIFY `IDExtra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT per la tabella `Indirizzo`
--
ALTER TABLE `Indirizzo`
  MODIFY `IDIndirizzo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT per la tabella `Indisponibilita`
--
ALTER TABLE `Indisponibilita`
  MODIFY `IDIndisponibilita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `Lingua`
--
ALTER TABLE `Lingua`
  MODIFY `IDLingua` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `Media`
--
ALTER TABLE `Media`
  MODIFY `IDMedia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Prenotazione`
--
ALTER TABLE `Prenotazione`
  MODIFY `IDPrenotazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT per la tabella `Prodotto_Extra`
--
ALTER TABLE `Prodotto_Extra`
  MODIFY `IDExtra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT per la tabella `Prodotto_Incluso`
--
ALTER TABLE `Prodotto_Incluso`
  MODIFY `IDIncluso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT per la tabella `Utente`
--
ALTER TABLE `Utente`
  MODIFY `IDUtente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Articolo_Blog`
--
ALTER TABLE `Articolo_Blog`
  ADD CONSTRAINT `Articolo_Blog_ibfk_1` FOREIGN KEY (`IDAutore`) REFERENCES `Utente` (`IDUtente`);

--
-- Limiti per la tabella `Articolo_Blog_Extra`
--
ALTER TABLE `Articolo_Blog_Extra`
  ADD CONSTRAINT `Articolo_Blog_Extra_ibfk_1` FOREIGN KEY (`IDArticolo`) REFERENCES `Articolo_Blog` (`IDArticolo`) ON DELETE CASCADE;

--
-- Limiti per la tabella `Indisponibilita`
--
ALTER TABLE `Indisponibilita`
  ADD CONSTRAINT `Indisponibilita_ibfk_1` FOREIGN KEY (`IDProdotto`) REFERENCES `Prodotto` (`IDProdotto`) ON DELETE CASCADE,
  ADD CONSTRAINT `Indisponibilita_ibfk_2` FOREIGN KEY (`Creato_Da`) REFERENCES `Utente` (`IDUtente`);

--
-- Limiti per la tabella `Media`
--
ALTER TABLE `Media`
  ADD CONSTRAINT `Media_ibfk_1` FOREIGN KEY (`IDUtente`) REFERENCES `Utente` (`IDUtente`) ON DELETE CASCADE,
  ADD CONSTRAINT `Media_ibfk_2` FOREIGN KEY (`IDProdotto`) REFERENCES `Prodotto` (`IDProdotto`) ON DELETE CASCADE,
  ADD CONSTRAINT `Media_ibfk_3` FOREIGN KEY (`IDArticolo`) REFERENCES `Articolo_Blog` (`IDArticolo`) ON DELETE CASCADE;

--
-- Limiti per la tabella `Prenotazione`
--
ALTER TABLE `Prenotazione`
  ADD CONSTRAINT `Prenotazione_ibfk_1` FOREIGN KEY (`IDUtente`) REFERENCES `Utente` (`IDUtente`),
  ADD CONSTRAINT `Prenotazione_ibfk_2` FOREIGN KEY (`IDProdotto`) REFERENCES `Prodotto` (`IDProdotto`);

--
-- Limiti per la tabella `Prodotto_Extra`
--
ALTER TABLE `Prodotto_Extra`
  ADD CONSTRAINT `Prodotto_Extra_ibfk_1` FOREIGN KEY (`IDProdotto`) REFERENCES `Prodotto` (`IDProdotto`) ON DELETE CASCADE;

--
-- Limiti per la tabella `Prodotto_Incluso`
--
ALTER TABLE `Prodotto_Incluso`
  ADD CONSTRAINT `Prodotto_Incluso_ibfk_1` FOREIGN KEY (`IDProdotto`) REFERENCES `Prodotto` (`IDProdotto`) ON DELETE CASCADE;

--
-- Limiti per la tabella `Prodotto_Lingua`
--
ALTER TABLE `Prodotto_Lingua`
  ADD CONSTRAINT `Prodotto_Lingua_ibfk_1` FOREIGN KEY (`IDProdotto`) REFERENCES `Prodotto` (`IDProdotto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Prodotto_Lingua_ibfk_2` FOREIGN KEY (`IDLingua`) REFERENCES `Lingua` (`IDLingua`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `Utente`
--
ALTER TABLE `Utente`
  ADD CONSTRAINT `Utente_ibfk_1` FOREIGN KEY (`IDIndirizzo`) REFERENCES `Indirizzo` (`IDIndirizzo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
