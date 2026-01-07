<?php
require_once '../../config/pages.php';
require_once __DIR__ . '/session/session.php';

function buildHeader($phpSelf) {
    $headerTemplatePath = __DIR__ . '/../public/pages/elementi_semantici/header.html';
    $headerTemplate = file_get_contents($headerTemplatePath);

    // Calcola il percorso relativo dalla directory dello script a public/php/
    // Gli script PHP sono in public/php/, quindi i link puntano a ../php/
    $relativePath = '../php/';

    // Determina la pagina corrente basata su basename di phpSelf
    $current = basename($phpSelf, '.php');

    // Usa la mappa unica delle pagine
    global $pages;

    // Funzione per creare item
    function createHeaderItem($key, $label, $current, $relativePath, $pages, $lang = '', $class = '') {
        $langAttr = $lang ? ' lang="' . $lang . '"' : '';
        $classAttr = $class ? ' class="' . $class . '"' : '';
        if ($current == $key) {
            return '<li aria-current="page"' . $langAttr . '>' . $label . '</li>';
        } else {
            return '<li><a href="' . $relativePath . $pages[$key] . '"' . $langAttr . $classAttr . '>' . $label . '</a></li>';
        }
    }

    // Main menu items
    $homeLi = createHeaderItem('index', 'Home', $current, $relativePath, $pages, 'en');
    $noleggioLi = createHeaderItem('catalogo_noleggio', 'Noleggio', $current, $relativePath, $pages);
    $esperienzeLi = createHeaderItem('catalogo_esperienze', 'Esperienze', $current, $relativePath, $pages);
    $blogLi = createHeaderItem('blog', 'Blog', $current, $relativePath, $pages, 'en');
    $chiSiamoLi = createHeaderItem('chi_siamo', 'Chi Siamo', $current, $relativePath, $pages);

    // Mobile menu items (same as main, plus login/logout)
    $mobileHomeLi = createHeaderItem('index', 'Home', $current, $relativePath, $pages, 'en');
    $mobileNoleggioLi = createHeaderItem('catalogo_noleggio', 'Noleggio', $current, $relativePath, $pages);
    $mobileEsperienzeLi = createHeaderItem('catalogo_esperienze', 'Esperienze', $current, $relativePath, $pages);
    $mobileBlogLi = createHeaderItem('blog', 'Blog', $current, $relativePath, $pages, 'en');
    $mobileChiSiamoLi = createHeaderItem('chi_siamo', 'Chi Siamo', $current, $relativePath, $pages);

    // Sostituisci placeholder
    $header = str_replace('[HOME LI]', $homeLi, $headerTemplate);
    $header = str_replace('[NOLEGGIO LI]', $noleggioLi, $header);
    $header = str_replace('[ESPERIENZE LI]', $esperienzeLi, $header);
    $header = str_replace('[BLOG LI]', $blogLi, $header);
    $header = str_replace('[CHI_SIAMO LI]', $chiSiamoLi, $header);
    $header = str_replace('[MOBILE HOME LI]', $mobileHomeLi, $header);
    $header = str_replace('[MOBILE NOLEGGIO LI]', $mobileNoleggioLi, $header);
    $header = str_replace('[MOBILE ESPERIENZE LI]', $mobileEsperienzeLi, $header);
    $header = str_replace('[MOBILE BLOG LI]', $mobileBlogLi, $header);
    $header = str_replace('[MOBILE CHI_SIAMO LI]', $mobileChiSiamoLi, $header);

    // Pulsante unico in base allo stato
    if (isLogged()) {
        if (isAdmin()) {
            $loginLink = '<a href="' . $relativePath . 'admin.php" class="btn-layout">Admin</a>';
            $mobileLoginLi = '<li><a href="' . $relativePath . 'admin.php">Admin</a></li>';
        } else {
            $loginLink = '<a href="' . $relativePath . 'profilo.php" class="btn-layout">Profilo</a>';
            $mobileLoginLi = '<li><a href="' . $relativePath . 'profilo.php">Profilo</a></li>';
        }
    } else {
        if ($current == 'login') {
            $loginLink = '<span lang="en" class="btn-layout">Login</span>';
        } else {
            $loginLink = '<a href="' . $relativePath . $pages['login'] . '" lang="en" class="btn-layout">Login</a>';
        }
        $mobileLoginLi = createHeaderItem('login', 'Login / Registrati', $current, $relativePath, $pages, 'en', 'menu-login');
    }
    $header = str_replace('[LOGIN LINK]', $loginLink, $header);
    $header = str_replace('[MOBILE LOGIN LI]', $mobileLoginLi, $header);

    return $header;
}

function buildFooter($phpSelf) {
    $footerTemplatePath = __DIR__ . '/../public/pages/elementi_semantici/footer.html';
    $footerTemplate = file_get_contents($footerTemplatePath);

    // Calcola il percorso relativo
    $relativePath = '../php/';

    // Pagina corrente
    $current = basename($phpSelf, '.php');

    // Usa la mappa unica delle pagine
    global $pages;

    // Funzione per creare item
    function createFooterItem($key, $label, $current, $relativePath, $pages, $lang = '') {
        $langAttr = $lang ? ' lang="' . $lang . '"' : '';
        if ($current == $key) {
            return '<li aria-current="page"' . $langAttr . '>' . $label . '</li>';
        } else {
            return '<li><a href="' . $relativePath . $pages[$key] . '"' . $langAttr . '>' . $label . '</a></li>';
        }
    }

    // Home item
    $homeLi = createFooterItem('index', 'Home', $current, $relativePath, $pages, 'en');

    // Altri item
    $noleggioLi = createFooterItem('catalogo_noleggio', 'Noleggio', $current, $relativePath, $pages);
    $esperienzeLi = createFooterItem('catalogo_esperienze', 'Esperienze', $current, $relativePath, $pages);
    $blogLi = createFooterItem('blog', 'Blog', $current, $relativePath, $pages, 'en');
    $chiSiamoLi = createFooterItem('chi_siamo', 'Chi Siamo', $current, $relativePath, $pages);
    $faqLi = createFooterItem('faq', 'Domande Frequenti (<abbr title="Frequently Asked Questions" lang="en">FAQ</abbr>)', $current, $relativePath, $pages);
    $privacyLi = createFooterItem('privacy', 'Privacy Policy', $current, $relativePath, $pages, 'en');
    $cookieLi = createFooterItem('cookie', '<span lang="en">Cookie</span> Policy', $current, $relativePath, $pages);

    // Recupera l'Anno corrente
    $annoCorrente = date('Y');

    // Sostituisci
    $footer = str_replace('[HOME LI]', $homeLi, $footerTemplate);
    $footer = str_replace('[NOLEGGIO LI]', $noleggioLi, $footer);
    $footer = str_replace('[ESPERIENZE LI]', $esperienzeLi, $footer);
    $footer = str_replace('[BLOG LI]', $blogLi, $footer);
    $footer = str_replace('[CHI_SIAMO LI]', $chiSiamoLi, $footer);
    $footer = str_replace('[FAQ LI]', $faqLi, $footer);
    $footer = str_replace('[PRIVACY LI]', $privacyLi, $footer);
    $footer = str_replace('[COOKIE LI]', $cookieLi, $footer);
    $footer = str_replace('[anno_corrente]', $annoCorrente, $footer);

    return $footer;
}

