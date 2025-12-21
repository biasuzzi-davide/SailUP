<?php

require_once '../../includes/session/session.php';
require_once '../../includes/helpers.php';

requireLogin();

$html = buildPage('../pages/profilo_prenotazioni.html', $_SERVER['PHP_SELF']);

echo $html;
?>
