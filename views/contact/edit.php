<article class="main_wrapper">
    <h2 class="home_title">Page de création</h2>
    <?php if ($flash->get('error')) : ?>
        <div class="alert-danger"><?= $flash->get('error') ?></div>
    <?php endif ?>

    <form action="" method="post">
        <?= $form->input("firstname", "Prénom du contact", ['placeholder' => 'Prénom']) ?>
        <?= $form->input("lastname", "Nom du contact", ['placeholder' => 'Nom']) ?>
        <?= $form->input("email", "Email du contact", ['placeholder' => 'Email', 'type' => 'email']) ?>
        <?= $form->input("number_phone", "Numéro de téléphone", ['placeholder' => 'Numéro de téléphone']) ?>
        <?= $form->input("address", "Adresse", ['placeholder' => 'Adresse (20 rue de Paris) ']) ?>
        <?= $form->input("city", "Ville", ['placeholder' => 'Ville (Paris)']) ?>
        <?= $form->input("country", "Pays", ['placeholder' => 'Numéro de téléphone']) ?>
        <input type="submit" value="Enregistrer" class="btn-orange">
    </form>
</article>