<?php
ob_start();
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2>Liste des articles</h2>
    <a href="/articles/create" class="btn btn-primary">+ Nouvel article</a>
</div>

<?php if (empty($articles)): ?>
    <div class="card">
        <p>Aucun article disponible.</p>
    </div>
<?php else: ?>
    <?php foreach ($articles as $article): ?>
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:start;">
                <div>
                    <span class="badge"><?= htmlspecialchars($article['categorie_libelle']) ?></span>
                    <h3 style="margin-top:10px;">
                        <a href="/articles/<?= $article['id'] ?>" style="color:#1a1a2e; text-decoration:none;">
                            <?= htmlspecialchars($article['titre']) ?>
                        </a>
                    </h3>
                    <p style="color:#666; margin-top:8px; font-size:13px;">
                        <?= htmlspecialchars(substr($article['contenu'], 0, 120)) ?>...
                    </p>
                    <small style="color:#999;">📅 <?= $article['dateCreation'] ?></small>
                </div>
                <div class="actions">
                    <a href="/articles/<?= $article['id'] ?>/edit" class="btn btn-secondary">✏️ Modifier</a>
                    <form method="POST" action="/articles/<?= $article['id'] ?>/delete" onsubmit="return confirm('Supprimer cet article ?')">
                        <button type="submit" class="btn btn-danger">🗑️ Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>