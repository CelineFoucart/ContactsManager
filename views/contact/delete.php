<article class="main_wrapper">
    <h2 class="home_title">Page de suppression</h2>
    <p>
        Êtes-vous sûr de vouloir supprimer le contact <strong><?= $contact->firstname ?> <?= $contact->lastname ?></strong> ?
        Tout retour en arrière sera impossible.
    </p>
    <?php if ($flash->get('error')) : ?>
        <div class="alert-danger"><?= $flash->get('error') ?></div>
    <?php endif ?>

    <form action="" method="post">
        <input type="hidden" name="_csrf" value="<?= $token ?>">
        <input type="submit" value="Supprimer" class="btn-orange">
    </form>
</article>