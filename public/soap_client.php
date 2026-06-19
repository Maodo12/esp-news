<?php
// Client SOAP en mode non-WSDL (avec location et uri)
$options = [
    'location' => 'http://localhost:8000/soap_server.php',
    'uri' => 'http://localhost:8000/soap_server.php',
    'trace' => 1
];

try {
    $client = new SoapClient(null, $options);
    
    echo "<h1>🧪 Test du service SOAP</h1>";
    
    // 1. Authentification
    echo "<h2>1. Authentification</h2>";
    $result = $client->authenticate('admin', 'admin123');
    echo "<pre>";
    print_r($result);
    echo "</pre>";
    
    $token = $result['token'] ?? '';
    
    if ($token) {
        // 2. Lister les utilisateurs
        echo "<h2>2. Liste des utilisateurs</h2>";
        $users = $client->listUsers($token);
        echo "<pre>";
        print_r($users);
        echo "</pre>";
        
        // 3. Ajouter un utilisateur
        echo "<h2>3. Ajouter un utilisateur</h2>";
        $newUser = $client->addUser($token, 'test_soap', 'password123', 'visiteur');
        echo "<pre>";
        print_r($newUser);
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Erreur : " . $e->getMessage() . "</p>";
    echo "<p><strong>Détails :</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>