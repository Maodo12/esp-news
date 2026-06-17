<?php
ob_start();
?>
<div style="margin-bottom:15px;">
    <a href="/articles" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <span class="badge"><?= htmlspecialchars($article['categorie_libelle']) ?></span>
    <h2 style="margin-top:15px;"><?= htmlspecialchars($article['titre']) ?></h2>
    <small style="color:#999;">
        📅 Créé le <?= $article['dateCreation'] ?> &nbsp;|&nbsp;
        ✏️ Modifié le <?= $article['dateModification'] ?>
    </small>
    <hr style="margin:15px 0; border:none; border-top:1px solid #eee;">
    <p style="line-height:1.8; font-size:15px;"><?= nl2br(htmlspecialchars($article['contenu'])) ?></p>

    <div class="actions" style="margin-top:20px;">
        <a href="/articles/<?= $article['id'] ?>/edit" class="btn btn-primary">✏️ Modifier</a>
        <form method="POST" action="/articles/<?= $article['id'] ?>/delete" onsubmit="return confirm('Supprimer cet article ?')">
            <button type="submit" class="btn btn-danger">🗑️ Supprimer</button>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';
?>