<?php
require_once '../../includes/session/session.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/utils/validation.php';
require_once '../../includes/helpers.php';

requireLogin();

// Funzione per gestire l'upload dell'immagine
function handleProfileImageUpload(int $userId): array
{
    $result = ['url' => null, 'error' => null, 'file' => null];

    if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] === UPLOAD_ERR_NO_FILE) {
        return $result;
    }

    $file = $_FILES['profile_image'];
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
        $result['error'] = 'Il sistema non supporta il formato di immagine richiesto. Contatta l\'assistenza all\'indirizzo it@sailup.it';
        return $result;
    }

    $uploadDir = __DIR__ . '/../img/avatars';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    // Forza sempre i permessi a 777
    @chmod($uploadDir, 0777);
    clearstatcache(true, $uploadDir);
    if (!is_writable($uploadDir)) {
        $result['error'] = 'Si è verificato un problema tecnico durante il caricamento dell\'immagine. Ti preghiamo di contattare l\'assistenza all\'indirizzo it@sailup.it';
        return $result;
    }

    foreach (glob($uploadDir . '/user_' . $userId . '.*') as $existing) {
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

    $filename = 'user_' . $userId . '.webp';
    $destPath = $uploadDir . '/' . $filename;

    if (!imagewebp($srcImage, $destPath, 85)) {
        imagedestroy($srcImage);
        $result['error'] = 'Si è verificato un problema nel salvataggio dell\'immagine. Riprova o contatta l\'assistenza all\'indirizzo it@sailup.it';
        return $result;
    }

    imagedestroy($srcImage);

    $result['file'] = $filename;
    $result['url'] = '../img/avatars/' . $filename . '?v=' . filemtime($destPath);
    return $result;
}

$user = $_SESSION['user'] ?? [];
$db = new DBConnection();
$addrPlaceholders = [
    '[ADDR_VIA]' => '',
    '[ADDR_CIVICO]' => '',
    '[ADDR_CAP]' => '',
    '[ADDR_CITTA]' => '',
    '[ADDR_PROVINCIA]' => '',
    '[ADDR_PAESE]' => '',
];

if (!empty($user['IDIndirizzo'])) {
    $addr = $db->getIndirizzoById((int)$user['IDIndirizzo']);
    if (is_array($addr)) {
        $addrPlaceholders = [
            '[ADDR_VIA]' => htmlspecialchars($addr['Via'] ?? ''),
            '[ADDR_CIVICO]' => htmlspecialchars($addr['N_Civico'] ?? ''),
            '[ADDR_CAP]' => htmlspecialchars($addr['CAP'] ?? ''),
            '[ADDR_CITTA]' => htmlspecialchars($addr['Citta'] ?? ''),
            '[ADDR_PROVINCIA]' => htmlspecialchars($addr['Provincia'] ?? ''),
            '[ADDR_PAESE]' => htmlspecialchars($addr['Paese'] ?? 'IT'),
        ];
    }
}

