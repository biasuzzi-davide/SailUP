-- phpMyAdmin SQL Dump
-- version 5.2.2deb1+deb13u1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Gen 31, 2026 alle 20:05
-- Versione del server: 11.8.3-MariaDB-0+deb13u1 from Debian
-- Versione PHP: 8.4.16

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
(1, 133, 'Le 10 Cale Più Belle della Costiera Sorrentina', 'Scopri le spiagge nascoste e i paesaggi mozzafiato della costiera.', 'La Costiera Sorrentina è un susseguirsi di scogliere frastagliate e insenature nascoste che spesso rimangono invisibili a chi percorre la strada statale. La vera mappa del tesoro di questo territorio si svela solo via mare, dove è possibile raggiungere angoli di paradiso inaccessibili via terra o raggiungibili solo attraverso sentieri impervi. Noleggiare una barca permette di disegnare la propria rotta personale tra queste dieci meraviglie, trasformando una giornata di mare in un\'esplorazione continua.\r\n\r\nIl viaggio non può che iniziare dai Bagni della Regina Giovanna, un luogo dove storia e natura si fondono in modo spettacolare. Qui, i resti di un\'imponente villa romana incorniciano una piscina naturale d\'acqua smeraldo, separata dal mare aperto da un arco di roccia. Poco distante, navigando verso il Capo di Sorrento, si incontra la Baia di Puolo, un borgo di pescatori con una spiaggia che digrada dolcemente, perfetta per una sosta rilassante. Proseguendo, si trova La Pignatella, non una spiaggia di sabbia ma una distesa di scogli piatti vulcanici, amata dai locali per l\'acqua cristallina e profonda, ideale per i tuffi.\r\n\r\nAvvicinandosi alla punta della penisola, lo scenario diventa più selvaggio con la Cala di Mitigliano. È un\'ampia baia aperta proprio di fronte all\'isola di Capri, caratterizzata da sassi bianchi e un mare di un blu intenso, spesso meno affollata perché difficile da raggiungere a piedi. Il vero gioiello naturalistico è però la Baia di Ieranto, proprietà del FAI e area marina protetta. Qui l\'accesso a motore è regolamentato per preservare la quiete assoluta e la biodiversità: è il luogo dove il mito collocava le Sirene e dove oggi si può nuotare nel silenzio più totale.\r\n\r\nDoppiando Punta Campanella ed entrando nel Golfo di Salerno, si apre la splendida Marina del Cantone a Nerano. Famosa per i suoi ristoranti stellati e per gli spaghetti alla Nerano, offre una spiaggia di ciottoli e acque sempre limpide. Proprio accanto, nascosta da un promontorio, c\'è la Baia di Recommone, un rifugio tranquillo e riparato dai venti, dove l\'acqua è spesso calma come in una piscina. Proseguendo lungo la costa alta e rocciosa, si scopre il Fiordo di Crapolla: una profonda spaccatura nella roccia che custodisce i resti di un\'antica abbazia e le tracce del passaggio di San Pietro. Arrivarci via mare risparmia la fatica di centinaia di gradini e regala una vista dal basso impressionante.\r\n\r\nNon meno affascinanti sono le cale minori che punteggiano il litorale di Massa Lubrense, come la Marina della Lobra, un antico borgo marinaro protetto da una scogliera naturale che crea piscine tranquille, e la suggestiva Cala di San Montano. Quest\'ultima è una piccola oasi di pace caratterizzata da colori vivaci e un fondale basso che la rende molto accogliente. Chiude questa lista ideale lo Scoglio del Vervece, che pur non essendo una cala in senso stretto, rappresenta una tappa obbligata per chi ama il mare profondo: un santuario sottomarino dove l\'acqua è di un blu cobalto che non ha eguali.\r\n\r\nVisitare queste dieci cale in una sola giornata è un\'impresa ambiziosa, ma la libertà di avere una barca tutta per sé sta proprio nel poter scegliere dove gettare l\'ancora. Che si tratti di cercare la storia antica, il buon cibo o semplicemente il silenzio interrotto solo dalle cicale, la costa sorrentina ha un\'insenatura pronta ad accogliere ogni desiderio.', '2025-01-15 00:00:00', 8, 1, '2025-12-23 12:11:10'),
(2, 132, 'Guida Completa allo Snorkeling a Capri', 'Tutto ciò che devi sapere per un\'indimenticabile giornata di snorkeling.', 'Capri non è solo glamour e Piazzetta; sotto la superficie luccicante del mare si nasconde un mondo silenzioso e colorato che aspetta solo di essere esplorato. Fare snorkeling qui è un\'esperienza che va oltre la semplice nuotata: grazie alla conformazione calcarea dell\'isola, l\'acqua raggiunge livelli di trasparenza incredibili, regalando una visibilità che spesso supera i venti metri. Noleggiare una barca è la strategia vincente per evitare le spiagge affollate e raggiungere le calette più segrete dove la fauna marina prospera indisturbata.\r\n\r\nPer godersi appieno l\'avventura, l\'attrezzatura è fondamentale, anche se non serve essere subacquei esperti. Una maschera di buona qualità che non si appanni e un boccaglio sono l\'essenziale, ma l\'uso delle pinne è fortemente raccomandato. Le correnti attorno all\'isola possono farsi sentire e avere una spinta in più permette di muoversi con sicurezza e minor fatica, godendosi lo spettacolo senza stress. Se non avete l\'attrezzatura con voi, spesso è possibile richiederla direttamente al momento del noleggio della barca.\r\n\r\nUno dei punti più iconici per immergersi è senza dubbio la zona dei Faraglioni. Nuotare all\'ombra di questi giganti di pietra è emozionante, ma è guardando sotto che si scopre la vera magia: le pareti rocciose scendono a picco nel blu profondo, colonizzate da spugne colorate e piccoli organismi incrostanti. Qui è facile incontrare banchi di occhiate e saraghi che nuotano veloci, abituati alla presenza delle imbarcazioni ma sempre sfuggenti.\r\n\r\nUn\'altra tappa imperdibile per gli amanti dello snorkeling è la Grotta Verde. Sebbene l\'interno sia accessibile a nuoto, l\'area circostante offre giochi di luce spettacolari. I raggi del sole, filtrando attraverso l\'acqua smeraldo, creano riflessi che illuminano il fondale e i pesci di passaggio, rendendo l\'atmosfera quasi onirica. Anche la zona di Marina Piccola, verso lo Scoglio delle Sirene, offre fondali interessanti e più bassi, ideali per i principianti o per chi vuole osservare da vicino stelle marine e ricci tra gli scogli.\r\n\r\nLa sicurezza deve essere sempre al primo posto. Il traffico marittimo attorno a Capri è intenso, quindi è vitale rimanere vicini alla propria imbarcazione o entro le aree delimitate dalle boe. Se ci si allontana, l\'uso di un pallone segnasub è obbligatorio per rendersi visibili alle altre barche. Inoltre, vige la regola del \"guardare ma non toccare\": organismi come i vermocani o le meduse possono essere urticanti, e toccare i coralli o le stelle marine può danneggiarli gravemente.\r\n\r\nConcludere la giornata con il sale sulla pelle e negli occhi le immagini di un mondo sottomarino vibrante aggiunge un tassello fondamentale al ricordo di Capri. Non si tratta solo di aver visto l\'isola, ma di averla vissuta in tutte le sue dimensioni, scoprendo che la sua bellezza sommersa compete ad armi pari con quella dei suoi celebri panorami terrestri.', '2025-01-12 00:00:00', 10, 1, '2025-12-23 12:11:10'),
(3, 131, 'Come Ormeggiare in Sicurezza: Guida per Principianti', 'Tecniche di ormeggio essenziali per chi inizia a navigare.', 'L\'ormeggio è spesso considerato il momento più stressante per chi si avvicina per la prima volta alla conduzione di una barca, eppure è una manovra che, se affrontata con la giusta preparazione, può diventare una semplice routine. La differenza tra un ormeggio caotico e uno perfetto non risiede tanto nell\'abilità tecnica innata, quanto nella capacità di mantenere la calma e pianificare le mosse in anticipo. Quando si rientra in porto dopo una giornata di sole e mare, la stanchezza può giocare brutti scherzi, per questo è fondamentale approcciare la banchina con la mente lucida e senza fretta.\r\n\r\nLa regola d\'oro per una manovra sicura è preparare tutto il necessario ben prima di avvicinarsi alla zona di manovra. Questo significa che i parabordi devono essere già posizionati e regolati all\'altezza giusta su entrambi i lati, e le cime d\'ormeggio devono essere sciolte, chiare e pronte per essere lanciate. Arrivare in prossimità del posto barca e dover ancora cercare una cima nel gavone è l\'errore più comune che genera panico a bordo. Avere la barca \"armata\" per l\'arrivo permette al comandante di concentrarsi esclusivamente sulla guida.\r\n\r\nUn aspetto cruciale riguarda la gestione dell\'equipaggio. Se hai a bordo amici o familiari inesperti, è vitale dare loro istruzioni precise prima di iniziare la manovra. La direttiva più importante per la sicurezza è quella di non usare mai, per nessun motivo, le mani o i piedi per cercare di fermare la barca o allontanarla da un\'altra imbarcazione. Una barca, anche piccola, ha un\'inerzia e un peso enormi: per frenarla si usa solo il motore, ingranando la retromarcia. Le persone devono rimanere sedute o, se devono aiutare con le cime, devono farlo solo quando la barca è ferma e in sicurezza.\r\n\r\nQuando si entra in porto, la velocità deve essere ridotta al minimo indispensabile per governare lo scafo. Bisogna osservare attentamente da dove proviene il vento: se possibile, è sempre meglio manovrare con la prua al vento, che funge da freno naturale, piuttosto che averlo in poppa che spinge e accelera l\'avvicinamento. Non avere paura di abortire la manovra se non ti senti sicuro: fare un giro in più, riallinearsi e riprovare con calma è segno di buon senso marinaro, non di incapacità.\r\n\r\nSe invece l\'obiettivo è l\'ancoraggio in rada per un bagno, la sicurezza passa dalla scelta del fondale. Bisogna individuare una zona di sabbia chiara (evitando la scura Posidonia) e dare fondo all\'ancora con la barca ferma prua al vento. Una volta che l\'ancora tocca il fondo, si va leggermente indietro per farla \"mordere\" il terreno. La quantità di catena da calare è fondamentale: deve essere almeno tre o quattro volte la profondità del fondale per garantire che la barca non ari, ovvero non trascini l\'ancora se si alza il vento.\r\n\r\nInfine, ricorda che non sei solo. In porto, gli ormeggiatori sono lì per aiutarti: segui le loro indicazioni e, se sei in difficoltà, non esitare a chiamarli via radio o con un cenno. Spesso ti passeranno una \"coda\" (una cima guida) o ti aiuteranno a raddrizzare la prua con il gommone. L\'umiltà di chiedere supporto è la migliore garanzia per proteggere la barca, l\'equipaggio e il tuo portafoglio da danni accidentali, permettendoti di godere fino all\'ultimo secondo della tua esperienza in mare.', '2025-01-10 00:00:00', 6, 1, '2025-12-23 12:11:10'),
(6, 133, 'Sicurezza in Mare: Protocolli e Attrezzature Obbligatorie', 'Tutto ciò che devi sapere per navigare in sicurezza.', 'La sicurezza in mare non è un semplice adempimento burocratico o una lista di regole da seguire per evitare multe, ma è la base fondamentale su cui si costruisce ogni esperienza di navigazione piacevole. Sapere di essere preparati a gestire eventuali imprevisti è l\'unico modo per rilassarsi veramente e godersi il mare. Spesso chi noleggia una barca per la prima volta tende a sottovalutare questi aspetti, ma il mare è un ambiente mutevole che richiede rispetto e consapevolezza, indipendentemente dalla grandezza dell\'imbarcazione o dalla distanza dalla costa.\r\n\r\nPrima di mollare gli ormeggi, il primo passo è familiarizzare con le dotazioni di sicurezza presenti a bordo. Per legge, ogni imbarcazione deve essere equipaggiata in base alla distanza di navigazione dalla costa. Gli elementi essenziali includono i giubbotti di salvataggio (uno per ogni persona imbarcata), che non devono mai essere chiusi a chiave in gavoni inaccessibili, ma tenuti a portata di mano. Altrettanto importanti sono la ciambella di salvataggio con la cima galleggiante, gli estintori controllati e carichi, e i segnali di soccorso come i razzi e le boette fumogene. Verificare la presenza e la posizione di questi oggetti insieme al noleggiatore è un dovere del comandante.\r\n\r\nUn protocollo spesso ignorato ma vitale è il briefing pre-partenza con l\'equipaggio. Dedicare cinque minuti, prima di accendere il motore, per spiegare ai passeggeri come comportarsi in caso di emergenza può fare la differenza. Tutti a bordo devono sapere dove si trovano i giubbotti, come indossarli, come utilizzare la radio VHF per lanciare un Mayday e dove si trova il kit di pronto soccorso. Se a bordo ci sono bambini o persone che non sanno nuotare bene, l\'uso del giubbotto deve essere obbligatorio durante la navigazione, non solo in caso di emergenza.\r\n\r\nLa comunicazione è il filo rosso che ci lega alla terraferma e ai soccorsi. Il numero blu 1530 della Guardia Costiera è attivo 24 ore su 24 su tutto il territorio nazionale ed è il riferimento immediato per ogni emergenza in mare. Tuttavia, in zone dove la copertura cellulare può mancare, la radio VHF sul canale 16 rimane lo strumento più affidabile. Saperla utilizzare correttamente, evitando chiacchiere inutili per lasciare libero il canale per le urgenze, è segno di grande professionalità marinara.\r\n\r\nLa sicurezza passa anche dal rispetto delle distanze e della velocità. Avvicinarsi troppo alle spiagge è pericoloso per i bagnanti e rischia di danneggiare l\'elica o lo scafo su fondali bassi. È obbligatorio mantenere una distanza di sicurezza (solitamente 200 metri dalle spiagge e 100 dalle scogliere a picco) e navigare a velocità minima quando si entra o si esce dai porti o si attraversa una zona di ancoraggio. La prudenza non è mai troppa: un occhio sempre attento all\'orizzonte e agli altri natanti previene collisioni e situazioni di panico.\r\n\r\nInfine, l\'elemento più imprevedibile: il meteo. Consultare le previsioni non solo per la mattina, ma per l\'intera giornata, è imperativo. Il vento in costiera può alzarsi improvvisamente nel pomeriggio, rendendo il rientro difficile per chi è inesperto. Un buon comandante sa rinunciare a raggiungere una cala lontana se le condizioni non sono ottimali; la vera abilità sta nel valutare i rischi e mettere sempre la sicurezza dell\'equipaggio al primo posto.', '2025-01-03 00:00:00', 12, 1, '2025-12-23 12:11:10'),
(9, 133, 'Ricette di Cucina Marinara: Piatti da Preparare a Bordo', 'Delizie culinarie da gustare durante una crociera.', 'Cucinare a bordo di una barca non è semplicemente un modo per sfamarsi, ma un vero e proprio rito che amplifica il piacere della navigazione. C\'è qualcosa di magico nel preparare un pasto cullati dal movimento del mare, circondati dal profumo della salsedine che sembra insaporire ogni piatto meglio di qualsiasi spezia. Spesso si teme che la cambusa ridotta e i fornelli limitati siano un ostacolo, ma la verità è che la cucina di mare migliore nasce proprio dalla semplicità e dalla freschezza estrema delle materie prime, che in Costiera Sorrentina e Amalfitana non mancano mai.\r\n\r\nLa regola d\'oro per lo chef di bordo è l\'organizzazione unita alla praticità. A pranzo, quando il sole è alto e si preferisce passare il tempo in acqua piuttosto che ai fornelli, la soluzione vincente risiede nei piatti freddi della tradizione campana. La \"fresella\" è la regina incontrastata di questi momenti: una base di pane biscottato bagnata velocemente in acqua di mare (o acqua dolce per i meno temerari) e condita con pomodorini succosi, basilico fresco, olio extravergine d\'oliva e, per chi vuole esagerare, tonno o mozzarella di bufala. È un pasto completo, fresco e che non richiede l\'accensione di fuochi, lasciando la cabina fresca.\r\n\r\nUn\'altra opzione perfetta per il pranzo, che richiede zero cottura ma offre il massimo del gusto, è l\'insalata caprese. Qui la qualità degli ingredienti fa la differenza: il pomodoro deve essere quello \"Cuore di Bue\" di Sorrento, carnoso e dolce, e la mozzarella deve essere freschissima. Alternare fette di pomodoro e mozzarella, guarnendo con abbondante basilico e un filo d\'olio locale, crea un piatto che racchiude i colori della bandiera italiana e i sapori dell\'estate mediterranea, ideale da gustare nel pozzetto dopo una lunga nuotata.\r\n\r\nQuando cala il sole e l\'aria si rinfresca, arriva il momento della classica spaghettata. In barca, la pasta lunga è sinonimo di convivialità. Una ricetta che non tradisce mai, facile da gestire anche con due soli fuochi, è lo spaghetto con le vongole o con la colatura di alici di Cetara. Se si ha la fortuna di aver acquistato vongole fresche al porto, basta farle aprire in padella con aglio e olio mentre la pasta cuoce. Se invece si opta per la colatura, il procedimento è ancora più semplice: si condisce la pasta a crudo con questo prezioso estratto ambrato, aglio e prezzemolo, ottenendo un piatto dal sapore di mare intenso e sofisticato senza sporcare troppe pentole.\r\n\r\nPer chi ama i secondi, il consiglio è di sfruttare il pescato del giorno cucinandolo \"all\'acqua pazza\". È una tecnica antica nata proprio sulle barche dei pescatori ponzesi e napoletani, che utilizzavano l\'acqua di mare per cuocere il pesce insieme a pomodorini, aglio e olio. Oggi si usa acqua dolce con un pizzico di sale, ma il risultato è ugualmente tenero e saporito. Filetti di orata o spigola cuociono in pochi minuti in questo guazzetto leggero, che invita inevitabilmente alla \"scarpetta\" con il pane casereccio.\r\n\r\nInfine, nessun pasto a bordo è completo senza il giusto accompagnamento. In queste zone, il vino bianco locale come una Falanghina o un Biancolella d\'Ischia, tenuto ben freddo in ghiacciaia, è quasi obbligatorio. E per chiudere in dolcezza, seguendo l\'usanza napoletana, non c\'è nulla di meglio delle \"percoche nel vino\": pesche gialle tagliate a pezzi e lasciate macerare nel vino rosso o bianco, da mangiare rigorosamente con la forchetta mentre si guarda la costa illuminarsi per la sera.', '2025-12-03 00:00:00', 6, 1, '2025-12-23 12:11:10'),
(10, 132, 'Flora e Fauna Marina della Costiera Sorrentina', 'Un approfondimento sulla biodiversità sottomarina della costa campana.', 'La Costiera Sorrentina è celebre in tutto il mondo per le sue scogliere a picco sul mare, i borghi colorati e il profumo di limoni, ma una parte fondamentale della sua bellezza rimane spesso nascosta agli occhi di chi si ferma alla superficie. Noleggiare una barca ed esplorare queste acque permette di scoprire un ecosistema vibrante e complesso, dove la biodiversità del Mar Tirreno si manifesta in forme sorprendenti. Non si tratta solo di fare un tuffo rinfrescante, ma di entrare in contatto con un habitat che richiede rispetto e attenzione per essere compreso appieno.\r\n\r\nUno degli elementi più importanti, anche se spesso sottovalutato o addirittura considerato fastidioso dai bagnanti meno esperti, è la Posidonia Oceanica. Non è un\'alga, ma una vera e propria pianta acquatica che forma praterie sottomarine essenziali. La sua presenza è un ottimo segnale: indica che le acque sono pulite e ben ossigenate. Queste distese verdi sono il polmone del Mediterraneo e offrono rifugio e nutrimento a innumerevoli specie marine, proteggendo inoltre la costa dall\'erosione. Vedere chiazze scure sul fondale sabbioso non deve quindi spaventare, ma rassicurare sulla salute del mare in cui ci si sta immergendo.\r\n\r\nPer chi ama lo snorkeling, le acque costiere offrono incontri frequenti con specie colorate e vivaci. Tra le rocce e la posidonia è facile avvistare banchi di castagnole, piccoli pesci dal colore nero o blu elettrico a seconda dell\'età, e le donzelle con le loro livree variopinte. Non mancano le occhiate e i saraghi, che spesso si avvicinano curiosi alle imbarcazioni in sosta. È un tipo di fauna che riempie il mare di movimento e vita, rendendo ogni nuotata un\'esperienza visiva appagante anche senza attrezzature professionali.\r\n\r\nScendendo più in profondità o esplorando gli anfratti rocciosi, magari con un po\' di fortuna e occhio attento, si possono incontrare abitanti più schivi. Le cernie, un tempo rare a causa della pesca eccessiva, popolano le zone più protette, nascondendosi tra le fessure insieme a polpi e murene. La presenza dell\'Area Marina Protetta di Punta Campanella ha giocato un ruolo cruciale nella salvaguardia di queste specie, creando un santuario dove la natura può rigenerarsi al riparo dalle attività umane più invasive.\r\n\r\nParlando di incontri speciali, capita talvolta di avvistare delfini al largo o, più raramente, tartarughe Caretta caretta. Tuttavia, è bene essere onesti: non siamo in un documentario programmato. Questi animali sono selvatici e liberi, e i loro avvistamenti sono eventi fortuiti che dipendono dalle correnti, dal traffico marittimo e dal caso. Noleggiare una barca offre la possibilità privilegiata di trovarsi nel posto giusto al momento giusto, ma il vero valore dell\'esperienza sta nel godersi il mare nella sua quotidianità, apprezzando la natura per quello che è realmente.', '2025-12-18 00:00:00', 10, 1, '2025-12-23 12:11:10'),
(12, 131, 'Sorrento: La Perla della Costiera', 'Alla scoperta della affascinante città di Sorrento.', 'Sorrento non è semplicemente una tappa di passaggio per raggiungere Capri o la Costiera Amalfitana, ma una destinazione che incarna l\'essenza stessa dell\'eleganza mediterranea. Arrivando dal mare, la città si presenta come una spettacolare terrazza naturale, arroccata su imponenti falesie di tufo che scendono a picco nell\'acqua blu profondo. Questa conformazione geologica unica non solo regala panorami mozzafiato, ma racconta la storia millenaria di un territorio plasmato dalle eruzioni vulcaniche e dal mito: non è un caso che queste fossero, secondo la leggenda, le acque delle Sirene che tentarono Ulisse.\r\n\r\nIl cuore pulsante della vita cittadina è Piazza Tasso, un salotto a cielo aperto da cui si diramano i caratteristici vicoli del centro storico. Passeggiare lungo Via San Cesareo e le stradine limitrofe significa immergersi in un labirinto di colori e profumi. Qui le botteghe artigiane espongono i celebri intarsi lignei, frutto di una tradizione locale di grande pregio, e i negozi offrono il limoncello, l\'oro liquido prodotto con i famosi limoni IGP della zona. L\'atmosfera è vivace ma mai caotica, mantenendo un equilibrio perfetto tra la dolce vita internazionale e l\'autenticità di un borgo vissuto.\r\n\r\nUn luogo che merita assolutamente uno sguardo, anche solo dall\'alto, è il Vallone dei Mulini. Si tratta di una profonda fenditura nella roccia, un antico vallone dove la natura si è riappropriata degli spazi, avvolgendo i resti di un vecchio mulino e di una segheria in un abbraccio di verde selvaggio. È uno spettacolo quasi surreale, un\'oasi di biodiversità che contrasta con la vivacità del centro urbano soprastante e testimonia la forza inarrestabile della natura.\r\n\r\nPer chi vive l\'esperienza via mare, però, la vera magia si scopre scendendo verso i borghi marinari. Marina Grande è un piccolo gioiello rimasto quasi intatto nel tempo, separato dal resto della città e accessibile tramite antiche scale o stradine tortuose. Con le sue case color pastello, le reti dei pescatori stese al sole e i ristoranti affacciati direttamente sulla spiaggia, Marina Grande conserva l\'anima più verace di Sorrento, quella che ha ispirato film con Sophia Loren e canzoni indimenticabili. È il luogo perfetto per un pranzo a base di pesce fresco, cullati dal rumore della risacca.\r\n\r\nNavigare lungo la costa sorrentina offre poi l\'opportunità di scoprire tesori nascosti come i Bagni della Regina Giovanna. Questo sito archeologico e naturale, situato verso la punta del Capo di Sorrento, ospita i resti di un\'imponente villa romana del I secolo a.C. e una piscina naturale d\'acqua smeraldo collegata al mare da un arco di roccia. Raggiungerlo in barca permette di godere di una prospettiva privilegiata e di fare un bagno in uno scenario che unisce storia antica e bellezza paesaggistica in modo unico.\r\n\r\nLa giornata ideale a Sorrento si conclude ammirando il tramonto, quando il sole scende dietro l\'isola di Ischia all\'orizzonte. Il cielo si tinge di sfumature che vanno dal rosa all\'arancione intenso, riflettendosi sulle pareti di tufo che sembrano accendersi di luce propria. È un momento di pace assoluta, da godersi magari con un aperitivo a bordo, prima di rientrare in porto con negli occhi e nel cuore l\'immagine indelebile della \"Perla della Costiera\".', '2025-12-10 00:00:00', 7, 1, '2025-12-23 12:11:10'),
(13, 131, 'Fotografia Subacquea: Cattura i Tuoi Momenti Migliori', 'Consigli pratici per fotografare il mondo sottomarino.', 'Portare a casa un ricordo tangibile delle proprie nuotate nelle acque cristalline della Costiera è un desiderio comune, e fortunatamente oggi la fotografia subacquea è alla portata di tutti. Non serve necessariamente un\'attrezzatura professionale ingombrante: le moderne action cam e le custodie impermeabili per smartphone permettono di ottenere risultati sorprendenti. Il segreto per scatti memorabili non sta tanto nella fotocamera, quanto nella capacità di sfruttare la luce naturale che, in queste zone, penetra l\'acqua creando giochi di rifrazione unici.\r\n\r\nLa regola d\'oro per chi inizia è gestire la distanza. L\'acqua agisce come un filtro denso che riduce la nitidezza e i colori, assorbendo rapidamente le tonalità calde come il rosso e l\'arancione. Per foto vivide e brillanti, il consiglio è di avvicinarsi il più possibile al soggetto, molto più di quanto si farebbe sulla terraferma. Rimanere entro i primi metri di profondità aiuta moltissimo: qui la luce solare è ancora forte e permette di catturare i colori reali dei pesci e della vegetazione senza bisogno di flash artificiali.\r\n\r\nLa prospettiva può trasformare una foto banale in un\'immagine artistica. Invece di scattare dall\'alto verso il basso, che spesso restituisce uno sfondo scuro e confuso, prova a immergerti leggermente più del tuo soggetto e a scattare verso l\'alto. Questa tecnica permette di avere la superficie del mare e i raggi del sole come sfondo, creando silhouette suggestive e donando grande tridimensionalità all\'immagine. È un trucco semplice che aggiunge subito un tocco professionale ai tuoi scatti vacanzieri.\r\n\r\nLa stabilità è fondamentale, ma in mare non è sempre facile mantenerla a causa delle correnti. Cerca di muoverti lentamente per non spaventare la fauna: i pesci sono curiosi ma scappano ai movimenti bruschi. Se fai snorkeling, sfrutta la tecnica dello scatto a raffica; gli animali marini sono veloci e imprevedibili, e avere una sequenza di foto ti permette di scegliere quell\'unico istante perfetto in cui la posa e la luce si allineano magicamente.\r\n\r\nRicorda sempre che sei un ospite in un ecosistema delicato. La ricerca dello scatto perfetto non deve mai giustificare il contatto con gli organismi marini o il danneggiamento dei fondali. Non inseguire i pesci e non toccare stelle marine o coralli per posizionarli meglio: le foto più belle sono quelle naturali, che raccontano l\'armonia del mondo sommerso rispettando i suoi abitanti. Un approccio etico non solo protegge il mare, ma spesso viene premiato dagli animali stessi, che si lasciano avvicinare di più se non si sentono minacciati.', '2025-12-08 00:00:00', 9, 1, '2025-12-23 12:11:10'),
(14, 133, 'Sostenibilità Marina: Come Navigare Responsabilmente', 'Pratiche ecologiche per proteggere l\'ecosistema marino.', 'Il mare ci regala senso di libertà e bellezza incondizionata, ma è una risorsa fragile che va protetta con gesti concreti. Navigare responsabilmente non significa rinunciare al piacere della scoperta, ma adottare un approccio consapevole che permetta di preservare l\'ecosistema marino per le generazioni future. La sostenibilità a bordo inizia molto prima di salpare, con piccole scelte quotidiane che, sommate, fanno una differenza enorme per la salute del Mediterraneo.\r\n\r\nUno dei primi aspetti da considerare è la gestione del motore e della velocità. Mantenere un\'andatura di crociera moderata non solo riduce drasticamente il consumo di carburante e le emissioni di CO2, ma limita anche l\'inquinamento acustico sottomarino, che è fonte di grande stress per i cetacei e i pesci. Evitare accelerazioni brusche e spegnere il motore quando si è in sosta, invece di lasciarlo al minimo, sono abitudini semplici che rendono la navigazione più \"gentile\" verso l\'ambiente circostante.\r\n\r\nUn punto cruciale riguarda l\'ancoraggio, una delle cause principali del degrado dei fondali costieri. Gettare l\'ancora sulle praterie di Posidonia Oceanica provoca danni irreparabili a questo polmone verde, che impiega anni per rigenerarsi. È fondamentale cercare sempre fondali sabbiosi o, meglio ancora, utilizzare i campi boa predisposti nelle aree protette. Queste strutture non solo garantiscono una tenuta sicura, ma eliminano completamente l\'impatto fisico dell\'ancora e della catena sul fondale vivo.\r\n\r\nLa gestione dei rifiuti a bordo richiede rigore assoluto. La regola d\'oro è che nulla deve finire in mare: dalla plastica ai mozziconi di sigaretta, che impiegano anni a degradarsi rilasciando sostanze tossiche. È buona norma ridurre a monte gli imballaggi monouso portando borracce e stoviglie riutilizzabili, e organizzare la raccolta differenziata anche in barca, per poi smaltire tutto correttamente una volta rientrati in porto. Anche un piccolo pezzo di plastica volato via per distrazione può diventare un pericolo mortale per una tartaruga o un uccello marino.\r\n\r\nInfine, attenzione ai prodotti che usiamo sul nostro corpo e per la pulizia. Le creme solari tradizionali contengono spesso filtri chimici dannosi per i coralli e gli organismi marini; scegliere solari \"ocean friendly\" o biodegradabili aiuta a mantenere l\'acqua pulita. Lo stesso vale per shampoo e saponi utilizzati per le docce all\'aperto sulla plancetta di poppa: utilizzare prodotti ecologici certificati assicura che la nostra igiene non diventi una fonte di inquinamento chimico per il mare in cui stiamo nuotando.', '2025-12-05 00:00:00', 8, 1, '2025-12-23 12:11:10'),
(15, 132, 'Isola di Capri: Leggenda e Realtà', 'Mito, storia e meraviglie dell\'isola più celebre del golfo.', 'Capri non è semplicemente un\'isola, ma un mito che emerge dalle acque del Mar Tirreno. Avvicinarsi alle sue coste in barca è un\'esperienza che mescola timore reverenziale e pura meraviglia estetica. Le pareti calcaree che si ergono imponenti sul mare sembrano custodire segreti millenari, ed è facile capire perché gli antichi credevano che queste fossero le dimore delle Sirene incantatrici. La realtà geologica dell\'isola, con le sue grotte e i suoi archi naturali, supera spesso la fantasia, offrendo uno spettacolo che cambia continuamente a seconda dell\'inclinazione dei raggi solari.\r\n\r\nIl simbolo indiscusso di questo paesaggio sono i Faraglioni, i tre giganti di roccia che fanno da guardia alla costa sud-orientale. Navigare vicino a Stella, al Faraglione di Mezzo e al Faraglione di Fuori è un rito di passaggio per chiunque visiti l\'isola via mare. Passare sotto l\'arco naturale del Faraglione di Mezzo non è solo una tradizione romantica che promette amore eterno, ma un momento di profonda connessione con la grandezza della natura. Il silenzio rotto solo dallo sciabordio dell\'acqua contro la roccia viva rende questo passaggio uno dei ricordi più intensi dell\'intera giornata.\r\n\r\nOltre alla celeberrima Grotta Azzurra, che spesso richiede lunghe attese per l\'ingresso, noleggiare una barca offre il privilegio di scoprire le altre meraviglie cromatiche dell\'isola. La Grotta Bianca, con le sue stalattiti che pendono minacciose e affascinanti, e la Grotta Verde, dove la luce filtra attraverso l\'acqua creando sfumature smeraldo quasi irreali, sono tappe imperdibili. Qui è possibile fermarsi per un tuffo in acque cristalline, lontano dalla folla dei traghetti turistici, godendo di una privacy che a terra è quasi impossibile trovare.\r\n\r\nMa Capri è anche storia imperiale. Alzando lo sguardo verso le cime, si scorgono le rovine di Villa Jovis, da cui l\'imperatore Tiberio governò l\'Impero Romano per un decennio. Immaginare la vita di duemila anni fa mentre si è cullati dalle onde offre una prospettiva unica: quella di osservare l\'isola non come un semplice turista, ma come un viaggiatore che ne comprende la profondità storica. L\'isola è stata rifugio di intellettuali, artisti ed esiliati, tutti rapiti da quella che viene definita \"l\'aria di Capri\", un misto di brezza marina e profumo di macchia mediterranea.\r\n\r\nEsiste poi una Capri più mondana, quella della Piazzetta e delle vie dello shopping di lusso, che contrasta nettamente con la pace che si respira al largo. L\'esperienza perfetta sta nel bilanciare questi due mondi: scendere a terra per un caffè e una passeggiata tra i vicoli di Anacapri o i Giardini di Augusto, per poi rifugiarsi nuovamente a bordo al tramonto. È proprio quando il sole cala e la folla dei visitatori giornalieri riparte che l\'isola svela la sua anima più autentica e silenziosa.\r\n\r\nLasciare Capri, guardando la sua sagoma svanire lentamente all\'orizzonte mentre si rientra verso la costiera, lascia addosso una sensazione particolare, spesso chiamata \"mal di Capri\". È la consapevolezza di aver toccato con mano un luogo dove la leggenda e la realtà si fondono in modo indistinguibile, lasciando la promessa silenziosa di un ritorno.', '2025-12-03 00:00:00', 10, 1, '2025-12-23 12:11:10');

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
(143, 10, '🌿 Flora Marina', 'Posidonia Oceanica - Pianta marina protetta', 1, '2026-01-14 17:50:06'),
(144, 10, '🐟 Specie Comuni', 'Dentice - Pesce argentato velocissimo', 2, '2026-01-14 17:50:06'),
(145, 10, '🐟 Specie Comuni', 'Cernia - Pesce grande e territoriale', 3, '2026-01-14 17:50:06'),
(146, 12, '🏛️ Monumenti', 'Duomo di Sorrento - Importante chiesa medievale', 1, '2026-01-14 17:52:03'),
(147, 12, '🏛️ Monumenti', 'Basilica Sant\'Antonino - Patrono della città', 2, '2026-01-14 17:52:03'),
(148, 13, '📷 Attrezzatura', 'GoPro Hero 12 - Compatta e versatile', 1, '2026-01-14 17:57:10'),
(149, 13, '📷 Attrezzatura', 'Custodia stagna professionale - Protezione totale', 2, '2026-01-14 17:57:10'),
(150, 13, '💡 Consigli di Fotografia', 'Evita il flash - Usa la luce naturale', 3, '2026-01-14 17:57:10'),
(151, 13, '💡 Consigli di Fotografia', 'Scatta da angolazioni diverse', 4, '2026-01-14 17:57:10'),
(152, 14, '♻️ Pratiche Ecologiche', 'Usa biodegradabili per pulizia a bordo', 1, '2026-01-14 17:58:46'),
(153, 14, '♻️ Pratiche Ecologiche', 'Non gettare spazzatura in mare', 2, '2026-01-14 17:58:46'),
(154, 14, '♻️ Pratiche Ecologiche', 'Rispetta le aree marine protette', 3, '2026-01-14 17:58:46'),
(155, 15, '🗿 Faraglioni', 'Tre enormi monoliti di roccia calcarea', 1, '2026-01-14 17:59:48'),
(156, 15, '🗿 Faraglioni', 'Grotta Azzurra - Fenomeno ottico straordinario', 2, '2026-01-14 17:59:48'),
(157, 15, '🗿 Faraglioni', 'Villa Jovis di Tiberio - Rovine imperiali', 3, '2026-01-14 17:59:48'),
(158, 9, '👨‍🍳 Ricette Semplici', 'Spaghetti alle Vongole Veraci', 1, '2026-01-14 18:01:15'),
(159, 9, '👨‍🍳 Ricette Semplici', 'Branzino al Forno con Limone', 2, '2026-01-14 18:01:15'),
(160, 9, '👨‍🍳 Ricette Semplici', 'Insalata di Frutti di Mare', 3, '2026-01-14 18:01:15'),
(161, 9, '👨‍🍳 Ricette Semplici', 'Carbonara di Ciccio', 4, '2026-01-14 18:01:15'),
(162, 1, '📋 Cosa Portare', 'Maschera e boccaglio in buone condizioni', 1, '2026-01-14 18:02:25'),
(163, 1, '📋 Cosa Portare', 'Crema solare con protezione forte (SPF 50+)', 2, '2026-01-14 18:02:25'),
(164, 1, '📋 Cosa Portare', 'Asciugamani microfibra (secchi subito)', 3, '2026-01-14 18:02:25'),
(165, 1, '📋 Cosa Portare', 'Snack e acqua abbondante', 4, '2026-01-14 18:02:25'),
(166, 1, '🏖️ Top 5 Cale da Non Perdere', 'Cala dell\'Infreschi - Splendida cala con spiaggia di sabbia bianca', 5, '2026-01-14 18:02:25'),
(167, 1, '🏖️ Top 5 Cale da Non Perdere', 'Marina di Puolo - Villaggio di pescatori pittoresco', 6, '2026-01-14 18:02:25'),
(168, 1, '🏖️ Top 5 Cale da Non Perdere', 'Tre Sorelle - Tre faraglioni iconici', 7, '2026-01-14 18:02:25'),
(169, 1, '🏖️ Top 5 Cale da Non Perdere', 'Cala di Mitigliano - Acque cristalline', 8, '2026-01-14 18:02:25'),
(170, 1, '🏖️ Top 5 Cale da Non Perdere', 'Cala dei Limoni - Piccolo paradiso per lo snorkeling', 9, '2026-01-14 18:02:25'),
(171, 2, '⏰ Periodo Migliore', 'Maggio-Settembre per condizioni ottimali', 1, '2026-01-14 18:03:38'),
(172, 2, '⏰ Periodo Migliore', 'Giugno-Agosto per acqua più calda', 2, '2026-01-14 18:03:38'),
(173, 2, '🐠 Fauna Marina', 'Dentici - Pesci d\'argento e molto diffidenti', 3, '2026-01-14 18:03:38'),
(174, 2, '🐠 Fauna Marina', 'Cernie - Predatori territoriali, affascinanti da osservare', 4, '2026-01-14 18:03:38'),
(175, 2, '🐠 Fauna Marina', 'Stelle Marine e Ricci di Mare - Fauna colorata', 5, '2026-01-14 18:03:38'),
(176, 2, '🐠 Fauna Marina', 'Murene - Creature affascinanti nelle fessure', 6, '2026-01-14 18:03:38'),
(177, 2, '📍 Spot Migliori', 'Grotta Azzurra - Lo spot più famoso', 7, '2026-01-14 18:03:38'),
(178, 2, '📍 Spot Migliori', 'Faraglioni - Pareti rocciose ricche di vita', 8, '2026-01-14 18:03:38'),
(179, 2, '📍 Spot Migliori', 'Punta Carena - Reef naturale', 9, '2026-01-14 18:03:38'),
(180, 3, '⚓ Tecniche Base', 'Ormeggio alla banchina con due cavi', 1, '2026-01-14 18:04:24'),
(181, 3, '⚓ Tecniche Base', 'Retromarcia controllata e parallela', 2, '2026-01-14 18:04:24'),
(182, 3, '⚓ Tecniche Base', 'Protezione dei parabordi', 3, '2026-01-14 18:04:24'),
(183, 3, '⚓ Tecniche Base', 'Lettura dei venti e delle correnti', 4, '2026-01-14 18:04:24'),
(184, 3, '🛟 Sicurezza', 'Mantenere sempre contatto radio con la torre di controllo', 5, '2026-01-14 18:04:24'),
(185, 3, '🛟 Sicurezza', 'Verificare la profondità dell\'acqua con scandaglio', 6, '2026-01-14 18:04:24'),
(186, 3, '🛟 Sicurezza', 'Avere a disposizione ancora di riserva', 7, '2026-01-14 18:04:24'),
(187, 6, '🆘 Attrezzature Obbligatorie', 'Giubbotti salvagente per tutto l\'equipaggio', 1, '2026-01-14 18:05:11'),
(188, 6, '🆘 Attrezzature Obbligatorie', 'Zattera di salvataggio ispezionata', 2, '2026-01-14 18:05:11'),
(189, 6, '🆘 Attrezzature Obbligatorie', 'Estintori e kit primo soccorso', 3, '2026-01-14 18:05:11'),
(190, 6, '🆘 Attrezzature Obbligatorie', 'Fari e segnali luminosi notturni', 4, '2026-01-14 18:05:11'),
(191, 6, '📞 Numeri di Emergenza', 'Capitaneria di Porto: 1530', 5, '2026-01-14 18:05:11'),
(192, 6, '📞 Numeri di Emergenza', 'Guardia Costiera: VHF Canale 16', 6, '2026-01-14 18:05:11'),
(193, 6, '📞 Numeri di Emergenza', 'Emergenze Mediche: 118', 7, '2026-01-14 18:05:11');

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
(10, 'Corso Italia', '88', '84010', 'Praiano', 'SA', 'IT', '2025-12-23 12:11:10'),
(11, 'Via Sant\'Antonio', '19', '80010', 'Pozzuoli', 'NA', 'IT', '2025-12-23 12:11:10'),
(12, 'Viale Europa', '75', '80062', 'Meta di Sorrento', 'NA', 'IT', '2025-12-23 12:11:10'),
(13, 'Via Orientale', '120', '80078', 'Anacapri', 'NA', 'IT', '2025-12-23 12:11:10'),
(14, 'Piazza Municipio', '1', '80133', 'Napoli', 'NA', 'IT', '2025-12-23 12:11:10'),
(15, 'Via Marina Piccola', '5', '80071', 'Anacapri', 'NA', 'IT', '2025-12-23 12:11:10'),
(16, 'Via Romaaewrewr', '123', '43434', 'Napolì', 'NA', 'IT', '2025-12-26 17:17:32'),
(17, 'Via Roma', '123', '80100', 'Napoli', 'NA', 'IT', '2026-01-07 09:08:38'),
(18, 'Via Roma', '123', '80100', 'Napoli', 'NA', 'IT', '2026-01-07 11:39:31'),
(19, 'Via Roma', '123', '80100', 'Napoli', 'NA', 'IT', '2026-01-08 06:27:28'),
(20, 'Via Frasnelli', '6', '31040', 'Nervesa', 'TV', 'IT', '2026-01-09 16:33:45'),
(22, 'Via Frasnelli', '6', '31040', 'Nervesa della Battaglia', 'TV', 'IT', '2026-01-09 19:29:36'),
(23, 'Via Frasnelli', '6', '31040', 'Nervesa della Battaglia', 'TV', 'IT', '2026-01-09 19:29:52'),
(24, 'Via di utente', '6', '31040', 'Citta di Utente', 'UT', 'IT', '2026-01-09 19:30:02'),
(25, 'Via Frasnelli', '6', '31040', 'Nervesa della Battaglia', 'TV', 'IT', '2026-01-10 11:48:38'),
(28, 'Via Frasnelli', '6', '31040', 'Comune', 'DD', 'IT', '2026-01-11 14:25:32'),
(31, 'Via Roma', '43', '80100', 'Napoli', 'NA', 'IT', '2026-01-11 17:37:04'),
(33, 'Via Roma', '58', '80100', 'Napoli', 'NA', 'IT', '2026-01-11 17:38:16'),
(39, 'Via di Davide', '1', '80100', 'Citta di Davide', 'DA', 'IT', '2026-01-11 18:20:44'),
(40, 'Via Roma', '114', '80100', 'Napoli', 'NA', 'IT', '2026-01-11 18:20:46'),
(41, 'Via Roma', '189', '80100', 'Napoli', 'NA', 'IT', '2026-01-12 13:46:10'),
(42, 'Via di Alberto', '1', '80122', 'Citta di Alberto', 'AL', 'IT', '2026-01-13 17:02:43'),
(43, 'Via di Francesco', '1', '80122', 'Citta di Francesco', 'FR', 'IT', '2026-01-13 17:04:12');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Media`
--

INSERT INTO `Media` (`IDMedia`, `URL_Media`, `Testo_Alternativo`, `Tipo_Media`, `IDUtente`, `IDProdotto`, `IDArticolo`, `Data_Caricamento`) VALUES
(5, '../img/prodotti/gozzo-sorrentino-classico.webp', 'Gozzo tradizionale con sedute perimetrali in legno e ampio tendalino beige che copre il ponte.', 'Immagine', NULL, 'BARCA-GOZZO-001', NULL, '2025-12-23 12:11:10'),
(6, '../img/prodotti/gozzoblu.webp', 'Gozzo blu scuro con pavimentazione in legno, divanetti imbottiti e grande tendalino parasole coordinato.', 'Immagine', NULL, 'BARCA-GOZZO-002', NULL, '2025-12-23 12:11:10'),
(7, '../img/prodotti/gommone250.webp', 'Gommone con cuscineria azzurra, ampio prendisole di prua e postazione di guida con tendalino nero ripiegabile.', 'Immagine', NULL, 'BARCA-GOMMONE-001', NULL, '2025-12-23 12:11:10'),
(8, '../img/prodotti/gommonecomfort.webp', 'Gommone con sedute color cuoio, cabina interna sotto la console e tendalino montato su roll-bar in acciaio.', 'Immagine', NULL, 'BARCA-GOMMONE-002', NULL, '2025-12-23 12:11:10'),
(9, '../img/prodotti/gommonelux.webp', 'Gommone di lusso con pavimentazione in teak, sedili ergonomici avvolgenti e ampi spazi calpestabili.', 'Immagine', NULL, 'BARCA-GOMMONE-003', NULL, '2025-12-23 12:11:10'),
(10, '../img/prodotti/azimut55fly.webp', 'Yacht di lusso a tre livelli con interni multi-cabina e flybridge superiore dotato di divanetti panoramici.', 'Immagine', NULL, 'BARCA-YACHT-001', NULL, '2025-12-23 12:11:10'),
(11, '../img/prodotti/cranchiendu.webp', 'Motoscafo cabinato con cabina abitabile, enorme prendisole imbottito a poppa e parabrezza protettivo.', 'Immagine', NULL, 'BARCA-YACHT-002', NULL, '2025-12-23 12:11:10'),
(14, '../img/prodotti/aperta6m.webp', 'Barca open con console centrale, sedute a prua e poppa, e tendalino nero a copertura della zona di guida.', 'Immagine', NULL, 'BARCA-PICCOLA-001', NULL, '2025-12-23 12:11:10'),
(15, '../img/prodotti/aperta8m.webp', 'Barca open con area prendisole prodiera e postazione di guida protetta da un tettuccio rigido.', 'Immagine', NULL, 'BARCA-PICCOLA-002', NULL, '2025-12-23 12:11:10'),
(103, '../img/prodotti/odyssey-45.webp', 'Barca a vela cabinata con pozzetto esterno dotato di sedute contrapposte e tavolino centrale.', 'Immagine', NULL, 'BARCA-VELA-002', NULL, '2026-01-09 17:29:59'),
(166, '../img/avatars/user_12.webp?v=1768231273', '', 'Immagine', 12, NULL, NULL, '2026-01-12 15:21:14'),
(175, '../img/avatars/user_131.webp?v=1768324178', '', 'Immagine', 131, NULL, NULL, '2026-01-13 17:09:38'),
(179, '../img/avatars/user_9.webp?v=1768381353', '', 'Immagine', 9, NULL, NULL, '2026-01-14 09:02:33'),
(181, '../img/blog/flora-fauna-marinara.webp', '', 'Immagine', NULL, NULL, 10, '2026-01-14 17:50:06'),
(182, '../img/blog/sorrento-perla-costiera.webp', 'Scenografica veduta di un borgo costiero con case colorate arroccate sulla scogliera al tramonto.', 'Immagine', NULL, NULL, 12, '2026-01-14 17:52:03'),
(183, '../img/blog/fotografia-subacquea.webp', '', 'Immagine', NULL, NULL, 13, '2026-01-14 17:57:10'),
(184, '../img/blog/sostenibilita-marina.webp', '', 'Immagine', NULL, NULL, 14, '2026-01-14 17:58:46'),
(185, '../img/blog/capri-leggenda-realta.webp', '', 'Immagine', NULL, NULL, 15, '2026-01-14 17:59:48'),
(186, '../img/blog/ricette-cucina-marinara.webp', 'Primo piano di un piatto di spaghetti ai frutti di mare servito in barca.', 'Immagine', NULL, NULL, 9, '2026-01-14 18:01:15'),
(187, '../img/blog/cale-costiera-sorrentina.webp', 'Piccola spiaggia sabbiosa racchiusa in una cala tra alte scogliere rocciose.', 'Immagine', NULL, NULL, 1, '2026-01-14 18:02:25'),
(188, '../img/blog/snorkeling-capri.webp', '', 'Immagine', NULL, NULL, 2, '2026-01-14 18:03:38'),
(189, '../img/blog/ormeggio-sicurezza.webp', '', 'Immagine', NULL, NULL, 3, '2026-01-14 18:04:24'),
(190, '../img/blog/sicurezza-mare.webp', '', 'Immagine', NULL, NULL, 6, '2026-01-14 18:05:11'),
(191, '../img/avatars/user_132.webp?v=1768414006', '', 'Immagine', 132, NULL, NULL, '2026-01-14 18:06:46'),
(192, '../img/prodotti/costieraamalfitana.webp', 'Suggestivo borgo della Costiera Amalfitana con case colorate arroccate sulla scogliera e barche nel porto.', 'Immagine', NULL, 'EXP-ESCURSIONE-001', NULL, '2026-01-14 20:07:38'),
(193, '../img/prodotti/tramontonapoli.webp', 'Veduta panoramica del Golfo di Napoli al tramonto con il profilo della città illuminata.', 'Immagine', NULL, 'EXP-TRAMONTO-001', NULL, '2026-01-14 20:08:09'),
(194, '../img/prodotti/snork.webp', 'Acque cristalline all''interno di una grotta marina illuminata da riflessi azzurri naturali.', 'Immagine', NULL, 'EXP-SNORKEL-001', NULL, '2026-01-14 20:08:26'),
(197, '../img/prodotti/prod_PRD-86AB070D.webp?v=1768422395', 'Gruppo di persone che assaggia cibo di strada tipico servito in cartocci di carta nel centro storico.', 'Immagine', NULL, 'PRD-86AB070D', NULL, '2026-01-14 20:26:35'),
(206, '../img/prodotti/prod_BARCA-VELA-001.webp?v=1768155062', 'Barca a vela con ponte in teak antiscivolo libero da ostacoli e sedute incassate nel pozzetto.', 'Immagine', NULL, 'BARCA-VELA-001', NULL, '2026-01-23 16:25:55'),
(208, '../img/prodotti/prod_PRD-F86512E8.webp?v=1768421611', 'Pescatori al lavoro su una barca di legno mentre estraggono una rete colma di pesci freschi.', 'Immagine', NULL, 'PRD-F86512E8', NULL, '2026-01-23 16:31:02'),
(211, '../img/prodotti/prod_PRD-4417612E.webp?v=1768422553', 'Scorcio della stretta via di Spaccanapoli con folla di passanti, architetture storiche e panni stesi.', 'Immagine', NULL, 'PRD-4417612E', NULL, '2026-01-23 16:48:47');

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
(114, 12, 'EXP-TRAMONTO-001', '2026-01-22 09:00:00', '2026-01-22 13:00:00', 0, 95.00, 'Bonifico', 'Confermata', NULL, '2026-01-11 14:45:12'),
(115, 12, 'BARCA-PICCOLA-001', '2026-01-12 00:00:00', '2026-01-15 23:59:59', 1, 680.00, 'Contanti', 'Confermata', NULL, '2026-01-11 14:46:02'),
(117, 12, 'EXP-SNORKEL-001', '2026-01-15 09:00:00', '2026-01-15 14:00:00', 1, 177.50, 'Bonifico', 'Confermata', NULL, '2026-01-12 15:43:31'),
(118, 12, 'EXP-TRAMONTO-001', '2026-01-30 09:00:00', '2026-01-30 13:00:00', 0, 95.00, 'Bonifico', 'Cancellata', NULL, '2026-01-13 06:17:19'),
(119, 14, 'EXP-SNORKEL-001', '2026-01-14 09:00:00', '2026-01-14 14:00:00', 0, 75.00, 'Bonifico', 'In Attesa', NULL, '2026-01-13 18:41:08'),
(120, 14, 'EXP-SNORKEL-001', '2026-01-29 09:00:00', '2026-01-29 14:00:00', 0, 75.00, 'Carta di Credito', 'Confermata', NULL, '2026-01-25 14:08:46');

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
('BARCA-GOMMONE-001', 'Noleggio', 'Gommone', NULL, 'Gommone Speed 250cv', 'Adrenalina e velocità. Ideale per chi ama l\'avventura sul mare.', 'Un gommone potente e veloce, ideale per chi cerca adrenalina e avventura in mare. Perfetto per escursioni dinamiche e divertenti.', 450.00, 8, 0, 9.50, 1, '2025-12-23 12:11:10', '2026-01-22 10:00:09', 1),
('BARCA-GOMMONE-002', 'Noleggio', 'Gommone', NULL, 'Gommone Comfort 200cv', 'Gommone versatile con cabina confortevole.', 'Un gommone confortevole con cabina spaziosa, ideale per famiglie o gruppi che desiderano esplorare il mare con comodità.', 380.00, 10, 0, 8.80, 1, '2025-12-23 12:11:10', '2025-12-23 12:11:10', 1),
('BARCA-GOMMONE-003', 'Noleggio', 'Gommone', NULL, 'Gommone Luxury 300cv', 'Esclusività e potenza. Per chi non vuole compromessi.', 'Un gommone di lusso con prestazioni eccezionali e comfort esclusivo. Perfetto per chi cerca un’esperienza premium in mare.', 550.00, 12, 1, 10.50, 1, '2025-12-23 12:11:10', '2025-12-23 12:11:10', 1),
('BARCA-GOZZO-001', 'Noleggio', 'Motore', NULL, 'Gozzo Sorrentino Classico', 'Barca tradizionale perfetta per famiglie. Ideale per giornate di relax e snorkeling.', 'Un gozzo tradizionale sorrentino, perfetto per giornate di relax e snorkeling. Ideale per famiglie e piccoli gruppi.', 280.00, 6, 1, 8.50, 0, '2025-12-23 12:11:10', '2025-12-27 07:47:34', 1),
('BARCA-GOZZO-002', 'Noleggio', 'Motore', NULL, 'Gozzo Blu Marino', 'Comfort e tradizione con motore affidabile. Perfetto per gite lunghe.', 'Un gozzo elegante e affidabile, ideale per lunghe escursioni e giornate di esplorazione costiera.', 300.00, 8, 0, 9.20, 0, '2025-12-23 12:11:10', '2025-12-27 07:47:35', 1),
('BARCA-PICCOLA-001', 'Noleggio', 'Motore', NULL, 'Barca Aperta 6m', 'Perfetta per principianti e famiglie con bambini.', 'Una barca aperta compatta, perfetta per principianti e famiglie con bambini. Facile da manovrare e sicura.', 150.00, 4, 1, 6.00, 0, '2025-12-23 12:11:10', '2025-12-23 12:35:51', 1),
('BARCA-PICCOLA-002', 'Noleggio', 'Motore', NULL, 'Barca Aperta 7.5m', 'Ideale per gite mezzagiornata. Facile da manovrare.', 'Una barca aperta spaziosa, ideale per gite di mezza giornata. Offre stabilità e facilità di utilizzo.', 180.00, 5, 0, 7.50, 0, '2025-12-23 12:11:10', '2025-12-23 12:35:46', 1),
('BARCA-VELA-001', 'Noleggio', 'Vela', NULL, 'Beneteau First 350', 'Vela sportiva con prestazioni eccellenti. Per velisti esperti.', 'Una barca a vela sportiva con prestazioni eccellenti, perfetta per velisti esperti che cercano avventura e sfida.', 400.00, 6, 0, 7.00, 1, '2025-12-23 12:11:10', '2026-01-22 09:24:25', 1),
('BARCA-VELA-002', 'Noleggio', 'Vela', NULL, 'Jeanneau Sun Odyssey 45', 'Vela con comfort abitativo. Ideale per crociere tranquille.', 'Una barca a vela confortevole e spaziosa, ideale per crociere rilassanti e tranquille lungo la costa.', 550.00, 8, 0, 13.80, 1, '2025-12-23 12:11:10', '2026-01-13 06:17:08', 1),
('BARCA-YACHT-001', 'Noleggio', 'Motore', NULL, 'Azimut 55 Fly', 'Yacht di lusso con salotto interno, cucina e 4 cabine. Esperienza premium.', 'Uno yacht di lusso con interni eleganti, cucina attrezzata e cabine esclusive. Perfetto per un’esperienza premium in mare.', 1200.00, 12, 1, 16.80, 1, '2025-12-23 12:11:10', '2025-12-27 07:47:38', 1),
('BARCA-YACHT-002', 'Noleggio', 'Motore', NULL, 'Cranchi Endurance 41', 'Eleganza e navigabilità. Perfetto per crociere di una o più giornate.', 'Uno yacht elegante e versatile, ideale per crociere di una o più giornate con massimo comfort.', 950.00, 10, 0, 12.50, 1, '2025-12-23 12:11:10', '2026-01-12 15:15:40', 1),
('EXP-ESCURSIONE-001', 'Experience', 'Tour', 8, 'Tour Costiera Amalfitana Completo', 'Giornata intera tra Positano, Amalfi e Praiano. Include pranzo.', 'Immergiti in una giornata intera di pura bellezza con il nostro tour completo della Costiera Amalfitana, un\'esperienza di 8 ore pensata per farti scoprire senza stress le perle di Positano, Amalfi e Praiano. A bordo troverai tutto il necessario per il massimo relax, dalle bevande fresche illimitate all\'attrezzatura per lo snorkeling, fino a un delizioso pranzo leggero incluso nel prezzo, perfetto per ricaricarsi tra un tuffo e l\'altro. Guidati da un esperto locale, potrai ammirare la costa dalla prospettiva migliore, quella del mare, godendo di un servizio curato nei minimi dettagli per gruppi intimi di massimo 10 persone.', 120.00, 10, 1, NULL, 0, '2025-12-23 12:11:10', '2026-01-14 20:07:38', 1),
('EXP-SNORKEL-001', 'Experience', 'Escursione', 5, 'Escursione Snorkeling Capri e Anacapri', 'Mezza giornata alla scoperta dei fondali cristallini di Capri.', 'Se la tua passione è il mondo sommerso, questa escursione di 5 ore dedicata allo snorkeling tra Capri e Anacapri è l\'avventura ideale per esplorare fondali cristallini ricchi di vita. Non dovrai preoccuparti di nulla: forniamo noi l\'attrezzatura completa, asciugamani premium e persino snack e bevande fresche per ristorarti dopo le nuotate. Accompagnati da una guida specializzata pronta a svelarti i segreti della fauna marina, vivrai un\'esperienza esclusiva per piccoli gruppi, pensata per chi vuole un contatto autentico e diretto con la natura incontaminata dell\'isola azzurra.', 75.00, 10, 0, NULL, 0, '2025-12-23 12:11:10', '2026-01-14 20:08:26', 1),
('EXP-TRAMONTO-001', 'Experience', 'Aperitivo', 4, 'Cena al Tramonto con Prosecco', 'Tour romantico della costa con cena leggera e prosecco.', 'Per chi cerca un\'atmosfera intima e indimenticabile, la nostra cena al tramonto offre quattro ore di puro romanticismo, cullati dalle onde mentre il cielo si tinge di colori caldi. Pensata esclusivamente per le coppie, questa esperienza include una coperta elegante per stendersi in relax, stuzzichini gourmet e prosecco di qualità per brindare a un momento speciale. Con la discrezione della nostra guida e lo scenario mozzafiato della costa al crepuscolo, è l\'occasione perfetta per celebrare un anniversario o semplicemente per godersi la dolce vita in due, lontano dal caos della terraferma.', 95.00, 2, 1, NULL, 0, '2025-12-23 12:11:10', '2026-01-14 20:08:09', 1),
('PRD-4417612E', 'Experience', 'Escursione', 4, 'Il Cuore di Napoli: Spaccanapoli e il Mistero del Cristo Velato', 'Un tuffo nell\'anima antica della città, camminando lungo la celebre Spaccanapoli fino a scoprire l\'incredibile capolavoro del Cristo Velato.', 'Napoli è una città di contrasti violenti e bellissimi, e questo tour ne cattura l\'essenza. Partiremo per una passeggiata lungo \"Spaccanapoli\", l\'arteria che divide in due la città antica, un fiume in piena di vita, motorini, edicole votive e panni stesi. Attraverseremo la via di San Gregorio Armeno, famosa in tutto il mondo per le botteghe degli artigiani del presepe, dove il sacro e il profano si mescolano in modo unico. Il culmine dell\'esperienza sarà l\'ingresso alla Cappella Sansevero per ammirare il Cristo Velato: un\'opera scultorea così realistica e commovente che la leggenda narra sia stata creata attraverso un processo alchemico di marmorizzazione di un vero velo. È un tour che tocca le corde dell\'emozione, passando dal frastuono della strada al silenzio contemplativo dell\'arte pura, svelando i segreti che si nascondono sotto la superficie della città.', 300.00, 4, 1, NULL, 0, '2026-01-14 20:29:13', '2026-01-23 16:48:47', 1),
('PRD-86AB070D', 'Experience', 'Tour', 6, 'Napoli Street Food Tour: I Sapori Veraci dei Vicoli', 'Passeggiata golosa nel centro storico per assaggiare il vero street food napoletano, dalla pizza a portafoglio alla sfogliatella calda.', 'Dimentica la dieta e immergiti nel caos meraviglioso del centro storico di Napoli per un\'esperienza sensoriale che va oltre la semplice degustazione. Questo tour a piedi è un viaggio attraverso i secoli, dove ogni vicolo racconta una storia e ogni angolo profuma di qualcosa di irresistibile. Accompagnato da una guida locale che conosce ogni \"buco\" (le piccole botteghe tradizionali), assaggerai i pilastri della cucina di strada partenopea: la pizza a portafoglio piegata calda, la frittatina di pasta croccante fuori e morbida dentro, il tarallo \'nzogna e pepe e, per chiudere in dolcezza, la sfida eterna tra sfogliatella riccia e frolla. Non si tratta solo di mangiare, ma di capire come il cibo sia l\'anima stessa di questa città, vivendo l\'atmosfera autentica dei Quartieri Spagnoli e di Spaccanapoli, mangiando in piedi tra la gente del posto.', 200.00, 2, 1, NULL, 0, '2026-01-14 20:26:35', '2026-01-14 20:30:09', 1),
('PRD-F86512E8', 'Experience', 'Escursione', 6, 'Pescatore per un Giorno: Tradizione e Gusto', 'Un misto tra esperienza e pranzo speciale', 'Vivi il mare come un vero locale. Sali a bordo di un peschereccio tradizionale riadattato e impara i segreti della pesca costiera dai nostri \"maestri d\'ascia\". Non è solo un tour, ma una lezione di vita marinara: tireremo su le reti insieme e prepareremo il pranzo a bordo proprio con il pescato del giorno, cucinato secondo le ricette antiche dei pescatori sorrentini. Autenticità garantita al 100%.', 800.00, 8, 1, NULL, 0, '2026-01-14 20:13:31', '2026-01-23 16:30:51', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `Prodotto_Extra`
--

CREATE TABLE `Prodotto_Extra` (
  `IDExtra` int(11) NOT NULL,
  `IDProdotto` varchar(50) NOT NULL,
  `Nome_Extra` varchar(255) NOT NULL,
  `Prezzo_Extra` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Prodotto_Extra`
