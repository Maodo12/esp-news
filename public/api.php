<?php
require_once __DIR__ . '/../config/database.php';

$pdo = Database::getInstance()->getPdo();

$format = $_GET['format'] ?? 'json';
$action = $_GET['action'] ?? '';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

try {
    switch ($action) {
        case 'articles':
            $stmt = $pdo->query("
                SELECT a.*, c.libelle as categorie_libelle
                FROM Article a
                LEFT JOIN Categorie c ON a.categorie = c.id
                ORDER BY a.dateCreation DESC
            ");
            $data = $stmt->fetchAll();
            break;
        case 'articles_categories':
            $stmt = $pdo->query("
                SELECT c.libelle as categorie, COUNT(a.id) as total
                FROM Categorie c
                LEFT JOIN Article a ON a.categorie = c.id
                GROUP BY c.id
            ");
            $data = $stmt->fetchAll();
            break;
        case 'articles_categorie':
            $categorieId = $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("
                SELECT a.*, c.libelle as categorie_libelle
                FROM Article a
                LEFT JOIN Categorie c ON a.categorie = c.id
                WHERE a.categorie = ?
                ORDER BY a.dateCreation DESC
            ");
            $stmt->execute([$categorieId]);
            $data = $stmt->fetchAll();
            break;
        default:
            $data = ['error' => 'Action non reconnue. Actions: articles, articles_categories, articles_categorie?id=X'];
            break;
    }

    if ($format === 'xml') {
        header('Content-Type: application/xml; charset=utf-8');
        echo generateXML($data, $action);
    } else {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

} catch (Exception $e) {
    if ($format === 'xml') {
        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<error><message>' . htmlspecialchars($e->getMessage()) . '</message></error>';
    } else {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function generateXML($data, $action) {
    // Démarrer le XML
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    
    if ($action === 'articles_categories') {
        $xml .= '<statistiques>' . "\n";
        foreach ($data as $item) {
            $xml .= '  <categorie>' . "\n";
            $xml .= '    <nom>' . htmlspecialchars($item['categorie']) . '</nom>' . "\n";
            $xml .= '    <total>' . intval($item['total']) . '</total>' . "\n";
            $xml .= '  </categorie>' . "\n";
        }
        $xml .= '</statistiques>';
    } else {
        $xml .= '<articles>' . "\n";
        foreach ($data as $article) {
            $xml .= '  <article>' . "\n";
            foreach ($article as $key => $value) {
                // Échapper les caractères spéciaux
                $safeValue = htmlspecialchars($value ?? '', ENT_XML1, 'UTF-8');
                $xml .= '    <' . $key . '>' . $safeValue . '</' . $key . '>' . "\n";
            }
            $xml .= '  </article>' . "\n";
        }
        $xml .= '</articles>';
    }
    
    return $xml;
}
?>