<?php
ob_start();
?>
<div style="margin-bottom:15px;">
    <a href="/articles" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <h2>Modifier l'article</h2>

    <?php if (!empty($error)): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="/articles/<?= $article['id'] ?>/update">
        <label>Titre</label>
        <input type="text" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required>

        <label>Catégorie</label>
        <select name="categorie" required>
            <option value="">-- Choisir une catégorie --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $article['categorie'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Contenu</label>
        <textarea name="contenu" required><?= htmlspecialchars($article['contenu']) ?></textarea>

        <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
    </form>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>