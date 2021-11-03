<h2>Page de creation</h2>
<?php if ($flash->get('success')) : ?>
    <div class="alert-success"><?= $flash->get('success') ?></div>
<?php endif ?>
<?php if ($flash->get('error')) : ?>
    <div class="alert-danger"><?= $flash->get('error') ?></div>
<?php endif ?>

<form action="" method="post">
    <?= $form->input('username', 'Nom<sup>*</sup>', ['placeholder' => "Nom"]) ?>
    <?= $form->input('email', 'Email<sup>*</sup>', ['placeholder' => "Email"]) ?>
    <?= $form->input("password", "Votre mot de passe", ['placeholder' => "votre mot de passe", "type" => "password", 'autocomplete' => 'new-password']) ?>
    <?= $form->input('confirm', 'Confirmez le mot de passe', ['placeholder' => "confirmez votre mot de passe", "type" => "password"]) ?>
    <input type="hidden" name="_csrf" value="<?= $token ?>">
    <button type="submit" class="btn btn-blue">Enregistrer</button>
</form>