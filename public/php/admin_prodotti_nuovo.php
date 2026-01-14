<?php

require_once '../../includes/session/session.php';
require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/utils/validation.php';

requireAdmin();

$uploadDirProducts = __DIR__ . '/../img/prodotti';

/**
 * per l'upload dell'immagine prodotto con conversione in webp
 */
function handleProductImageUpload(string $productId, string $uploadDir): array {
    $result = ['url' => null, 'error' => null, 'file' => null];

    if (!isset($_FILES['product_image_main']) || $_FILES['product_image_main']['error'] === UPLOAD_ERR_NO_FILE) {
        return $result;
    }

    $file = $_FILES['product_image_main'];
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
        $result['error'] = 'Il sistema non supporta il formato di immagine richiesto.';
        return $result;
    }

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    // Forza sempre i permessi a 777
    @chmod($uploadDir, 0777);
    clearstatcache(true, $uploadDir);
    if (!is_writable($uploadDir)) {
        $result['error'] = 'Cartella upload non scrivibile. Verifica i permessi della cartella: ' . basename($uploadDir);
        return $result;
    }

    $safeId = preg_replace('/[^A-Za-z0-9_-]/', '-', $productId);
    foreach (glob($uploadDir . '/prod_' . $safeId . '.*') as $existing) {
        @unlink($existing);
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
        $result['error'] = 'Il file caricato sembra essere danneggiato. Riprova con un\'altra immagine.';
        return $result;
    }

    $filename = 'prod_' . $safeId . '.webp';
    $destPath = $uploadDir . '/' . $filename;

    if (!imagewebp($srcImage, $destPath, 85)) {
        imagedestroy($srcImage);
        $result['error'] = 'Si è verificato un problema nel salvataggio dell\'immagine.';
        return $result;
    }

    imagedestroy($srcImage);

    $result['file'] = $filename;
    $result['url'] = '../img/prodotti/' . $filename . '?v=' . filemtime($destPath);
    return $result;
}

function getBoatTypePlaceholders(?string $tipologia): array {
    $key = strtolower(trim((string) $tipologia));
    return [
        '[PROD_TIPOLOGIA_MOTORE]' => $key === 'motore' ? 'selected' : '',
        '[PROD_TIPOLOGIA_VELA]' => $key === 'vela' ? 'selected' : '',
        '[PROD_TIPOLOGIA_GOMMONE]' => $key === 'gommone' ? 'selected' : '',
    ];
}

$db = new DBConnection();
$feedback = '';
$feedbackClass = 'hidden';
$mode = 'create';
$editingId = '';
$placeholders = [
    '[PROD_NAME]' => '',
    '[PROD_TYPE_NOLEGGIO]' => '',
    '[PROD_TYPE_EXP]' => '',
    '[PROD_TIPOLOGIA_MOTORE]' => '',
    '[PROD_TIPOLOGIA_VELA]' => '',
    '[PROD_TIPOLOGIA_GOMMONE]' => '',
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
    '[CHECK_STATUS_AVAILABLE]' => '',
    '[LANG_IT]' => '',
    '[LANG_EN]' => '',
    '[LANG_FR]' => '',
    '[LANG_ES]' => '',
    '[LANG_DE]' => '',
    '[PROD_EXTRAS]' => '',
];
$placeholders['[CHECK_STATUS_AVAILABLE]'] = 'checked';
$extrasForForm = [];

