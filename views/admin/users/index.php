<h2 class="admin-title">Gestion des membres</h2>
<aside class="admin_nav_tools">
    <a href="<?= $router->url('admin.users.create') ?>" class="btn btn-blue">Ajouter</a>
</aside>
<?php if ($flash->get('success')) : ?>
    <div class="alert-success"><?= $flash->get('success') ?></div>
<?php endif ?>
<table class="default_table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Prénom</th>
            <th>Email</th>
            <th class="actions">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?= App\Tools\HTML\TableHelper::loop($items, $router, "users") ?>
    </tbody>
</table>
<nav class="paging-block">
    <div class="pagination">
        <?= $pagination['previous'] ?>
        <?= join(" ", $pagination['pages']) ?>
        <?= $pagination['next'] ?>
    </div>
</nav>