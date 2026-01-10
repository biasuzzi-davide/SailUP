<?php

require_once '../../includes/helpers.php';

$html = buildPage('../pages/cookie.html', $_SERVER['PHP_SELF']);

// Keywords per SEO
$keywords = '<meta name="keywords" content="cookie, policy, privacy, gestione, informativa, SailUP">';
$html = str_replace('[KEYWORDS]', $keywords, $html);

echo $html;
?>