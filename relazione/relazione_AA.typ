#set page(
  paper: "a4",
  margin: (x: 2.5cm, y: 2.5cm),
  numbering: "1",
)

#show heading.where(level: 1): set text(size: 20pt, weight: "bold")

#show heading.where(level: 2): set text(size: 18pt, weight: "bold")

#show heading.where(level: 3): set text(size: 16pt, weight: "bold")

#show heading.where(level: 4): set text(size: 13.5pt, weight: "bold")


#set text(
  size: 12pt,
  lang: "it"
)

#set par(
  justify: true,
  leading: 0.65em,
)

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
  
  #text(size: 16pt)[Focus sull'accessibilità]

  #v(3em)
  
  #align(center, image("../public/img/logo_light.svg", width: 45%))

  #v(2em)
  
  #block(width: 100%, stroke: 1pt + gray, inset: 2em, radius: 5pt)[
    #align(center)[
      *Componenti del gruppo:*
      #v(0.5em)
      #table(
        stroke: none,
        [Davide Biasuzzi],
        [Francesco Marcon],
        [Alberto Reginato],
      )
    ]
  ]

  #v(1cm)
  
  #align(left)[
    *Referente del gruppo:* \
    #link("mailto:davide.biasuzzi@studenti.unipd.it")
    
    #v(0.5em)
    *Indirizzo del sito:* \
    #link("https://caa.studenti.math.unipd.it/dbiasuzz")
    
    #v(0.5em)
    *Informazioni di accesso:* \
    - *Amministratore:* username `admin`, password `admin`
    - *Utente Semplice:* username `user`, password `user`
  ]
]

#pagebreak()


#heading(level: 1, numbering: none, outlined: false)[Abstract]
SailUP è una piattaforma web dedicata al noleggio di imbarcazioni e alla prenotazione di esperienze nautiche nel suggestivo scenario del Golfo di Napoli. Siamo onesti, a chi verrebbe l'idea di sviluppare un sito per il corso di Tecnologie Web su un tema così 'di nicchia' o, forse qualcuno penserà, così noioso? \ 
Uno dei maggiori problemi riguardanti la gestione tradizionale delle richieste di noleggio e dei tour turistici è la frammentazione dei servizi tra canali telefonici e fisici, che rischia di generare inefficienze e sovrapposizioni nelle disponibilità. SailUP nasce proprio con l'ambizione di mettere ordine in questo _mare magnum_, offrendo un supporto tecnologico capace di ottimizzare i processi gestionali interni e, contemporaneamente, garantire agli utenti un servizio immediato, intuitivo e finalmente autonomo.
Il sito web desiderato è dunque focalizzato sull'implementazione delle funzionalità di *Noleggio* e *Esperienze*, guidando l'utente dalla consultazione del catalogo fino alla prenotazione. SailUP sfrutta la piattaforma anche come una vetrina virtuale attraverso una sezione *Blog* dedicata, attraverso cui mira a fornire contenuti informativi di valore ai clienti e a supportare l'attività di content marketing per attrarre nuovi utenti, siano essi turisti o residenti locali.
Per chi decide di salire a bordo la piattaforma offre un'*area personale* per la gestione del profilo e il monitoraggio dello storico prenotazioni. Parallelamente, un pannello di *controllo amministrativo* permette la gestione completa dei contenuti dinamici del sito.

= Accessibilità e Testing
Dopo una lunga fase di sviluppo è seguita poi quella di validazione e _testing_, un vero e proprio bagno di umiltà che ci ha permesso di individuare tutta quella moltitudine di errori e mancanze sfuggiteci. Col senno di poi sarebbe stato decisamente più saggio portare avanti queste due fasi parallelamente.

== Accessibilità
Il garantire l'accessibilità del sito a tutte le categorie di utente ha richiesto accorgimenti su tutte le componenti del progetto: struttura, presentazione e comportamento. Vengono elencati di seguito tutte le attenzioni riposte, al netto di inevitabili dimenticanze:

- *Principi WCAG e struttura semantica:* Abbiamo utilizzato con rigore i tag semantici HTML (`<main>`, `<nav>`, `<header>`, `<footer>`) per fornire una mappa logica immediata del sito, facilitando l'interpretazione dei contenuti da parte delle tecnologie assistive come gli _screen reader_.

- *Navigazione da tastiera:* Il sito è stato progettato per essere completamente navigabile utilizzando esclusivamente la tastiera. Attraverso l'estensione _Wave_ è stato verificato per ogni pagina che l'ordine di focus mediante tabulazione avvenisse correttamente. 

