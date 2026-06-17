<?php
require_once __DIR__ . '/../config/database.php';
$pdo = getDb();

$categories = $pdo->query("SELECT * FROM Categorie ORDER BY libelle")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESP News - Version 1</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; color: #333; }
        nav { 
            background: #1a1a2e; 
            padding: 15px 30px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            flex-wrap: wrap;
            gap: 10px;
        }
        nav h1 { color: #e94560; font-size: 24px; }
        .nav-links { display: flex; align-items: center; flex-wrap: wrap; gap: 5px; }
        .nav-links a { 
            color: #fff; 
            text-decoration: none; 
            padding: 8px 12px; 
            font-size: 14px; 
            border-radius: 4px;
            transition: all 0.3s;
        }
        .nav-links a:hover { 
            color: #e94560; 
            background: rgba(233, 69, 96, 0.15);
        }
        .nav-links .category-link { 
            font-size: 13px; 
            background: rgba(255, 255, 255, 0.05);
        }
        .nav-links .category-link:hover { 
            background: rgba(233, 69, 96, 0.25);
        }
        .nav-links .separator {
            color: #444;
            margin: 0 5px;
        }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        .btn { padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; font-size: 14px; display: inline-block; }
        .btn-primary { background: #e94560; color: white; }
        .btn-secondary { background: #1a1a2e; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn:hover { opacity: 0.85; }
        .card { background: white; border-radius: 8px; padding: 20px; margin-bottom: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .badge { padding: 4px 10px; border-radius: 20px; background: #e94560; color: white; font-size: 12px; display: inline-block; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        form input, form textarea, form select { width: 100%; padding: 10px; margin: 8px 0 16px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        form textarea { height: 150px; resize: vertical; }
        form label { font-weight: bold; font-size: 14px; }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        h2 { margin-bottom: 20px; color: #1a1a2e; }
        .meta { color: #999; font-size: 13px; }
    </style>
</head>
<body>
    <nav>
        <h1>📰 ESP News</h1>
        <div class="nav-links">
            <a href="index.php">🏠 Tous les articles</a>
            <?php if (!empty($categories)): ?>
                <span class="separator">|</span>
                <?php foreach ($categories as $cat): ?>
                    <a href="by_categorie.php?id=<?= $cat['id'] ?>" class="category-link">
                        <?= htmlspecialchars($cat['libelle']) ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
            <span class="separator">|</span>
            <a href="categories.php">⚙️ Gérer</a>
        </div>
    </nav>
    <div class="container">