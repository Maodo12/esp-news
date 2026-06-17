<?php
require_once __DIR__ . '/../includes/header.php';

$stmt = $pdo->query("
    SELECT a.*, c.libelle as categorie_libelle
    FROM Article a
    LEFT JOIN Categorie c ON a.categorie = c.id
    ORDER BY a.dateCreation DESC
");
$articles = $stmt->fetchAll();
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2>Liste des articles</h2>
    <a href="article_create.php" class="btn btn-primary">+ Nouvel article</a>
</div>

<?php if (empty($articles)): ?>
    <div class="card">
        <p>Aucun article disponible.</p>
    </div>
<?php else: ?>
    <?php foreach ($articles as $article): ?>
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:start; flex-wrap:wrap;">
                <div>
                    <span class="badge"><?= htmlspecialchars($article['categorie_libelle']) ?></span>
                    <h3 style="margin-top:10px;">
                        <a href="article_detail.php?id=<?= $article['id'] ?>" style="color:#1a1a2e; text-decoration:none;">
                            <?= htmlspecialchars($article['titre']) ?>
                        </a>
                    </h3>
                    <p style="color:#666; margin-top:8px; font-size:13px;">
                        <?= htmlspecialchars(substr($article['contenu'], 0, 120)) ?>...
                    </p>
                    <small class="meta">📅 <?= $article['dateCreation'] ?></small>
                </div>
                <div class="actions">
                    <a href="article_edit.php?id=<?= $article['id'] ?>" class="btn btn-secondary">✏️ Modifier</a>
                    <a href="article_delete.php?id=<?= $article['id'] ?>" class="btn btn-danger" onclick="return confirm('Supprimer cet article ?')">🗑️ Supprimer</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>