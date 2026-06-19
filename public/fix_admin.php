<?php
require_once __DIR__ . '/../config/database.php';

$pdo = Database::getInstance()->getPdo();

echo "<h1>🔧 Réinitialisation de admin et editeur1</h1>";

try {
    // Générer de nouveaux hashs
    $hashAdmin = password_hash('admin123', PASSWORD_DEFAULT);
    $hashEdit = password_hash('edit123', PASSWORD_DEFAULT);
    
    // Mettre à jour les mots de passe
    $pdo->exec("
        UPDATE Utilisateur SET password = '$hashAdmin' WHERE login = 'admin';
        UPDATE Utilisateur SET password = '$hashEdit' WHERE login = 'editeur1';
    ");
    
    echo "<p style='color:green;'>✅ Mots de passe réinitialisés avec succès !</p>";
    
    // Vérifier
    $users = $pdo->query("SELECT login, password FROM Utilisateur WHERE login IN ('admin', 'editeur1')")->fetchAll();
    
    echo "<h2>Vérification :</h2>";
    foreach ($users as $user) {
        echo "<p><strong>" . $user['login'] . "</strong> : " . substr($user['password'], 0, 30) . "...</p>";
    }
    
    echo "<h2>🧪 Test rapide :</h2>";
    
    $testUsers = [
        ['admin', 'admin123'],
        ['editeur1', 'edit123']
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
    
    echo "<h2>🔐 Testez maintenant :</h2>";
    echo "<p><a href='/auth/login' class='btn btn-primary'>Aller à la page de connexion</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Erreur : " . $e->getMessage() . "</p>";
}
?>