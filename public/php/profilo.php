<?php

require_once '../../includes/session/session.php';
require_once '../../includes/db_connection.php';
require_once '../../includes/helpers.php';

requireLogin();

$user = $_SESSION['user'] ?? [];
$db = new DBConnection();

$addrPlaceholders = [
    '[ADDR_VIA]' => '',
    '[ADDR_CIVICO]' => '',
    '[ADDR_CAP]' => '',
    '[ADDR_CITTA]' => '',
    '[ADDR_PROVINCIA]' => '',
    '[ADDR_PROVINCIA_FULL]' => '',
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
            '[ADDR_PROVINCIA_FULL]' => htmlspecialchars($addr['Provincia'] ?? ''),
            '[ADDR_PAESE]' => htmlspecialchars($addr['Paese'] ?? ''),
        ];
    }
}

$placeholders = [
    '[USER_NOME]' => htmlspecialchars($user['Nome'] ?? ''),
    '[USER_COGNOME]' => htmlspecialchars($user['Cognome'] ?? ''),
    '[USER_EMAIL]' => htmlspecialchars($user['Email'] ?? ''),
    '[USER_CF]' => htmlspecialchars($user['CF'] ?? ''),
    '[USER_PATENTE]' => htmlspecialchars($user['Numero_Patente_Nautica'] ?? '—'),
];

$statTotPren = '0';
$statAttive = '0';
$pren = $db->getPrenotazioniUtente((int)($user['IDUtente'] ?? 0));
if (is_array($pren)) {
    $statTotPren = (string) count($pren);
    $statAttive = (string) count(array_filter($pren, fn($p) => ($p['Stato_Prenotazione'] ?? '') !== 'Cancellata'));
}
$placeholders['[STAT_TOT_PREN]'] = $statTotPren;
$placeholders['[STAT_ATTIVE]'] = $statAttive;

$html = buildPage('../pages/profilo.html', $_SERVER['PHP_SELF']);
$html = str_replace(array_keys($placeholders + $addrPlaceholders), array_values($placeholders + $addrPlaceholders), $html);

// Forza i valori delle statistiche dopo il load per evitare sovrascritture client-side
$syncScript = '<script>(function(){'
    . 'const tot=' . json_encode($statTotPren) . ';'
    . 'const att=' . json_encode($statAttive) . ';'
    . 'const apply=function(){'
        . 'const t=document.querySelector(\"[data-stat=tot]\");'
        . 'const a=document.querySelector(\"[data-stat=attive]\");'
        . 'if(t) t.textContent=tot;'
        . 'if(a) a.textContent=att;'
    . '};'
    . 'document.addEventListener(\"DOMContentLoaded\",function(){'
        . 'apply();'
        . 'const targets=document.querySelectorAll(\"[data-stat=tot],[data-stat=attive]\");'
        . 'const obs=new MutationObserver(apply);'
        . 'targets.forEach(el=>obs.observe(el,{childList:true,subtree:true,characterData:true}));'
        . 'setTimeout(()=>obs.disconnect(),5000);'
    . '});'
    . '})();</script>';
$html = str_replace('</body>', $syncScript . '</body>', $html);

echo $html;
?>
