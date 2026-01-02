#set page(
  paper: "a4",
  margin: (x: 2.5cm, y: 2.5cm),
  numbering: "1",
)

#set text(
  size: 11pt,
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
Per intercettare il target di riferimento, il sito web è strutturato per soddisfare le seguenti intenzioni di ricerca (SEO):
- Noleggio barche Golfo di Napoli
- Escursioni in barca Capri e Positano
- Esperienze nautiche Napoli
- Affitto gommone Ischia
- Blog consigli nautica
- Tour in barca con skipper


= Progettazione

== Linee Guida
Per la gestione del ciclo di vita del software e il coordinamento del team, si è scelto di utilizzare un repository su GitHub per il versionamento del codice.

In questa sezione vengono illustrati i principi che hanno guidato la realizzazione del sito web, collegando i contenuti offerti alle funzionalità individuate in fase di analisi.
L'eterogeneità della clientela (turisti e residenti) ci ha portato a perseguire un design minimale e pulito. Si è scelto di puntare su un *branding* coerente con l'identità marittima: seppur il bianco sia predominante per garantire leggibilità e chiarezza delle informazioni, è stata adottata una palette cromatica basata su diverse tonalità di blu per richiamare il tema nautico e, sfruttando la psicologia dei colori, trasmettere eleganza, calma, sicurezza e freschezza.

È stata mantenuta una rigida separazione tra struttura (HTML), presentazione (CSS) e comportamento (PHP & JavaScript), per garantire modularità e rispetto degli standard web.

Infine, la progettazione del sito è stata condotta cercando garantire l'accessibilità a tutte le categorie di utenti.

== Struttura
La struttura del sito segue il modello gerarchico schematizzato in #link(<fig-sitemap>)[Figura 1]. In questa fase si è pianificata una suddivisione nelle seguenti pagine principali, accessibili tramite un menù di navigazione globale:

- *Home:*
  La pagina Home funge da punto di snodo principale. Deve contenere informazioni essenziali e presentative di SailUP, utilizzando immagini di impatto per catturare l'attenzione del visitatore e offrire collegamenti rapidi alle funzionalità principali, quindi le sezioni Noleggio ed Esperienze.
  Copre il requisito //*Vetrina*.

- *Cataloghi Noleggio ed Esperienze:*
  Queste pagine permettono all'utente di visualizzare l'offerta completa. Devono prevedere sistemi di filtraggio (per data, prezzo, tipologia) per agevolare la ricerca. Selezionando un elemento, l'utente accede a una pagina di dettaglio dove può consultare le specifiche e procedere alla prenotazione. Il sistema effettuerà un controllo sulla disponibilità delle date scelte restituendo un feedback all'utente.
  Coprono i requisiti di //*Vetrina* e *Prenotazione*.

- *Blog:*
  La pagina Blog raccoglie articoli informativi e consigli turistici. Ogni articolo è visualizzabile singolarmente. Questa sezione non offre interattività transazionale ma è fondamentale per l'attrattiva del sito.
  Copre i requisiti di //*Vetrina* e *Strategia SEO*.

- *Pagine Informative 'Chi Siamo', 'FAQ', 'Privacy' e 'Cookie':*
  Queste pagine offrono supporto all'utente, spiegando la storia dell'azienda e rispondendo alle domande frequenti per ridurre il carico di assistenza diretta.
  Coprono in parte il requisito //*Vetrina*.

- *Area Riservata:*
  Questa sezione gestisce l'accesso alla piattaforma. La pagina di *Login/Registrazione* permette all'utente di autenticarsi o creare un nuovo profilo. Una volta loggato, il sistema indirizza l'utente alla vista corretta in base al suo ruolo:

  - *Pagina Profilo Cliente:*
    Offre al cliente la possibilità di visualizzare e modificare i propri dati anagrafici. Include una sezione per consultare lo storico delle prenotazioni (attive e passate), permettendo all'utente di avere riscontro immediato sulle proprie attività.
    Copre il requisito di //*Gestione Profilo*.

  - *Dashboard Amministratore:*
    Questa sezione, accessibile solo agli utenti con privilegi elevati, funge da centro di controllo. Permette di visualizzare la totalità delle prenotazioni nel sistema, gestire l'anagrafica degli utenti registrati e modificare dinamicamente i contenuti del sito (aggiunta/modifica/rimozione di Barche, Esperienze e Articoli del Blog).
    Copre il requisito di //*Amministrazione*.

  #figure(
    image("../img/diagramma_albero.png", width: 100%), // Imposta la larghezza al 90% della pagina
    gap: 2em,
    caption: [Sitemap gerarchica della piattaforma SailUP],
  ) <fig-sitemap>

