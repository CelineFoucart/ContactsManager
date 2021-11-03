<article class="main_wrapper">
    <h2 class="home_title">Voir les coordonnées d'un contact</h2>
    <?php if ($flash->get('success')) : ?>
        <div class="alert-success"><?= $flash->get('success') ?></div>
    <?php endif ?>
    <?php if ($flash->get('error')) : ?>
        <div class="alert-danger"><?= $flash->get('error') ?></div>
    <?php endif ?>
    <div class="profil__block-desk">
        <section class="block">
            <h3><?= $contact->firstname ?> <?= $contact->lastname ?></h3>
            <ul class="contact_info">
                <li><strong>Prénom :</strong> <?= $contact->firstname ?></li>
                <li><strong>Nom :</strong> <?= $contact->lastname ?></li>
                <li><strong>Email :</strong> <?= $contact->email ?></li>
                <li><strong>Numéro :</strong> <?= $contact->numberPhone ?></li>
                <li><strong>Adresse :</strong> <?= $contact->address ?> <?= $contact->city ?></li>
                <li><strong>Pays :</strong> <?= $contact->country ?></li>
            </ul>
        </section>
    </div>
    <nav class="text-center">
        <a href="<?= $router->url('contact.index') ?>" class="btn btn-blue">Retour à la liste</a>
        <a href="<?= $router->url('contact.edit', ['id' => $contact->id]) ?>" class="btn btn-success">Editer</a>
        <a href="<?= $router->url('contact.delete', ['id' => $contact->id]) ?>" class="btn btn-danger">Supprimer</a>
    </nav>
</article>