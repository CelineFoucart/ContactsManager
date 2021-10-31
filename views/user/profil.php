<article class="main_wrapper">
    <h2 class="home_title">Bienvenue sur votre profil !</h2>
    <p class="text-right"><a href="<?= $router->url("contactList") ?>" class="btn btn-success">Mes contacts</a></p>
    <?php if ($flash->get('success')) : ?>
        <div class="alert-success"><?= $flash->get('success') ?></div>
    <?php endif ?>
    <?php if ($flash->get('error')) : ?>
        <div class="alert-danger"><?= $flash->get('error') ?></div>
    <?php endif ?>
    <div class="card-group">
        <div class="card">
            <h3>Mettre à jour mon email</h3>
            <form action="" method="post">
                <?= $form->input("email", "Votre email", ['placeholder' => 'Votre email', 'type' => 'email']) ?>
                <input type="submit" value="Envoyer" class="btn-orange">
            </form>
        </div>

        <div class="card">
            <h3>Mettre à jour mon mot de passe</h3>
            <form action="" method="post">
                <?= $form->input("password", "Votre nouveau mot de passe", ['placeholder' => "Mot de passe", "type" => "password", 'autocomplete' => 'new-password']) ?>
                <?= $form->input('confirm', 'Confirmez le mot de passe', ['placeholder' => "confirmez le mot de passe", "type" => "password"]) ?>
                <input type="submit" value="Envoyer" class="btn-orange">
            </form>
        </div>
    </div>
</article>