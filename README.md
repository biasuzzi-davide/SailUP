# ⛵ SailUP - Vivi il mare, senza pensieri.

<div align="center">

🏆 **Progetto Vincitore del Concorso "Accattivante Accessibile" (Quinta Edizione - 2026)** 🏆  
*Indetto dall'Università degli Studi di Padova (Dipartimento di Matematica "Tullio Levi-Civita")*  
[Scopri di più sul concorso](https://web.math.unipd.it/CAA/)

[![Status](https://img.shields.io/badge/status-active-success.svg)](https://github.com)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://www.php.net/)
[![Maintenance](https://img.shields.io/badge/Maintained%3F-yes-green.svg)](https://github.com)

**Piattaforma web moderna e accessibile per la prenotazione di imbarcazioni e la scoperta di esperienze nautiche nel suggestivo scenario del Golfo di Napoli.**

[Caratteristiche](#-caratteristiche) • [Il Progetto](#-il-progetto) • [Installazione](#-installazione) • [Accessibilità & Design](#%EF%B8%8F-accessibilit%C3%A0--design) • [Tecnologie](#-tecnologie)

</div>

---

## 🌊 Il Progetto

**SailUP** nasce con l'obiettivo di semplificare e modernizzare la frammentaria offerta di servizi nautici nel Golfo di Napoli, unendo le funzionalità di prenotazione e consultazione in un'unica piattaforma intuitiva e accessibile a tutti.

Il nostro target si colloca in un segmento medio-alto, richiedendo quindi un'esperienza utente _premium_, veloce e senza attriti, perfetta sia per il cliente abituale che per il turista dell'ultimo minuto.

### Obiettivi chiave:
- **Gestione integrata**: Un unico sistema per barche, esperienze e gestione utenti.
- **Usabilità in mobilità**: Approccio rigoroso *"Mobile-first"*, pensato per chi prenota sotto il sole cocente con uno smartphone.
- **Accessibilità globale**: Strutturata e certificata per garantire l'accessibilità a tutte le categorie di utenti (Screen Readers, navigazione da tastiera, contrasto colore ottimizzato), rendendo la navigazione del sito piacevole per chiunque.
- **Inbound Marketing**: Integrazione di un Blog nautico dedicato al content marketing (SEO) per intercettare curiosi e convertirli in esploratori.

---

## 🌟 Caratteristiche

<details>
<summary>👤 <b>Navigazione e Funzioni Utente</b></summary>

- **Esplorazione Cataloghi**: Scopri facilmente barche disponibili ed emozionanti escursioni, accompagnate da filtri e descrizioni dettagliate.
- **Prenotazioni Intelligenti**: Prendi il mare con pochi clic, verificando istantaneamente la disponibilità delle risorse ed evitando l'overbooking tramite rigidi controlli server-side.
- **Area Personale (Clienti)**: Monitora e gestisci le tue avventure nautiche e l'anagrafica, aggiornando le tue preferenze e le norme di sicurezza.
- **Magazine Nautico**: Un blog informativo per orientarti e trovare ispirazione fin dalla terraferma.
</details>

<details>
<summary>🎛️ <b>Pannello di Controllo Amministratore (Admin)</b></summary>

- **Dashboard Unificata**: Una panoramica essenziale sulle operazioni della piattaforma.
- **Gestione CRUD Completa**: Controllo totale su prodotti (barche/esperienze), post del blog per la SEO e utenti.
- **Tracking Prenotazioni**: Un cruscotto per monitorare in modo globale le prenotazioni del portale.
</details>

---

## 🛠️ Tecnologie Utilizzate

L'applicativo rispetta i più recenti standard web, mantenendo una netta separazione tra struttura, presentazione e comportamento:

*   **Front-End**: HTML5 (semantico e strutturato), CSS3 (Mobile First, stile pulito nautico con palette sfumature di blu e bianco), JavaScript Vanilla (validazione asincrona e interazione DOM).
*   **Back-End**: PHP 7.4+ per una gestione veloce, sicura e affidabile delle sessioni, dei CSRF checker e della logica server.
*   **Database**: Schema relazionale in MySQL.
*   **Sicurezza e Prestazioni**: Forte validazione input (prevenzione XSS e SQL Injection in PDO ready style), password hashing sicura.

---

## ⚙️ Accessibilità & Design (Il Cuore del Progetto)

> *"Sfatare il mito del brutto anatroccolo del sito accessibile dimostrando che è un castello di carta basato su preconcetti e pigrizia".*

Progettato per l'eccellenza, SailUP incarna gli standard del concorso **"Accattivante Accessibile"**, combinando un'esperienza utente visivamente magnifica (UX funzionale) con un'accessibilità inflessibile:
- **Markup Semantico** e architettura ad albero ottimizzata.
- Utilizzo integrato di `aria-attributes` dinamici.
- Introduzione di _Skip Links_ nel payload per scorrere rapidamente le info.

---

## 🚀 Installazione e Utilizzo

### Prerequisiti
- PHP 7.4+
- MySQL 5.7+
- Server web (Apache/Nginx/ecc.)

### Procedura Rapida

1. **Clona la Repository:**
   ```bash
   git clone https://github.com/tuousername/SailUP.git
   cd SailUP
   ```

2. **Setup Database:** (Utilizza il _dump_ situato sotto `db/dbiasuzz.sql`)
   ```bash
   mysql -u root -p < db/dbiasuzz.sql
   ```

3. **Configurazione Connection Strings:**
   Modifica i puntamenti nel file `config/conf.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'tuo_utente');
   define('DB_PASS', 'tua_password');
   define('DB_NAME', 'dbiasuzz');
   ```

4. **Start & Naviga:**
   Avvia un locale development server:
   ```bash
   php -S localhost:8000
   ```
   **Account Dimostrativi (per Reviewer)**:
   - *Amministratore:* `admin` | `admin`
   - *Cliente Semplice:* `user` | `user`

---

## 👥 Autori

- 👨‍💻 **Davide Biasuzzi** (Backend Dev)
- 👨‍💻 **Francesco Marcon** (Frontend Dev)
- 👨‍💻 **Alberto Reginato** (Frontend Dev)

Iniziativa ideata e valutata in seno al corso di **Tecnologie Web (2025/2026)** dell'Università degli Studi di Padova.

<div align="center">

**⚓ Fatto con il cuore... e tanto codice.**
⭐ Se ti è piaciuto il progetto, lascia una stella! ⭐

</div>
