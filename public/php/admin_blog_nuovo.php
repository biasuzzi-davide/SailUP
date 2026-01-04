<?php

require_once '../../includes/session/session.php';
require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

requireAdmin();

$db = new DBConnection();
$feedback = '';
$feedbackClass = 'hidden';
$userId = $_SESSION['user']['IDUtente'] ?? null;
$old = [
    'title' => '',
    'excerpt' => '',
    'content' => '',
    'date' => '',
    'category' => '',
    'tags' => '',
    'image' => '',
    'alt' => '',
    'meta_title' => '',
    'meta_desc' => '',
    'status' => 'draft',
    'extras' => [],
    'category' => '',
];

function stimaTempoLettura(string $contenuto): int {
    $parole = str_word_count(strip_tags($contenuto));
    $minuti = (int)ceil($parole / 200);
    return max(1, $minuti);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? null;
    if (!verifyCsrfToken($csrf)) {
        $feedbackClass = 'alert alert-error';
        $feedback = 'Sessione scaduta, ricarica la pagina.';
    } elseif ($userId === null) {
        $feedbackClass = 'alert alert-error';
        $feedback = 'Utente non autenticato.';
    } else {
        $titolo = trim($_POST['post-title'] ?? '');
        $excerpt = trim($_POST['post-excerpt'] ?? '');
        $contenuto = trim($_POST['post-content'] ?? '');
        $dataPub = $_POST['post-date'] ?? '';
        $status = $_POST['post-status'] ?? 'draft';
        $urlImg = trim($_POST['post-image'] ?? '');
        $altImg = trim($_POST['Testo_Alternativo'] ?? '');
        $categoria = trim($_POST['post-category'] ?? '');
        $tags = trim($_POST['post-tags'] ?? '');
        $metaTitle = trim($_POST['post-meta-title'] ?? '');
        $metaDesc = trim($_POST['post-meta-description'] ?? '');
        $extraTitles = $_POST['extra_title'] ?? [];
        $extraItems = $_POST['extra_item'] ?? [];

        $old = [
            'title' => $titolo,
            'excerpt' => $excerpt,
            'content' => $contenuto,
            'date' => $dataPub,
            'category' => $categoria,
            'tags' => $tags,
            'image' => $urlImg,
            'alt' => $altImg,
            'meta_title' => $metaTitle,
            'meta_desc' => $metaDesc,
            'status' => $status,
            'extras' => [],
            'category' => $categoria,
        ];

        $errors = [];
        $categorieAmmesse = ['guide', 'destinazioni', 'consigli', 'eventi', 'sicurezza', 'manutenzione'];
        if ($titolo === '') $errors[] = 'Inserisci il titolo';
        if ($excerpt === '') $errors[] = 'Inserisci l\'estratto';
        if ($contenuto === '') $errors[] = 'Inserisci il contenuto';
        if ($dataPub === '') $errors[] = 'Inserisci la data di pubblicazione';
        if ($categoria === '' || !in_array($categoria, $categorieAmmesse, true)) $errors[] = 'Seleziona una categoria';
        if ($urlImg === '') $errors[] = 'URL immagine obbligatorio';
        if ($altImg === '') $errors[] = 'Testo alternativo obbligatorio';

        $extras = [];
        if (is_array($extraTitles) && is_array($extraItems)) {
            $len = max(count($extraTitles), count($extraItems));
            for ($i = 0; $i < $len; $i++) {
                $t = trim($extraTitles[$i] ?? '');
                $el = trim($extraItems[$i] ?? '');
                if ($t !== '' || $el !== '') {
                    $extras[] = ['titolo' => $t, 'elemento' => $el];
                }
            }
        }
        $old['extras'] = $extras;

        if (empty($errors)) {
            $pubblicato = $status === 'published' || ($_POST['action'] ?? '') === 'publish';
            $tempo = stimaTempoLettura($contenuto);
            $newId = $db->creaArticoloBlog(
                (int)$userId,
                $titolo,
                $excerpt,
                $contenuto,
                $dataPub,
                $pubblicato,
                $tempo
            );

            if ($newId) {
                $db->upsertMediaArticolo((int)$newId, $urlImg, $altImg);
                if (!empty($extras)) {
                    $db->setArticoloBlogExtra((int)$newId, $extras);
                }
                $feedbackClass = 'alert alert-success';
                $feedback = 'Articolo salvato correttamente.';
                $old = [
                    'title' => '',
                    'excerpt' => '',
                    'content' => '',
                    'date' => '',
                    'category' => '',
                    'tags' => '',
                    'image' => '',
                    'alt' => '',
                    'meta_title' => '',
                    'meta_desc' => '',
                    'status' => 'draft',
                    'extras' => [],
                ];
            } else {
                $feedbackClass = 'alert alert-error';
                $feedback = 'Errore durante il salvataggio.';
            }
        } else {
            $feedbackClass = 'alert alert-error';
            $feedback = implode(' | ', $errors);
        }
    }
}

