<?php
require_once __DIR__ . '/../config/database.php';

echo "<h1>🔍 Test de la base de données</h1>";

try {
    $pdo = Database::getInstance()->getPdo();
    echo "<p style='color:green;'>✅ Connexion SQLite réussie !</p>";
    
    // 1. Vérifier les tables
    echo "<h2>📋 Tables :</h2>";
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll();
    if (empty($tables)) {
        echo "<p style='color:red;'>⚠️ Aucune table trouvée !</p>";
    } else {
        echo "<ul>";
        foreach ($tables as $t) {
            echo "<li>" . $t['name'] . "</li>";
        }
        echo "</ul>";
    }
    
    // 2. Compter les catégories
    echo "<h2>📂 Catégories :</h2>";
    $count = $pdo->query("SELECT COUNT(*) FROM Categorie")->fetchColumn();
    echo "<p>Nombre : <strong>" . $count . "</strong></p>";
    
    if ($count > 0) {
        $categories = $pdo->query("SELECT * FROM Categorie")->fetchAll();
        echo "<ul>";
        foreach ($categories as $cat) {
            echo "<li>ID " . $cat['id'] . " : " . htmlspecialchars($cat['libelle']) . "</li>";
        }
        echo "</ul>";
    }
    
    // 3. Compter les articles
    echo "<h2>📄 Articles :</h2>";
    $count = $pdo->query("SELECT COUNT(*) FROM Article")->fetchColumn();
    echo "<p>Nombre : <strong>" . $count . "</strong></p>";
    
    if ($count > 0) {
        $articles = $pdo->query("
            SELECT a.*, c.libelle as categorie_libelle 
            FROM Article a 
            LEFT JOIN Categorie c ON a.categorie = c.id
        ")->fetchAll();
        echo "<ul>";
        foreach ($articles as $a) {
            echo "<li>ID " . $a['id'] . " : " . htmlspecialchars($a['titre']) . " - " . htmlspecialchars($a['categorie_libelle'] ?? 'Sans catégorie') . "</li>";
        }
        echo "</ul>";
    }
    
    // 4. Test de la méthode findAll du modèle
    echo "<h2>🧪 Test du modèle ArticleModel :</h2>";
    require_once __DIR__ . '/../app/models/ArticleModel.php';
    $articleModel = new ArticleModel();
    $articles = $articleModel->findAll();
    echo "<p>Résultat findAll() : <strong>" . count($articles) . "</strong> articles</p>";
    
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Erreur : " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>