function buildPage($templatePath, $phpSelf) {
    $html = file_get_contents($templatePath);
    $header = buildHeader($phpSelf);
    $footer = buildFooter($phpSelf);
    $html = str_replace('[HEADER]', $header, $html);
    $html = str_replace('[FOOTER]', $footer, $html);
    return $html;
}

/**
 * ritorna l'url dell'avatar utente se presente su disco altrimemti uso placeholder di default
 */
function getProfileImageUrl(array $user = []): string {
    $default = 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=200&q=80';
    $userId = (int)($user['IDUtente'] ?? 0);
    if ($userId <= 0) {
        return $default;
    }

    $uploadDir = __DIR__ . '/../public/uploads/avatars';

    //se in sessione c'è il nome file, prova quello
    if (!empty($user['AvatarFile'])) {
        $candidate = $uploadDir . '/' . basename($user['AvatarFile']);
        if (file_exists($candidate)) {
            return '../uploads/avatars/' . basename($candidate) . '?v=' . filemtime($candidate);
        }
    }

    // cerca file salvati con pattern user_<id>.<ext>
    foreach (['webp', 'jpg', 'jpeg', 'png'] as $ext) {
        $path = $uploadDir . '/user_' . $userId . '.' . $ext;
        if (file_exists($path)) {
            return '../uploads/avatars/' . basename($path) . '?v=' . filemtime($path);
        }
    }

    return $default;
}

/**
 * Restituisce un messaggio HTML composto da paragrafi puliti.
 */
function buildParagraphsFromText(?string $text, string $emptyMessage = 'Contenuto non disponibile.'): string {
    $cleaned = trim((string) $text);
    if ($cleaned === '') {
        return '<p>' . htmlspecialchars($emptyMessage, ENT_QUOTES) . '</p>';
    }

    $lines = preg_split('/\r\n|\r|\n/', $cleaned);
    $paragraphs = array_filter(array_map('trim', (array) $lines), fn($value) => $value !== '');

    if (empty($paragraphs)) {
        return '<p>' . htmlspecialchars($emptyMessage, ENT_QUOTES) . '</p>';
    }

    $html = '';
    foreach ($paragraphs as $paragraph) {
        $html .= '<p>' . htmlspecialchars($paragraph, ENT_QUOTES) . '</p>';
    }

    return $html;
}

/**
 * Formatta un numero decimale con la virgola italiana e rimuove gli zeri finali.
 */
function formatDecimalNumber(?string $value, int $decimals = 2): string {
    if ($value === null || $value === '') {
        return '—';
    }

    $formatted = number_format((float) $value, $decimals, ',', '.');
    if (strpos($formatted, ',') !== false) {
        $formatted = rtrim($formatted, '0');
        $formatted = rtrim($formatted, ',');
    }

    return $formatted;
}

/**
 * Formattta un valore monetario senza decimali (#) per i prezzi "da".
 */
function formatPriceValue(?string $value): string {
    if ($value === null || $value === '') {
        return '—';
    }

    return number_format((float) $value, 0, ',', '.');
}

/**
 * Rende una stringa numerica con due decimali e virgole italiane.
 */
function formatCurrencyWithDecimals(?string $value): string {
    if ($value === null || $value === '') {
        return '';
    }

    return number_format((float) $value, 2, ',', '.');
}

/**
 * Calcola il prezzo totale per un noleggio.
 */
function calcolaPrezzoNoleggio(float $prezzoBase, string $dataInizio, string $dataFine, array $extraSelezionati, array $extraDisponibili, bool $skipperRichiesto): float {
    // Calcolo giorni di noleggio
    $dateStart = new DateTime($dataInizio);
    $dateEnd = new DateTime($dataFine);
    $giorni = $dateStart->diff($dateEnd)->days + 1; // +1 perché include entrambi i giorni

    $prezzoBaseGiorni = $prezzoBase * $giorni;

    // Calcolo extra - cerco per IDExtra
    $prezzoExtra = 0;
    foreach ($extraSelezionati as $extraId) {
        foreach ($extraDisponibili as $extraItem) {
            if (isset($extraItem['IDExtra']) && (int)$extraItem['IDExtra'] === (int)$extraId) {
                $prezzoExtra += (float) ($extraItem['Prezzo_Extra'] ?? 0);
                break;
            }
        }
    }

    // Calcolo +10% skipper (solo sul prezzo base)
    $prezzoSkipper = 0;
    if ($skipperRichiesto) {
        $prezzoSkipper = $prezzoBaseGiorni * 0.10;
    }

    return $prezzoBaseGiorni + $prezzoExtra + $prezzoSkipper;
}

