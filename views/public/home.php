<article class="main_wrapper">
    <!-- insérer une image ici -->
    <h2 class="home_title"><?= WEBSITE_LEGEND ?></h2>
    <div class="card-group">
        <div class="card">
            <h3>Gérez vos contacts</h2>
            <p>Bienvenue sur <strong>Mes Contacts</strong>, un site <strong>gratuit</strong> de gestion de vos contacts. Retrouvez les tous au même endroit.</p>
        </div>

        <div class="card">
            <h3>Editez vos contacts</h2>
            <p>Vous pouvez modifier ou supprimer à tous moments vos données d'une manière <strong>rapide</strong> et <strong>facile</strong>.</p>
        </div>
    </div>
    <div class="actions"><a href="<?= $router->url('register') ?>" class="btn-orange">Commencer à gérer vos contacts</a></div>
</article>