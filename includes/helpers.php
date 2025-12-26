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

    // Mobile menu items (same as main, plus login)
    $mobileHomeLi = createHeaderItem('index', 'Home', $current, $relativePath, $pages, 'en');
    $mobileNoleggioLi = createHeaderItem('catalogo_noleggio', 'Noleggio', $current, $relativePath, $pages);
    $mobileEsperienzeLi = createHeaderItem('catalogo_esperienze', 'Esperienze', $current, $relativePath, $pages);
    $mobileBlogLi = createHeaderItem('blog', 'Blog', $current, $relativePath, $pages, 'en');
    $mobileChiSiamoLi = createHeaderItem('chi_siamo', 'Chi Siamo', $current, $relativePath, $pages);
    $mobileLoginLi = createHeaderItem('login', 'Login / Registrati', $current, $relativePath, $pages, 'en', 'menu-login');

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
    $header = str_replace('[MOBILE LOGIN LI]', $mobileLoginLi, $header);

    // Link area (login / profilo / admin / logout)
    $userLinks = '';
    if (isLogged()) {
        if (isAdmin()) {
            $userLinks .= '<a href="' . $relativePath . 'admin.php" class="btn-layout">Admin</a>';
        }
        $userLinks .= '<a href="' . $relativePath . 'profilo.php" class="btn-layout">Profilo</a>';
        $userLinks .= '<a href="' . $relativePath . 'logout.php" class="btn-layout">Logout</a>';
    } else {
        if ($current == 'login') {
            $userLinks = '<span lang="en" class="btn-layout">Login</span>';
        } else {
            $userLinks = '<a href="' . $relativePath . $pages['login'] . '" lang="en" class="btn-layout">Login</a>';
        }
    }
    $header = str_replace('[LOGIN LINK]', $userLinks, $header);

    // Mobile login/profile/admin/logout area
    if (isLogged()) {
        $mobileLoginLi = '';
        if (isAdmin()) {
            $mobileLoginLi .= '<li><a href="' . $relativePath . 'admin.php">Admin</a></li>';
        }
        $mobileLoginLi .= '<li><a href="' . $relativePath . 'profilo.php">Profilo</a></li>';
        $mobileLoginLi .= '<li><a href="' . $relativePath . 'logout.php">Logout</a></li>';
    } else {
        $mobileLoginLi = createHeaderItem('login', 'Login / Registrati', $current, $relativePath, $pages, 'en', 'menu-login');
    }
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

    // Altri item (sempre link)
    $faqLi = '<li><a href="' . $relativePath . $pages['faq'] . '">Domande Frequenti (<abbr title="Frequently Asked Questions" lang="en">FAQ</abbr>)</a></li>';
    $privacyLi = '<li><a href="' . $relativePath . $pages['privacy'] . '" lang="en">Privacy Policy</a></li>';
    $cookieLi = '<li><a href="' . $relativePath . $pages['cookie'] . '"><span lang="en">Cookie</span> Policy</a></li>';

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
?>
