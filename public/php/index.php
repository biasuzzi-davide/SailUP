<?php

// Le seguenti righe abilitano la visualizzazione degli errori per il debug
ini_set('display_errors', 1);
error_reporting(E_ALL);
// --------------- Da rimuovere in seguito -------------------------------

require_once '../../includes/helpers.php';

$html = buildPage('../pages/index.html', $_SERVER['PHP_SELF']);

echo $html;
?>