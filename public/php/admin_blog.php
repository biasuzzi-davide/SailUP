<?php

require_once '../../includes/session/session.php';
require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

requireAdmin();

$db = new DBConnection();
$csrfToken = htmlspecialchars(getCsrfToken());
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

$rows = '';
if (!empty($articoli)) {
    foreach ($articoli as $a) {
        $statoPub = !empty($a['Pubblicato']);
        $badge = $statoPub ? '<span class="status-badge active">Pubblicato</span>' : '<span class="status-badge pending">Bozza</span>';
        $dataPub = !empty($a['Data_Pubblicazione']) ? date('d/m/y', strtotime($a['Data_Pubblicazione'])) : '—';
        $rows .= '<tr>'
            . '<td data-label="ID">' . htmlspecialchars($a['IDArticolo']) . '</td>'
            . '<td data-label="Titolo">' . htmlspecialchars($a['Titolo'] ?? '') . '</td>'
            . '<td data-label="Categoria">—</td>'
            . '<td data-label="Data">' . htmlspecialchars($dataPub) . '</td>'
            . '<td data-label="Stato">' . $badge . '</td>'
            . '<td data-label="Azioni" class="actions-cell">'
            . '<form method="post" class="inline-form">'
            . '<input type="hidden" name="csrf_token" value="' . $csrfToken . '">'
            . '<input type="hidden" name="id_articolo" value="' . htmlspecialchars($a['IDArticolo']) . '">'
            . '<input type="hidden" name="action" value="' . ($statoPub ? 'draft' : 'publish') . '">'
            . '<button type="submit" class="btn-icon" aria-label="' . ($statoPub ? 'Imposta come bozza' : 'Pubblica') . ' ' . htmlspecialchars($a['Titolo'] ?? '') . '">' . ($statoPub ? '⏸️' : '🚀') . '</button>'
            . '</form>'
            . '<form method="post" class="inline-form" onsubmit="return confirm(\'Eliminare questo articolo?\');">'
            . '<input type="hidden" name="csrf_token" value="' . $csrfToken . '">'
            . '<input type="hidden" name="id_articolo" value="' . htmlspecialchars($a['IDArticolo']) . '">'
            . '<input type="hidden" name="action" value="delete">'
            . '<button type="submit" class="btn-icon danger" aria-label="Elimina articolo ' . htmlspecialchars($a['Titolo'] ?? '') . '">🗑️</button>'
            . '</form>'
            . '</td>'
            . '</tr>';
    }
} else {
    $rows = '<tr><td colspan="6">Nessun articolo trovato.</td></tr>';
}

$pages = $total > 0 ? (int)ceil($total / $perPage) : 1;
$pagination = '';
if ($pages > 1) {
    $pagination .= '<nav class="pagination" aria-label="Paginazione articoli"><ul>';
    for ($i = 1; $i <= $pages; $i++) {
        $currentClass = $i === $page ? ' class="current-page"' : '';
        $query = http_build_query([
            'page' => $i,
            'q' => $search,
        ]);
        $pagination .= '<li' . $currentClass . '><a href="admin_blog.php?' . htmlspecialchars($query) . '">' . $i . '</a></li>';
    }
    $pagination .= '</ul></nav>';
}

$feedbackBlock = '';
if ($feedback !== '' && $feedbackClass !== '') {
    $feedbackBlock = '<div class="' . $feedbackClass . '" role="status" aria-live="polite">' . htmlspecialchars($feedback) . '</div>';
}

$html = buildPage('../pages/admin_blog.html', $_SERVER['PHP_SELF']);
$html = str_replace(
    ['[ADMIN_BLOG_ROWS]', '[ADMIN_BLOG_FEEDBACK]', '[ADMIN_BLOG_PAGINATION]', '[ADMIN_BLOG_SEARCH]'],
    [
        $rows,
        $feedbackBlock,
        $pagination,
        htmlspecialchars($search),
    ],
    $html
);

echo $html;
?>
