<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/main.css">
	<title><?= $title ?? 'Mes contacts' ?></title>
</head>
<body>
	<div class="container">
		<header>
			<nav></nav>
			<h1><strong><?= WEBSITE_NAME ?></strong>, <span><?= WEBSITE_LEGEND ?></span></h1>
		</header>
		<main>
			<?= $content ?? 'Cette page n\'existe pas' ?>
		</main>
		<footer>
			<h5><strong><?= WEBSITE_NAME ?></strong>, <span><?= WEBSITE_LEGEND ?></span></h5>
			<small>© Celine Foucart</small>
		</footer>
	</div>
</body>
</html>