if (isset($_GET['id']) && trim($_GET['id']) !== '') {
    $editingId = trim($_GET['id']);
    $prod = $db->getProdottoAdminById($editingId);
    if (is_array($prod)) {
        $mode = 'edit';
        $langs = $db->getLinguePerProdotto($editingId) ?: [];
        $inclusi = $db->getProdottoInclusi($editingId) ?: [];
        $extrasDb = $db->getProdottoExtra($editingId);
        if (is_array($extrasDb)) {
            foreach ($extrasDb as $ex) {
                $extrasForForm[] = [
                    'nome' => $ex['Nome_Extra'] ?? '',
                    'prezzo' => isset($ex['Prezzo_Extra']) ? (int) $ex['Prezzo_Extra'] : '',
                ];
            }
        }
        $langsCodes = array_map(fn($row) => $row['Codice'] ?? '', $langs);
        $placeholders = array_merge($placeholders, [
            '[PROD_NAME]' => htmlspecialchars($prod['Nome_Prodotto'] ?? ''),
            '[PROD_TYPE_NOLEGGIO]' => ($prod['Tipo_Prodotto'] ?? '') === 'Noleggio' ? 'selected' : '',
            '[PROD_TYPE_EXP]' => ($prod['Tipo_Prodotto'] ?? '') === 'Experience' ? 'selected' : '',
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
            '[CHECK_STATUS_AVAILABLE]' => !empty($prod['Attivo']) ? 'checked' : '',
            '[PROD_FEATURES]' => htmlspecialchars(implode("\n", array_map(fn($row) => $row['Nome_Incluso'] ?? '', $inclusi))),
        ]);
        $placeholders = array_merge($placeholders, getBoatTypePlaceholders($prod['Tipologia_Prodotto'] ?? ''));
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
        $boatTypeMap = [
            'motore' => 'Motore',
            'vela' => 'Vela',
            'gommone' => 'Gommone',
        ];
        $tipologiaRaw = trim($_POST['product-category'] ?? '');
        $tipologiaKey = strtolower($tipologiaRaw);
        $tipologia = $boatTypeMap[$tipologiaKey] ?? '';
        $durata = $_POST['product-duration'] ?? '';
        $descrBreve = trim($_POST['product-description'] ?? '');
        $descr = trim($_POST['product-long-description'] ?? '');
        $prezzo = (float)($_POST['product-price'] ?? 0);
        $posti = (int)($_POST['product-capacity'] ?? 0);
        $lunghezza = isset($_POST['product-length']) && $_POST['product-length'] !== '' ? (float)$_POST['product-length'] : null;
        $richiedePatente = !empty($_POST['requires-license']) ? 1 : 0;
        $accessibile = !empty($_POST['is-accessible']) ? 1 : 0;
        $status = isset($_POST['product-status']) ? 'available' : 'unavailable';
        $urlImg = trim($_POST['existing-image-url'] ?? '');
        $altImg = trim($_POST['Testo_Alternativo'] ?? '');
        $lingue = $_POST['product-languages'] ?? [];
        $features = trim($_POST['product-features'] ?? '');
        $extraNames = $_POST['extra_name'] ?? [];
        $extraPrices = $_POST['extra_price'] ?? [];
        $prodIdPost = trim($_POST['product-id'] ?? '');
        $uploadRes = ['url' => null, 'error' => null, 'file' => null];
        if ($tipo !== 'experience') {
            $durata = '';
        }
        if ($tipo !== 'noleggio') {
            $lunghezza = null;
        }
        if ($tipo !== 'noleggio') {
            if ($prodIdPost !== '') {
                $currentProd = $db->getProdottoAdminById($prodIdPost);
                $tipologia = is_array($currentProd) ? trim((string) ($currentProd['Tipologia_Prodotto'] ?? '')) : '';
            } else {
                $tipologia = '';
            }
        }

        $errors = [];
        if ($nome === '') $errors[] = 'Inserisci il nome del prodotto';
        if ($tipo !== 'noleggio' && $tipo !== 'experience') $errors[] = 'Seleziona il tipo di prodotto';
        if ($descrBreve === '') $errors[] = 'Inserisci la descrizione breve';
        if ($descr === '') $errors[] = 'Inserisci la descrizione dettagliata';
        if ($prezzo <= 0) $errors[] = 'Prezzo non valido';
        if ($prezzo > 50000) $errors[] = 'Il prezzo non può superare €50.000';
        if ($posti <= 0) $errors[] = 'Capacità non valida';
        $hasNewImage = isset($_FILES['product_image_main']) && $_FILES['product_image_main']['error'] !== UPLOAD_ERR_NO_FILE;
        if (!$hasNewImage && $urlImg === '') {
            $errors[] = 'Seleziona un\'immagine per il prodotto';
        }
        if ($altImg === '') $errors[] = 'Testo alternativo obbligatorio';
        if ($tipo === 'noleggio' && $tipologia === '') {
            $errors[] = 'Seleziona la tipologia di barca per il noleggio';
        }
        if ($tipo === 'experience') {
            if ($durata === '' || (int) $durata < 1) {
                $errors[] = 'Inserisci la durata dell\'esperienza in ore';
            }
            if (empty($lingue) || !is_array($lingue)) {
                $errors[] = 'Seleziona almeno una lingua per le esperienze';
            }
        }

        $extras = [];
        if (is_array($extraNames) && is_array($extraPrices)) {
            $len = max(count($extraNames), count($extraPrices));
            for ($i = 0; $i < $len; $i++) {
                $nomeExtra = trim($extraNames[$i] ?? '');
                $prezzoRaw = trim((string)($extraPrices[$i] ?? ''));
                if ($nomeExtra === '' && $prezzoRaw === '') {
                    continue;
                }
                if ($nomeExtra === '') {
                    $errors[] = 'Inserisci il nome per ogni extra';
                    continue;
                }
                $prezzoVal = filter_var($prezzoRaw, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
                if ($prezzoVal === false) {
                    $errors[] = 'Prezzo extra non valido';
                    continue;
                }
                if ($prezzoVal > 10000) {
                    $errors[] = 'Il prezzo extra non può superare €10.000';
                    continue;
                }
                $extras[] = [
                    'nome' => $nomeExtra,
                    'prezzo' => (int) $prezzoVal,
                ];
            }
        }
        $extrasForForm = $extras;

        if (empty($errors)) {
            $idProdotto = $prodIdPost !== '' ? $prodIdPost : '';
            if ($idProdotto === '') {
                try {
                    $idProdotto = 'PRD-' . strtoupper(bin2hex(random_bytes(4)));
                } catch (Throwable $t) {
                    $idProdotto = 'PRD-' . time();
                }
            }
            $uploadRes = ['url' => null, 'error' => null, 'file' => null];
            if ($hasNewImage) {
                $uploadRes = handleProductImageUpload($idProdotto, $uploadDirProducts);
                if (!empty($uploadRes['error'])) {
                    $errors[] = $uploadRes['error'];
                }
            }
        }

        if (empty($errors)) {
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
                $finalUrl = !empty($uploadRes['url']) ? $uploadRes['url'] : $urlImg;
                $db->upsertMediaProdotto($idProdotto, $finalUrl, $altImg);
                $db->setLingueProdotto($idProdotto, is_array($lingue) ? $lingue : []);
                $featLines = $features === '' ? [] : array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $features)));
                $db->setInclusiProdotto($idProdotto, $featLines);
                if (!$db->setProdottoExtra($idProdotto, $extras)) {
                    $feedbackClass = 'alert alert-error';
                    $feedback = 'Errore durante il salvataggio degli extra.';
                }
                //indirizzo l admin alla catalago corispondente in base al tipo di prodotto creato
                if ($feedback === '' && $prodIdPost === '') {
                    $redirectTarget = $tipo === 'noleggio' ? 'catalogo_noleggio.php' : 'catalogo_esperienze.php';
                    header('Location: ' . $redirectTarget);
                    exit;
                }
                if ($feedback === '') {
                    $feedbackClass = 'alert alert-success';
                    $feedback = $prodIdPost !== '' ? 'Prodotto aggiornato correttamente.' : 'Prodotto creato correttamente.';
                    $mode = 'edit';
                    $editingId = $idProdotto;
                }
                $placeholders = array_merge($placeholders, [
                    '[PROD_NAME]' => htmlspecialchars($nome),
                    '[PROD_TYPE_NOLEGGIO]' => $tipo === 'noleggio' ? 'selected' : '',
                    '[PROD_TYPE_EXP]' => $tipo === 'experience' ? 'selected' : '',
                    '[PROD_DURATION]' => htmlspecialchars((string)$durata),
                    '[PROD_DESC]' => htmlspecialchars($descrBreve),
                    '[PROD_DESC_LONG]' => htmlspecialchars($descr),
                    '[PROD_PRICE]' => htmlspecialchars((string)$prezzo),
                    '[PROD_CAPACITY]' => htmlspecialchars((string)$posti),
                    '[PROD_LENGTH]' => htmlspecialchars((string)($lunghezza ?? '')),
                    '[IMG_URL]' => htmlspecialchars($finalUrl),
                    '[IMG_ALT]' => htmlspecialchars($altImg),
                    '[CHECK_PATENTE]' => $richiedePatente ? 'checked' : '',
                    '[CHECK_ACCESS]' => $accessibile ? 'checked' : '',
                    '[CHECK_STATUS_AVAILABLE]' => $status === 'available' ? 'checked' : '',
                    '[PROD_FEATURES]' => htmlspecialchars($features),
                ]);
                $placeholders = array_merge($placeholders, getBoatTypePlaceholders($tipologia));
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

        // Ripopola il form con i valori inseriti dall'utente
        $editingId = $prodIdPost !== '' ? $prodIdPost : $editingId;
        $placeholders = array_merge($placeholders, [
            '[PROD_NAME]' => htmlspecialchars($nome),
            '[PROD_TYPE_NOLEGGIO]' => $tipo === 'noleggio' ? 'selected' : '',
            '[PROD_TYPE_EXP]' => $tipo === 'experience' ? 'selected' : '',
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
            '[CHECK_STATUS_AVAILABLE]' => $status === 'available' ? 'checked' : '',
            '[PROD_FEATURES]' => htmlspecialchars($features),
        ]);
        $placeholders = array_merge($placeholders, getBoatTypePlaceholders($tipologia));
        $langsCodes = is_array($lingue) ? $lingue : [];
        $placeholders['[LANG_IT]'] = in_array('IT', $langsCodes, true) ? 'checked' : '';
        $placeholders['[LANG_EN]'] = in_array('EN', $langsCodes, true) ? 'checked' : '';
        $placeholders['[LANG_FR]'] = in_array('FR', $langsCodes, true) ? 'checked' : '';
        $placeholders['[LANG_ES]'] = in_array('ES', $langsCodes, true) ? 'checked' : '';
        $placeholders['[LANG_DE]'] = in_array('DE', $langsCodes, true) ? 'checked' : '';
    }
}
}

$html = buildPage('../pages/admin_prodotti_nuovo.html', $_SERVER['PHP_SELF']);

$isEdit = $mode === 'edit';
$pageTitle = $isEdit ? 'Modifica Prodotto' : 'Aggiungi Nuovo Prodotto';
$pageSub = $isEdit ? 'Aggiorna i dettagli di barca o esperienza' : 'Inserisci i dettagli della nuova barca o esperienza';
$placeholders['[PROD_TITLE]'] = $pageTitle;
$placeholders['[PROD_HEADING]'] = $pageTitle;
$placeholders['[PROD_SUBHEADING]'] = $pageSub;
$placeholders['[PROD_BREADCRUMB]'] = $isEdit ? 'Modifica Prodotto' : 'Nuovo Prodotto';
$placeholders['[PROD_EXTRAS]'] = buildProductExtraInputs($extrasForForm);

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
