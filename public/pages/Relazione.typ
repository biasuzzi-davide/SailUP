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
La fase di analisi rappresenta un momento critico in cui si gettano le fondamenta del progetto. In questa sezione vengono esaminate le componenti essenziali che hanno guidato le scelte successive: l'identificazione dell'utenza target e dei dispositivi di accesso, la definizione delle funzionalità necessarie e la pianificazione di una strategia di ottimizzazione per i motori di ricerca (SEO). Un'analisi accurata assicura che il prodotto finale sia non solo tecnicamente valido, ma anche efficace e pertinente per i suoi utilizzatori.

== Utenza Target
L'utenza della piattaforma SailUP è stata segmentata in due categorie principali, ciascuna con esigenze e priorità distinte:

- *Nuovi Clienti/Turisti:* Si tratta di utenti che non conoscono il servizio e sono alla ricerca di informazioni su gite in barca ed esperienze nel Golfo di Napoli. Per questo segmento, il sito deve essere visivamente accattivante, ricco di informazioni chiare e facile da navigare. L'obiettivo è catturare il loro interesse, trasmettere fiducia e guidarli fluidamente verso la prenotazione.
- *Clienti Abituali/Utenti Registrati:* Questi utenti hanno già interagito con SailUP o prevedono di utilizzarlo con regolarità. La loro priorità è l'efficienza: desiderano accedere rapidamente al sistema di prenotazione, gestire le proprie prenotazioni attive e consultare lo storico delle esperienze passate.

All'interno del sistema, sono stati definiti due ruoli operativi:
- *Utente Semplice:* Un utente registrato che può effettuare e gestire le proprie prenotazioni.
- *Amministratore:* Un utente con privilegi elevati che gestisce l'intero contenuto del sito, inclusi prodotti, utenti, prenotazioni e articoli del blog.

== Devices
Considerando che una porzione significativa dell'utenza target, in particolare i turisti, accede al web tramite dispositivi mobili, è stata adottata una filosofia di progettazione *"mobile-first"*, progettando quindi l'interfaccia utente partendo dalle limitazioni e dalle specificità degli schermi più piccoli. Successivamente, il layout e le funzionalità vengono arricchiti e adattati per schermi più grandi, come quelli dei computer desktop, attraverso l'uso di tecniche di design responsivo e media queries.

== Funzionalità
La piattaforma SailUP è stata dotata di un set di funzionalità specifiche per ogni ruolo utente, garantendo un'esperienza personalizzata e sicura.

*Ospite (Utente non autenticato):*
- Visualizzazione dei cataloghi di noleggio barche ed esperienze.
- Lettura degli articoli pubblicati sul blog.
- Accesso alle pagine informative statiche (Chi Siamo, FAQ, Privacy/Cookie Policy).
- Possibilità di registrarsi al sito.

*Utente Registrato:*
- Tutte le funzionalità dell'Ospite.
- Accesso al sistema tramite Login e Logout.
- Gestione del proprio profilo utente, inclusa la modifica dei dati anagrafici e della password.
- Visualizzazione e gestione delle proprie prenotazioni, suddivise tra attive e completate.

*Amministratore:*
- Accesso a un pannello di controllo (Dashboard) dedicato e protetto.
- Gestione completa degli utenti registrati (visualizzazione, modifica, eliminazione).
- Operazioni CRUD (Create, Read, Update, Delete) sui prodotti del catalogo (barche, esperienze ed articoli del blog).
- Gestione centralizzata di tutte le prenotazioni presenti nel sistema.

== Ricerche da Soddisfare e Strategia SEO
Per garantire che SailUP sia facilmente reperibile dagli utenti, è stata definita una strategia SEO mirata a intercettare le ricerche più pertinenti.

*Potenziali ricerche degli utenti:*
- "noleggio barche Golfo di Napoli"
- "escursioni in barca Capri"
- "gita in barca a Positano"
- "esperienze nautiche Napoli"
- "blog nautica consigli"
- "affitto gommone Ischia"

*Strategie SEO implementate:*
Per migliorare il posizionamento del sito sui motori di ricerca, sono state adottate le seguenti tecniche:

