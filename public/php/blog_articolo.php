<?php

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

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

$articleTitleRaw = $article['Titolo'] ?? 'Articolo SailUP';
$articleTitleVisual = formatText($articleTitleRaw);
$articleTitleSafe = htmlspecialchars($articleTitleRaw, ENT_QUOTES, 'UTF-8');

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

$articleImageSrc = resolveImageUrl($article['Articolo_URL'] ?? null);
$articleImageAlt = $article['Articolo_Alt'] ?: 'Immagine per ' . $articleTitleSafe;

$authorName = trim(($article['Autore_Nome'] ?? '') . ' ' . ($article['Autore_Cognome'] ?? ''));
if ($authorName === '') {
    $authorName = 'Autore SailUP';
}

$authorRole = ($article['Autore_Is_Admin'] ?? 0) ? 'Team SailUP' : 'Utente SailUP';
$registrationYear = '';
if (!empty($article['Autore_Data_Registrazione'])) {
    try {
        $registrationYear = (new DateTime($article['Autore_Data_Registrazione']))->format('Y');
    } catch (Throwable) {
    }
}
$authorDescription = 'Collabora con SailUP dal ' . ($registrationYear ?: 'primo equipaggio') . ' e condivide rotte curate per chi ama il mare.';
$authorImageSrc = resolveImageUrl($article['Autore_URL'] ?? null);
$authorImageAlt = $article['Autore_Alt'] ?: 'Foto profilo di ' . $authorName;

$html = buildPage('../pages/blog_articolo.html', $_SERVER['PHP_SELF']);

// Genera keywords dinamiche basate sull'articolo del blog
$titleWords = array_filter(array_map('trim', explode(' ', strtolower($articleTitleSafe))));
$keywordParts = array_merge(['blog', 'nautico'], array_slice($titleWords, 0, 5), ['Napoli', 'golfo', 'mare', 'consigli']);
$keywordsContent = implode(', ', array_unique($keywordParts));
$keywords = '<meta name="keywords" content="' . htmlspecialchars($keywordsContent, ENT_QUOTES, 'UTF-8') . '">';

$placeholders = [
    '[ARTICLE_TITLE]' => $articleTitleSafe,
    '[ARTICLE_TITLE_VISUAL]' => $articleTitleVisual,
    '[BREADCRUMB_TITLE]' => $articleTitleSafe,
    '[ARTICLE_DESCRIPTION]' => htmlspecialchars($articleSummary, ENT_QUOTES, 'UTF-8'),
    '[ARTICLE_SUMMARY]' => formatText($articleSummary),
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
    '[KEYWORDS]' => $keywords,
];

echo str_replace(array_keys($placeholders), array_values($placeholders), $html);
