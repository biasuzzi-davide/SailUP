<?php

require_once '../../includes/helpers.php';

http_response_code(404);
$html = buildPage('../pages/404.html', $_SERVER['PHP_SELF']);

echo $html;
?>