<?php

require_once '../../includes/helpers.php';

$html = buildPage('../pages/privacy.html', $_SERVER['PHP_SELF']);

// Keywords per SEO
$keywords = '<meta name="keywords" content="privacy, policy, protezione, dati, personali, GDPR, trattamento, informativa, SailUP">';
$html = str_replace('[KEYWORDS]', $keywords, $html);

echo $html;
?>