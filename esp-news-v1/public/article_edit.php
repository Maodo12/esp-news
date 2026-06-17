<?php
require_once __DIR__ . '/../includes/header.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM Article WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    echo "<h1>Article non trouvé</h1>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$categories = $pdo->query("SELECT * FROM Categorie ORDER BY libelle")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $contenu = $_POST['contenu'] ?? '';
    $categorie = $_POST['categorie'] ?? '';

    $stmt = $pdo->prepare("UPDATE Article SET titre = ?, contenu = ?, categorie = ?, dateModification = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->execute([$titre, $contenu, $categorie, $id]);
    header('Location: index.php');
    exit;
}
?>

<div style="margin-bottom:15px;">
    <a href="index.php" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <h2>Modifier l'article</h2>

    <form method="POST">
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>