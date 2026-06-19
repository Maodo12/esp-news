<?php
ob_start();
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2>
        <?php if (isset($titre_categorie)): ?>
            Articles dans la catégorie : <?= htmlspecialchars($titre_categorie) ?>
        <?php else: ?>
            Liste des articles
        <?php endif; ?>
    </h2>
    <a href="/articles/create" class="btn btn-primary">+ Nouvel article</a>
</div>

<?php if (empty($articles)): ?>
    <div class="card">
        <p>Aucun article disponible.</p>
        <?php if (isset($titre_categorie)): ?>
            <a href="/articles" class="btn btn-secondary" style="margin-top:10px;">Voir tous les articles</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <?php foreach ($articles as $article): ?>
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:start; flex-wrap:wrap;">
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
                <div class="actions" style="margin-top:10px;">
                    <a href="/articles/<?= $article['id'] ?>/edit" class="btn btn-secondary">✏️ Modifier</a>
                    <form method="POST" action="/articles/<?= $article['id'] ?>/delete" onsubmit="return confirm('Supprimer cet article ?')">
                        <button type="submit" class="btn btn-danger">🗑️ Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    
    <!-- ⬇️⬇️⬇️ PAGINATION AJOUTÉE ⬇️⬇️⬇️ -->
    <div class="pagination" style="display:flex; justify-content:space-between; align-items:center; margin-top:20px; padding:15px 0; border-top:1px solid #eee;">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="btn btn-secondary">◀ Précédent</a>
        <?php else: ?>
            <span class="btn btn-secondary" style="opacity:0.5; cursor:not-allowed;">◀ Précédent</span>
        <?php endif; ?>
        
        <span style="color:#666;">Page <?= $page ?> sur <?= $totalPages ?> (<?= $totalArticles ?> articles)</span>
        
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>" class="btn btn-secondary">Suivant ▶</a>
        <?php else: ?>
            <span class="btn btn-secondary" style="opacity:0.5; cursor:not-allowed;">Suivant ▶</span>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>