- *HTML Semantico:* Utilizzo corretto e strutturato dei tag HTML5 (`<header>`, `<nav>`, `<main>`, `<h1>`, etc.) per comunicare in modo efficace la gerarchia dei contenuti agli spider dei motori di ricerca.
- *Meta Tag:* Compilazione accurata dei meta title e meta description per ogni pagina, al fine di ottimizzare lo snippet visualizzato nei risultati di ricerca.
- *Alternative Testuali:* Inserimento di attributi `alt` descrittivi per le immagini non decorative, migliorando l'accessibilità e fornendo contesto ai motori di ricerca.
- *Content Marketing:* Creazione di contenuti di qualità e pertinenti tramite il blog integrato, per attrarre traffico organico e far conoscere il servizio.
- *Controllo SEO sui Contenuti:* Il form di creazione degli articoli del blog include campi dedicati per "Meta Titolo" e "Meta Descrizione", offrendo all'amministratore un controllo diretto e granulare sull'ottimizzazione SEO di ogni singolo post.

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
La fase di progettazione ha il compito di tradurre i requisiti emersi dall'analisi in un piano tecnico e strutturale concreto. In questa sezione vengono definite l'architettura logica del sito e le linee guida che hanno orientato le scelte di sviluppo, assicurando coerenza, manutenibilità e aderenza agli standard qualitativi richiesti.

== Linee Guida
Lo sviluppo di SailUP è stato guidato da un insieme di principi fondamentali, volti a garantire la qualità e la robustezza della piattaforma:

- *Repository condiviso:* È stato scelto di sviluppare il progetto *SailUP* adottando un un repository su github per la condivisione del codice tra i membri del team.
- *Separazione dei Livelli:* È stata mantenuta una rigida separazione tra la struttura del contenuto (HTML), lo stile di presentazione (CSS) e il comportamento interattivo (JavaScript e php), come esplicitamente richiesto dalle specifiche del corso. Questo approccio migliora la manutenibilità e la modularità del codice.
- *Design Moderno e Pulito:* La scelta è ricaduta su un layout grafico minimale ed elegante, con l'obiettivo di valorizzare i contenuti visivi (immagini di barche e paesaggi) e migliorare l'esperienza utente, rendendo la navigazione piacevole e intuitiva. Seppur l'interfaccia sia minimale ed il bianco sia predominante, è stata adottata una palette cromatica di toni di blu più o meno accesi, per richiamare il tema marino e per trasmettere...
- *Accessibilità by Design:* La progettazione del sito è stata condotta cercando garantire l'accessibilità a tutte le categorie di utenti.
- *Standard Web:* Il progetto si impegna a utilizzare esclusivamente standard web consolidati come HTML5 e CSS3. Per la gestione del layout responsivo, sono state impiegate le moderne tecniche di Flexbox e Grid, garantendo compatibilità cross-browser e una solida base per future evoluzioni.

== Architettura del Sito
È stato scelto un modello di architettura gerarchica per la sua chiarezza e facilità di navigazione. La struttura del sito è organizzata come segue, garantendo un percorso logico per l'utente:

- Home
- Noleggio (Catalogo barche)
- Esperienze (Catalogo esperienze)
- Blog
  - Articolo Singolo
- Chi Siamo
- FAQ
- Login / Registrazione
- Area Utente
  - Profilo e Modifica Dati
  - Le Mie Prenotazioni
- Dashboard Admin
  - Gestione Utenti
  - Gestione Prodotti
    - Aggiungi/Modifica Prodotto
  - Gestione Prenotazioni
  - Gestione Blog
    - Aggiungi/Modifica Articolo
- Privacy Policy
- Cookie Policy

Questa struttura chiara e ben definita ha costituito la base per la successiva fase di realizzazione tecnica.

= Realizzazione
In questa sezione vengono descritte le scelte tecniche e le soluzioni implementative adottate per costruire la piattaforma SailUP. Vengono analizzati gli aspetti legati sia allo sviluppo frontend, che definisce l'interfaccia utente, sia al backend, che gestisce la logica applicativa e la persistenza dei dati.

== Implementazione del Frontend
=== Struttura (HTML)
...
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