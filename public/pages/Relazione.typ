#set page(
  paper: "a4",
  margin: (x: 2.5cm, y: 2.5cm),
  numbering: "1",
)

#show heading.where(level: 1): set text(size: 24pt, weight: "bold")

#show heading.where(level: 2): set text(size: 22pt, weight: "bold")

#show heading.where(level: 3): set text(size: 18pt, weight: "bold")

#show heading.where(level: 4): set text(size: 14pt, weight: "bold")


#set text(
  size: 12pt,
  lang: "it"
)

#set par(
  justify: true,
  leading: 0.65em,
)


#set heading(numbering: "1.1")

// Personalizzazione link
#show link: it => text(fill: rgb("#0033ff"), it)



// Personalizzazione codice inline (per i tag HTML)
#show raw.where(block: false): box.with(
  fill: luma(240),
  inset: (x: 3pt, y: 0pt),
  outset: (y: 3pt),
  radius: 2pt,
)

// --- Frontespizio ---
#align(center)[
  #v(2cm)
  #text(size: 24pt, weight: "bold")[Relazione del Progetto: SailUP]
  
  #v(1em)
  #text(size: 16pt)[Corso di Tecnologie Web, A.A. 2025-2026]

  #v(2em)
  
  #align(center, image("../img/logo.svg", width: 25%))

  #v(2em)
  
  #block(width: 80%, stroke: 0.5pt + gray, inset: 1em, radius: 5pt)[
    #align(left)[
      *Componenti del gruppo:*
      #v(0.5em)
      #table(
        columns: (1fr, auto),
        stroke: none,
        [Davide Biasuzzi], [2111000],
        [Hossam Ezzemouri], [2079250],
        [Francesco Marcon], [2101070],
        [Alberto Reginato], [2110450],
      )
    ]
  ]

  #v(1cm)
  
  #align(left)[
    *Referente del gruppo:* \
    #link("mailto:x@studenti.unipd.it")
    
    #v(0.5em)
    *Indirizzo del sito:* \
    #link("http://tecweb.studenti.math.unipd.it/[username]/")[tecweb.studenti.math.unipd.it/[username]/]
    
    #v(0.5em)
    *Informazioni di accesso:* \
    - *Amministratore:* username `admin`, password `admin`
    - *Utente Semplice:* username `user`, password `user`
  ]
]

#pagebreak()

#outline(
  title: "Indice",
  indent: auto,
)
#pagebreak()

= Abstract

SailUP è la piattaforma web dedicata al noleggio di imbarcazioni e alla prenotazione di esperienze nautiche nel suggestivo scenario del Golfo di Napoli.
Uno dei maggiori problemi riguardanti la gestione tradizionale delle richieste di noleggio e dei tour turistici è la frammentazione dei servizi tra canali telefonici e fisici, che rischia di generare inefficienze e sovrapposizioni nelle disponibilità. Il progetto nasce proprio dalla volontà di offrire un supporto tecnologico capace di ottimizzare i processi gestionali interni e, contemporaneamente, garantire agli utenti finali un servizio immediato, intuitivo, trasparente e autonomo.

Il sito web desiderato è dunque focalizzato sull'implementazione delle funzionalità di *Noleggio* e *Esperienze*, guidando l'utente dalla consultazione del catalogo fino alla prenotazione. SailUP sfrutta la piattaforma anche come una vetrina virtuale attraverso una sezione *Blog* dedicata, attraverso cui mira a fornire contenuti informativi di valore ai clienti e a supportare l'attività di content marketing per attrarre nuovi utenti, siano essi turisti o residenti locali.

Attraverso la registrazione la piattaforma consente l'accesso ad un'*area personale* per la gestione del profilo e il monitoraggio dello storico prenotazioni. Parallelamente, un pannello di *controllo amministrativo* permette la gestione completa dei contenuti dinamici del sito.

SailUP è stato progettato e sviluppato con l'intenzione di essere utilizzato come un prodotto reale, ponendo come priorità la versione mobile ("mobile-first"), data la natura turistica del servizio, e cercando di ottimizzare l'usabilità, l'accessibilità e la coerenza grafica con l'identità marittima del brand.




