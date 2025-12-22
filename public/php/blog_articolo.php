<?php

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

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

function buildArticleExtraList(array|bool $extras): string {
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

$db = new DBConnection();
$articleId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$articleId) {
    header('Location: blog.php');
    exit;
}

$article = $db->getArticoloBlogCompleto($articleId);
if (!$article) {
    http_response_code(404);
    echo buildPage('../pages/404.html', $_SERVER['PHP_SELF']);
    exit;
}

$contentExtras = buildArticleExtraList($db->getArticoloBlogExtra($articleId));

$articleTitle = $article['Titolo'] ?? 'Articolo SailUP';
$articleSummary = $article['Descrizione_Breve'] ?? 'Scopri un nuovo racconto di mare firmato SailUP.';
$articleContent = formatArticleContent($article['Contenuto'] ?? '');

try {
    $articleDate = new DateTime($article['Data_Pubblicazione']);
} catch (Throwable) {
    $articleDate = new DateTime();
}

$articleDateIso = $articleDate->format('Y-m-d\TH:i:sP');
$articleDateFormatted = formatItalianDate($articleDate);
$readingTime = max(1, (int) ($article['Tempo_Lettura'] ?? 0));

$articleImageSrc = $article['Articolo_URL'] ?: '../img/placeholder.png';
$articleImageAlt = $article['Articolo_Alt'] ?: 'Immagine per ' . $articleTitle;

$authorName = trim(($article['Autore_Nome'] ?? '') . ' ' . ($article['Autore_Cognome'] ?? ''));
if ($authorName === '') {
    $authorName = 'Autore SailUP';
}

$authorRole = ($article['Autore_Is_Admin'] ?? 0) ? 'Team SailUP' : 'Skipper SailUP';
$registrationYear = '';
if (!empty($article['Autore_Data_Registrazione'])) {
    try {
        $registrationYear = (new DateTime($article['Autore_Data_Registrazione']))->format('Y');
    } catch (Throwable) {
    }
}
$authorDescription = 'Collabora con SailUP dal ' . ($registrationYear ?: 'primo equipaggio') . ' e condivide rotte curate per chi ama il mare.';
$authorImageSrc = $article['Autore_URL'] ?: '../img/placeholder.png';
$authorImageAlt = $article['Autore_Alt'] ?: 'Foto profilo di ' . $authorName;

$html = buildPage('../pages/blog_articolo.html', $_SERVER['PHP_SELF']);

$placeholders = [
    '[ARTICLE_TITLE]' => htmlspecialchars($articleTitle, ENT_QUOTES, 'UTF-8'),
    '[BREADCRUMB_TITLE]' => htmlspecialchars($articleTitle, ENT_QUOTES, 'UTF-8'),
    '[ARTICLE_DESCRIPTION]' => htmlspecialchars($articleSummary, ENT_QUOTES, 'UTF-8'),
    '[ARTICLE_SUMMARY]' => htmlspecialchars($articleSummary, ENT_QUOTES, 'UTF-8'),
    '[ARTICLE_BODY_TITLE]' => htmlspecialchars('Approfondimento', ENT_QUOTES, 'UTF-8'),
    '[ARTICLE_CONTENT]' => $articleContent,
    '[ARTICLE_IMAGE_SRC]' => htmlspecialchars($articleImageSrc, ENT_QUOTES, 'UTF-8'),
    '[ARTICLE_IMAGE_ALT]' => htmlspecialchars($articleImageAlt, ENT_QUOTES, 'UTF-8'),
    '[ARTICLE_DATE_ISO]' => $articleDateIso,
    '[ARTICLE_DATE_FORMATTED]' => htmlspecialchars($articleDateFormatted, ENT_QUOTES, 'UTF-8'),
    '[ARTICLE_READING_TIME]' => $readingTime,
    '[AUTHOR_IMAGE_SRC]' => htmlspecialchars($authorImageSrc, ENT_QUOTES, 'UTF-8'),
    '[AUTHOR_IMAGE_ALT]' => htmlspecialchars($authorImageAlt, ENT_QUOTES, 'UTF-8'),
    '[AUTHOR_FULL_NAME]' => htmlspecialchars($authorName, ENT_QUOTES, 'UTF-8'),
    '[AUTHOR_ROLE]' => htmlspecialchars($authorRole, ENT_QUOTES, 'UTF-8'),
    '[AUTHOR_DESCRIPTION]' => htmlspecialchars($authorDescription, ENT_QUOTES, 'UTF-8'),
    '[ARTICLE_EXTRA_LIST]' => $contentExtras,
];

echo str_replace(array_keys($placeholders), array_values($placeholders), $html);