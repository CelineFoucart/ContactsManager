<article class="main_wrapper">
    <h2 class="home_title">Page d'inscription</h2>
    <?php if ($flash->get('error')) : ?>
        <div class="alert-danger"><?= $flash->get('error') ?></div>
    <?php endif ?>
    <p class="text-center">Pour vous inscrire, remplissez le formulaire suivant. Tous les champs sont obligatoires.</p>
    <p class="text-center">Déjà un compte ? <a href="<?= $router->url("login") ?>">Connectez-vous</a>.</p>
    <form action="" method="post">
        <?= $form->input("username", "Votre nom", ['placeholder' => 'Votre nom']) ?>
        <?= $form->input("email", "Votre email", ['placeholder' => 'Votre email', 'type' => 'email']) ?>
        <?= $form->input("password", "Votre mot de passe", ['placeholder' => "votre mot de passe", "type" => "password", 'autocomplete' => 'new-password']) ?>
        <?= $form->input('confirm', 'Confirmez le mot de passe', ['placeholder' => "confirmez votre mot de passe", "type" => "password"]) ?>
        <input type="submit" value="Envoyer" class="btn-orange">
    </form>
</article>