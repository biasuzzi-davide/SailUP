<?php

require_once '../../includes/session/session.php';
require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

requireAdmin();

$db = new DBConnection();
$feedback = '';
$feedbackClass = 'hidden';
$userId = $_SESSION['user']['IDUtente'] ?? null;

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

        $errors = [];
        if ($titolo === '') $errors[] = 'Inserisci il titolo';
        if ($excerpt === '') $errors[] = 'Inserisci l\'estratto';
        if ($contenuto === '') $errors[] = 'Inserisci il contenuto';
        if ($dataPub === '') $errors[] = 'Inserisci la data di pubblicazione';
        if ($urlImg === '' || !filter_var($urlImg, FILTER_VALIDATE_URL)) $errors[] = 'URL immagine non valido';
        if ($altImg === '') $errors[] = 'Testo alternativo obbligatorio';

        if (empty($errors)) {
            $pubblicato = $status === 'published';
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
                $feedbackClass = 'alert alert-success';
                $feedback = 'Articolo salvato correttamente.';
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

$html = str_replace(
    ['[ADMIN_BLOG_NEW_FEEDBACK]', '[CSRF_TOKEN]', '[ADMIN_BLOG_ACTION]', '[KEYWORDS]'],
    [
        $feedback ? '<div class="' . $feedbackClass . '" role="status" aria-live="polite">' . htmlspecialchars($feedback) . '</div>' : '',
        htmlspecialchars(getCsrfToken()),
        htmlspecialchars($_SERVER['PHP_SELF']),
        $keywords,
    ],
    $html
);

echo $html;
?>
