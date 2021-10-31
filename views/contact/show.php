<article class="main_wrapper">
    <h2 class="home_title">Voir les coordonnées d'un contact</h2>
    <?php if ($flash->get('success')) : ?>
        <div class="alert-success"><?= $flash->get('success') ?></div>
    <?php endif ?>
    <?php if ($flash->get('error')) : ?>
        <div class="alert-danger"><?= $flash->get('error') ?></div>
    <?php endif ?>
    <!-- infos du contact -->
    <!-- liens éditer et supprimer -->
</article>