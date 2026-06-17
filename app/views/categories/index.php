<?php
ob_start();
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2>Liste des catégories</h2>
    <a href="/categories/create" class="btn btn-primary">+ Nouvelle catégorie</a>
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
                    <a href="/categories/<?= $cat['id'] ?>/edit" class="btn btn-secondary">✏️ Modifier</a>
                    <form method="POST" action="/categories/<?= $cat['id'] ?>/delete" onsubmit="return confirm('Supprimer cette catégorie ?')">
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