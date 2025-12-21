<?php

require_once '../../includes/session/session.php';
require_once '../../includes/helpers.php';

requireAdmin();

$html = buildPage('../pages/admin_blog_nuovo.html', $_SERVER['PHP_SELF']);

echo $html;
?>