= Realizzazione
In questa sezione vengono descritte le scelte tecniche e le soluzioni implementative adottate per costruire la piattaforma SailUP. Vengono analizzati gli aspetti legati sia allo sviluppo frontend, che definisce l'interfaccia utente, sia al backend, che gestisce la logica applicativa e la persistenza dei dati.

== Implementazione del Frontend
=== Struttura (HTML)
- ...

=== Presentazione (CSS)
...
=== Comportamento (JavaScript)
...
=== Convenzioni Interne
...

== Logica Applicativa e Dati (Backend)
=== JavaScript
...
=== PHP
...
=== Database
...

= Accessibilità
== Validazione HTML5
...
== Validazione CSS
...
== Contrasti
...
== Screen Reader
...

= Testing e Validazione
La fase di testing è essenziale per garantire che il prodotto finale sia corretto, performante e conforme ai requisiti. Questa sezione documenta il processo di verifica rigoroso a cui è stato sottoposto il sito SailUP, coprendo la validazione del codice, l'accessibilità, i test funzionali e la compatibilità cross-browser.

== Validazione del Codice


== Accessibilità
L'accessibilità è stata un pilastro del progetto, guidata dai principi studiati durante il corso e dalle linee guida internazionali.

- *Principi WCAG e Struttura Semantica:* Il progetto aderisce ai quattro principi fondamentali delle WCAG (Web Content Accessibility Guidelines), riassunti nell'acronimo PURO: Percepibile, Utilizzabile, Comprensibile e Robusto. L'uso rigoroso di HTML semantico (`<main>`, `<nav>`, `<header>`, `<footer>`) fornisce una struttura chiara e prevedibile, che facilita l'interpretazione dei contenuti da parte delle tecnologie assistive come gli screen reader.
- *Navigazione da Tastiera:* Il sito è stato progettato per essere completamente navigabile utilizzando esclusivamente la tastiera. È stato verificato per ogni pagina che l'ordine di focus mediante tabulazione avvenisse correttamente. \ È stata poi implementato il link "Salta al contenuto" per permettere agli utenti di screen reader di bypassare i blocchi di navigazione ripetitivi e a loro superflui.

- *Contrasto Cromatico e Colori:* È stata prestata particolare attenzione al contrasto tra testo e sfondo, verificando che i rapporti cromatici rispettassero almeno il livello AA delle WCAG. Inoltre, l'implementazione di un selettore di tema light/dark offre agli utenti la possibilità di scegliere la modalità di visualizzazione con il contrasto che preferiscono, migliorando ulteriormente la leggibilità.

- *Alternative Testuali:* Ogni immagine portatrice di informazione, quindi non puramente decorativa, è stata dotata di un attributo `alt` descrittivo di modo table da veicolare informazioni grafiche attraverso strumenti di sintesi vocale. Per rafforzare ciò, i form amministrativi per l'aggiunta di prodotti e articoli del blog includono un campo obbligatorio per il "Testo Alternativo", assicurando che questa buona norma venga applicata a tutti i contenuti futuri.

- *WAI-ARIA:* Dove necessario, sono stati utilizzati attributi WAI-ARIA (Accessible Rich Internet Applications) come `aria-expanded`, `aria-label` e `aria-required` per arricchire semanticamente i componenti dinamici. Questo permette di rendere il loro stato e la loro funzione pienamente comprensibili per gli screen reader, come richiesto dalle linee guida per le Rich Internet Applications.

- *Test con Strumenti:* L'accessibilità è stata verificata attraverso ...

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

- *Sistema di Recensioni e Valutazioni:* Aggiungere una funzionalità che permetta agli utenti di lasciare recensioni e valutazioni sui prodotti, aumentando la fiducia e fornendo un feedback prezioso.

- *Notifiche Automatiche via Email:* Sviluppare un sistema per l'invio automatico di email di conferma, promemoria e aggiornamenti relativi alle prenotazioni effettuate dagli utenti.

- *Localizzazione:* Introdurre supporto alle lingue più diffuse tra i turisti che affollano il Golfo di Napoli, come ad esempio francese, inglese e spagnolo.