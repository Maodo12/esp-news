<?php
require_once __DIR__ . '/../config/database.php';

echo "<h1>🧪 Test de la base de données</h1>";
echo "<hr>";

try {
    $pdo = Database::getInstance()->getPdo();
    echo "<p style='color:green; font-size:18px;'>✅ Connexion SQLite réussie !</p>";
    
    // 1. Voir les tables
    echo "<h2>📋 Structure des tables</h2>";
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll();
    foreach ($tables as $t) {
        echo "<h3>Table : " . $t['name'] . "</h3>";
        $columns = $pdo->query("PRAGMA table_info(" . $t['name'] . ")")->fetchAll();
        echo "<ul>";
        foreach ($columns as $col) {
            echo "<li>" . $col['name'] . " (" . $col['type'] . ")" . ($col['pk'] ? " 🔑 Clé primaire" : "") . "</li>";
        }
        echo "</ul>";
    }
    
    // 2. Catégories
    echo "<h2>📂 Catégories</h2>";
    $count = $pdo->query("SELECT COUNT(*) FROM Categorie")->fetchColumn();
    echo "<p><strong>Nombre total : " . $count . "</strong></p>";
    
    if ($count > 0) {
        $categories = $pdo->query("SELECT * FROM Categorie ORDER BY id")->fetchAll();
        echo "<table border='1' cellpadding='8' style='border-collapse:collapse;'>";
        echo "<tr style='background:#1a1a2e; color:white;'><th>ID</th><th>Libellé</th></tr>";
        foreach ($categories as $cat) {
            echo "<tr><td>" . $cat['id'] . "</td><td>" . htmlspecialchars($cat['libelle']) . "</td></tr>";
        }
        echo "</table>";
    }
    
    // 3. Articles
    echo "<h2>📄 Articles</h2>";
    $count = $pdo->query("SELECT COUNT(*) FROM Article")->fetchColumn();
    echo "<p><strong>Nombre total : " . $count . "</strong></p>";
    
    if ($count > 0) {
        $articles = $pdo->query("
            SELECT a.*, c.libelle as categorie_libelle 
            FROM Article a 
            LEFT JOIN Categorie c ON a.categorie = c.id
            ORDER BY a.id
        ")->fetchAll();
        echo "<table border='1' cellpadding='8' style='border-collapse:collapse;'>";
        echo "<tr style='background:#1a1a2e; color:white;'>";
        echo "<th>ID</th><th>Titre</th><th>Catégorie</th><th>Date création</th>";
        echo "</tr>";
        foreach ($articles as $a) {
            echo "<tr>";
            echo "<td>" . $a['id'] . "</td>";
            echo "<td>" . htmlspecialchars($a['titre']) . "</td>";
            echo "<td>" . htmlspecialchars($a['categorie_libelle'] ?? 'Sans catégorie') . "</td>";
            echo "<td>" . $a['dateCreation'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // 4. Test des requêtes SQL
    echo "<h2>🔍 Test des requêtes SQL</h2>";
    
    // Tester le filtrage par catégorie
    echo "<h3>Articles de la catégorie 'Sport' (id=1) :</h3>";
    $stmt = $pdo->prepare("
        SELECT a.*, c.libelle as categorie_libelle 
        FROM Article a 
        LEFT JOIN Categorie c ON a.categorie = c.id 
        WHERE a.categorie = ?
    ");
    $stmt->execute([1]);
    $sportArticles = $stmt->fetchAll();
    echo "<p>Nombre d'articles de Sport : <strong>" . count($sportArticles) . "</strong></p>";
    
    // Compter les articles par catégorie
    echo "<h3>Nombre d'articles par catégorie :</h3>";
    $stats = $pdo->query("
        SELECT c.libelle, COUNT(a.id) as nb 
        FROM Categorie c 
        LEFT JOIN Article a ON a.categorie = c.id 
        GROUP BY c.id
    ")->fetchAll();
    echo "<ul>";
    foreach ($stats as $s) {
        echo "<li>" . htmlspecialchars($s['libelle']) . " : " . $s['nb'] . " article(s)</li>";
    }
    echo "</ul>";
    
    echo "<hr>";
    echo "<p style='color:green; font-size:18px;'>✅ Tous les tests sont passés avec succès !</p>";
    
} catch (Exception $e) {
    echo "<p style='color:red; font-size:18px;'>❌ Erreur : " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>