= Analisi

== Utenza Target
SailUP si pone come punto di riferimento per il turismo nautico nel Golfo di Napoli. L'utenza prevista è eterogenea, spaziando dal turista internazionale al residente locale in cerca di svago.
Gli utenti si suddividono principalmente in due categorie:

- *Clienti Abituali / Utenti Registrati:*
  Sono coloro che hanno già usufruito dei servizi SailUP o pianificano di farlo regolarmente. La loro priorità è l'efficienza: desiderano accedere rapidamente al sistema, gestire le prenotazioni attive e consultare lo storico.
- *Nuovi Clienti / Turisti:*
  Non conoscono il sito o il servizio. È necessario in primo luogo ottimizzare il ranking del sito nelle ricerche, in secondo luogo è altrettanto importante garantire la loro permanenza nel sito, una volta entrati, tramite una grafica accattivante e una struttira intuitiva, che possa accompagnarli fluidamente verso la prenotazione di un'imbarcazione o di un'esperienza.

== Devices
Considerando la natura turistica del servizio e l'utilizzo in mobilità, ci si aspetta una netta prevalenza di accessi da smartphone. Per questo motivo, il design e l'interfaccia utente sono stati progettati seguendo rigorosamente l'approccio *mobile-first*.

== Funzionalità
Sono state individuate e realizzate le seguenti funzionalità principali:

- *Catalogo noleggio, esperienze e Blog:*
  - La Home e le pagine di catalogo devono presentare le imbarcazioni e le esperienze con immagini accattivanti e dettagli tecnici chiari.
  - È prevista una sezione Blog per il content marketing, utile ad attrarre traffico organico e fornire consigli utili agli utenti (es. itinerari, guide).
  - La grafica deve essere coerente con il tema nautico e garantire la massima leggibilità ed intuitività.

- *Prenotazione:*
  - La prenotazione è caratterizzata dalla scelta di una data, del numero di ospiti e di eventuali servizi accessori (es. skipper).
  - Il sistema deve verificare la disponibilità della risorsa (barca o esperienza) per la data richiesta tramite controlli server-side.
  - Le prenotazioni possono essere effettuate da utenti registrati; gli ospiti vengono invitati a registrarsi o accedere per finalizzare la prenotazione.
  - In seguito alla richiesta, il sistema fornisce un feedback immediato sull'esito (conferma o errore per indisponibilità).

- *Area Personale e Amministrazione:*
  - Sezione *Cliente* per la gestione del profilo e visualizzazione dello storico prenotazioni.
  - Sezione *Admin* per il controllo completo della piattaforma. L'amministratore necessita di una visione globale su utenti iscritti e prenotazioni effettuate e un sistema CRUD (Create, Read, Update, Delete) per gestire dinamicamente i prodotti, quindi barche, esperienze ed articoli del blog.

- *Ospite:* 
  - L' utente non autenticato potrà visualizzare i cataloghi di noleggio barche ed esperienze, leggere gli articoli pubblicati sul blog, accedere alle pagine informative statiche (Chi Siamo, FAQ, Privacy/Cookie Policy) e registrarsi al sito.

- *Registrazione:* 
  - Si prevede un form di compilazione dati per consentire ai nuovi utenti di creare un account, requisito necessario per effettuare una prenotazione e per gestirla.

== Ricerche da soddisfare
Al fine di migliorare la SEO ed intercettare il target di riferimento, il sito web è strutturato per soddisfare le seguenti intenzioni di ricerca:
- Noleggio barche Golfo di Napoli
- Escursioni in barca Capri e Positano
- Esperienze nautiche Napoli
- Affitto gommone Ischia
- Blog consigli nautica
- Tour in barca con skipper


= Progettazione

== Linee Guida
Per la gestione del ciclo di vita del software e il coordinamento del team, si è scelto di utilizzare un repository su GitHub per il versionamento del codice.

