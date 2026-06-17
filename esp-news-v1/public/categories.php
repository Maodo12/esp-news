<?php
require_once __DIR__ . '/../includes/header.php';

$categories = $pdo->query("SELECT * FROM Categorie ORDER BY libelle")->fetchAll();
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2>Liste des catégories</h2>
    <a href="categorie_create.php" class="btn btn-primary">+ Nouvelle catégorie</a>
</div>

<?php if (empty($categories)): ?>
    <div class="card">
        <p>Aucune catégorie disponible.</p>
    </div>
<?php else: ?>
    <?php foreach ($categories as $cat): ?>
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <h3><?= htmlspecialchars($cat['libelle']) ?></h3>
                <div class="actions">
                    <a href="categorie_edit.php?id=<?= $cat['id'] ?>" class="btn btn-secondary">✏️ Modifier</a>
                    <a href="categorie_delete.php?id=<?= $cat['id'] ?>" class="btn btn-danger" onclick="return confirm('Supprimer cette catégorie ?')">🗑️ Supprimer</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>