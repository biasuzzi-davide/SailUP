<?php

require_once '../../includes/helpers.php';

$html = buildPage('../pages/404.html', $_SERVER['PHP_SELF']);

echo $html;
?>