L'eterogeneità della clientela (turisti e residenti) ci ha spinti a progettare un design minimale e pulito. Si è scelto di puntare su un branding coerente con l'identità marittima: seppur il bianco sia predominante in modo da garantire leggibilità e chiarezza delle informazioni, è stata adottata una palette cromatica basata su diverse tonalità di blu per richiamare il tema nautico e, sfruttando la psicologia dei colori, trasmettere eleganza, calma, sicurezza e freschezza.

È stata mantenuta una rigida separazione tra struttura (HTML), presentazione (CSS) e comportamento (PHP e JavaScript), per garantire modularità e rispetto degli standard web.

Infine, la progettazione del sito è stata condotta cercando di garantire l'accessibilità a tutte le categorie di utenti.

== Struttura
La struttura del sito segue il modello gerarchico schematizzato in #link(<fig-sitemap>)[Figura 1]. In questa fase si è pianificata una suddivisione nelle seguenti pagine principali, accessibili tramite un menù di navigazione globale:

- *Home:*
  La pagina Home funge da punto di snodo principale. Deve contenere informazioni essenziali e presentative di SailUP, utilizzando immagini di impatto per catturare l'attenzione del visitatore e offrire collegamenti rapidi alle funzionalità principali, quindi le sezioni Noleggio ed Esperienze.

- *Cataloghi Noleggio ed Esperienze:*
  Queste pagine permettono all'utente di visualizzare l'offerta completa. Devono prevedere sistemi di filtraggio (per data, prezzo, tipologia) per agevolare la ricerca. Selezionando un elemento, l'utente accede a una pagina di dettaglio dove può consultare le specifiche e procedere alla prenotazione. Il sistema effettuerà un controllo sulla disponibilità delle date scelte restituendo un feedback all'utente.

- *Blog:*
  La pagina Blog raccoglie articoli informativi e consigli turistici. Ogni articolo è visualizzabile singolarmente. Questa sezione non offre interattività transazionale ma è fondamentale per l'attrattiva del sito.

- *Pagine Informative 'Chi Siamo', 'FAQ', 'Privacy' e 'Cookie':*
  Queste pagine offrono supporto all'utente, presentando il team di SailUP, rispondendo ai dubbi più comuni per ridurre il carico di assistenza diretta e illustrando in modo trasparente le politiche di privacy e gestione dei cookie adottate dalla piattaforma.

- *Area Riservata:*
  Questa sezione gestisce l'accesso alla piattaforma. La pagina di *Login/Registrazione* permette all'utente di autenticarsi o creare un nuovo profilo. Una volta loggato, il sistema indirizza l'utente alla vista corretta in base al suo ruolo:

  - *Pagina Profilo Cliente:*
    Offre al cliente la possibilità di visualizzare e modificare i propri dati anagrafici. Include una sezione per consultare lo storico delle prenotazioni (attive e passate), permettendo all'utente di avere riscontro immediato sulle proprie attività.

  - *Dashboard Amministratore:*
    Questa sezione, accessibile solo agli utenti con privilegi elevati, funge da centro di controllo. Permette di visualizzare la totalità delle prenotazioni nel sistema, gestire l'anagrafica degli utenti registrati e modificare dinamicamente i contenuti del sito (aggiunta/modifica/rimozione di Barche, Esperienze e Articoli del Blog).

  #figure(
    image("../img/diagramma_albero.png", width: 100%), 
    gap: 2em,
    caption: [Mappa gerarchica della piattaforma SailUP],
  ) <fig-sitemap>

= Realizzazione
In questa sezione vengono descritte le soluzioni implementative adottate per costruire la piattaforma SailUP. Vengono di seguito analizzati gli aspetti legati al *frontend* ed al *backend*.

== Front-End

=== Struttura (HTML)
La costruzione delle pagine web sfrutta il markup di HTML5, garantendo una chiara gerarchia delle informazioni. L'ossatura di ogni documento sfrutta i tag standard `<header>`, `<nav>`, `<main>` e `<footer>`, che permettono agli utenti di _screen reader_ di orientarsi rapidamente all'interno della pagina.

