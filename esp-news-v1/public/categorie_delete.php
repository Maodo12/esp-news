<?php
require_once __DIR__ . '/../config/database.php';
$pdo = getDb();

$id = $_GET['id'] ?? 0;

// Supprimer d'abord les articles liés
$stmt = $pdo->prepare("DELETE FROM Article WHERE categorie = ?");
$stmt->execute([$id]);

// Puis supprimer la catégorie
$stmt = $pdo->prepare("DELETE FROM Categorie WHERE id = ?");
$stmt->execute([$id]);

header('Location: categories.php');
exit;
?>