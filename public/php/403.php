<?php

require_once '../../includes/helpers.php';
require_once '../../includes/session/session.php';

$html = buildPage('../pages/403.html', $_SERVER['PHP_SELF']);

http_response_code(403);
echo $html;
?>