/**
 * Calcola il prezzo totale per un'esperienza.
 */
function calcolaPrezzoEsperienza(float $prezzoBase, array $extraSelezionati, array $extraDisponibili, bool $pickupRichiesto): float {
    // Calcolo extra - cerco per IDExtra
    $prezzoExtra = 0;
    foreach ($extraSelezionati as $extraId) {
        foreach ($extraDisponibili as $extraItem) {
            if (isset($extraItem['IDExtra']) && (int)$extraItem['IDExtra'] === (int)$extraId) {
                $prezzoExtra += (float) ($extraItem['Prezzo_Extra'] ?? 0);
                break;
            }
        }
    }

    // Calcolo +10% pickup (solo sul prezzo base)
    $prezzoPickup = 0;
    if ($pickupRichiesto) {
        $prezzoPickup = $prezzoBase * 0.10;
    }

    return $prezzoBase + $prezzoExtra + $prezzoPickup;
}

/**
 * Costruisce un elenco HTML da un array di righe.
 */
function buildItemsList(array $items, string $valueKey, string $emptyMessage, ?callable $formatter = null): string {
    $filtered = array_filter($items, fn($row) => !empty($row[$valueKey]));
    if (empty($filtered)) {
        return '<li>' . htmlspecialchars($emptyMessage, ENT_QUOTES) . '</li>';
    }

    $html = '';
    foreach ($filtered as $row) {
        if ($formatter !== null) {
            $content = $formatter($row);
        } else {
            $content = htmlspecialchars($row[$valueKey], ENT_QUOTES);
        }

        $html .= '<li>' . $content . '</li>';
    }

    return $html;
}

/**
 * Ritorna il placeholder condiviso dagli articoli nel caso in cui manchi un media.
 */
function getPlaceholderImage(): string {
    return '../img/placeholder.png';
}

/**
 * Risolve un URL immagine, utilizzando il placeholder se necessario.
 */
function resolveImageUrl(?string $url): string {
    $trimmed = trim((string) $url);
    return $trimmed !== '' ? $trimmed : getPlaceholderImage();
}

/**
 * Genera l'HTML di una card prodotto semplice (usata nell'homepage).
 * 
 * @param array $prodotto Dati del prodotto dal database
 * @param string $tipo Tipo di prodotto: 'noleggio' o 'experience'
 * @return string HTML della card
 */
function buildSimpleProductCard(array $prodotto, string $tipo = 'noleggio'): string {
    $idProdotto = $prodotto['IDProdotto'] ?? '';
    if ($idProdotto === '') {
        return '';
    }

    $imageUrl = resolveImageUrl($prodotto['URL_Media'] ?? null);
    $altText = $prodotto['Testo_Alternativo'] ?? 'Immagine non disponibile';
    $productName = htmlspecialchars($prodotto['Nome_Prodotto'] ?? 'Prodotto', ENT_QUOTES);

    // Determina URL di dettaglio e testo CTA in base al tipo
    if ($tipo === 'experience') {
        $detailUrl = 'dettaglio_esperienza.php?id=' . rawurlencode($idProdotto);
        $ctaText = 'Prenota ora &rarr;';
        $ariaLabel = 'Prenota ' . $productName;
    } else {
        $detailUrl = 'dettaglio_barca.php?id=' . rawurlencode($idProdotto);
        $ctaText = 'Scopri di più &rarr;';
        $ariaLabel = 'Vedi dettagli ' . $productName;
    }

    // Attributo loading per esperienze
    $loadingAttr = ($tipo === 'experience') ? ' loading="lazy"' : '';

    return '<article class="product-card">
          <img class="product-card-image" src="' . htmlspecialchars($imageUrl, ENT_QUOTES) . '" alt="' . htmlspecialchars($altText, ENT_QUOTES) . '"' . $loadingAttr . '>
          <div class="product-card-content">
            <div class="product-header">
              <h3 class="product-title">' . $productName . '</h3>
            </div>
            <div class="product-footer">
              <a href="' . htmlspecialchars($detailUrl, ENT_QUOTES) . '" class="product-cta" aria-label="' . htmlspecialchars($ariaLabel, ENT_QUOTES) . '">' . $ctaText . '</a>
            </div>
          </div>
        </article>';
}

/**
 * Genera l'HTML di una card per il catalogo noleggio.
 * 
 * @param array $prodotto Dati del prodotto dal database
 * @return string HTML della card
 */
