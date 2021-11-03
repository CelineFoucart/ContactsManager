<h2>Page d'édition</h2>
<?php if ($flash->get('success')) : ?>
    <div class="alert-success"><?= $flash->get('success') ?></div>
<?php endif ?>
<?php if ($flash->get('error')) : ?>
    <div class="alert-danger"><?= $flash->get('error') ?></div>
<?php endif ?>

<form action="" method="post">
    <?php include dirname(dirname(__DIR__)) . "/assets/contact_form.php" ?>
    <input type="hidden" name="_csrf" value="<?= $token ?>">
    <button type="submit" class="btn btn-blue">Enregistrer</button>
</form>