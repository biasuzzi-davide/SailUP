<?php

require_once '../../includes/helpers.php';

$html = buildPage('../pages/privacy.html', $_SERVER['PHP_SELF']);

// Keywords per SEO
$keywords = '<meta name="keywords" content="privacy policy SailUP, protezione dati personali, GDPR, trattamento dati, informativa privacy">';
$html = str_replace('[KEYWORDS]', $keywords, $html);

echo $html;
?>