function buildNoleggioCatalogCard(array $prodotto): string {
    $idProdotto = $prodotto['IDProdotto'] ?? '';
    if ($idProdotto === '') {
        return '';
    }

    $imageUrl = resolveImageUrl($prodotto['URL_Media'] ?? null);
    $altText = $prodotto['Testo_Alternativo'] ?? 'Immagine non disponibile';
    $productName = htmlspecialchars($prodotto['Nome_Prodotto'] ?? 'Prodotto', ENT_QUOTES);
    $description = htmlspecialchars($prodotto['Descrizione_Breve'] ?? 'Descrizione non disponibile.', ENT_QUOTES);

    // Badge tipologia
    $badgeTextRaw = $prodotto['Tipologia_Prodotto'] ?? 'Noleggio';
    $badgeText = htmlspecialchars($badgeTextRaw, ENT_QUOTES);
    $badgeKey = strtolower($badgeTextRaw);
    $badgeClass = 'badge-motore';
    if (strpos($badgeKey, 'vela') !== false) {
        $badgeClass = 'badge-vela';
    } elseif (strpos($badgeKey, 'gommone') !== false) {
        $badgeClass = 'badge-gommone';
    } elseif (strpos($badgeKey, 'motore') !== false) {
        $badgeClass = 'badge-motore';
    }

    // Lunghezza barca
    $lengthValue = $prodotto['Lunghezza_Barca_Metri'];
    $formattedLength = '—';
    if ($lengthValue !== null && $lengthValue !== '') {
        $formattedLength = number_format((float) $lengthValue, 2, ',', '.');
        $formattedLength = rtrim(rtrim($formattedLength, '0'), ',');
        $formattedLength .= 'm';
    }

    // Posti totali
    $postiTotali = isset($prodotto['Posti_Totali']) ? (int) $prodotto['Posti_Totali'] : null;
    $postiDescrizione = $postiTotali !== null ? $postiTotali . ' posti' : '—';

    // Patente
    $richiedePatente = filter_var($prodotto['Richiede_Patente'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    $richiedePatente = $richiedePatente ?? false;
    $patenteIcon = $richiedePatente ? '🎫' : '✅';
    $patenteLabel = $richiedePatente ? 'Patente Richiesta' : 'Patente non Richiesta';

    // Prezzo
    $prezzoBase = isset($prodotto['Prezzo_Base']) ? number_format((float) $prodotto['Prezzo_Base'], 0, ',', '.') : '—';

    $detailUrl = 'dettaglio_barca.php?id=' . rawurlencode($idProdotto);

    return '<article class="product-card">
        <img class="product-card-image" src="' . htmlspecialchars($imageUrl, ENT_QUOTES) . '" alt="' . htmlspecialchars($altText, ENT_QUOTES) . '" width="400" height="267" loading="lazy">
        <div class="product-card-content">
            <div class="product-header">
                <h3 class="product-title">
                    ' . $productName . '
                </h3>
                <span class="product-badge ' . $badgeClass . '">' . $badgeText . '</span>
            </div>

            <p class="product-description">
                ' . $description . '
            </p>

            <ul class="product-specs">
                <li class="spec-item">
                    <span class="spec-icon" aria-hidden="true">📏</span>
                    ' . $formattedLength . '
                </li>
                <li class="spec-item">
                    <span class="spec-icon" aria-hidden="true">👥</span>
                    ' . $postiDescrizione . '
                </li>
                <li class="spec-item">
                    <span class="spec-icon" aria-hidden="true">' . $patenteIcon . '</span>
                    ' . $patenteLabel . '
                </li>
            </ul>

            <div class="product-footer">
                <div class="product-price">
                    <span class="price-label">da</span>
                    <span class="price-value">' . $prezzoBase . '€</span>
                    <span class="price-period">/giorno</span>
                </div>
                <a href="' . $detailUrl . '" class="product-cta" aria-label="Vedi dettagli ' . $productName . '">
                    Vedi dettagli →
                </a>
            </div>
        </div>
    </article>';
}

/**
 * Genera l'HTML di una card per il catalogo esperienze.
 * 
 * @param array $prodotto Dati del prodotto dal database
 * @param array $lingueDisponibili Array con le lingue disponibili per questo prodotto
 * @return string HTML della card
 */
function buildExperienceCatalogCard(array $prodotto, array $lingueDisponibili = []): string {
    $idProdotto = $prodotto['IDProdotto'] ?? '';
    if ($idProdotto === '') {
        return '';
    }

    $imageUrl = resolveImageUrl($prodotto['URL_Media'] ?? null);
    $altText = $prodotto['Testo_Alternativo'] ?? 'Immagine non disponibile';
    $productName = htmlspecialchars($prodotto['Nome_Prodotto'] ?? 'Esperienza', ENT_QUOTES);
    $description = htmlspecialchars($prodotto['Descrizione_Breve'] ?? 'Descrizione non disponibile.', ENT_QUOTES);

    // Badge tipologia
    $tipologiaRaw = $prodotto['Tipologia_Prodotto'] ?? 'Tour';
    $badgeText = htmlspecialchars($tipologiaRaw, ENT_QUOTES);
    $badgeSlug = strtolower($tipologiaRaw);
    if (strpos($badgeSlug, 'aperitivo') !== false) {
        $badgeClass = 'badge-aperitivo';
    } elseif (strpos($badgeSlug, 'escursione') !== false) {
        $badgeClass = 'badge-escursione';
    } elseif (strpos($badgeSlug, 'tour') !== false) {
        $badgeClass = 'badge-tour';
    } else {
        $badgeClass = 'badge-tour';
    }

    // Posti totali
    $postiTotali = isset($prodotto['Posti_Totali']) ? (int) $prodotto['Posti_Totali'] : null;
    $postiDescrizione = $postiTotali !== null ? $postiTotali . ' posti' : '—';

    // Accessibilità
    $accessibile = filter_var($prodotto['Accessibile_Disabili'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    $accessibileLabel = $accessibile ? 'Accessibile a tutti' : 'Accessibilità limitata';

    // Lingue disponibili
    $lingueNomi = [];
    if (is_array($lingueDisponibili)) {
        foreach ($lingueDisponibili as $lingua) {
            if (!empty($lingua['Nome'])) {
                $lingueNomi[] = htmlspecialchars($lingua['Nome'], ENT_QUOTES);
            }
        }
    }
    $lingueDescrizione = !empty($lingueNomi) ? implode(', ', $lingueNomi) : 'Lingue in definizione';

    // Prezzo
    $price = isset($prodotto['Prezzo_Base']) ? number_format((float) $prodotto['Prezzo_Base'], 0, ',', '.') : '—';

    $detailUrl = 'dettaglio_esperienza.php?id=' . rawurlencode($idProdotto);

    return '<article class="product-card">
        <img class="product-card-image" src="' . htmlspecialchars($imageUrl, ENT_QUOTES) . '" alt="' . htmlspecialchars($altText, ENT_QUOTES) . '" width="400" height="267" loading="lazy">
        <div class="product-card-content">
          <div class="product-header">
            <h3 class="product-title">
              ' . $productName . '
            </h3>
            <span class="product-badge ' . $badgeClass . '">' . $badgeText . '</span>
          </div>

          <p class="product-description">
            ' . $description . '
          </p>

          <ul class="product-specs">
            <li class="spec-item">
              <span class="spec-icon" aria-hidden="true">👥</span>
              ' . $postiDescrizione . '
            </li>
            <li class="spec-item">
              <span class="spec-icon" aria-hidden="true">♿</span>
              ' . $accessibileLabel . '
            </li>
            <li class="spec-item">
              <span class="spec-icon" aria-hidden="true">🌐</span>
              Lingue: ' . $lingueDescrizione . '
            </li>
          </ul>

          <div class="product-footer">
            <div class="product-price">
              <span class="price-label">da</span>
              <span class="price-value">' . $price . '€</span>
              <span class="price-period">/tour</span>
            </div>
            <a href="' . $detailUrl . '" class="product-cta" aria-label="Vedi dettagli ' . $productName . '">
              Vedi dettagli →
            </a>
          </div>
        </div>
      </article>';
}

/**
 * Genera l'HTML di una card articolo blog.
 * 
 * @param array $articolo Dati dell'articolo dal database
 * @return string HTML della card
 */
function buildBlogArticleCard(array $articolo): string {
    $idArticolo = $articolo['IDArticolo'] ?? '';
    if ($idArticolo === '') {
        return '';
    }

    $imageUrl = resolveImageUrl($articolo['URL_Media'] ?? null);
    $altText = $articolo['Testo_Alternativo'] ?? 'Immagine articolo non disponibile';
    $titolo = htmlspecialchars($articolo['Titolo'] ?? 'Articolo SailUP', ENT_QUOTES);
    $descrizione = htmlspecialchars($articolo['Descrizione_Breve'] ?? 'Nessuna descrizione disponibile.', ENT_QUOTES);
    $detailUrl = 'blog_articolo.php?id=' . rawurlencode($idArticolo);

    return '<article class="product-card">
        <img class="product-card-image" src="' . htmlspecialchars($imageUrl, ENT_QUOTES) . '" alt="' . htmlspecialchars($altText, ENT_QUOTES) . '" width="400" height="267" loading="lazy">
        <div class="product-card-content">
          <div class="product-header">
            <h3 class="product-title">
              ' . $titolo . '
            </h3>
          </div>

          <p class="product-description">
            ' . $descrizione . '
          </p>

          <div class="product-footer">
            <a href="' . $detailUrl . '" class="product-cta" aria-label="Leggi l\'articolo: ' . $titolo . '">
              Leggi tutto →
            </a>
          </div>
        </div>
      </article>';
}

/**
 * Genera l'HTML delle liste di consigli extra per gli articoli del blog.
 * Gli extra sono raggruppati per titolo.
 * 
 * @param array|bool $extras Array di consigli extra dal database
 * @return string HTML delle liste
 */
function buildArticleExtraList($extras): string {
    if ($extras === false || empty($extras)) {
        return '<p class="catalog-empty">Non ci sono consigli extra per questo articolo al momento.</p>';
    }

    $html = '';
    $currentTitle = '';
    foreach ($extras as $extra) {
        $title = $extra['Titolo'] ?? '';
        $element = $extra['Elemento'] ?? '';
        if ($title !== $currentTitle) {
            if ($currentTitle !== '') {
                $html .= '</ul></div>';
            }
            $currentTitle = $title;
            $html .= '<div class="amenity-box included">';
            $html .= '<h3>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h3>';
            $html .= '<ul>';
        }
        $html .= '<li>' . htmlspecialchars($element, ENT_QUOTES, 'UTF-8') . '</li>';
    }

    if ($currentTitle !== '') {
        $html .= '</ul></div>';
    }

    return $html ?: '<p class="catalog-empty">Non ci sono consigli extra per questo articolo al momento.</p>';
}

/**
 * Genera l'HTML delle checkbox per gli extra (prodotti).
 * Gli extra obbligatori vengono pre-selezionati e disabilitati, con un hidden input per inviarli.
 * 
 * @param array|bool $extras Array degli extra dal database
 * @return string HTML delle checkbox
 */
function buildExtraCheckboxes($extras): string {
    if ($extras === false || !is_array($extras) || count($extras) === 0) {
        return '';
    }

    $html = '';
    foreach ($extras as $index => $extraItem) {
        $extraName = htmlspecialchars($extraItem['Nome_Extra'] ?? '', ENT_QUOTES);
        $extraPrice = $extraItem['Prezzo_Extra'] ?? 0;
        $extraId = $extraItem['IDExtra'] ?? $index;
        $isOptional = isset($extraItem['Opzionale']) ? filter_var($extraItem['Opzionale'], FILTER_VALIDATE_BOOLEAN) : true;
        $formattedPrice = number_format((float) $extraPrice, 0, ',', '.');
        
        $checkboxId = 'extra-' . $extraId;
        $checkedAttr = !$isOptional ? 'checked' : '';
        $disabledAttr = !$isOptional ? 'disabled' : '';
        $priceText = $formattedPrice !== '' && $formattedPrice !== '0' ? '+' . htmlspecialchars($formattedPrice, ENT_QUOTES) . ' €' : '';
        
        $html .= '<div class="form-check checkbox-highlight">';
        $html .= '<input type="checkbox" id="' . $checkboxId . '" name="extras[]" value="' . $extraId . '" ' . $checkedAttr . ' ' . $disabledAttr . '>';
        // Aggiungi hidden input per extra obbligatori (disabled non viene inviato)
        if (!$isOptional) {
            $html .= '<input type="hidden" name="extras[]" value="' . $extraId . '">';
        }
        $html .= '<label for="' . $checkboxId . '">';
        $html .= '<span>' . $extraName . '</span>';
        if ($priceText !== '') {
            $html .= '<span class="text-accent">' . $priceText . '</span>';
        }
        $html .= '</label>';
        $html .= '</div>' . "\n";
    }

    return $html;
}

/**
 * Genera l'HTML delle checkbox per le tipologie (filtro catalogo noleggio).
 * 
 * @param array $tipologieDisponibili Array delle tipologie disponibili
 * @param array $tipologieSelezionate Array delle tipologie già selezionate
 * @return string HTML delle checkbox
 */
function buildTipologieCheckboxes(array $tipologieDisponibili, array $tipologieSelezionate = []): string {
    if (empty($tipologieDisponibili)) {
        return '<p class="filter-empty">Nessuna tipologia disponibile.</p>';
    }

    $html = '';
    foreach ($tipologieDisponibili as $index => $tipologia) {
        $label = htmlspecialchars($tipologia, ENT_QUOTES);
        $inputId = 'tipo-' . preg_replace('/[^a-z0-9]+/i', '-', strtolower($tipologia));
        $inputId = trim($inputId, '-');
        if ($inputId === '') {
            $inputId = 'tipo-' . $index;
        }
        $checked = in_array($tipologia, $tipologieSelezionate, true) ? ' checked' : '';
        $html .= '<label class="filter-checkbox">
              <input type="checkbox" id="' . htmlspecialchars($inputId, ENT_QUOTES) . '" name="tipo[]" value="' . $label . '"' . $checked . '>
              <span>' . $label . '</span>
            </label>';
    }

    return $html;
}

/**
 * Genera l'HTML delle options per le lingue (filtro catalogo esperienze).
 * 
 * @param array $lingueDisponibili Array delle lingue disponibili
 * @param string $linguaSelezionata Codice della lingua già selezionata
 * @return string HTML delle options
 */
function buildLinguaOptions(array $lingueDisponibili, string $linguaSelezionata = ''): string {
    $html = '';
    $indifferenteSelected = $linguaSelezionata === '' ? ' selected' : '';
    $html .= '<option value=""' . $indifferenteSelected . '>Indifferente</option>';
    
    if (empty($lingueDisponibili)) {
        $html .= '<option value="" disabled>Nessuna lingua disponibile</option>';
        return $html;
    }

    foreach ($lingueDisponibili as $lingua) {
        $codice = htmlspecialchars($lingua['Codice'] ?? '', ENT_QUOTES);
        $nome = htmlspecialchars($lingua['Nome'] ?? '', ENT_QUOTES);
        if ($codice === '') {
            continue;
        }
        $selected = ($linguaSelezionata === $lingua['Codice']) ? ' selected' : '';
        $html .= '<option value="' . $codice . '"' . $selected . '>' . $nome . '</option>';
    }

    return $html;
}

/**
 * Formatta una data in formato italiano (es: "5 Gennaio 2026").
 * 
 * @param DateTime $dateTime Oggetto DateTime da formattare
 * @return string Data formattata in italiano
 */
function formatItalianDate(DateTime $dateTime): string {
    $months = [
        1 => 'Gennaio',
        2 => 'Febbraio',
        3 => 'Marzo',
        4 => 'Aprile',
        5 => 'Maggio',
        6 => 'Giugno',
        7 => 'Luglio',
        8 => 'Agosto',
        9 => 'Settembre',
        10 => 'Ottobre',
        11 => 'Novembre',
        12 => 'Dicembre'
    ];

    $month = (int) $dateTime->format('n');
    $day = $dateTime->format('j');
    $year = $dateTime->format('Y');

    return sprintf('%s %s %s', $day, $months[$month] ?? $dateTime->format('F'), $year);
}

/**
 * Formatta il contenuto di un articolo convertendo paragrafi separati da doppie newline in tag <p>.
 * 
 * @param string|null $content Contenuto grezzo dell'articolo
 * @return string HTML del contenuto formattato
 */
function formatArticleContent(?string $content): string {
    $text = trim((string) $content);
    if ($text === '') {
        return '<p>Il contenuto dell\'articolo non è ancora disponibile.</p>';
    }

    $paragraphs = preg_split('/(?:\r?\n){2,}/', $text);
    $html = '';
    foreach ($paragraphs as $paragraph) {
        $paragraph = trim($paragraph);
        if ($paragraph === '') {
            continue;
        }
        $html .= '<p>' . nl2br(htmlspecialchars($paragraph, ENT_QUOTES, 'UTF-8')) . '</p>';
    }

    return $html ?: '<p>Il contenuto dell\'articolo non è ancora disponibile.</p>';
}

//per generare messaggi di feedback in seguito ad azioni come inserimenti/modifiche
function buildFeedbackBlock(string $message, string $class): string {
    if ($message === '' || $class === '') {
        return '';
    }

    return '<div class="' . $class . '" role="status" aria-live="polite">' . htmlspecialchars($message) . '</div>';
}

//genera l html della barra di paginazione
function buildPaginationNav(int $currentPage, int $totalPages, string $baseUrl, array $queryParams, string $ariaLabel): string {
    if ($totalPages <= 1) {
        return '';
    }

    $html = '<nav class="pagination" aria-label="' . htmlspecialchars($ariaLabel, ENT_QUOTES) . '"><ul>';
    for ($i = 1; $i <= $totalPages; $i++) {
        $currentClass = $i === $currentPage ? ' class="current-page"' : '';
        $queryParams['page'] = $i;
        $query = http_build_query($queryParams);
        $url = $baseUrl . '?' . $query;
        $html .= '<li' . $currentClass . '><a href="' . htmlspecialchars($url, ENT_QUOTES) . '">' . $i . '</a></li>';
    }
    $html .= '</ul></nav>';

    return $html;
}

//genera la tabella degli utenti visualizzata dagli admin
function buildAdminUsersRows(array $users): string {
    if (empty($users)) {
        return '<tr><td colspan="5">Nessun utente trovato.</td></tr>';
    }

    $rows = '';
    foreach ($users as $u) {
        $ruolo = !empty($u['Is_Admin'])
            ? '<span class="status-badge active">Admin</span>'
            : '<span class="status-badge completed">Standard</span>';
        $rawDate = $u['Data_Registrazione'] ?? '';
        $dataIscr = $rawDate !== '' ? htmlspecialchars(date('d/m/Y', strtotime($rawDate))) : '—';
        $rows .= '<tr>'
            . '<td data-label="ID">' . htmlspecialchars($u['IDUtente']) . '</td>'
            . '<td data-label="Nome">' . htmlspecialchars(($u['Nome'] ?? '') . ' ' . ($u['Cognome'] ?? '')) . '</td>'
            . '<td data-label="Email">' . htmlspecialchars($u['Email'] ?? '') . '</td>'
            . '<td data-label="Data Iscrizione"><time datetime="' . htmlspecialchars($rawDate) . '">' . $dataIscr . '</time></td>'
            . '<td data-label="Ruolo">' . $ruolo . '</td>'
            . '</tr>';
    }

    return $rows;
}

//builda la tabella dei blog visualizzati dall admin
function buildAdminBlogRows(array $articoli, string $csrfToken): string {
    if (empty($articoli)) {
        return '<tr><td colspan="6">Nessun articolo trovato.</td></tr>';
    }

    $rows = '';
    foreach ($articoli as $a) {
        $statoPub = !empty($a['Pubblicato']);
        $badge = $statoPub
            ? '<span class="status-badge active">Pubblicato</span>'
            : '<span class="status-badge pending">Bozza</span>';
        $dataPub = !empty($a['Data_Pubblicazione']) ? date('d/m/y', strtotime($a['Data_Pubblicazione'])) : '—';
        $titolo = htmlspecialchars($a['Titolo'] ?? '');
        $idArticolo = htmlspecialchars($a['IDArticolo']);
        $actionValue = $statoPub ? 'draft' : 'publish';
        $actionLabel = $statoPub ? 'Imposta bozza' : 'Pubblica';
        $ariaActionLabel = $statoPub ? 'Imposta come bozza' : 'Pubblica';
        $rows .= '<tr>'
            . '<td data-label="ID">' . $idArticolo . '</td>'
            . '<td data-label="Titolo">' . $titolo . '</td>'
            . '<td data-label="Categoria">—</td>'
            . '<td data-label="Data">' . htmlspecialchars($dataPub) . '</td>'
            . '<td data-label="Stato">' . $badge . '</td>'
            . '<td data-label="Azioni" class="actions-cell">'
            . '<form method="post" class="inline-form">'
            . '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken) . '">'
            . '<input type="hidden" name="id_articolo" value="' . $idArticolo . '">'
            . '<input type="hidden" name="action" value="' . $actionValue . '">'
            . '<button type="submit" class="btn-text" aria-label="' . $ariaActionLabel . ' ' . $titolo . '">' . $actionLabel . '</button>'
            . '</form>'
            . '<form method="post" class="inline-form" onsubmit="return confirm(\'Eliminare questo articolo?\');">'
            . '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken) . '">'
            . '<input type="hidden" name="id_articolo" value="' . $idArticolo . '">'
            . '<input type="hidden" name="action" value="delete">'
            . '<button type="submit" class="btn-text danger" aria-label="Elimina articolo ' . $titolo . '">Elimina</button>'
            . '</form>'
            . '</td>'
            . '</tr>';
    }

    return $rows;
}

//builda la tabella prenotazioni visualizzata dall admin
function buildAdminBookingRows(array $pren, string $csrfToken): string {
    if (empty($pren)) {
        return '<tr><td colspan="7">Nessuna prenotazione trovata.</td></tr>';
    }

    $rows = '';
    foreach ($pren as $p) {
        $stato = $p['Stato_Prenotazione'] ?? '';
        $badgeClass = 'pending';
        if ($stato === 'Confermata') {
            $badgeClass = 'active';
        }
        if ($stato === 'Cancellata') {
            $badgeClass = 'cancelled';
        }
        $idPren = htmlspecialchars($p['IDPrenotazione']);
        $rows .= '<tr>'
            . '<td data-label="ID">' . $idPren . '</td>'
            . '<td data-label="Prodotto">' . htmlspecialchars($p['IDProdotto']) . '</td>'
            . '<td data-label="Cliente">' . htmlspecialchars(($p['Utente_Nome'] ?? '') . ' ' . ($p['Utente_Cognome'] ?? '')) . '</td>'
            . '<td data-label="Data"><time datetime="' . htmlspecialchars($p['Data_Ora_Inizio'] ?? '') . '">' . htmlspecialchars($p['Data_Ora_Inizio'] ?? '') . '</time></td>'
            . '<td data-label="Totale">€ ' . htmlspecialchars($p['Prezzo_Totale'] ?? '') . '</td>'
            . '<td data-label="Stato"><span class="status-badge ' . $badgeClass . '">' . htmlspecialchars($stato) . '</span></td>'
            . '<td data-label="Azioni" class="actions-cell">'
            . '<a href="dettaglio_prenotazione.php?id=' . $idPren . '" class="btn-text" aria-label="Vedi dettagli prenotazione ' . $idPren . '">Dettagli</a>'
            . '<form method="post" class="inline-form">'
            . '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken) . '">'
            . '<input type="hidden" name="id_prenotazione" value="' . $idPren . '">'
            . '<input type="hidden" name="action" value="confirm">'
            . '<button type="submit" class="btn-text" aria-label="Conferma prenotazione ' . $idPren . '">Conferma</button>'
            . '</form>'
            . '<form method="post" class="inline-form" onsubmit="return confirm(\'Cancellare questa prenotazione?\');">'
            . '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken) . '">'
            . '<input type="hidden" name="id_prenotazione" value="' . $idPren . '">'
            . '<input type="hidden" name="action" value="cancel">'
            . '<button type="submit" class="btn-text danger" aria-label="Cancella prenotazione ' . $idPren . '">Cancella</button>'
            . '</form>'
            . '</td>'
            . '</tr>';
    }

    return $rows;
}

//builda la tabella prodotti vista da un admin
function buildAdminProductRows(array $prodotti, string $csrfToken): string {
    if (empty($prodotti)) {
        return '<tr><td colspan="7">Nessun prodotto trovato.</td></tr>';
    }

    $rows = '';
    foreach ($prodotti as $p) {
        $tipo = $p['Tipo_Prodotto'] ?? '';
        $tipologia = $p['Tipologia_Prodotto'] ?? ($p['Tipologia_Experience'] ?? '');
        $tipoDisplay = htmlspecialchars($tipo) . ($tipologia ? ' • ' . htmlspecialchars($tipologia) : '');
        $prezzo = isset($p['Prezzo_Base']) ? '€ ' . number_format((float)$p['Prezzo_Base'], 2, ',', '.') : '—';
        $stato = !empty($p['Attivo'])
            ? '<span class="status-badge active">Attivo</span>'
            : '<span class="status-badge cancelled">Disattivo</span>';
        $toggleLabel = !empty($p['Attivo']) ? 'Disattiva' : 'Attiva';
        $toggleClass = !empty($p['Attivo']) ? 'btn-text danger' : 'btn-text';
        $toggleConfirm = !empty($p['Attivo']) ? ' onsubmit="return confirm(\'Disattivare questo prodotto?\');"' : '';
        $idProdotto = htmlspecialchars($p['IDProdotto']);
        $nomeProdotto = htmlspecialchars($p['Nome_Prodotto'] ?? '');
        $rows .= '<tr>'
            . '<td data-label="ID">' . $idProdotto . '</td>'
            . '<td data-label="Nome Prodotto">' . $nomeProdotto . '</td>'
            . '<td data-label="Tipo">' . $tipoDisplay . '</td>'
            . '<td data-label="Dettagli">' . htmlspecialchars($p['Descrizione_Breve'] ?? '—') . '</td>'
            . '<td data-label="Prezzo">' . $prezzo . '</td>'
            . '<td data-label="Stato">' . $stato . '</td>'
            . '<td data-label="Azioni" class="actions-cell">'
            . '<a class="btn-text" href="admin_prodotti_nuovo.php?id=' . $idProdotto . '" aria-label="Modifica ' . $nomeProdotto . '">Modifica</a>'
            . '<form method="post" class="inline-form"' . $toggleConfirm . '>'
            . '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken) . '">'
            . '<input type="hidden" name="id_prodotto" value="' . $idProdotto . '">'
            . '<input type="hidden" name="attivo" value="' . (!empty($p['Attivo']) ? 1 : 0) . '">'
            . '<input type="hidden" name="action" value="toggle">'
            . '<button type="submit" class="' . $toggleClass . '" aria-label="' . htmlspecialchars($toggleLabel) . ' prodotto ' . $nomeProdotto . '">' . htmlspecialchars($toggleLabel) . '</button>'
            . '</form>'
            . '</td>'
            . '</tr>';
    }

    return $rows;
}

//builda la tabella delle prenotazioni di un utente/anche admin se ha fatto prenotazioni personali
function buildProfileBookingRows(array $prenotazioni, string $csrfToken): string {
    if (empty($prenotazioni)) {
        return '<tr><td colspan="6">Nessuna prenotazione trovata.</td></tr>';
    }

    $rowsHtml = '';
    foreach ($prenotazioni as $p) {
        $stato = $p['Stato_Prenotazione'] ?? '';
        $statusAttr = 'active';
        if ($stato === 'Cancellata') {
            $statusAttr = 'cancelled';
        }
        if ($stato === 'Completata' || $stato === 'Conclusa') {
            $statusAttr = 'completed';
        }

        $dataInizio = !empty($p['Data_Ora_Inizio']) ? date('d/m/Y', strtotime($p['Data_Ora_Inizio'])) : '—';
        $dataFine = !empty($p['Data_Ora_Fine']) ? date('d/m/Y', strtotime($p['Data_Ora_Fine'])) : '—';
        $prezzo = number_format((float)($p['Prezzo_Totale'] ?? 0), 2, ',', '.');
        $isCancellable = in_array($statusAttr, ['active', 'pending'], true);
        $idPren = htmlspecialchars($p['IDPrenotazione']);

        $rowsHtml .= '<tr class="booking-row" data-status="' . htmlspecialchars($statusAttr) . '">'
            . '<td>' . $idPren . '</td>'
            . '<td>' . htmlspecialchars($p['IDProdotto']) . '</td>'
            . '<td>' . htmlspecialchars($dataInizio . ' — ' . $dataFine) . '</td>'
            . '<td>€ ' . htmlspecialchars($prezzo) . '</td>'
            . '<td>' . htmlspecialchars($stato) . '</td>'
            . '<td>'
            . ($isCancellable
                ? '<form method="post" class="inline-form" onsubmit="return confirm(\'Annullare questa prenotazione?\');">'
                    . '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken) . '">'
                    . '<input type="hidden" name="action" value="cancel_booking">'
                    . '<input type="hidden" name="booking_id" value="' . $idPren . '">'
                    . '<button type="submit" class="btn-text">Annulla</button>'
                . '</form>'
                : '—'
            )
            . '</td>'
            . '</tr>';
    }

    return $rowsHtml;
}

//builda l extra dei blog
function buildBlogExtraInputs(array $extras): string {
    $extrasHtml = '';
    foreach ($extras as $ex) {
        $extrasHtml .= '<div class="extra-row">'
            . '<div class="form-group">'
            . '<label>Titolo Extra</label>'
            . '<input type="text" name="extra_title[]" value="' . htmlspecialchars($ex['titolo'] ?? '', ENT_QUOTES) . '" placeholder="es. Cosa portare a bordo" />'
            . '</div>'
            . '<div class="form-group">'
            . '<label>Contenuto</label>'
            . '<textarea name="extra_item[]" rows="2" placeholder="Elenco o testo descrittivo">' . htmlspecialchars($ex['elemento'] ?? '') . '</textarea>'
            . '</div>'
            . '</div>';
    }

    if ($extrasHtml === '') {
        $extrasHtml = '<div class="extra-row">'
            . '<div class="form-group">'
            . '<label>Titolo Extra</label>'
            . '<input type="text" name="extra_title[]" placeholder="es. Cosa portare a bordo" />'
            . '</div>'
            . '<div class="form-group">'
            . '<label>Contenuto</label>'
            . '<textarea name="extra_item[]" rows="2" placeholder="Elenco o testo descrittivo"></textarea>'
            . '</div>'
            . '</div>';
    }

    return $extrasHtml;
}
?>