- *Struttura generale:* La navigazione principale è contenuta nell'`<header>` e si adatta ai dispositivi mobili trasformandosi in un menu a scomparsa gestito tramite un pulsante ad 'hamburger', il cui stato è comunicato alle tecnologie assistive tramite l'attributo `aria-expanded`. \ Per facilitare l'esperienza d'uso via tastiera è stato inserito all'inizio del `<body>` il collegamento nascosto #underline[_Skip Link_], che consente di saltare i menù ripetitivi andando direttamente al contenuto principale della pagina. \ Il corpo centrale della pagina è racchiuso nel tag `<main>`, al cui interno i contenuti sono organizzati logicamente: le schede del singolo prodotto nei cataloghi sono marcate con il tag `<article>`, identificandole come entità indipendenti, mentre le sezioni accessorie, come i filtri di ricerca e i riepiloghi d'ordine, sono delimitate dal tag `<aside>`.
- *Breadcrumbs:* L'orientamento all'interno delle pagine è agevolato dalle breadcrumbs, presenti in tutte le pagine ad eccezione di quella d'errore.
- *Attributi:* Particolare attenzione è stata inoltre posta nel definire attributi adeguati per il contenuto e gli elementi funzionali: ai termini in lingua inglese è stato associato l'attributo `lang="en"` (es. _Privacy Policy_, _Login_), alle sigle ed acronimi (es. CAP, NA, S.r.l., FAQ) l'attributo `title` all'interno del tag `<abbr>` per esplicitarne il significato e alle date l'attributo datetime nel tag `<time>` per renderle _machine-readable_ (e quindi interpretabili da motori di ricerca e _screen reader_). 
- *Ottimizzazioni:* Sono stati infine impiegati attributi specifici per migliorare l'esperienza utente, come `autocomplete` e `pattern` per facilitare la compilazione dei form e `loading="lazy"` per ottimizzare il caricamento delle immagini.

=== Presentazione (CSS)
La parte grafica è gestita interamente tramite fogli di stile CSS, mantenendo una netta separazione tra struttura e presentazione. Il sistema è stato reso modulare attraverso l'uso di file specifici: `style.css` per il desktop, `mobile.css` per i dispositivi portatili e `print.css` per la stampa.

==== Style.css (Desktop e Base)
Questo foglio di stile definisce l'identità visiva principale del sito. Le scelte stilistiche includono:
- *Responsive design*: L'interfaccia adotta un approccio fluido che si adatta alle diverse risoluzioni dello schermo. Per il posizionamento degli elementi sono state impiegate le tecnologie *Flexbox* per header e footer e *CSS Grid* per le griglie dei prodotti e le specifiche tecniche.
- *Variabili*: L'uso di variabili CSS definite in `:root` ha permesso di centralizzare la gestione del tema. Questo facilita la manutenzione e abilita il supporto alla *Dark Mode* semplicemente modificando i valori delle variabili colore per la modalità scura.
- *Grid e flexbox*: L'impaginazione sfrutta CSS Grid per le strutture bidimensionali (come le card dei prodotti) e Flexbox per gli allineamenti monodimensionali (header e navbar).
- *Accessibilità visiva*: I colori scelti rispettano i criteri di contrasto WCAG AA. Inoltre, è stato definito un feedback visivo chiaro per gli stati di interazione (`:hover`, `:focus`), migliorando l'usabilità per chi naviga da tastiera.

==== Mobile.css (Dispositivi Portatili)
Richiamato tramite media query per dispositivi con larghezza inferiore a 768px, questo foglio di stile ottimizza l'esperienza utente su schermi ridotti:
- *Navigazione semplificata*: Il menu di navigazione orizzontale viene nascosto e viene introdotto un menù "Hamburger" espandibile, massimizzando lo spazio disponibile per i contenuti.
- *Linearizzazione del layout*: Le griglie multi-colonna, come le card per i prodotti, vengono riconfigurate in un layout a colonna singola per facilitare la lettura e lo scorrimento verticale.
- *Tabelle responsive*: Per risolvere il problema della leggibilità delle tabelle su schermi stretti, le righe vengono trasformate visivamente in "card". L'intestazione della colonna viene inserita direttamente nella cella, permettendo all'utente di leggere il dato contestualizzato senza dover scorrere orizzontalmente o zoomare.
- *Aree interattive*: Le dimensioni dei pulsanti e delle aree interattive sono aumentate per facilitare l'interazione tramite tocco.

