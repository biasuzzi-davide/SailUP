<?php

require_once '../../includes/helpers.php';

$html = buildPage('../pages/FAQ.html', $_SERVER['PHP_SELF']);

// Keywords per SEO
$keywords = '<meta name="keywords" content="FAQ, domande, risposte, aiuto, noleggio, barche, prenotazioni, patente, nautica, SailUP">';
$html = str_replace('[KEYWORDS]', $keywords, $html);

echo $html;
?>