$profileState = 'hidden';
$profileMsg = '';
$pwState = 'hidden';
$pwMsg = '';
$profileImageUrl = getProfileImageUrl($user);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['form_type'] ?? '';
    $csrf = $_POST['csrf_token'] ?? null;

    if (!verifyCsrfToken($csrf)) {
        if ($formType === 'password') {
            $pwState = 'error-message';
            $pwMsg = 'Sessione scaduta, ricarica la pagina.';
        } elseif ($formType === 'delete_account') {
            $profileState = 'error-message';
            $profileMsg = 'Sessione scaduta, ricarica la pagina.';
        } else {
            $profileState = 'error-message';
            $profileMsg = 'Sessione scaduta, ricarica la pagina.';
        }
    } elseif ($formType === 'password') {
        $current = $_POST['current-password'] ?? '';
        $new = $_POST['new-password'] ?? '';
        $confirm = $_POST['confirm-password'] ?? '';

        $errors = [];
        if ($current === '')
            $errors[] = 'Inserisci la password attuale';
        if (!validatePassword($new))
            $errors[] = 'La nuova password non rispetta i requisiti';
        if ($new !== $confirm)
            $errors[] = 'Le password non coincidono';

        if (empty($errors)) {
            $db = new DBConnection();
            $check = $db->loginUser($user['Email'] ?? '', $current);
            if (!is_array($check)) {
                $errors[] = 'Password attuale errata';
            } else {
                $newHash = password_hash($new, PASSWORD_DEFAULT);
                if ($db->updateUserPassword((int) $user['IDUtente'], $newHash)) {
                    $pwState = 'success-message';
                    $pwMsg = 'Password aggiornata con successo.';
                    // Aggiorna la sessione
                    $user['PasswordHash'] = $newHash;
                    $_SESSION['user'] = $user;
                } else {
                    $errors[] = 'Errore durante l\'aggiornamento, riprova.';
                }
            }
        }

        if (!empty($errors)) {
            $pwState = 'error-message';
            $pwMsg = '<ul>';
            foreach ($errors as $err) {
                $pwMsg .= '<li>' . htmlspecialchars($err) . '</li>';
            }
            $pwMsg .= '</ul>';
        }
    } elseif ($formType === 'profile') {
        $nome = trim($_POST['nome'] ?? '');
        $cognome = trim($_POST['cognome'] ?? '');
        $cf = strtoupper(trim($_POST['cf'] ?? ''));
        $email = trim($_POST['email'] ?? '');
        $via = trim($_POST['indirizzo_via'] ?? '');
        $civico = trim($_POST['indirizzo_civico'] ?? '');
        $cap = trim($_POST['indirizzo_cap'] ?? '');
        $citta = trim($_POST['indirizzo_citta'] ?? '');
        $provincia = strtoupper(trim($_POST['indirizzo_provincia'] ?? ''));
        $paese = trim($_POST['indirizzo_paese'] ?? 'IT');

        $errors = [];
        if (!isValidName($nome))
            $errors[] = 'Nome non valido';
        if (!isSurnameValid($cognome))
            $errors[] = 'Cognome non valido';
        if (!isValidCF($cf))
            $errors[] = 'Codice fiscale non valido';
        if (!isValidEmail($email))
            $errors[] = 'Email non valida';
        if (!isValidIndirizzo($via))
            $errors[] = 'Via non valida';
        if (!isValidCivico($civico))
            $errors[] = 'Civico non valido';
        if (!isValidCAP($cap))
            $errors[] = 'CAP non valido';
        if (!isValidCitta($citta))
            $errors[] = 'Città non valida';
        if (!isValidProvincia($provincia))
            $errors[] = 'Provincia non valida';

        if (empty($errors)) {
            $okAddr = true;
            if (!empty($user['IDIndirizzo'])) {
                $okAddr = $db->updateIndirizzo(
                    (int) $user['IDIndirizzo'],
                    $via,
                    $civico,
                    $cap,
                    $citta,
                    $provincia,
                    $paese === '' ? 'IT' : $paese
                );
            }

            if (!$okAddr) {
                $errors[] = 'Errore nell\'aggiornamento indirizzo';
            } else {
                $res = $db->updateUserProfile((int) $user['IDUtente'], $nome, $cognome, $cf, $email);
                if ($res === true) {
                    $user['Nome'] = $nome;
                    $user['Cognome'] = $cognome;
                    $user['CF'] = $cf;
                    $user['Email'] = $email;
                    $_SESSION['user'] = $user;

                    $uploadRes = handleProfileImageUpload((int) $user['IDUtente']);
                    $uploadError = !empty($uploadRes['error']);
                    if ($uploadError) {
                        $profileState = 'error-message';
                        $profileMsg = htmlspecialchars($uploadRes['error']);
                    } elseif (!empty($uploadRes['url'])) {
                        $profileImageUrl = $uploadRes['url'];
                        $_SESSION['user']['AvatarFile'] = $uploadRes['file'];
                        $mediaOk = $db->upsertMediaUtente((int) $user['IDUtente'], $uploadRes['url'], 'Avatar utente');
                        if (!$mediaOk) {
                            $profileState = 'error-message';
                            $profileMsg = 'Errore durante il salvataggio immagine profilo.';
                        }
                    }

                    $addrPlaceholders = [
                        '[ADDR_VIA]' => htmlspecialchars($via),
                        '[ADDR_CIVICO]' => htmlspecialchars($civico),
                        '[ADDR_CAP]' => htmlspecialchars($cap),
                        '[ADDR_CITTA]' => htmlspecialchars($citta),
                        '[ADDR_PROVINCIA]' => htmlspecialchars($provincia),
                        '[ADDR_PAESE]' => htmlspecialchars($paese === '' ? 'IT' : $paese),
                    ];

                    if (empty($uploadError)) {
                        $profileState = 'success-message';
                        $profileMsg = 'Profilo aggiornato correttamente.';
                    }
                } elseif ($res === -1) {
                    $errors[] = 'Email già utilizzata';
                } elseif ($res === -2) {
                    $errors[] = 'Codice fiscale già utilizzato';
                } else {
                    $errors[] = 'Errore durante l\'aggiornamento profilo';
                }
            }
        }

        if (!empty($errors)) {
            $profileState = 'error-message';
            $profileMsg = '<ul>';
            foreach ($errors as $err) {
                $profileMsg .= '<li>' . htmlspecialchars($err) . '</li>';
            }
            $profileMsg .= '</ul>';
        }
    } elseif ($formType === 'delete_account') {
        // Elimina l'account
        $userId = (int) $user['IDUtente'];
        $deleted = $db->deleteUser($userId);

        if ($deleted) {
            // Logout e redirect
            session_destroy();
            header('Location: index.php');
            exit;
        } else {
            $profileState = 'error-message';
            $profileMsg = 'Errore durante l\'eliminazione dell\'account. Riprova.';
        }
    }
}

$placeholders = [
    '[USER_NOME]' => htmlspecialchars($user['Nome'] ?? ''),
    '[USER_COGNOME]' => htmlspecialchars($user['Cognome'] ?? ''),
    '[USER_EMAIL]' => htmlspecialchars($user['Email'] ?? ''),
    '[USER_CF]' => htmlspecialchars($user['CF'] ?? ''),
    '[CSRF_TOKEN]' => htmlspecialchars(getCsrfToken()),
    '[PROFILE_SERVER_STATE]' => $profileState,
    '[PROFILE_SERVER_MESSAGES]' => $profileMsg,
    '[PW_SERVER_STATE]' => $pwState,
    '[PW_SERVER_MESSAGES]' => $pwMsg,
    '[PROFILE_IMAGE_URL]' => htmlspecialchars($profileImageUrl),
] + $addrPlaceholders;

$html = buildPage('../pages/profilo_sicurezza.html', $_SERVER['PHP_SELF']);
$html = str_replace('[ADMIN_MENU_ITEM]', buildAdminMenuItem(), $html);
$html = str_replace('[ADMIN_BREADCRUMB]', buildAdminBreadcrumb(), $html);
$html = str_replace(array_keys($placeholders), array_values($placeholders), $html);

echo $html;
?>