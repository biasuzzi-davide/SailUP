<?php

require_once '../../includes/session/session.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/helpers.php';

requireAdmin();

$db = new DBConnection();
$feedback = '';
$feedbackClass = '';
$csrfToken = getCsrfToken();
$productStats = $db->getProductStats();

$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$search = trim($_GET['q'] ?? '');
$filterTipo = $_GET['tipo'] ?? '';
$filterStato = $_GET['stato'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? null;
    $action = $_POST['action'] ?? '';
    $idProdotto = trim($_POST['id_prodotto'] ?? '');

    if (!verifyCsrfToken($csrf)) {
        $feedbackClass = 'alert alert-error';
        $feedback = 'Sessione scaduta, ricarica la pagina.';
    } elseif ($idProdotto === '') {
        $feedbackClass = 'alert alert-error';
        $feedback = 'Prodotto non valido.';
    } else {
        if ($action === 'toggle') {
            $attivo = (int)($_POST['attivo'] ?? 1) === 1 ? false : true;
            $ok = $db->setProdottoStatus($idProdotto, $attivo);
            if ($ok) {
                $feedbackClass = 'alert alert-success';
                $feedback = $attivo ? 'Prodotto riattivato correttamente.' : 'Prodotto disattivato.';
            } else {
                $feedbackClass = 'alert alert-error';
                $feedback = 'Impossibile aggiornare lo stato del prodotto.';
            }
        } elseif ($action === 'delete') {
            $ok = $db->deleteProdotto($idProdotto);
            if ($ok) {
                $feedbackClass = 'alert alert-success';
                $feedback = 'Prodotto eliminato definitivamente.';
            } else {
                $feedbackClass = 'alert alert-error';
                $feedback = 'Impossibile eliminare il prodotto.';
            }
        } else {
            $feedbackClass = 'alert alert-error';
            $feedback = 'Azione non valida.';
        }
    }
}

$offset = ($page - 1) * $perPage;
$prodottiRes = $db->getProdottiAdmin(
    $search === '' ? null : $search,
    $filterTipo === '' ? null : $filterTipo,
    $filterStato === '' ? null : $filterStato,
    $perPage,
    $offset
);

$prodotti = [];
$total = 0;
if (is_array($prodottiRes)) {
    $prodotti = $prodottiRes['data'] ?? [];
    $total = (int)($prodottiRes['total'] ?? 0);
}

$rows = buildAdminProductRows($prodotti, $csrfToken);

$totalPages = $total > 0 ? (int)ceil($total / $perPage) : 1;
$pagination = buildPaginationNav(
    $page,
    $totalPages,
    'admin_prodotti.php',
    ['q' => $search, 'tipo' => $filterTipo, 'stato' => $filterStato],
    'Paginazione prodotti'
);

$feedbackBlock = buildFeedbackBlock($feedback, $feedbackClass);

$html = buildPage('../pages/admin_prodotti.html', $_SERVER['PHP_SELF']);
$html = str_replace('[ADMIN_PRODUCTS_ROWS]', $rows, $html);
$html = str_replace(
    [
        '[ADMIN_PRODUCTS_FEEDBACK]',
        '[ADMIN_PRODUCTS_PAGINATION]',
        '[ADMIN_PRODUCTS_SEARCH]',
        '[IF_TIPO_NOLEGGIO]',
        '[IF_TIPO_EXPERIENCE]',
        '[IF_STATO_ATTIVI]',
        '[IF_STATO_DISATTIVI]',
        '[STAT_PRODUCTS_TOTAL]',
        '[STAT_PRODUCTS_ACTIVE]',
        '[STAT_PRODUCTS_INACTIVE]',
        '[STAT_PRODUCTS_NOL]',
        '[STAT_PRODUCTS_EXP]',
    ],
    [
        $feedbackBlock,
        $pagination,
        htmlspecialchars($search),
        $filterTipo === 'Noleggio' ? 'selected' : '',
        $filterTipo === 'Experience' ? 'selected' : '',
        $filterStato === 'attivi' ? 'selected' : '',
        $filterStato === 'disattivi' ? 'selected' : '',
        htmlspecialchars((string)($productStats['total_products'] ?? 0)),
        htmlspecialchars((string)($productStats['active_products'] ?? 0)),
        htmlspecialchars((string)($productStats['inactive_products'] ?? 0)),
        htmlspecialchars((string)($productStats['rental_products'] ?? 0)),
        htmlspecialchars((string)($productStats['experience_products'] ?? 0)),
    ],
    $html
);

echo $html;
?>
