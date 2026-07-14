<?php
ob_start();
?>
<div style="margin-bottom:15px;">
    <a href="/categories" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <h2>Nouvelle catégorie</h2>

    <?php if (!empty($error)): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="/categories/store">
        <label>Libellé</label>
        <input type="text" name="libelle" placeholder="Nom de la catégorie" required>
        <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
    </form>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>