--

INSERT INTO `Prodotto_Extra` (`IDExtra`, `IDProdotto`, `Nome_Extra`, `Prezzo_Extra`) VALUES
(1, 'BARCA-GOZZO-001', 'Skipper Professionista', 100.00),
(2, 'BARCA-GOZZO-001', 'Attrezzatura Snorkeling Completa', 45.00),
(3, 'BARCA-GOZZO-001', 'Assicurazione Danni Aggiuntiva', 60.00),
(4, 'BARCA-GOZZO-002', 'Skipper Professionista', 120.00),
(5, 'BARCA-GOZZO-002', 'Cuoco a Bordo', 150.00),
(6, 'BARCA-GOZZO-002', 'Attrezzatura Snorkeling Completa', 60.00),
(7, 'BARCA-GOMMONE-001', 'Skipper Professionista', 130.00),
(8, 'BARCA-GOMMONE-001', 'Ciambella Trainabile', 50.00),
(9, 'BARCA-GOMMONE-001', 'GoPro Subacquea Noleggio', 25.00),
(10, 'BARCA-GOMMONE-002', 'Skipper Professionista', 140.00),
(11, 'BARCA-GOMMONE-002', 'Ciambella Trainabile', 50.00),
(12, 'BARCA-GOMMONE-002', 'Attrezzatura Snorkeling', 70.00),
(13, 'BARCA-GOMMONE-003', 'Skipper Professionista Luxury', 200.00),
(14, 'BARCA-GOMMONE-003', 'Champagne Dom Pérignon', 120.00),
(15, 'BARCA-GOMMONE-003', 'Ciambella Trainabile Gold', 80.00),
(16, 'BARCA-YACHT-001', 'Skipper Capitano', 250.00),
(17, 'BARCA-YACHT-001', 'Hostess Bordo', 200.00),
(18, 'BARCA-YACHT-001', 'Cena Gourmet Privata', 400.00),
(19, 'BARCA-YACHT-001', 'Drone Riprese Aeree', 300.00),
(20, 'BARCA-YACHT-002', 'Skipper Capitano', 200.00),
(21, 'BARCA-YACHT-002', 'Hostess Bordo', 180.00),
(22, 'BARCA-YACHT-002', 'Attrezzatura Snorkeling Luxury', 100.00),
(28, 'BARCA-PICCOLA-001', 'Giubbotto Salvagente Bimbi', 20.00),
(29, 'BARCA-PICCOLA-002', 'Giubbotto Salvagente Bimbi', 25.00),
(30, 'BARCA-PICCOLA-002', 'Attrezzatura Snorkeling', 40.00),
(42, 'BARCA-VELA-002', 'Attrezzatura Snorkeling Luxury', 80.00),
(43, 'BARCA-VELA-002', 'Cuoco a Bordo', 250.00),
(44, 'BARCA-VELA-002', 'Skipper Professionista Vela', 150.00),
(137, 'EXP-ESCURSIONE-001', 'Cena Sotto le Stelle Aggiunta', 150.00),
(138, 'EXP-ESCURSIONE-001', 'Fotografia Professionale Costiera', 100.00),
(139, 'EXP-TRAMONTO-001', 'Bottiglia Champagne Premium', 80.00),
(140, 'EXP-TRAMONTO-001', 'Rose per la Sorpresa', 35.00),
(141, 'EXP-SNORKEL-001', 'Fotografia Subacquea Professionale', 65.00),
(142, 'EXP-SNORKEL-001', 'Snack Gourmet Aggiuntivo', 30.00),
(145, 'PRD-86AB070D', 'Birra Artigianale', 20.00),
(146, 'PRD-86AB070D', 'Box Dolci da Portare a casa', 20.00),
(147, 'PRD-86AB070D', 'Limoncello Artigianale', 20.00),
(158, 'BARCA-VELA-001', 'Attrezzatura Snorkeling', 60.00),
(159, 'BARCA-VELA-001', 'Skipper Professionista', 140.00),
(161, 'PRD-F86512E8', 'Servizio Fotografico', 80.00),
(164, 'PRD-4417612E', 'Napoli Sotterranea', 60.00),
(165, 'PRD-4417612E', 'Pranzo In trattoria Tipica', 130.00);

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
(49, 'BARCA-PICCOLA-001', 'Assicurazione Responsabilità Civile'),
(50, 'BARCA-PICCOLA-001', 'Giubbotti Salvagente'),
(51, 'BARCA-PICCOLA-001', 'Mappa Cartacea'),
(52, 'BARCA-PICCOLA-002', 'Assicurazione Responsabilità Civile'),
(53, 'BARCA-PICCOLA-002', 'Giubbotti Salvagente'),
(54, 'BARCA-PICCOLA-002', 'Mappa Digitale'),
(55, 'BARCA-PICCOLA-002', 'Ombrellone da Sole'),
(137, 'BARCA-VELA-002', '2 Cabine'),
(138, 'BARCA-VELA-002', 'Assicurazione Responsabilità Civile'),
(139, 'BARCA-VELA-002', 'Attrezzatura Vela Completa'),
(140, 'BARCA-VELA-002', 'Cucina'),
(141, 'BARCA-VELA-002', 'Giubbotti Salvagente'),
(142, 'BARCA-VELA-002', 'Sistema Autopilota'),
(304, 'EXP-ESCURSIONE-001', 'Attrezzatura Snorkeling'),
(305, 'EXP-ESCURSIONE-001', 'Bevande Fresche Illimitate'),
(306, 'EXP-ESCURSIONE-001', 'Giubbotti Salvagente'),
(307, 'EXP-ESCURSIONE-001', 'Guida Turistica Esperta'),
(308, 'EXP-ESCURSIONE-001', 'Ombrellone da Sole'),
(309, 'EXP-ESCURSIONE-001', 'Pranzo Leggero'),
(310, 'EXP-TRAMONTO-001', 'Coperta Elegante'),
(311, 'EXP-TRAMONTO-001', 'Giubbotti Salvagente'),
(312, 'EXP-TRAMONTO-001', 'Guida Turistica Italiana'),
(313, 'EXP-TRAMONTO-001', 'Prosecco di Qualità'),
(314, 'EXP-TRAMONTO-001', 'Stuzzichini Gourmet'),
(315, 'EXP-SNORKEL-001', 'Asciugamani Premium'),
(316, 'EXP-SNORKEL-001', 'Attrezzatura Snorkeling Completa'),
(317, 'EXP-SNORKEL-001', 'Giubbotti Salvagente'),
(318, 'EXP-SNORKEL-001', 'Guida Turistica Specializzata'),
(319, 'EXP-SNORKEL-001', 'Snack e Bevande Fresche'),
(332, 'PRD-86AB070D', 'Guida Local \"Foodie\"'),
(333, 'PRD-86AB070D', 'Pizza a portafoglio'),
(334, 'PRD-86AB070D', 'Frittatina di pasta o Crocchè'),
(335, 'PRD-86AB070D', 'Tarallo \'nzogna e pepe'),
(336, 'PRD-86AB070D', 'Sfogliatella (Riccia o Frolla)'),
(337, 'PRD-86AB070D', 'Caffè espresso napoletano'),
(338, 'PRD-86AB070D', 'Acqua'),
(385, 'BARCA-VELA-001', 'Assicurazione Responsabilità Civile'),
(386, 'BARCA-VELA-001', 'Attrezzatura Vela Base'),
(387, 'BARCA-VELA-001', 'Giubbotti Salvagente'),
(388, 'BARCA-VELA-001', 'Mappa Nautica'),
(395, 'PRD-F86512E8', 'Attrezzatura da pesca completa'),
(396, 'PRD-F86512E8', 'Esche'),
(397, 'PRD-F86512E8', 'Guida locale (pescatore esperto)'),
(398, 'PRD-F86512E8', 'Lezione di nodi marinari'),
(399, 'PRD-F86512E8', 'Pranzo completo a base di pescato fresco'),
(400, 'PRD-F86512E8', 'Vino locale e acqua'),
(407, 'PRD-4417612E', 'Auricolari per ascoltare la guida'),
(408, 'PRD-4417612E', 'Biglietto d\'ingresso garantito'),
(409, 'PRD-4417612E', 'Cappella Sansevero (Cristo Velato)'),
(410, 'PRD-4417612E', 'Guida Turistica Certificata'),
(411, 'PRD-4417612E', 'Passeggiata guidata San Gregorio Armeno'),
(412, 'PRD-4417612E', 'Sosta Caffè al Bar Nilo (Altare di Maradona)');

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
('BARCA-YACHT-001', 1),
('BARCA-YACHT-002', 1),
('EXP-ESCURSIONE-001', 1),
('EXP-TRAMONTO-001', 1),
('PRD-4417612E', 1),
('PRD-86AB070D', 1),
('PRD-F86512E8', 1),
('BARCA-GOMMONE-001', 2),
('BARCA-GOMMONE-002', 2),
('BARCA-GOMMONE-003', 2),
('BARCA-GOZZO-001', 2),
('BARCA-GOZZO-002', 2),
('BARCA-PICCOLA-001', 2),
('BARCA-PICCOLA-002', 2),
('BARCA-YACHT-001', 2),
('BARCA-YACHT-002', 2),
('EXP-ESCURSIONE-001', 2),
('EXP-TRAMONTO-001', 2),
('PRD-86AB070D', 2),
('EXP-ESCURSIONE-001', 3),
('EXP-SNORKEL-001', 3),
('EXP-TRAMONTO-001', 3),
('EXP-ESCURSIONE-001', 4),
('EXP-TRAMONTO-001', 4),
('PRD-86AB070D', 4);

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
  `IDIndirizzo` int(11) NOT NULL,
  `Is_Admin` tinyint(1) NOT NULL DEFAULT 0,
  `Data_Registrazione` timestamp NULL DEFAULT current_timestamp(),
  `Data_Ultimo_Accesso` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Utente`
--

INSERT INTO `Utente` (`IDUtente`, `Nome`, `Cognome`, `CF`, `Email`, `PasswordHash`, `IDIndirizzo`, `Is_Admin`, `Data_Registrazione`, `Data_Ultimo_Accesso`) VALUES
(9, 'Davide', 'Biasuzzi', 'BSZDVD04D19F443F', 'biasuzzi.davide@gmail.com', '$2y$12$U0SQeTC7WZqgChD6SxVteeIdrVBqf4Q4XPHTnaAacjmEjoRKAUSGG', 16, 1, '2025-12-26 17:17:32', '2026-01-31 20:05:18'),
(12, 'Admin', 'Admin', 'CFADMINADMINCFCF', 'admin', '$2y$12$8zAg6nl3GiiNgQUZ6dvvp.7z78MciHKCpwqW7b0TI.c.ZlzlWh/7i', 19, 1, '2026-01-08 06:27:28', '2026-01-30 18:10:54'),
(14, 'User', 'User', 'CFUSERUSERCFUSER', 'user', '$2y$12$nE6ZkeAcGm4zSud34GSWSOLnkbTF8vAy1PmpGQYq2BRBPi.67FxFy', 24, 0, '2026-01-09 19:30:02', '2026-01-25 14:05:22'),
(131, 'Davide', 'Biasuzzi', 'DDDDDDDDDDDDDDDD', 'davidebiasuzzi@mail.com', '$2y$12$tje07cGBBancFO7PSnfS7OtdH2b8ZMelitdB88NcwuQci0ByVPaLO', 39, 0, '2026-01-11 18:20:44', '2026-01-13 17:08:46'),
(132, 'Alberto', 'Reginato', 'AAAAAAAAAAAAAAAA', 'albertoreginato@mail.com', '$2y$12$1l1aMeS28ne76ypCBK/eHuQN9u9/V0qjRW6YJmAvRTzM1JH9Ad/IK', 42, 0, '2026-01-13 17:02:43', '2026-01-14 18:05:24'),
(133, 'Francesco', 'Marcon', 'FFFFFFFFFFFFFFFF', 'francescomarcon@mail.com', '$2y$12$1CrdOMoHgmF9BiMQgBv9DuX4VNA5S5ELuzqDUTvg28FETi3R2Gxoi', 43, 0, '2026-01-13 17:04:12', '2026-01-13 17:04:21');

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
  ADD KEY `idx_email` (`Email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `Articolo_Blog`
