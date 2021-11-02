<h2>Accueil général du panneau d'administration</h2>
<p>
    Bienvenue sur le panneau d'administration de votre encyclopédie.
    Ici, vous allez pouvoir gérer le site et consulter les statistiques.
</p>

<article class="card-group">
    <?php foreach ($cards as $card) : ?>
        <div class="card">
            <h3>Gérer les <?= $card['element'] ?>s</h3>
            <p>
                Il y a <strong><?= $card['stats'] ?></strong> <?= $card['element'] ?><?= ((int)$card['stats']) > 1 ? 's' : ''; ?>
            </p>
            <a href="<?= $router->url($card['admin_path']) ?>" class="btn btn-success">Administrer</a>
        </div>
    <?php endforeach; ?>
</article>