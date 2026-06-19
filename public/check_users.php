<?php
require_once __DIR__ . '/../config/database.php';

$pdo = Database::getInstance()->getPdo();

echo "<h1>🔍 Vérification des utilisateurs</h1>";

try {
    // Vérifier si la table Utilisateur existe
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='Utilisateur'")->fetchAll();
    
    if (empty($tables)) {
        echo "<p style='color:red;'>❌ La table Utilisateur n'existe pas !</p>";
        echo "<p>Exécutez d'abord <a href='/migrate.php'>migrate.php</a></p>";
        exit;
    }
    
    echo "<p style='color:green;'>✅ Table Utilisateur existe</p>";
    
    // Compter les utilisateurs
    $count = $pdo->query("SELECT COUNT(*) FROM Utilisateur")->fetchColumn();
    echo "<p>Nombre d'utilisateurs : <strong>" . $count . "</strong></p>";
    
    if ($count > 0) {
        $users = $pdo->query("SELECT id, login, role, password FROM Utilisateur")->fetchAll();
        echo "<h2>👥 Liste des utilisateurs :</h2>";
        echo "<table border='1' cellpadding='8'>";
        echo "<tr><th>ID</th><th>Login</th><th>Rôle</th><th>Password (hash)</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . htmlspecialchars($user['login']) . "</td>";
            echo "<td>" . htmlspecialchars($user['role']) . "</td>";
            echo "<td style='font-size:11px;'>" . substr($user['password'], 0, 30) . "...</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Test de vérification du mot de passe
        echo "<h2>🧪 Test d'authentification :</h2>";
        
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
            } else {
                echo "<p>Login: <strong>$login</strong> - <span style='color:red;'>❌ Utilisateur non trouvé</span></p>";
            }
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Erreur : " . $e->getMessage() . "</p>";
}
?>