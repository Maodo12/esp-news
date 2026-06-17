<?php
require_once __DIR__ . '/../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $libelle = $_POST['libelle'] ?? '';

    if (empty($libelle)) {
        $error = "Le libellé est obligatoire.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO Categorie (libelle) VALUES (?)");
        $stmt->execute([$libelle]);
        header('Location: categories.php');
        exit;
    }
}
?>

<div style="margin-bottom:15px;">
    <a href="categories.php" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <h2>Nouvelle catégorie</h2>

    <?php if ($error): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Libellé</label>
        <input type="text" name="libelle" placeholder="Nom de la catégorie" required>
        <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>