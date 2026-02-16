<?php
$target = 'public/php/index.php';
if (!headers_sent()) {
		header('Location: ' . $target, true, 302);
}
?>
<!doctype html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<meta http-equiv="refresh" content="0;url=<?php echo htmlspecialchars($target, ENT_QUOTES, 'UTF-8'); ?>">
	<title>Reindirizzamento...</title>
</head>
<body>
	Se non vieni reindirizzato automaticamente, clicca <a href="<?php echo htmlspecialchars($target, ENT_QUOTES, 'UTF-8'); ?>">qui</a>.
</body>
</html>

