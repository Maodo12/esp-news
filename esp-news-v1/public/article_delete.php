<?php
require_once __DIR__ . '/../config/database.php';
$pdo = getDb();

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("DELETE FROM Article WHERE id = ?");
$stmt->execute([$id]);

header('Location: index.php');
exit;
?>