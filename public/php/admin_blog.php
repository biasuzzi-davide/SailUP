<?php

require_once '../../includes/session/session.php';
require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

requireAdmin();

$db = new DBConnection();
$blogStats = $db->getBlogStats();
$csrfToken = getCsrfToken();
$feedback = '';
$feedbackClass = '';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$search = trim($_GET['q'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? null;
    $action = $_POST['action'] ?? '';
    $idArt = isset($_POST['id_articolo']) ? (int)$_POST['id_articolo'] : 0;

    if (!verifyCsrfToken($csrf)) {
        $feedbackClass = 'alert alert-error';
        $feedback = 'Sessione scaduta, ricarica la pagina.';
    } elseif ($idArt <= 0) {
        $feedbackClass = 'alert alert-error';
        $feedback = 'Articolo non valido.';
    } else {
        if ($action === 'publish') {
            $ok = $db->setArticoloStato($idArt, true);
            $feedbackClass = $ok ? 'alert alert-success' : 'alert alert-error';
            $feedback = $ok ? 'Articolo pubblicato.' : 'Impossibile pubblicare l\'articolo.';
        } elseif ($action === 'draft') {
            $ok = $db->setArticoloStato($idArt, false);
            $feedbackClass = $ok ? 'alert alert-success' : 'alert alert-error';
            $feedback = $ok ? 'Articolo impostato come bozza.' : 'Impossibile aggiornare l\'articolo.';
        } elseif ($action === 'delete') {
            $ok = $db->deleteArticolo($idArt);
            $feedbackClass = $ok ? 'alert alert-success' : 'alert alert-error';
            $feedback = $ok ? 'Articolo eliminato.' : 'Impossibile eliminare l\'articolo.';
        } else {
            $feedbackClass = 'alert alert-error';
            $feedback = 'Azione non valida.';
        }
    }
}

$offset = ($page - 1) * $perPage;
$articoliRes = $db->getArticoliAdmin($search === '' ? null : $search, $perPage, $offset);
$articoli = [];
$total = 0;
if (is_array($articoliRes)) {
    $articoli = $articoliRes['data'] ?? [];
    $total = (int)($articoliRes['total'] ?? 0);
}

$rows = buildAdminBlogRows($articoli, $csrfToken);
$pages = $total > 0 ? (int)ceil($total / $perPage) : 1;
$pagination = buildPaginationNav($page, $pages, 'admin_blog.php', ['q' => $search], 'Paginazione articoli');
$feedbackBlock = buildFeedbackBlock($feedback, $feedbackClass);

$html = buildPage('../pages/admin_blog.html', $_SERVER['PHP_SELF']);
$html = str_replace(
    [
        '[ADMIN_BLOG_ROWS]',
        '[ADMIN_BLOG_FEEDBACK]',
        '[ADMIN_BLOG_PAGINATION]',
        '[ADMIN_BLOG_SEARCH]',
        '[STAT_BLOG_PUBLISHED]',
        '[STAT_BLOG_DRAFTS]',
        '[STAT_BLOG_VIEWS]',
        '[STAT_BLOG_COMMENTS]',
        '[KEYWORDS]',
    ],
    [
        $rows,
        $feedbackBlock,
        $pagination,
        htmlspecialchars($search),
        htmlspecialchars((string)($blogStats['published'] ?? 0)),
        htmlspecialchars((string)($blogStats['drafts'] ?? 0)),
        '0',
        '0',
        '<meta name="keywords" content="gestione blog, admin articoli, dashboard blog SailUP">',
    ],
    $html
);

echo $html;
?>
