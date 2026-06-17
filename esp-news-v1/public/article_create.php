<?php
require_once __DIR__ . '/../includes/header.php';

$categories = $pdo->query("SELECT * FROM Categorie ORDER BY libelle")->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $contenu = $_POST['contenu'] ?? '';
    $categorie = $_POST['categorie'] ?? '';

    if (empty($titre) || empty($contenu) || empty($categorie)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO Article (titre, contenu, categorie) VALUES (?, ?, ?)");
        $stmt->execute([$titre, $contenu, $categorie]);
        header('Location: index.php');
        exit;
    }
}
?>

<div style="margin-bottom:15px;">
    <a href="index.php" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <h2>Nouvel article</h2>

    <?php if ($error): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>