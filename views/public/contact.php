<article class="main_wrapper">
    <h2 class="home_title">Page de contact</h2>

    <?php if ($flash->get('success')) : ?>
        <div class="alert-success"><?= $flash->get('success') ?></div>
    <?php endif ?>
    <?php if ($flash->get('error')) : ?>
        <div class="alert-danger"><?= $flash->get('error') ?></div>
    <?php endif ?>

    <form action="" method="post">
        <?= $form->input("name", "Votre nom", ['placeholder' => 'Votre nom']) ?>
        <?= $form->input("mail", "Votre email", ['placeholder' => 'Votre email', 'type' => 'email']) ?>
        <?= $form->input("subject", "L'objet du message", ['placeholder' => 'L\'objet du message']) ?>
        <?= $form->textarea("content", "Votre message", ['placeholder' => 'Votre message']) ?>
        <input type="submit" value="Envoyer" class="btn-orange">
    </form>
</article>