$html = buildPage('../pages/admin_blog_nuovo.html', $_SERVER['PHP_SELF']);

// Keywords per SEO (pagine admin sono noindex)
$keywords = '<meta name="keywords" content="nuovo articolo blog, crea articolo, admin blog SailUP">';

$categorySelections = [
    'guide' => '',
    'destinazioni' => '',
    'consigli' => '',
    'eventi' => '',
    'sicurezza' => '',
    'manutenzione' => '',
];
if (isset($categorySelections[$old['category']])) {
    $categorySelections[$old['category']] = 'selected';
}

$statusDraft = $old['status'] === 'published' ? '' : 'selected';
$statusPub = $old['status'] === 'published' ? 'selected' : '';

$extrasHtml = '';
if (!empty($old['extras'])) {
    foreach ($old['extras'] as $ex) {
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

$html = str_replace(
    [
        '[ADMIN_BLOG_NEW_FEEDBACK]',
        '[CSRF_TOKEN]',
        '[ADMIN_BLOG_ACTION]',
        '[KEYWORDS]',
        '[OLD_TITLE]',
        '[IF_CAT_GUIDE]',
        '[IF_CAT_DEST]',
        '[IF_CAT_CONSIGLI]',
        '[IF_CAT_EVENTI]',
        '[IF_CAT_SIC]',
        '[IF_CAT_MAN]',
        '[OLD_DATE]',
        '[OLD_EXCERPT]',
        '[OLD_CONTENT]',
        '[OLD_TAGS]',
        '[OLD_IMAGE]',
        '[OLD_ALT]',
        '[OLD_META_TITLE]',
        '[OLD_META_DESC]',
        '[IF_STATUS_DRAFT]',
        '[IF_STATUS_PUB]',
        '[ADMIN_BLOG_EXTRAS]',
    ],
    [
        $feedback ? '<div class="' . $feedbackClass . '" role="status" aria-live="polite">' . htmlspecialchars($feedback) . '</div>' : '',
        htmlspecialchars(getCsrfToken()),
        htmlspecialchars($_SERVER['PHP_SELF']),
        $keywords,
        htmlspecialchars($old['title'], ENT_QUOTES),
        $categorySelections['guide'],
        $categorySelections['destinazioni'],
        $categorySelections['consigli'],
        $categorySelections['eventi'],
        $categorySelections['sicurezza'],
        $categorySelections['manutenzione'],
        htmlspecialchars($old['date']),
        htmlspecialchars($old['excerpt']),
        htmlspecialchars($old['content']),
        htmlspecialchars($old['tags']),
        htmlspecialchars($old['image'], ENT_QUOTES),
        htmlspecialchars($old['alt']),
        htmlspecialchars($old['meta_title'], ENT_QUOTES),
        htmlspecialchars($old['meta_desc']),
        $statusDraft,
        $statusPub,
        $extrasHtml,
    ],
    $html
);

echo $html;
?>
