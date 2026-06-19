<?php
require_once __DIR__ . '/../config/database.php';

$pdo = Database::getInstance()->getPdo();

echo "<h1>🔑 Réinitialisation des mots de passe</h1>";

try {
    // Vérifier si la table Utilisateur existe
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='Utilisateur'")->fetchAll();
    
    if (empty($tables)) {
        echo "<p style='color:red;'>❌ La table Utilisateur n'existe pas !</p>";
        exit;
    }
    
    // Générer de nouveaux hashs avec password_hash()
    $hashAdmin = password_hash('admin123', PASSWORD_DEFAULT);
    $hashEdit = password_hash('edit123', PASSWORD_DEFAULT);
    $hashVisit = password_hash('visit123', PASSWORD_DEFAULT);
    
    // Mettre à jour les mots de passe
    $pdo->exec("
        UPDATE Utilisateur SET password = '$hashAdmin' WHERE login = 'admin';
        UPDATE Utilisateur SET password = '$hashEdit' WHERE login = 'editeur1';
        UPDATE Utilisateur SET password = '$hashVisit' WHERE login = 'visiteur1';
    ");
    
    echo "<p style='color:green;'>✅ Mots de passe réinitialisés avec succès !</p>";
    
    // Afficher les nouveaux hashs
    echo "<h2>Nouveaux hashs générés :</h2>";
    echo "<ul>";
    echo "<li><strong>admin</strong> -> admin123 -> $hashAdmin</li>";
    echo "<li><strong>editeur1</strong> -> edit123 -> $hashEdit</li>";
    echo "<li><strong>visiteur1</strong> -> visit123 -> $hashVisit</li>";
    echo "</ul>";
    
    // Tester l'authentification
    echo "<h2>🧪 Test d'authentification après réinitialisation :</h2>";
    
    $testUsers = [
        ['admin', 'admin123'],
        ['editeur1', 'edit123'],
        ['visiteur1', 'visit123']
    ];
    
    foreach ($testUsers as $test) {
        $login = $test[0];
        $password = $test[1];
        
        $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();
        
        if ($user) {
            $valid = password_verify($password, $user['password']);
            echo "<p>Login: <strong>$login</strong> - Mot de passe: <strong>$password</strong> - ";
            echo $valid ? "<span style='color:green;'>✅ VALIDE</span>" : "<span style='color:red;'>❌ INVALIDE</span>";
            echo "</p>";
        }
    }
    
    echo "<h2>🔐 Testez maintenant la connexion :</h2>";
    echo "<p><a href='/auth/login' class='btn btn-primary'>Aller à la page de connexion</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Erreur : " . $e->getMessage() . "</p>";
}
?>