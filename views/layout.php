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
    <div class="flex_container">
        <header class="header_container">
            <nav class="header_navigation">
                <div class="header_wrapper">
                    <div class="header_brand"><a href="<?= $router->url("home") ?>"><?= WEBSITE_NAME ?></a></div>
                    <ul class="header_navigation_list">
                        <li class="header_navigation_list_item"><a href="<?= $router->url("login") ?>">Se connecter</a></li>
                        <li class="header_navigation_list_item"><a href="<?= $router->url("register") ?>">S'inscrire</a></li>
                    </ul>
                </div>
            </nav>
            <div class="header_wrapper">
                <h1 class="website_logo">
                    <strong class="logo_name"><?= WEBSITE_NAME ?></strong>
                    <span class="logo_legend"><?= WEBSITE_LEGEND ?></span>
                </h1>
            </div>
        </header>

        <main>
            <?= $content ?? 'Cette page n\'existe pas' ?>
        </main>
        <footer class="main_footer">
            <h5><strong class="logo_name"><?= WEBSITE_NAME ?></strong></h5>
            <p class="footer_links">
                <a href="<?= $router->url('contact') ?>">Contactez-nous</a> |
                <a href="<?= $router->url('about') ?>">A propos</a>
            </p>
            <small>Copyright © 2020. Tous droits réservés.<br /> © Celine Foucart</small>
            <?php if (App\Session\SessionFactory::getAuth()->isAdmin()) : ?>
                <small class="admin_panel">
                    <a href="<?= $router->url('admin.index') ?>">Accéder au panneau d'administration</a>
                </small>
            <?php endif; ?>
        </footer>
    </div>
</body>

</html>