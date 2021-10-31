<article class="main_wrapper">
    <h2 class="home_title">Liste de vos contacts</h2>
    <p class="text-right"><a href="<?= $router->url("register") ?>" class="btn btn-success">Ajouter</a></p>
    <?php if (empty($contacts)) : ?>
        <div class="alert-danger">
            <p>Vous n'avez aucun contact.</p>
        </div>
    <?php else : ?>
        <!-- table avec contact + lien edit et supprimer -->
    <?php endif; ?>
</article>