<?php

require_once '../../includes/session/session.php';
require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/utils/validation.php';

requireAdmin();

$db = new DBConnection();
$feedback = '';
$feedbackClass = 'hidden';
$mode = 'create';
$editingId = '';
$placeholders = [
    '[PROD_NAME]' => '',
    '[PROD_TYPE_NOLEGGIO]' => '',
    '[PROD_TYPE_EXP]' => '',
    '[PROD_TIPOLOGIA]' => '',
    '[PROD_DURATION]' => '',
    '[PROD_DESC]' => '',
    '[PROD_DESC_LONG]' => '',
    '[PROD_PRICE]' => '',
    '[PROD_CAPACITY]' => '',
    '[PROD_LENGTH]' => '',
    '[PROD_FEATURES]' => '',
    '[IMG_URL]' => '',
    '[IMG_ALT]' => '',
    '[CHECK_PATENTE]' => '',
    '[CHECK_ACCESS]' => '',
    '[SEL_STATUS_AVAILABLE]' => '',
    '[SEL_STATUS_MAINT]' => '',
    '[SEL_STATUS_UNAVAIL]' => '',
    '[LANG_IT]' => '',
    '[LANG_EN]' => '',
    '[LANG_FR]' => '',
    '[LANG_ES]' => '',
    '[LANG_DE]' => '',
];
$placeholders['[SEL_STATUS_AVAILABLE]'] = 'selected';

