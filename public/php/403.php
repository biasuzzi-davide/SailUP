<?php

require_once '../../includes/helpers.php';
require_once '../../includes/session/session.php';

$html = buildPage('../pages/403.html', $_SERVER['PHP_SELF']);

// Keywords per SEO (pagine di errore sono noindex)
$keywords = '<meta name="keywords" content="403, accesso, negato, SailUP">';

$html = str_replace('[KEYWORDS]', $keywords, $html);

http_response_code(403);
echo $html;
?>