==== Print.css (Stampa)
Per assicurare che i contenuti siano fruibili in maniera ottimale su carta è stato predisposto un foglio di stile dedicato, che modifica la struttura di una pagina come segue:
- *Rimozione degli elementi sperflui*: Gli elementi interattivi inutili su carta (menu, breadcrumb, pulsanti "prenota", hero images) vengono nascosti tramite la classe `.print-none`, lasciando solamente il contenuto informativo essenziale come ad esempio indirizzo e P.IVA nel footer.
- *Ottimizzazioni per la lettura*: Il font viene cambiato globalmente in _Times New Roman_ (serif), più leggibile su supporto cartaceo rispetto ai font sans-serif usati a video. I colori vengono forzati al nero su bianco e i link perdono la sottolineatura per una pulizia visiva maggiore.
- *Layout adattivo*: La struttura a colonne viene linearizzata, permettendo al contenuto principale di occupare l'intera larghezza del foglio stampato, evitando tagli laterali.
- *Gestione griglie*: Le sezioni a griglia vengono mantenute ma adattate con l'aggiunta di bordi per delimitare le aree, sostituendo la distinzione cromatica che viene persa in stampa.
- *Visualizzazione link esterni:* Sebbene attualmente disabilitata in assenza di collegamenti esterni, è stata predisposta una regola per stampare in chiaro l'URL di destinazione accanto ai link. Questo accorgimento permette a chi consulta la versione cartacea di conoscere l'indirizzo delle risorse citate, altrimenti irrecuperabile su carta.

=== Convenzioni interne
Oltre agli standard web generali, il progetto adotta specifiche convenzioni stilistiche e funzionali per garantire un'esperienza utente coerente e prevedibile:

- *Link*: i collegamenti ipertestuali sono distinguibili dal testo grazie alla sottolineatura presente. I link visitati, poi, assumono una colorazione azzurra, in linea con l'identità del brand.
- *Pagina corrente e link circolari*: Nei menu di navigazione la voce corrispondente alla pagina attuale è evidenziata visivamente con una sottolineatura blu e resa non cliccabile. In generale tutti i link che normalemte riporterebbero alla pagina corrente, i link circolari, vengono resi non cliccabili evitando ricaricamenti inutili e aiutando l'orientamento dell'utente.
- *Convenzione font*: _Montserrat_ è riservato esclusivamente alle intestazioni (h1-h6) e ai bottoni per impatto visivo, mentre _Open Sans_ è utilizzato per tutto il corpo del testo.
- *Badge*: Nelle tabelle di riepilogo (es. prenotazioni), lo stato viene esplicitato da etichette colorate in base allo stato dell'elemento.
- *Divisione contenuti*: I contenuti indipendenti, come i prodotti nei cataloghi, sono sempre incapsulati in card con bordi arrotondati per facilitarne la distinzione.
- *Feedback nei Form*: I messaggi di aiuto sono sempre posizionati sotto il campo input in colore grigio, mentre i messaggi di errore appaiono in rosso.

=== Comportamento (JavaScript)
Le funzionalità interattive lato client sono gestite da script modulari che arricchiscono l'esperienza utente secondo il principio del *Progressive Enhancement*, garantendo funzionalità di base anche in assenza di JavaScript.

Il file `script.js` orchestra le seguenti funzioni:
- *Menu mobile*: Gestisce l'apertura e chiusura del menu "hamburger", alternando le icone di stato (aperto/chiuso) e sincronizzando l'attributo ARIA `aria-expanded` per garantire la corretta comunicazione dello stato alle tecnologie assistive.
- *Modalità scura*: Controlla il cambio del tema visivo (chiaro/scuro) agendo sull'attributo `data-theme` del tag `html` e memorizzando la preferenza dell'utente nel `localStorage` per mantenere la scelta nelle visite successive.
- *Filtri*: Viene implementato un sistema di filtraggio per lo storico delle prenotazioni. Questo permette di visualizzare istantaneamente le prenotazioni in base al loro stato ("Tutte", "Attive", "Completate") agendo sulla visibilità delle righe della tabella e aggiornando in tempo reale i contatori presenti nelle tab di filtro.

