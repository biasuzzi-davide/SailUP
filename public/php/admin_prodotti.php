<?php

require_once '../../includes/session/session.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/helpers.php';

requireAdmin();

$db = new DBConnection();
$feedback = '';
$feedbackClass = '';
$csrfToken = htmlspecialchars(getCsrfToken());

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
            $ok = $db->setProdottoStatus($idProdotto, false);
            if ($ok) {
                $feedbackClass = 'alert alert-success';
                $feedback = 'Prodotto disattivato.';
            } else {
                $feedbackClass = 'alert alert-error';
                $feedback = 'Impossibile disattivare il prodotto.';
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

$rows = '';
if (is_array($prodotti) && !empty($prodotti)) {
    foreach ($prodotti as $p) {
        $tipo = $p['Tipo_Prodotto'] ?? '';
        $tipologia = $p['Tipologia_Prodotto'] ?? ($p['Tipologia_Experience'] ?? '');
        $tipoDisplay = htmlspecialchars($tipo) . ($tipologia ? ' • ' . htmlspecialchars($tipologia) : '');
        $prezzo = isset($p['Prezzo_Base']) ? '€ ' . number_format((float)$p['Prezzo_Base'], 2, ',', '.') : '—';
        $stato = !empty($p['Attivo']) ? '<span class="status-badge active">Attivo</span>' : '<span class="status-badge cancelled">Disattivo</span>';
        $toggleLabel = !empty($p['Attivo']) ? 'Disattiva' : 'Attiva';
        $toggleClass = !empty($p['Attivo']) ? 'btn-text danger' : 'btn-text';
        $toggleConfirm = !empty($p['Attivo']) ? ' onsubmit="return confirm(\'Disattivare questo prodotto?\');"' : '';
        $rows .= '<tr>'
            . '<td data-label="ID">' . htmlspecialchars($p['IDProdotto']) . '</td>'
            . '<td data-label="Nome Prodotto">' . htmlspecialchars($p['Nome_Prodotto'] ?? '') . '</td>'
            . '<td data-label="Tipo">' . $tipoDisplay . '</td>'
            . '<td data-label="Dettagli">' . htmlspecialchars($p['Descrizione_Breve'] ?? '—') . '</td>'
            . '<td data-label="Prezzo">' . $prezzo . '</td>'
            . '<td data-label="Stato">' . $stato . '</td>'
            . '<td data-label="Azioni" class="actions-cell">'
            . '<a class="btn-text" href="admin_prodotti_nuovo.php?id=' . htmlspecialchars($p['IDProdotto']) . '" aria-label="Modifica ' . htmlspecialchars($p['Nome_Prodotto'] ?? '') . '">Modifica</a>'
            . '<form method="post" class="inline-form"' . $toggleConfirm . '>'
            . '<input type="hidden" name="csrf_token" value="' . $csrfToken . '">'
            . '<input type="hidden" name="id_prodotto" value="' . htmlspecialchars($p['IDProdotto']) . '">'
            . '<input type="hidden" name="attivo" value="' . (!empty($p['Attivo']) ? 1 : 0) . '">'
            . '<input type="hidden" name="action" value="toggle">'
            . '<button type="submit" class="' . $toggleClass . '" aria-label="' . htmlspecialchars($toggleLabel) . ' prodotto ' . htmlspecialchars($p['Nome_Prodotto'] ?? '') . '">' . htmlspecialchars($toggleLabel) . '</button>'
            . '</form>'
            . '</td>'
            . '</tr>';
    }
} else {
    $rows = '<tr><td colspan="7">Nessun prodotto trovato.</td></tr>';
}

$pages = $total > 0 ? (int)ceil($total / $perPage) : 1;
$pagination = '';
if ($pages > 1) {
    $pagination .= '<nav class="pagination" aria-label="Paginazione prodotti"><ul>';
    for ($i = 1; $i <= $pages; $i++) {
        $currentClass = $i === $page ? ' class="current-page"' : '';
        $query = http_build_query([
            'page' => $i,
            'q' => $search,
            'tipo' => $filterTipo,
            'stato' => $filterStato
        ]);
        $pagination .= '<li' . $currentClass . '><a href="admin_prodotti.php?' . htmlspecialchars($query) . '">' . $i . '</a></li>';
    }
    $pagination .= '</ul></nav>';
}

$feedbackBlock = '';
if ($feedback !== '' && $feedbackClass !== '') {
    $feedbackBlock = '<div class="' . $feedbackClass . '" role="status" aria-live="polite">' . htmlspecialchars($feedback) . '</div>';
}

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
    ],
    [
        $feedbackBlock,
        $pagination,
        htmlspecialchars($search),
        $filterTipo === 'Noleggio' ? 'selected' : '',
        $filterTipo === 'Experience' ? 'selected' : '',
        $filterStato === 'attivi' ? 'selected' : '',
        $filterStato === 'disattivi' ? 'selected' : '',
    ],
    $html
);

echo $html;
?>
