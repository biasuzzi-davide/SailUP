<?php

require_once '../../includes/helpers.php';

$html = buildPage('../pages/FAQ.html', $_SERVER['PHP_SELF']);

// Keywords per SEO
$keywords = '<meta name="keywords" content="FAQ noleggio barche, domande frequenti Napoli, come noleggiare barca, info prenotazioni, patente nautica, aiuto SailUP">';
$html = str_replace('[KEYWORDS]', $keywords, $html);

echo $html;
?>