- *Salta al contenuto:* È stato implementato il link 'Salta al contenuto' per permettere agli utenti di _screen reader_ di saltare i blocchi di navigazione ripetitivi e a loro superflui.

- *Dettagli semantici:* Abbiamo riposto attenzione a quegli attributi che rimangono invisibili agli utenti comuni ma che sono fondamentali per _ranking_ e per la corretta sintesi vocale attraverso _screen reader_. L'uso dell'attributo `lang` (principalmente per termini in inglese e in francese) evita una riproduzione maccheronica della sintesi vocale, mentre il tag `<abbr>` e l'attributo `datetime` rendono acronimi e date _machine-readable_. 

- *Semantica dinamica:* Gran parte dei contenuti di SailUP è dinamica. Per evitare di inserire manualmente i tag di accessibilità ad ogni occorrenza, abbiamo implementato nel modulo helpers.php una serie di funzioni di formattazione che, consultando dei dizionari presenti nella directory `/config`, automatizzano l'inserimento di attributi e tag. `formatTextAbbr` si occupa di trasformare automaticamente acronimi (es. 'GPS', 'TV') ed unità di misura (es. 'm', 'h', 'cv') nel tag `abbr` con il relativo title esplicativo, mentre `formatTextLang` si occupa di identificare i termini stranieri (es. 'skipper', 'champagne') assegnandogli il corretto attributo `lang`.

- *Alternative testuali:* Ogni immagine che fornisce informazioni aggiuntive rispetto al contesto, ovvero non puramente decorativa, è stata dotata di un attributo alt descrittivo. Per le immagini puramente grafiche tale attributo è stato lasciato vuoto anziché rimosso per permette allo _screen reader_ di interpretare la scelta come intenzionale e non come una svista in fase di sviluppo. I form amministrativi che consentono l'aggiunta di prodotti e articoli del blog includono inoltre un campo opzionale che permette all'occorrenza di riempire questo campo.

- *Contrasto cromatico e colori:* È stata prestata particolare attenzione ad utilizzare una palette cromatica che mantenesse i rapporti cromatici tali da rispettare almeno il livello AA delle WCAG 2.2 pur rimanendo esteticamente gradevole, operazione che ci è costata più tempo di quanto vorremmo ammettere. L'implementazione di un selettore di tema light/dark offre inoltre agli utenti la possibilità di scegliere la modalità di visualizzazione che preferiscono, migliorando ulteriormente la leggibilità. Sebbene il tema testato e validato per rispettare il livello AA delle linee guida WCAG sia quello chiaro, abbiamo riposto attenzione anche per quello scuro. 

- *WAI-ARIA:* Poiché gran parte di SailUP vive di interazioni in tempo reale, abbiamo sfruttato gli attributi WAI-ARIA per evitare che l'esperienza d'uso si trasformasse in un silenzio assordante per chi usa uno _screen reader_. Abbiamo utilizzato `aria-live="polite"` e i ruoli `status/alert` per fare in modo che venissero comunicati i campi obbligatori e i messaggi di feedback. Attraverso l'uso di `aria-describedby` abbiamo collegato ogni campo di input alle proprie istruzioni e ai messaggi d'errore specifici. Nella navigazione abbiamo sfruttato `aria-current="page"` e la coppia `aria-expanded/aria-controls` per comunicare dinamicamente lo stato del menu mobile. Abbiamo cercato di rendere l'interfaccia meno generica delegando al backend la generazione di `aria-label` descrittivi, ad esempio nelle card dei prodotti un link 'Dettagli' avrà associato il nome del prodotto. Per pulire il flusso audio da rumore inutile, infine, abbiamo sfruttato `aria-hidden="true"` per evitare che icone puramente decorative ed emoji venissero lette. Qualora queste icone venissero inserite attraverso foglio di stile CSS abbiamo provveduto a sostituirle con immagini.

== Validazione e Testing
Il codice è stato esaminato per individuare quegli errori che, inevitabilmente, passano inosservati durante la fase di sviluppo. Non possiamo nascondere quanto ottenere un 'bollino verde' da un validator induca in noi un rilascio istantaneo di dopamina.

=== Validazione

- *Validatori W3C HTML e CSS:* Sono stati utilizzati per assicurare che la struttura HTML5 e i fogli di stile siano conformi agli standard internazionali. Al netto di qualche avviso relativo all'uso delle variabili CSS (ces. `var(--colore-primario)`), il codice ha superato i test senza errori critici.

