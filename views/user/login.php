<article class="main_wrapper">
    <h2 class="home_title">Page de connexion</h2>
    <p class="text-center">
        Veuillez vous connecter pour accéder à votre profil. 
        Pas de compte ? <a href="<?= $router->url("register") ?>">Inscrivez-vous</a>.
    </p>

    <?php if ($flash->get('error')) : ?>
        <div class="alert-danger"><?= $flash->get('error') ?></div>
    <?php endif ?>

    <form action="" method="post">
        <?= $form->input("username", "Votre nom", ['placeholder' => 'Votre nom']) ?>
        <?= $form->input("password", "Votre mot de passe", ['placeholder' => 'Votre mot de passe', 'type' => 'password']) ?>
        <input type="submit" value="Envoyer" class="btn-orange">
    </form>
</article>