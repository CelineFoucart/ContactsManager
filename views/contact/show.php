<article class="main_wrapper">
    <h2 class="home_title">Voir les coordonnées d'un contact</h2>
    <?php if ($flash->get('success')) : ?>
        <div class="alert-success"><?= $flash->get('success') ?></div>
    <?php endif ?>
    <?php if ($flash->get('error')) : ?>
        <div class="alert-danger"><?= $flash->get('error') ?></div>
    <?php endif ?>
    <table class="default_table">
        <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Numéro</th>
                <th>Adresse</th>
                <th>Pays</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $contact->firstname ?></td>
                <td><?= $contact->lastname ?></td>
                <td><?= $contact->email ?></td>
                <td><?= $contact->numberPhone ?></td>
                <td><?= $contact->address ?><br /><?= $contact->city ?></td>
                <td><?= $contact->country ?></td>
            </tr>
        </tbody>
    </table>
    <nav class="text-center">
        <a href="<?= $router->url('contactEdit', ['id' => $contact->id]) ?>" class="btn btn-success">Editer</a>
        <a href="<?= $router->url('contactDelete', ['id' => $contact->id]) ?>" class="btn btn-danger">Supprimer</a>
    </nav>
</article>