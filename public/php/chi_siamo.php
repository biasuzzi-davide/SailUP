<?php

require_once '../../includes/helpers.php';

$html = buildPage('../pages/chi_siamo.html', $_SERVER['PHP_SELF']);

// Keywords per SEO
$keywords = '<meta name="keywords" content="team, staff, azienda, storia, noleggio, barche, nautico, Napoli, SailUP">';
$html = str_replace('[KEYWORDS]', $keywords, $html);

echo $html;
?>