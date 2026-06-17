<?php
require_once __DIR__ . '/../includes/header.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM Categorie WHERE id = ?");
$stmt->execute([$id]);
$categorie = $stmt->fetch();

if (!$categorie) {
    echo "<h1>Catégorie non trouvée</h1>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $libelle = $_POST['libelle'] ?? '';

    $stmt = $pdo->prepare("UPDATE Categorie SET libelle = ? WHERE id = ?");
    $stmt->execute([$libelle, $id]);
    header('Location: categories.php');
    exit;
}
?>

<div style="margin-bottom:15px;">
    <a href="categories.php" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <h2>Modifier la catégorie</h2>

    <form method="POST">
        <label>Libellé</label>
        <input type="text" name="libelle" value="<?= htmlspecialchars($categorie['libelle']) ?>" required>
        <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>