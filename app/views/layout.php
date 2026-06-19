<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESP News</title>
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
        nav h1 { 
            color: #e94560; 
            font-size: 24px; 
        }
        .nav-links { 
            display: flex; 
            align-items: center; 
            flex-wrap: wrap; 
            gap: 5px;
        }
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
            user-select: none;
        }
        .nav-links .user-info {
            color: #e94560;
            font-size: 13px;
            margin-left: 15px;
            padding: 5px 10px;
            background: rgba(233, 69, 96, 0.15);
            border-radius: 20px;
        }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        .btn { padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; font-size: 14px; display: inline-block; }
        .btn-primary { background: #e94560; color: white; }
        .btn-secondary { background: #1a1a2e; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: #1a1a2e; }
        .btn:hover { opacity: 0.85; }
        .card { background: white; border-radius: 8px; padding: 20px; margin-bottom: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .badge { padding: 4px 10px; border-radius: 20px; background: #e94560; color: white; font-size: 12px; display: inline-block; }
        .badge-admin { background: #dc3545; }
        .badge-editeur { background: #ffc107; color: #1a1a2e; }
        .badge-visiteur { background: #28a745; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        form input, form textarea, form select { width: 100%; padding: 10px; margin: 8px 0 16px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        form textarea { height: 150px; resize: vertical; }
        form label { font-weight: bold; font-size: 14px; }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        h2 { margin-bottom: 20px; color: #1a1a2e; }
        .meta { color: #999; font-size: 13px; }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding: 15px 0;
            border-top: 1px solid #eee;
        }
        .pagination .btn {
            min-width: 100px;
            text-align: center;
        }
        
        /* Tableau utilisateurs */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th {
            background: #1a1a2e;
            color: white;
            padding: 12px;
            text-align: left;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        table tr:hover {
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <nav>
        <h1>📰 ESP News</h1>
        <div class="nav-links">
            <a href="/articles">🏠 Tous les articles</a>
            <?php
            // Menu dynamique des catégories
            try {
                require_once __DIR__ . '/../models/CategorieModel.php';
                $categorieModel = new CategorieModel();
                $categories = $categorieModel->findAll();
                if (!empty($categories)) {
                    echo '<span class="separator">|</span>';
                    foreach ($categories as $cat): ?>
                        <a href="/articles/categorie/<?= $cat['id'] ?>" class="category-link">
                            <?= htmlspecialchars($cat['libelle']) ?>
                        </a>
                    <?php endforeach;
                }
            } catch (Exception $e) {
                // Silencieux si la base n'est pas encore créée
            }
            ?>
            <span class="separator">|</span>
            <a href="/categories">⚙️ Gérer</a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="separator">|</span>
                <span class="user-info">
                    👤 <?= htmlspecialchars($_SESSION['user_login']) ?>
                    <span class="badge badge-<?= $_SESSION['user_role'] ?>">
                        <?= htmlspecialchars($_SESSION['user_role']) ?>
                    </span>
                </span>
                <?php if ($_SESSION['user_role'] === 'administrateur'): ?>
                    <a href="/users">👥 Utilisateurs</a>
                <?php endif; ?>
                <a href="/auth/logout">🚪 Déconnexion</a>
            <?php else: ?>
                <span class="separator">|</span>
                <a href="/auth/login">🔐 Connexion</a>
                <a href="/auth/register">📝 Inscription</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container">
        <?= $content ?>
    </div>
</body>
</html>