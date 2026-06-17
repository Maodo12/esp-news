<?php
ob_start();
?>
<div style="margin-bottom:15px;">
    <a href="/articles" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <h2>Nouvel article</h2>

    <?php if (!empty($error)): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="/articles/store">
        <label>Titre</label>
        <input type="text" name="titre" placeholder="Titre de l'article" required>

        <label>Catégorie</label>
        <select name="categorie" required>
            <option value="">-- Choisir une catégorie --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['libelle']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Contenu</label>
        <textarea name="contenu" placeholder="Contenu de l'article" required></textarea>

        <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
    </form>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>