I file di validazione dedicati (`register_validation.js`, `login_validation.js`, `blog_validation.js`) garantiscono l'integrità dei dati e migliorano l'usabilità dei form:
- *Validazione in tempo reale*: Verifica la correttezza del campo alla perdita del focus, controllando formati complessi come il Codice Fiscale, la validità strutturale delle Email o delle URL.
- *Assistenza all'input*: Include comportamenti come la conversione automatica in maiuscolo dei caratteri durante la digitazione nei campi Codice Fiscale e Provincia.
- *Invio del modulo*: Lo script intercetta il tentativo di invio del modulo e lo valida. Se la validazione fallisce la richiesta al server viene bloccata e la pagina esegue uno scroll automatico verso il primo campo errato, portandovi il focus per facilitare la correzione immediata.

== Back-End

=== Architettura (PHP)
Lo sviluppo lato server è stato realizzato con un approccio modulare che simula il pattern architetturale *Model-View-Controller* (MVC).
Ogni pagina PHP funge da *Controller*: gestisce la logica, verifica i permessi e prepara i dati. La generazione dell'interfaccia (*View*) avviene separando la logica dall'HTML: il codice PHP carica i template statici tramite la funzione helper `buildPage()` e inietta i dati dinamici sostituendo i segnaposto predefiniti.

Per garantire manutenibilità e sicurezza, sono stati adottati i seguenti accorgimenti:
- *Configurazione*: I parametri di connessione e le costanti globali sono definiti nel file `conf.php`, escluso dal versionamento per evitare l'esposizione accidentale di credenziali sensibili.
- *Gestione percorsi*: La gestione dei percorsi e delle risorse è centralizzata nel file `pages.php`, che funge da mappa per gli URL del sito.

=== Gestione Sessioni e Autenticazione
Il mantenimento dello stato utente è gestito tramite un sistema dedicato nel file `session.php`. Sono state implementate funzioni helper per semplificare i controlli di accesso trasversali:
- `isLogged()` e `isAdmin()`: Verificano rispettivamente se un utente è autenticato e se possiede i privilegi di amministratore.
- `requireLogin()` e `requireAdmin()`: Queste funzioni sono poste in cima alle pagine protette. Se i requisiti non sono soddisfatti esse interrompono l'esecuzione e reindirizzano forzatamente l'utente alla pagina di login o mostrano un errore di accesso negato.

=== Gestione dei Dati (Database)
L'interazione con il database è incapsulata nella classe `DBConnection`. Ogni metodo implementa blocchi `try-catch` per intercettare eccezioni SQL, restituendo codici di errore specifici o `false` in modo che il controller possa gestire il fallimento senza esporre dettagli tecnici all'utente.

Le entità implementate sono state schematizzate in #link(<fig-database>)[Figura 2] (per migliorarne la leggibilità non sono stati inclusi gli attributi), e sono: 

- *Utente*: Contiene le informazioni di tutti gli iscritti, come credenziali e dati anagrafici. Il campo `Is_Admin` serve per definire i privilegi di accesso.
- *Indirizzo*: Memorizza i dati geografici (Via, Città, CAP) separandoli dall'utente per una migliore normalizzazione.
- *Prodotto*: Contiene i dati comuni dei prototti, Noleggi ed Esperienze, del catalogo (prezzo, descrizione, posti).
- *Prodotto_Extra*: Gestisce i servizi opzionali a pagamento (es. Skipper, Champagne).
- *Prodotto_Incluso*: Elenca i servizi già compresi nel prezzo base (es. Carburante, Assicurazione).
- *Lingua*: Memorizza Le lingue supportate per i servizi guidati (IT, EN, FR, ES).
- *Prodotto_Lingua*: Relazione che collega Prodotto e Lingua, indicando quali lingue sono parlate nell'esperienza specifica.
- *Prenotazione*: Necessaria per mantenere lo storico delle transazioni. 
- *Indisponibilita*: Permette agli amministratori di bloccare date specifiche (es. per manutenzione).
- *Articolo_Blog*: Necessaria per gestire gli articoli del blog.
- *Articolo_Blog_Extra*: Struttura i contenuti complessi degli articoli (es. liste puntate, sezioni "Cosa portare").
- *Media*: centralizza la gestione delle immagini.

  #figure(
    image("../img/schema_database.png", width: 150%), 
    gap: 2em,
    caption: [Schema ER del database],
  ) <fig-database>

