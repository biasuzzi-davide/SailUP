<?php

require_once __DIR__ . '/../../includes/helpers.php';

http_response_code(500);

$html = buildPage(__DIR__ . '/../pages/500.html', '/php/500.php');
echo $html;
?>