if (isset($_GET['id']) && trim($_GET['id']) !== '') {
    $editingId = trim($_GET['id']);
    $prod = $db->getProdottoAdminById($editingId);
    if (is_array($prod)) {
        $mode = 'edit';
        $langs = $db->getLinguePerProdotto($editingId) ?: [];
        $inclusi = $db->getProdottoInclusi($editingId) ?: [];
        $langsCodes = array_map(fn($row) => $row['Codice'] ?? '', $langs);
        $placeholders = array_merge($placeholders, [
            '[PROD_NAME]' => htmlspecialchars($prod['Nome_Prodotto'] ?? ''),
            '[PROD_TYPE_NOLEGGIO]' => ($prod['Tipo_Prodotto'] ?? '') === 'Noleggio' ? 'selected' : '',
            '[PROD_TYPE_EXP]' => ($prod['Tipo_Prodotto'] ?? '') === 'Experience' ? 'selected' : '',
            '[PROD_TIPOLOGIA]' => htmlspecialchars($prod['Tipologia_Prodotto'] ?? ''),
            '[PROD_DURATION]' => htmlspecialchars($prod['Durata_Ore'] ?? ''),
            '[PROD_DESC]' => htmlspecialchars($prod['Descrizione_Breve'] ?? ''),
            '[PROD_DESC_LONG]' => htmlspecialchars($prod['Descrizione'] ?? ''),
            '[PROD_PRICE]' => htmlspecialchars($prod['Prezzo_Base'] ?? ''),
            '[PROD_CAPACITY]' => htmlspecialchars($prod['Posti_Totali'] ?? ''),
            '[PROD_LENGTH]' => htmlspecialchars($prod['Lunghezza_Barca_Metri'] ?? ''),
            '[IMG_URL]' => htmlspecialchars($prod['URL_Media'] ?? ''),
            '[IMG_ALT]' => htmlspecialchars($prod['Testo_Alternativo'] ?? ''),
            '[CHECK_PATENTE]' => !empty($prod['Richiede_Patente']) ? 'checked' : '',
            '[CHECK_ACCESS]' => !empty($prod['Accessibile_Disabili']) ? 'checked' : '',
            '[SEL_STATUS_AVAILABLE]' => !empty($prod['Attivo']) ? 'selected' : '',
            '[SEL_STATUS_MAINT]' => '',
            '[SEL_STATUS_UNAVAIL]' => empty($prod['Attivo']) ? 'selected' : '',
            '[PROD_FEATURES]' => htmlspecialchars(implode("\n", array_map(fn($row) => $row['Nome_Incluso'] ?? '', $inclusi))),
        ]);
        $placeholders['[LANG_IT]'] = in_array('IT', $langsCodes, true) ? 'checked' : '';
        $placeholders['[LANG_EN]'] = in_array('EN', $langsCodes, true) ? 'checked' : '';
        $placeholders['[LANG_FR]'] = in_array('FR', $langsCodes, true) ? 'checked' : '';
        $placeholders['[LANG_ES]'] = in_array('ES', $langsCodes, true) ? 'checked' : '';
        $placeholders['[LANG_DE]'] = in_array('DE', $langsCodes, true) ? 'checked' : '';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? null;
    if (!verifyCsrfToken($csrf)) {
        $feedbackClass = 'alert alert-error';
        $feedback = 'Sessione scaduta, ricarica la pagina.';
    } else {
        $nome = trim($_POST['product-name'] ?? '');
        $tipo = $_POST['product-type'] ?? '';
        $tipologia = trim($_POST['product-category'] ?? '');
        $durata = $_POST['product-duration'] ?? '';
        $descrBreve = trim($_POST['product-description'] ?? '');
        $descr = trim($_POST['product-long-description'] ?? '');
        $prezzo = (float)($_POST['product-price'] ?? 0);
        $posti = (int)($_POST['product-capacity'] ?? 0);
        $lunghezza = isset($_POST['product-length']) && $_POST['product-length'] !== '' ? (float)$_POST['product-length'] : null;
        $richiedePatente = !empty($_POST['requires-license']) ? 1 : 0;
        $accessibile = !empty($_POST['is-accessible']) ? 1 : 0;
        $status = $_POST['product-status'] ?? 'available';
        $urlImg = trim($_POST['product-image-main'] ?? '');
        $altImg = trim($_POST['Testo_Alternativo'] ?? '');
        $lingue = $_POST['product-languages'] ?? [];
        $features = trim($_POST['product-features'] ?? '');
        $prodIdPost = trim($_POST['product-id'] ?? '');

        $errors = [];
        if ($nome === '') $errors[] = 'Inserisci il nome del prodotto';
        if ($tipo !== 'noleggio' && $tipo !== 'experience') $errors[] = 'Seleziona il tipo di prodotto';
        if ($descrBreve === '') $errors[] = 'Inserisci la descrizione breve';
        if ($prezzo <= 0) $errors[] = 'Prezzo non valido';
        if ($posti <= 0) $errors[] = 'Capacità non valida';
        if ($urlImg === '' || !filter_var($urlImg, FILTER_VALIDATE_URL)) $errors[] = 'URL immagine non valido';
        if ($altImg === '') $errors[] = 'Testo alternativo obbligatorio';
        if ($tipo === 'experience' && (empty($lingue) || !is_array($lingue))) {
            $errors[] = 'Seleziona almeno una lingua per le esperienze';
        }

        if (empty($errors)) {
            $idProdotto = $prodIdPost !== '' ? $prodIdPost : '';
            if ($idProdotto === '') {
                try {
                    $idProdotto = 'PRD-' . strtoupper(bin2hex(random_bytes(4)));
                } catch (Throwable $t) {
                    $idProdotto = 'PRD-' . time();
                }
            }
            $data = [
                'IDProdotto' => $idProdotto,
                'Tipo_Prodotto' => $tipo === 'noleggio' ? 'Noleggio' : 'Experience',
                'Tipologia_Prodotto' => $tipologia === '' ? null : $tipologia,
                'Durata_Ore' => $durata === '' ? null : (int)$durata,
                'Nome_Prodotto' => $nome,
                'Descrizione_Breve' => $descrBreve,
                'Descrizione' => $descr,
                'Prezzo_Base' => $prezzo,
                'Posti_Totali' => $posti,
                'Accessibile_Disabili' => $accessibile,
                'Lunghezza_Barca_Metri' => $lunghezza,
                'Richiede_Patente' => $richiedePatente,
                'Attivo' => $status === 'available' ? 1 : 0,
            ];

            $ok = false;
            if ($prodIdPost !== '') {
                $ok = $db->updateProdotto($prodIdPost, $data);
            } else {
                $ok = $db->creaProdotto($data);
            }

            if ($ok) {
                $db->upsertMediaProdotto($idProdotto, $urlImg, $altImg);
                $db->setLingueProdotto($idProdotto, is_array($lingue) ? $lingue : []);
                $featLines = $features === '' ? [] : array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $features)));
                $db->setInclusiProdotto($idProdotto, $featLines);
                $feedbackClass = 'alert alert-success';
                $feedback = $prodIdPost !== '' ? 'Prodotto aggiornato correttamente.' : 'Prodotto creato correttamente.';
                $mode = 'edit';
                $editingId = $idProdotto;
                $placeholders = array_merge($placeholders, [
                    '[PROD_NAME]' => htmlspecialchars($nome),
                    '[PROD_TYPE_NOLEGGIO]' => $tipo === 'noleggio' ? 'selected' : '',
                    '[PROD_TYPE_EXP]' => $tipo === 'experience' ? 'selected' : '',
                    '[PROD_TIPOLOGIA]' => htmlspecialchars($tipologia),
                    '[PROD_DURATION]' => htmlspecialchars((string)$durata),
                    '[PROD_DESC]' => htmlspecialchars($descrBreve),
                    '[PROD_DESC_LONG]' => htmlspecialchars($descr),
                    '[PROD_PRICE]' => htmlspecialchars((string)$prezzo),
                    '[PROD_CAPACITY]' => htmlspecialchars((string)$posti),
                    '[PROD_LENGTH]' => htmlspecialchars((string)($lunghezza ?? '')),
                    '[IMG_URL]' => htmlspecialchars($urlImg),
                    '[IMG_ALT]' => htmlspecialchars($altImg),
                    '[CHECK_PATENTE]' => $richiedePatente ? 'checked' : '',
                    '[CHECK_ACCESS]' => $accessibile ? 'checked' : '',
                    '[SEL_STATUS_AVAILABLE]' => $status === 'available' ? 'selected' : '',
                    '[SEL_STATUS_MAINT]' => $status === 'maintenance' ? 'selected' : '',
                    '[SEL_STATUS_UNAVAIL]' => $status === 'unavailable' ? 'selected' : '',
                    '[PROD_FEATURES]' => htmlspecialchars($features),
                ]);
                $langsCodes = is_array($lingue) ? $lingue : [];
                $placeholders['[LANG_IT]'] = in_array('IT', $langsCodes, true) ? 'checked' : '';
                $placeholders['[LANG_EN]'] = in_array('EN', $langsCodes, true) ? 'checked' : '';
                $placeholders['[LANG_FR]'] = in_array('FR', $langsCodes, true) ? 'checked' : '';
                $placeholders['[LANG_ES]'] = in_array('ES', $langsCodes, true) ? 'checked' : '';
                $placeholders['[LANG_DE]'] = in_array('DE', $langsCodes, true) ? 'checked' : '';
            } else {
                $feedbackClass = 'alert alert-error';
                $feedback = 'Errore durante il salvataggio del prodotto.';
            }
        } else {
            $feedbackClass = 'alert alert-error';
            $feedback = implode(' | ', $errors);
        }
    }
}

$html = buildPage('../pages/admin_prodotti_nuovo.html', $_SERVER['PHP_SELF']);
$html = str_replace(
    ['[ADMIN_PRODUCT_FEEDBACK]', '[CSRF_TOKEN]', '[ADMIN_PRODUCT_ACTION]', '[PROD_MODE]', '[PRODUCT_ID_VALUE]'],
    [
        $feedback ? '<div class="' . $feedbackClass . '" role="status" aria-live="polite">' . htmlspecialchars($feedback) . '</div>' : '',
        htmlspecialchars(getCsrfToken()),
        htmlspecialchars($_SERVER['PHP_SELF']),
        htmlspecialchars($mode),
        htmlspecialchars($editingId),
    ],
    $html
);

$html = str_replace(array_keys($placeholders), array_values($placeholders), $html);

echo $html;
?>
