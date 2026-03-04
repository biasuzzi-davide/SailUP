# ⛵ SailUP - Piattaforma di Prenotazione Barche ed Esperienze Nautiche

<div align="center">

[![Status](https://img.shields.io/badge/status-inactive-red.svg)](https://github.com)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://www.php.net/)
[![Maintenance](https://img.shields.io/badge/Maintained%3F-yes-green.svg)](https://github.com)

Un'applicazione web moderna e intuitiva per scoprire, prenotare e noleggiare barche e vivere indimenticabili esperienze nautiche.

[Caratteristiche](#-caratteristiche) • [Installazione](#-installazione) • [Utilizzo](#-utilizzo) • [Struttura](#-struttura-del-progetto) • [Tecnologie](#-tecnologie)

</div>

---

## 🌟 Caratteristiche

### Per gli Utenti
- 🔐 **Autenticazione Sicura** - Registrazione e login con gestione sessioni avanzate
- 🏄 **Catalogo Esperienze** - Scopri emozionanti esperienze nautiche e lezioni di vela
- ⛵ **Noleggio Barche** - Sfoglia un'ampia selezione di imbarcazioni disponibili
- 📅 **Prenotazioni Intelligenti** - Sistema di prenotazione intuitivo e veloce
- 💳 **Pagamenti Sicuri** - Gateway di pagamento integrato e validato
- 👤 **Profilo Utente** - Gestisci i tuoi dati, prenotazioni e preferenze di sicurezza
- 📚 **Blog Interattivo** - Leggi articoli su vela, nautica e avventure marine

### Per gli Amministratori
- 🎛️ **Dashboard Amministrativa** - Pannello di controllo completo
- 📝 **Gestione Blog** - Crea, modifica e elimina articoli
- ⛴️ **Gestione Prodotti** - Amministra barche e esperienze
- 👥 **Gestione Utenti** - Monitora e gestisci gli utenti del sistema
- 📊 **Prenotazioni** - Visualizza e gestisci tutte le prenotazioni

---

## 🚀 Installazione

### Prerequisiti
- PHP 7.4 o superiore
- MySQL 5.7+
- Server web (Apache/Nginx)
- Composer (opzionale)

### Passaggi di Installazione

1. **Clona il repository**
```bash
git clone https://github.com/tuousername/SailUP.git
cd SailUP
```

2. **Configura il database**
```bash
# Importa il database dal file SQL
mysql -u root -p < db/dbiasuzz.sql
```

3. **Configura la connessione al database**
Modifica il file `config/conf.php` con le tue credenziali:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'tuo_utente');
define('DB_PASS', 'tua_password');
define('DB_NAME', 'sailup_db');
```

4. **Configura le pagine**
Modifica `config/pages.php` se necessario con i percorsi del tuo server

5. **Imposta i permessi**
```bash
chmod 755 public/
chmod 755 public/img/
chmod 755 public/img/avatars/
chmod 755 public/img/prodotti/
```

6. **Avvia il server**
```bash
# Se usi PHP built-in
php -S localhost:8000

# Oppure usa il tuo server web configurato
```

7. **Accedi all'applicazione**
Visita `http://localhost:8000` nel tuo browser

---

## 💻 Utilizzo

### Account di Prova
Per testare l'applicazione, puoi creare un nuovo account tramite la pagina di registrazione o contattare gli amministratori.

### Navigazione Principale

- **Home** - Pagina principale con panoramica
- **Chi Siamo** - Informazioni sull'azienda
- **Catalogo Noleggio** - Sfoglia le barche disponibili
- **Catalogo Esperienze** - Scopri le esperienze nautiche
- **Blog** - Leggi articoli interessanti
- **FAQ** - Domande frequenti
- **Area Personale** - Accedi al tuo profilo

### Area Amministrativa
Accedi all'area admin (solo per utenti autorizzati):
- Gestisci blog, prodotti e utenti
- Visualizza e gestisci le prenotazioni
- Monitora il sistema

---

## 📁 Struttura del Progetto

```
SailUP/
├── config/
│   ├── conf.php              # Configurazione del database
│   └── pages.php             # Configurazione percorsi pagine
│
├── db/
│   └── dbiasuzz.sql          # Schema e dati del database
│
├── includes/
│   ├── db_connection.php     # Connessione al database
│   ├── helpers.php           # Funzioni helper
│   ├── auth/
│   │   └── auth.php          # Logica autenticazione
│   ├── session/
│   │   └── session.php       # Gestione sessioni
│   └── utils/
│       └── validation.php    # Validazione input
│
├── public/
│   ├── css/                  # Fogli di stile
│   │   ├── style.css         # Stile principale
│   │   ├── mobile.css        # Responsive mobile
│   │   └── print.css         # Stile stampa
│   │
│   ├── js/                   # Script JavaScript
│   │   ├── script.js         # Script principale
│   │   ├── login_validation.js
│   │   ├── register_validation.js
│   │   ├── blog_validation.js
│   │   ├── products_validation.js
│   │   └── payment_validation.js
│   │
│   ├── img/                  # Risorse grafiche
│   │   ├── avatars/          # Avatar utenti
│   │   ├── blog/             # Immagini blog
│   │   ├── prodotti/         # Foto barche/esperienze
│   │   └── utenti/           # Immagini utenti
│   │
│   ├── pages/                # Template HTML
│   │   ├── index.html
│   │   ├── login.html
│   │   ├── registrazione.html
│   │   ├── chi_siamo.html
│   │   ├── blog.html
│   │   ├── catalogo_noleggio.html
│   │   ├── catalogo_esperienze.html
│   │   ├── profilo.html
│   │   ├── admin.html
│   │   └── ... altre pagine
│   │
│   └── php/                  # File PHP (logica backend)
│       ├── index.php         # Homepage
│       ├── login.php
│       ├── registrazione.php
│       ├── logout.php
│       ├── admin.php
│       ├── blog_articolo.php
│       ├── dettaglio_barca.php
│       ├── conferma_prenotazione.php
│       ├── pagamento.php
│       └── ... altre pagine
│
└── README.md                 # Questo file
```

---

## 🛠️ Tecnologie Utilizzate

### Backend
- **PHP 7.4+** - Linguaggio di programmazione server-side
- **MySQL** - Database relazionale
- **Sessions PHP** - Gestione utenti e autenticazione

### Frontend
- **HTML5** - Markup semantico
- **CSS3** - Styling responsivo (Mobile First)
- **JavaScript** - Interattività e validazione lato client

### Funzionalità Speciali
- Validazione form lato client e server
- Protezione CSRF
- Gestione sessioni sicura
- Responsive design mobile-first
- Supporto per stampa

---

## 🔐 Sicurezza

### Misure di Sicurezza Implementate
- ✅ Validazione input lato client e server
- ✅ Protezione dagli attacchi XSS
- ✅ Protezione dagli attacchi SQL Injection
- ✅ Sessioni autenticate
- ✅ Crittografia password (password_hash)
- ✅ HTTPS ready

### Best Practices
Assicurati di:
- Usare HTTPS in produzione
- Configurare correttamente i permessi dei file
- Mantenere PHP aggiornato
- Disabilitare display_errors in produzione
- Usare un environment file per le credenziali sensibili

---

## 📝 Guida agli Sviluppatori

### Aggiungere una Nuova Pagina

1. Crea un file HTML in `public/pages/`
2. Crea il corrispondente file PHP in `public/php/`
3. Registra la pagina in `config/pages.php`
4. Importa gli helper necessari

Esempio:
```php
<?php
include '../../includes/db_connection.php';
include '../../includes/auth/auth.php';

// La tua logica qui
?>
```

### Validazione
Usa le funzioni in `includes/utils/validation.php`:
```php
if (validateEmail($email)) {
    // Email valida
}
```

### Database
Usa la connessione globale:
```php
$result = mysqli_query($conn, $query);
```

---

## 📄 Licenza

Questo progetto è licenziato sotto la Licenza MIT - vedi il file [LICENSE](LICENSE) per i dettagli.

```
MIT License

Copyright (c) 2026 SailUP

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and subject to the following conditions:
...
```

---

## 👥 Autori

- **Davide Biasuzzi** - Sviluppatore Backend
- **Francesco Marcon** - Sviluppatore Frontend
- **Alberto Reginato** - Sviluppatore Frontend

---

## 🙏 Ringraziamenti

- Grazie a tutti i contributori
- Icone da [Font Awesome](https://fontawesome.com/)
- Ispirazione dalle migliori pratiche web

---

<div align="center">

**Fatto con ❤️ da Alberto, Davide, Francesco**

⭐ Se ti è piaciuto il progetto, lascia una stella! ⭐

</div>