=== Sicurezza lato Server
Per quanto riguarda la sicurezza, oltre alla validazione degli input, sono state implementate lato backend i seguenti controlli:

- *Prevenzione SQL injection*: Tutti i metodi della classe `DBConnection` utilizzano Prepared Statements. I parametri vengono vincolati alla query e mai concatenati direttamente nelle stringhe SQL, eliminando alla radice il rischio di iniezione.
- *Hashing delle password*: Le password non sono mai salvate in chiaro. In fase di registrazione viene utilizzato `password_hash()` con l'algoritmo `PASSWORD_DEFAULT` (Bcrypt). Al login, la verifica avviene tramite `password_verify()`, garantendo la sicurezza delle credenziali anche in caso di compromissione del DB.
- *CSRF*: Per prevenire attacchi *Cross-Site Request Forgery*, tutti i form che modificano lo stato (login, registrazione, pannello admin) sono protetti da un token univoco. `getCsrfToken()` genera un token crittograficamente sicuro usando `bin2hex(random_bytes(32))`, mentre `verifyCsrfToken()` valida la corrispondenza del token inviato via POST con quello in sessione prima di elaborare la richiesta.
- *XSS*: Ogni dato dinamico stampato a video viene sanitizzato tramite `htmlspecialchars()`, convertendo i caratteri speciali in entità HTML sicure per prevenire l'esecuzione di script malevoli.

= Accessibilità
L'accessibilità è stata un pilastro del progetto, guidata dai principi studiati durante il corso e dalle linee guida internazionali.

- *Principi WCAG e Struttura Semantica:* Il progetto aderisce ai quattro principi fondamentali delle WCAG (Web Content Accessibility Guidelines), riassunti nell'acronimo PURO: Percepibile, Utilizzabile, Comprensibile e Robusto. L'uso rigoroso di HTML semantico (`<main>`, `<nav>`, `<header>`, `<footer>`) fornisce una struttura chiara e prevedibile, che facilita l'interpretazione dei contenuti da parte delle tecnologie assistive come gli _screen reader_.
- *Navigazione da Tastiera:* Il sito è stato progettato per essere completamente navigabile utilizzando esclusivamente la tastiera. È stato verificato per ogni pagina che l'ordine di focus mediante tabulazione avvenisse correttamente. \ È stata poi implementato il link "Salta al contenuto" per permettere agli utenti di _screen reader_ di bypassare i blocchi di navigazione ripetitivi e a loro superflui.

- *Contrasto Cromatico e Colori:* È stata prestata particolare attenzione al contrasto tra testo e sfondo, verificando che i rapporti cromatici rispettassero almeno il livello AA delle WCAG. Inoltre, l'implementazione di un selettore di tema light/dark offre agli utenti la possibilità di scegliere la modalità di visualizzazione con il contrasto che preferiscono, migliorando ulteriormente la leggibilità.

- *Alternative Testuali:* Ogni immagine con contenuto informativo, quindi non puramente decorativa, è stata dotata di un attributo `alt` descrittivo in modo tale da veicolare informazioni grafiche attraverso strumenti di sintesi vocale. I form amministrativi che consentono l'aggiunta di prodotti e articoli del blog includono un campo per il "Testo Alternativo", assicurando che questa buona norma venga applicata a tutti i contenuti che in futuro verranno inseriti.

- *WAI-ARIA:* Dove necessario, sono stati utilizzati attributi WAI-ARIA (Accessible Rich Internet Applications) come `aria-expanded`, `aria-label` e `aria-required` per arricchire semanticamente i componenti dinamici. Questo permette di rendere il loro stato e la loro funzione pienamente comprensibili per gli _screen reader_.

