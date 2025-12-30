<?php

require_once '../../includes/helpers.php';

$html = buildPage('../pages/chi_siamo.html', $_SERVER['PHP_SELF']);

// Keywords per SEO
$keywords = '<meta name="keywords" content="chi siamo SailUP, team SailUP, azienda noleggio barche Napoli, storia SailUP, staff nautico">';
$html = str_replace('[KEYWORDS]', $keywords, $html);

echo $html;
?>