- *Total Validator:* Abbiamo utilizzato questo strumento per una verifica più completa, testando contemporaneamente la validità dell'HTML, la conformità alle linee guida WCAG e l'integrità dei collegamenti ipertestuali. Se da un lato ci ha permesso di scovare diverse sviste sugli attributi ARIA, dall'altro abbiamo dovuto ignorare molti falsi positivi riguardanti lo spell-check. Il test delle pagine private, accessibili solo in seguto a login, sono stati eseguiti inviando il codice sorgente perchè altrimenti inaccessibili. Alcuni di questi controlli sono stati effettuati anche con 'axe DevTools' per conferma.

- *WCAG Contrast Checker:* Questo strumento è stato prezioso per analizzare e correggere i contrasti tra gli elementi presenti all'interno delle pagine. Questo _tool_ ci ha messo di fronte alla dura realtà che quel blu che ci piaceva tanto, purtroppo, non è per tutti facilmente leggibile.

- *w3ba11y:* Abbiamo utilizzato questo strumento per verificare che le _keywords_ inserite fossero effettivamente presenti nella pagina.

- *WAVE e SilkTide:* Oltre a condurre un secondo controllo sui contrasti, abbiamo sfruttato questi _tool_ per verificare che l'ordine di navigazione da tastiera fosse corretto. Oltre a questo ci hanno permesso di individuare altri errori relativi alle intestazioni. _WAVE_ ha segnalato alcuni warning per '_redundant link_', riferendosi al doppio collegamento alla _Home_ presente sia sul logo che nel menu. In questo caso abbiamo esercitato il nostro diritto di libero arbitrio ignorandolo: rimuovere uno dei due sarebbe stato tecnicamente 'pulito' secondo il _tool_, ma poco intuitivo per un utente reale.

=== Test
Sono stati condotti test approfonditi di accessibilità. Ecco il resoconto delle principali effettuate:

- *Tecnologie assistive:* La piattaforma è stata navigata utilizzando screen reader quali VoiceOver e NVDA per assicurarsi che la navigazione fosse comprensibile anche attraverso l'uso di questi strumenti di sintesi vocale. È stato ad esempio controllato che gli elementi interattivi venissero correttamente etichettati, che i cambiamenti di elementi a schermo venissero comunicati o che elementi non necessari (es. emoji) non venissero letti. Tutto ciò ci ha ricordato che il codice perfetto non esiste, specialmente se deve essere interpretato da uno _screen reader_.

- *Test automatizzati di accessibilità:* Per verificare sistematicamente l'accessibilità di tutte le pagine PHP del sito è stato utilizzato lo strumento _pa11y_ per l'esecuzione di test automatizzati. Questo _tool_ ci ha permesso di analizzare l'intero sito in modo efficiente, identificando eventuali problemi di accessibilità su tutte le pagine dinamiche.

- *Audit automatizzato con Unlighthouse:* Per ottenere una valutazione completa delle performance, dell'accessibilità, delle best practices e della SEO del sito, è stato utilizzato Unlighthouse v0.17.4. I risultati ottenuti sono stati particolarmente soddisfacenti: *100% su SEO*, *100% su Best Practices*, *100% su Accessibility* e *67% su Performance*. Il punteggio relativo alle performance, seppur non perfetto, è comunque accettabile considerando la natura dinamica del sito, i limiti dell'hosting e la quantità di contenuti multimediali presenti.

- *Compatibilità e design responsivo:* Il sito è stato testato sui principali _browser_ web moderni, tra cui Google Chrome, Opera, Mozilla Firefox, Safari e Microsoft Edge per accertarci che venissero renderizzati correttamente. Il design responsivo è stato verificato a diverse risoluzioni simulando dispositivi che vanno da smartphone ai tablet, fino ai monitor desktop. Non sono state invece prese in considerazione versioni più vecchie dei _browser_, considerato il profilo dell'utenza target del sito orientata all'utilizzo di dispositivi moderni.

= Conclusioni e Sviluppi Futuri
Messi di fronte alla complessità delle sfide tecniche che a volte si sono rivelate più difficili del previsto, lavorare a questo progetto è stata un'esperienza estremamente formativa. 
SailUP non è probabilmente l'innovazione che cambierà le sorti del turismo globale ma è un lavoro di cui siamo orgogliosi e che saremmo fieri di esporre in un possibile portfolio personale. Ci ha dato modo di dimostrare la nostra capacità di costruire un'applicazione web che non sia solo funzionante ma anche solida, sicura e navigabile da tutti.