- *Test con Strumenti:* L'accessibilità è stata verificata attraverso ...

= Testing e Validazione
La fase di testing è essenziale per garantire che il prodotto finale sia corretto, performante e conforme ai requisiti. Questa sezione documenta il processo di verifica rigoroso a cui è stato sottoposto il sito SailUP, coprendo la validazione del codice, l'accessibilità, i test funzionali e la compatibilità cross-browser.

== Validazione del Codice

== Test Funzionali
Sono stati condotti test approfonditi sulla validazione degli input utente per garantire la robustezza e la sicurezza dei form. Di seguito le principali regole di validazione implementate lato client:

*Form di Registrazione:*
- Nome/Cognome: Minimo 2 caratteri, solo lettere.
- Codice Fiscale: 16 caratteri alfanumerici.
- Email: Formato valido (es. utente\@dominio.it).
- Password: Minimo 8 caratteri, deve contenere almeno una lettera maiuscola, una minuscola e un numero.
- CAP: 5 cifre numeriche.
- Provincia: 2 lettere maiuscole.
- Privacy Policy: Accettazione obbligatoria.

*Form di Login:*
- Email: Campo obbligatorio e in formato valido.
- Password: Campo obbligatorio, minimo 8 caratteri.

*Form Creazione Prodotto/Blog (Admin):*
È stata verificata la validazione di tutti i campi obbligatori per garantire la completezza dei dati. Per i prodotti, i campi validati includono nome, tipo, descrizione, prezzo, capacità, URL immagine e testo alternativo. Per gli articoli del blog, i controlli si applicano a titolo, categoria, data, estratto, contenuto, URL immagine e testo alternativo.

== Compatibilità e Design Responsivo
Il sito è stato testato sui principali browser web moderni, tra cui Google Chrome, Mozilla Firefox, Apple Safari e Microsoft Edge, per assicurare una resa grafica e funzionale coerente. Il design responsivo è stato verificato su un'ampia gamma di risoluzioni, simulando dispositivi che vanno dai piccoli smartphone ai tablet, fino ai monitor desktop di grandi dimensioni, per garantire un'esperienza utente ottimale su ogni device.

= Suddivisione del Lavoro
Sebbene il progetto sia stato il risultato di uno sforzo collaborativo e di una continua sinergia tra i membri del gruppo, le attività sono state suddivise per aree di competenza principale al fine di ottimizzare il flusso di lavoro.

#table(
  columns: (30%, 1fr),
  inset: 10pt,
  align: (center, left),
  fill: (col, row) => if row == 0 {rgb("#0033ff")} else { none },
  table.header(
    // Applichi il colore bianco manualmente a ogni cella dell'intestazione
    text(fill: white)[*Membro del Gruppo*],
    text(fill: white)[*Aree di Contributo Principale*],
  ),
  [Davide Biasuzzi], [],
  [Hossam Ezzemouri], [],
  [Francesco Marcon], [],
  [Alberto Reginato], []
)

= Conclusioni e Sviluppi Futuri
Il progetto *SailUP* ha raggiunto con successo tutti gli obiettivi prefissati, realizzando una piattaforma web completa, funzionale e conforme a tutti i requisiti tecnici e qualitativi del corso. Il risultato è un'applicazione moderna, accessibile e user-friendly, che dimostra una solida comprensione delle tecnologie web e delle best practice di sviluppo.

== Sviluppi Futuri
La base tecnologica solida e modulare di SailUP si presta a numerose evoluzioni future. Tra le possibili implementazioni, si possono ipotizzare:

- *Sistema di recensioni e valutazioni:* Aggiungere una funzionalità che permetta agli utenti di lasciare recensioni e valutazioni sui prodotti, aumentando la fiducia e fornendo un feedback prezioso.

- *Notifiche automatiche via email:* Sviluppare un sistema per l'invio automatico di email di conferma, promemoria e aggiornamenti relativi alle prenotazioni effettuate dagli utenti.

- *Localizzazione:* Introdurre supporto alle lingue più diffuse tra i turisti che affollano il Golfo di Napoli, come ad esempio francese, inglese e spagnolo.