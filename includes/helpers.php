<?php
require_once '../../config/pages.php';

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

    // Login link for header buttons
    if ($current == 'login') {
        $loginLink = '<span lang="en" class="btn-layout">Login</span>';
    } else {
        $loginLink = '<a href="' . $relativePath . $pages['login'] . '" lang="en" class="btn-layout">Login</a>';
    }
    $header = str_replace('[LOGIN LINK]', $loginLink, $header);

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
?>
