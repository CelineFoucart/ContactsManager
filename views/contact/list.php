<article class="main_wrapper">
    <h2 class="home_title">Liste de vos contacts</h2>
    <p class="text-center"><a href="<?= $router->url("contact.create") ?>" class="btn btn-success">Ajouter</a></p>
    <?php if ($flash->get('success')) : ?>
        <div class="alert-success"><?= $flash->get('success') ?></div>
    <?php endif ?>
    <?php if (empty($contacts)) : ?>
        <div class="alert-danger">
            <p>Vous n'avez aucun contact.</p>
        </div>
    <?php else : ?>
        <table class="default_table">
            <thead>
                <tr>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Numéro</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contacts as $contact) : ?>
                    <tr>
                        <td><?= $contact->firstname ?></td>
                        <td><?= $contact->lastname ?></td>
                        <td><?= $contact->email ?></td>
                        <td><?= $contact->numberPhone ?></td>
                        <td>
                            <a href="<?= $router->url('contact.show', ['id' => $contact->id]) ?>" class="btn btn-blue">Voir plus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <nav class="paging-block">
        <div class="pagination">
            <?= $pagination['previous'] ?>
            <?= join(" ", $pagination['pages']) ?>
            <?= $pagination['next'] ?>
        </div>
    </nav>
</article>