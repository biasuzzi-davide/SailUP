<?php

require_once '../../includes/helpers.php';

$html = buildPage('../pages/404.html', $_SERVER['PHP_SELF']);

// Keywords per SEO
$keywords = '<meta name="keywords" content="errore 404, pagina non trovata, errore SailUP">';
$html = str_replace('[KEYWORDS]', $keywords, $html);

echo $html;
?>