<article class="main_wrapper">
    <h2 class="home_title">Page de création</h2>
    <?php if ($flash->get('error')) : ?>
        <div class="alert-danger"><?= $flash->get('error') ?></div>
    <?php endif ?>

    <form action="" method="post">
        <input type="hidden" name="_csrf" value="<?= $token ?>">
        <?php include dirname(__DIR__) . "/assets/contact_form.php" ?>
        <input type="submit" value="Enregistrer" class="btn-orange">
    </form>
</article>