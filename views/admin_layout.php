<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <meta name="description" content="<?= SEO_DESCRIPTION ?>">
    <title><?= $title ?? WEBSITE_NAME ?></title>
</head>

<body>
    <nav class="admin_main_nav">
        <div class="container">
            <ul class="admin_main_nav_list">
                <li class="main"><a href="<?= $router->url("admin.index") ?>">Administration</a></li>
                <li><a href="<?= $router->url("admin.users") ?>">Utilisateurs</a></li>
                <li><a href="<?= $router->url("admin.contacts") ?>">Contacts</a></li>
            </ul>
        </div>
    </nav>
    <main class="container">
        <header class="admin_header">
            <div class="admin_header_title">
                <h1>Panneau d'administration</h1>
                <p>
                    <a href="<?= $router->url("admin.index") ?>" class="admin_header_title_link">Index de l'administration</a> ●
                    <a href="<?= $router->url("home") ?>" class="admin_header_title_link">Accueil du site</a>
                </p>
            </div>
        </header>
        <section class="admin_main">
            <?= $content; ?>
        </section>
    </main>
</body>

</html>