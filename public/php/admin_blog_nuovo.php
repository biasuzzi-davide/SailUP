<?php

require_once '../../includes/session/session.php';
require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

requireAdmin();

$uploadDirBlog = __DIR__ . '/../uploads/blog';

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
        mkdir($uploadDir, 0775, true);
    }
    if (!is_writable($uploadDir)) {
        @chmod($uploadDir, 0775);
        if (!is_writable($uploadDir)) {
            $result['error'] = 'Cartella upload non scrivibile.';
            return $result;
        }
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
    $result['url'] = '../uploads/blog/' . $filename . '?v=' . filemtime($destPath);
    return $result;
}

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
        $urlImg = trim($_POST['existing-image-url'] ?? '');
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
        $hasNewImage = isset($_FILES['post_image']) && $_FILES['post_image']['error'] !== UPLOAD_ERR_NO_FILE;
        if (!$hasNewImage && $urlImg === '') $errors[] = 'Immagine obbligatoria';
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

        $uploadRes = ['url' => null, 'error' => null, 'file' => null, 'path' => null];
        if (empty($errors) && $hasNewImage) {
            $uploadRes = handleBlogImageUpload($uploadDirBlog);
            if (!empty($uploadRes['error'])) {
                $errors[] = $uploadRes['error'];
            }
        }

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
                $finalUrl = !empty($uploadRes['url']) ? $uploadRes['url'] : $urlImg;
                $okMedia = $db->upsertMediaArticolo((int)$newId, $finalUrl, $altImg);
                if ($okMedia) {
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
                    if (!empty($uploadRes['path']) && file_exists($uploadRes['path'])) {
                        @unlink($uploadRes['path']);
                    }
                    $feedbackClass = 'alert alert-error';
                    $feedback = 'Errore durante il salvataggio dell\'immagine.';
                }
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

$extrasHtml = buildBlogExtraInputs(!empty($old['extras']) && is_array($old['extras']) ? $old['extras'] : []);
$feedbackBlock = buildFeedbackBlock($feedback, $feedbackClass);

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
        $feedbackBlock,
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
