<?php

require_once '../../includes/helpers.php';

$html = buildPage('../pages/404.html', $_SERVER['PHP_SELF']);

// Keywords per SEO
$keywords = '<meta name="keywords" content="pagina, trovata, SailUP">';
$html = str_replace('[KEYWORDS]', $keywords, $html);

echo $html;
?>