--
ALTER TABLE `Articolo_Blog`
  MODIFY `IDArticolo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT per la tabella `Articolo_Blog_Extra`
--
ALTER TABLE `Articolo_Blog_Extra`
  MODIFY `IDExtra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT per la tabella `Indirizzo`
--
ALTER TABLE `Indirizzo`
  MODIFY `IDIndirizzo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT per la tabella `Lingua`
--
ALTER TABLE `Lingua`
  MODIFY `IDLingua` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `Media`
--
ALTER TABLE `Media`
  MODIFY `IDMedia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT per la tabella `Prenotazione`
--
ALTER TABLE `Prenotazione`
  MODIFY `IDPrenotazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT per la tabella `Prodotto_Extra`
--
ALTER TABLE `Prodotto_Extra`
  MODIFY `IDExtra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT per la tabella `Prodotto_Incluso`
--
ALTER TABLE `Prodotto_Incluso`
  MODIFY `IDIncluso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=413;

--
-- AUTO_INCREMENT per la tabella `Utente`
--
ALTER TABLE `Utente`
  MODIFY `IDUtente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

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
  ADD CONSTRAINT `Prenotazione_ibfk_2` FOREIGN KEY (`IDProdotto`) REFERENCES `Prodotto` (`IDProdotto`) ON DELETE CASCADE;

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
