<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/ArticleModel.php';

echo "<h1>Test direct du modèle ArticleModel</h1>";

try {
    $model = new ArticleModel();
    $articles = $model->findAll();
    
    echo "<p>Nombre d'articles trouvés : <strong>" . count($articles) . "</strong></p>";
    
    if (count($articles) > 0) {
        echo "<h2>Liste des articles :</h2>";
        echo "<ul>";
        foreach ($articles as $a) {
            echo "<li>";
            echo "ID: " . $a['id'] . " - ";
            echo "<strong>" . htmlspecialchars($a['titre']) . "</strong>";
            echo " (Catégorie: " . htmlspecialchars($a['categorie_libelle']) . ")";
            echo "</li>";
        }
        echo "</ul>";
        
        // Test du render avec la vue
        echo "<h2>Test du rendu avec la vue :</h2>";
        ob_start();
        require_once __DIR__ . '/../app/views/articles/index.php';
        $content = ob_get_clean();
        echo "<div style='border:2px solid green; padding:10px;'>";
        echo $content;
        echo "</div>";
        
    } else {
        echo "<p style='color:red;'>⚠️ Aucun article trouvé !</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Erreur : " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>