<?php

require_once '../../includes/session/session.php';
require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

requireAdmin();

$uploadDirBlog = __DIR__ . '/../img/blog';

$functionImageError = 'Impossibile salvare l\'immagine, riprova.';
/**
 * Gestisce l'upload dell'immagine di copertina del blog convertendola in WebP.
 */
function handleBlogImageUpload(string $uploadDir): array {
    $result = ['url' => null, 'error' => null, 'file' => null, 'path' => null];

    if (!isset($_FILES['post_image']) || $_FILES['post_image']['error'] === UPLOAD_ERR_NO_FILE) {
        return $result;
    }

    $file = $_FILES['post_image'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $result['error'] = 'Errore durante il caricamento dell\'immagine.';
        return $result;
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        $result['error'] = 'Immagine troppo grande (max 2MB).';
        return $result;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if (!isset($allowed[$mime])) {
        $result['error'] = 'Formato immagine non supportato. Usa JPG, PNG o WebP.';
        return $result;
    }

    if (!function_exists('imagewebp')) {
        $result['error'] = 'Conversione WebP non disponibile sul server.';
        return $result;
    }

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    // Forza sempre i permessi a 777
    @chmod($uploadDir, 0777);
    clearstatcache(true, $uploadDir);
    if (!is_writable($uploadDir)) {
        $result['error'] = 'Cartella upload non scrivibile. Verifica i permessi della cartella: blog';
        return $result;
    }

    $srcImage = null;
    if ($mime === 'image/jpeg') {
        $srcImage = imagecreatefromjpeg($file['tmp_name']);
    } elseif ($mime === 'image/png') {
        $srcImage = imagecreatefrompng($file['tmp_name']);
        if ($srcImage) {
            imagepalettetotruecolor($srcImage);
            imagealphablending($srcImage, true);
            imagesavealpha($srcImage, true);
        }
    } elseif ($mime === 'image/webp') {
        $srcImage = imagecreatefromwebp($file['tmp_name']);
    }

    if (!$srcImage) {
        $result['error'] = 'Impossibile leggere l\'immagine.';
        return $result;
    }

    try {
        $token = bin2hex(random_bytes(4));
    } catch (Throwable) {
        $token = (string) time();
    }
    $filename = 'article_' . $token . '.webp';
    $destPath = $uploadDir . '/' . $filename;

    if (!imagewebp($srcImage, $destPath, 85)) {
        imagedestroy($srcImage);
        $result['error'] = 'Impossibile salvare l\'immagine, riprova.';
        return $result;
    }

    imagedestroy($srcImage);

    $result['file'] = $filename;
    $result['path'] = $destPath;
    $result['url'] = '../img/blog/' . $filename . '?v=' . filemtime($destPath);
    return $result;
}

$db = new DBConnection();
$feedback = '';
$feedbackClass = 'hidden';
//per distinguire dalla modifica
$mode = 'create';
//per distringuere da create(viene preso l id del blog da modificare)
$editingId = '';
$userId = $_SESSION['user']['IDUtente'] ?? null;
$old = [
    'title' => '',
    'excerpt' => '',
    'content' => '',
    'date' => '',
    'image' => '',
    'alt' => '',
    'status' => 'draft',
    'extras' => [],
    'reading_time' => '',
];

function stimaTempoLettura(string $contenuto): int {
    $parole = str_word_count(strip_tags($contenuto));
    $minuti = (int)ceil($parole / 200);
    return max(1, $minuti);
}

//se trovo id nel get-> è modifica
if (isset($_GET['id']) && trim($_GET['id']) !== '') {
    $editingId = (int)$_GET['id'];
    $article = $db->getArticoloAdminById($editingId);
    if (is_array($article)) {
        $mode = 'edit';
        $extras = $db->getArticoloBlogExtra($editingId);
        $extrasFormatted = [];
        if (is_array($extras)) {
            foreach ($extras as $ex) {
                $extrasFormatted[] = [
                    'titolo' => $ex['Titolo'] ?? '',
                    'elemento' => $ex['Elemento'] ?? '',
                ];
            }
        }
        $old = [
            'title' => $article['Titolo'] ?? '',
            'excerpt' => $article['Descrizione_Breve'] ?? '',
            'content' => $article['Contenuto'] ?? '',
            'date' => !empty($article['Data_Pubblicazione']) ? date('Y-m-d', strtotime($article['Data_Pubblicazione'])) : '',
            'image' => normalizeImageUrl($article['URL_Media'] ?? ''),
            'alt' => $article['Testo_Alternativo'] ?? '',
            'status' => !empty($article['Pubblicato']) ? 'published' : 'draft',
            'extras' => $extrasFormatted,
            'reading_time' => (int)($article['Tempo_Lettura'] ?? 0),
        ];
    } else {
        $feedbackClass = 'alert alert-error';
        $feedback = 'Articolo non trovato.';
        $editingId = '';
        $mode = 'create';
    }
}

//quando invio i dati al server
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
        $urlImg = normalizeImageUrl(trim($_POST['existing-image-url'] ?? ''));
        $altImg = trim($_POST['Testo_Alternativo'] ?? '');
        $readingTime = (int)($_POST['post-reading-time'] ?? 0);
        $postId = trim($_POST['post-id'] ?? '');
        if ($postId !== '') {
            $editingId = (int)$postId;
            $mode = 'edit';
        }
        $extraTitles = $_POST['extra_title'] ?? [];
        $extraItems = $_POST['extra_item'] ?? [];

        $old = [
            'title' => $titolo,
            'excerpt' => $excerpt,
            'content' => $contenuto,
            'date' => $dataPub,
            'image' => $urlImg,
            'alt' => $altImg,
            'status' => $status,
            'extras' => [],
            'reading_time' => $readingTime,
        ];

        $errors = [];
        if ($titolo === '') $errors[] = 'Inserisci il titolo';
        if ($excerpt === '') $errors[] = 'Inserisci l\'estratto';
        if ($contenuto === '') $errors[] = 'Inserisci il contenuto';
        if ($dataPub === '') $errors[] = 'Inserisci la data di pubblicazione';
        $hasNewImage = isset($_FILES['post_image']) && $_FILES['post_image']['error'] !== UPLOAD_ERR_NO_FILE;
        if (!$hasNewImage && $urlImg === '') $errors[] = 'Immagine obbligatoria';
        if ($altImg === '') $errors[] = 'Testo alternativo obbligatorio';
        if ($readingTime < 1) $errors[] = 'Inserisci il tempo medio di lettura in minuti';

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

        $uploadRes = ['url' => null, 'error' => null, 'file' => null, 'path' => null];
        if (empty($errors) && $hasNewImage) {
            $uploadRes = handleBlogImageUpload($uploadDirBlog);
            if (!empty($uploadRes['error'])) {
                $errors[] = $uploadRes['error'];
            }
        }

        if (empty($errors)) {
            $action = $_POST['action'] ?? '';
            if ($action === 'publish') {
                $pubblicato = true;
            } elseif ($action === 'draft') {
                $pubblicato = false;
            } else {
                $pubblicato = $status === 'published';
            }
            $tempo = $readingTime > 0 ? $readingTime : stimaTempoLettura($contenuto);
            $targetId = null;
            $ok = false;
            if ($postId !== '') {
                $targetId = (int)$postId;
                $ok = $db->updateArticoloBlog(
                    $targetId,
                    $titolo,
                    $excerpt,
                    $contenuto,
                    $dataPub,
                    $pubblicato,
                    $tempo
                );
            } else {
                $targetId = $db->creaArticoloBlog(
                    (int)$userId,
                    $titolo,
                    $excerpt,
                    $contenuto,
                    $dataPub,
                    $pubblicato,
                    $tempo
                );
                $ok = (bool)$targetId;
            }

            if ($ok && $targetId) {
                $finalUrl = !empty($uploadRes['url']) ? $uploadRes['url'] : $urlImg;
                $okMedia = $db->upsertMediaArticolo((int)$targetId, $finalUrl, $altImg);
                if ($okMedia) {
                    $db->setArticoloBlogExtra((int)$targetId, $extras);
                    $redirectTarget = $postId !== '' ? 'admin_blog.php' : 'blog.php';
                    header('Location: ' . $redirectTarget);
                    exit;
                }
                if (!empty($uploadRes['path']) && file_exists($uploadRes['path'])) {
                    @unlink($uploadRes['path']);
                }
                $feedbackClass = 'alert alert-error';
                $feedback = 'Errore durante il salvataggio dell\'immagine.';
            } else {
                if (!empty($uploadRes['path']) && file_exists($uploadRes['path'])) {
                    @unlink($uploadRes['path']);
                }
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
$keywords = '<meta name="keywords" content="nuovo, articolo, blog, crea, admin, SailUP">';

$statusDraft = $old['status'] === 'published' ? '' : 'selected';
$statusPub = $old['status'] === 'published' ? 'selected' : '';

$extrasHtml = buildBlogExtraInputs(!empty($old['extras']) && is_array($old['extras']) ? $old['extras'] : []);
$feedbackBlock = buildFeedbackBlock($feedback, $feedbackClass);
$pageTitle = $mode === 'edit' ? 'Modifica Articolo Blog - Dashboard Admin - SailUP' : 'Nuovo Articolo Blog - Dashboard Admin - SailUP';
$pageDesc = $mode === 'edit'
    ? 'Form per modificare un articolo del blog SailUP. Aggiorna i dettagli, il contenuto e la pubblicazione.'
    : 'Form per creare un nuovo articolo del blog SailUP. Compila i dettagli, aggiungi contenuto e pubblica.';
$pageHeading = $mode === 'edit' ? 'Modifica Articolo Blog' : 'Nuovo Articolo Blog';
$pageSubtitle = $mode === 'edit' ? 'Aggiorna i contenuti dell\'articolo selezionato.' : 'Crea un nuovo articolo per il blog SailUP';
$breadcrumb = $mode === 'edit' ? 'Modifica Articolo' : 'Nuovo Articolo';

$html = str_replace(
    [
        '[ADMIN_BLOG_NEW_FEEDBACK]',
        '[CSRF_TOKEN]',
        '[ADMIN_BLOG_ACTION]',
        '[ADMIN_BLOG_ID]',
        '[KEYWORDS]',
        '[ADMIN_BLOG_PAGE_TITLE]',
        '[ADMIN_BLOG_PAGE_DESC]',
        '[ADMIN_BLOG_HEADING]',
        '[ADMIN_BLOG_SUBTITLE]',
        '[ADMIN_BLOG_BREADCRUMB]',
        '[OLD_TITLE]',
        '[OLD_DATE]',
        '[OLD_EXCERPT]',
        '[OLD_CONTENT]',
        '[OLD_IMAGE]',
        '[OLD_ALT]',
        '[OLD_READING_TIME]',
        '[IF_STATUS_DRAFT]',
        '[IF_STATUS_PUB]',
        '[ADMIN_BLOG_EXTRAS]',
    ],
    [
        $feedbackBlock,
        htmlspecialchars(getCsrfToken()),
        htmlspecialchars($_SERVER['PHP_SELF']),
        htmlspecialchars($editingId),
        $keywords,
        htmlspecialchars($pageTitle),
        htmlspecialchars($pageDesc),
        htmlspecialchars($pageHeading),
        htmlspecialchars($pageSubtitle),
        htmlspecialchars($breadcrumb),
        htmlspecialchars($old['title'], ENT_QUOTES),
        htmlspecialchars($old['date']),
        htmlspecialchars($old['excerpt']),
        htmlspecialchars($old['content']),
        htmlspecialchars($old['image'], ENT_QUOTES),
        htmlspecialchars($old['alt']),
        htmlspecialchars((string)$old['reading_time']),
        $statusDraft,
        $statusPub,
        $extrasHtml,
    ],
    $html
);

echo $html;
?>
