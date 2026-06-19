<?php
echo "<h1>🔍 Vérification de SOAP</h1>";

// 1. Vérifier si l'extension SOAP est chargée
if (extension_loaded('soap')) {
    echo "<p style='color:green;'>✅ L'extension SOAP est chargée</p>";
} else {
    echo "<p style='color:red;'>❌ L'extension SOAP n'est PAS chargée</p>";
    echo "<p>Modifiez php.ini et activez : <strong>extension=soap</strong></p>";
}

// 2. Afficher les extensions chargées
echo "<h2>Extensions chargées :</h2>";
echo "<ul>";
$extensions = get_loaded_extensions();
sort($extensions);
foreach ($extensions as $ext) {
    echo "<li>" . $ext . "</li>";
}
echo "</ul>";

// 3. Test simple de classe SoapServer
if (class_exists('SoapServer')) {
    echo "<p style='color:green;'>✅ La classe SoapServer existe</p>";
} else {
    echo "<p style='color:red;'>❌ La classe SoapServer n'existe pas</p>";
}

// 4. Tester un serveur SOAP simple
if (extension_loaded('soap')) {
    try {
        $options = ['uri' => 'http://localhost:8000/check_soap.php'];
        $server = new SoapServer(null, $options);
        echo "<p style='color:green;'>✅ SoapServer créé avec succès</p>";
    } catch (Exception $e) {
        echo "<p style='color:red;'>❌ Erreur SoapServer : " . $e->getMessage() . "</p>";
    }
}
?>