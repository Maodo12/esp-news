<?php
require_once __DIR__ . '/../config/database.php';

try {
    $pdo = Database::getInstance()->getPdo();
    
    // Créer la table Utilisateur
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS Utilisateur (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            login VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(20) DEFAULT 'visiteur',
            dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✅ Table Utilisateur créée avec succès<br>";

    // Vérifier s'il y a déjà des utilisateurs
    $count = $pdo->query("SELECT COUNT(*) FROM Utilisateur")->fetchColumn();
    if ($count == 0) {
        // Insérer les utilisateurs par défaut
        $pdo->exec("
            INSERT INTO Utilisateur (login, password, role) VALUES 
                ('admin', 'admin123', 'administrateur'),
                ('editeur1', 'edit123', 'editeur'),
                ('visiteur1', 'visit123', 'visiteur')
        ");
        echo "✅ Utilisateurs par défaut insérés<br>";
    } else {
        echo "ℹ️ Des utilisateurs existent déjà<br>";
    }

    // Ajouter la colonne utilisateur_id dans Article
    try {
        $pdo->exec("ALTER TABLE Article ADD COLUMN utilisateur_id INTEGER");
        echo "✅ Colonne utilisateur_id ajoutée à Article<br>";
    } catch (PDOException $e) {
        echo "ℹ️ La colonne utilisateur_id existe déjà ou erreur : " . $e->getMessage() . "<br>";
    }

    // Afficher les utilisateurs
    $users = $pdo->query("SELECT * FROM Utilisateur")->fetchAll();
    echo "<h2>👥 Utilisateurs dans la base :</h2>";
    echo "<ul>";
    foreach ($users as $user) {
        echo "<li>ID: " . $user['id'] . " - Login: " . htmlspecialchars($user['login']) . " - Rôle: " . htmlspecialchars($user['role']) . "</li>";
    }
    echo "</ul>";
    
    echo "<p style='color:green;'><strong>✅ Migration terminée avec succès !</strong></p>";
    echo "<p><a href='/'>Retour à l'accueil</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Erreur : " . $e->getMessage() . "</p>";
}
?>