<?php
class Auth {
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'administrateur';
    }

    public static function isEditeur() {
        return isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'editeur' || $_SESSION['user_role'] === 'administrateur');
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /auth/login');
            exit;
        }
    }

    public static function requireAdmin() {
        self::requireLogin();
        if (!self::isAdmin()) {
            self::showError403('Vous devez être administrateur pour accéder à cette page.');
        }
    }

    public static function requireEditeur() {
        self::requireLogin();
        if (!self::isEditeur()) {
            self::showError403('Vous devez être éditeur ou administrateur pour accéder à cette page.');
        }
    }

    public static function showError403($message = 'Accès refusé') {
        http_response_code(403);
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>403 - Accès refusé</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { 
                    font-family: 'Segoe UI', Arial, sans-serif; 
                    background: #f4f4f4; 
                    display: flex; 
                    justify-content: center; 
                    align-items: center; 
                    min-height: 100vh;
                    margin: 0;
                }
                .error-container {
                    background: white;
                    padding: 50px 60px;
                    border-radius: 15px;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
                    text-align: center;
                    max-width: 500px;
                }
                .error-icon {
                    font-size: 80px;
                    margin-bottom: 20px;
                    display: block;
                }
                h1 {
                    color: #dc3545;
                    font-size: 28px;
                    margin-bottom: 15px;
                }
                p {
                    color: #666;
                    font-size: 16px;
                    line-height: 1.6;
                    margin-bottom: 25px;
                }
                .user-info {
                    background: #f8f9fa;
                    padding: 12px;
                    border-radius: 8px;
                    margin-bottom: 25px;
                    font-size: 14px;
                    color: #333;
                }
                .user-info strong {
                    color: #1a1a2e;
                }
                .btn {
                    display: inline-block;
                    padding: 10px 25px;
                    background: #1a1a2e;
                    color: white;
                    text-decoration: none;
                    border-radius: 8px;
                    font-size: 14px;
                    transition: all 0.3s;
                    margin: 5px;
                }
                .btn:hover {
                    background: #e94560;
                    transform: translateY(-2px);
                }
                .btn-primary {
                    background: #e94560;
                }
                .btn-primary:hover {
                    background: #c73652;
                }
                .btn-secondary {
                    background: #6c757d;
                }
                .btn-secondary:hover {
                    background: #5a6268;
                }
                .badge {
                    display: inline-block;
                    padding: 3px 12px;
                    border-radius: 20px;
                    font-size: 12px;
                    font-weight: bold;
                }
                .badge-visiteur { background: #28a745; color: white; }
                .badge-editeur { background: #ffc107; color: #1a1a2e; }
                .badge-administrateur { background: #dc3545; color: white; }
            </style>
        </head>
        <body>
            <div class="error-container">
                <span class="error-icon">⛔</span>
                <h1>Accès refusé</h1>
                <p><?= htmlspecialchars($message) ?></p>
                
                <?php if (self::isLoggedIn()): ?>
                    <div class="user-info">
                        👤 Vous êtes connecté en tant que <strong><?= htmlspecialchars($_SESSION['user_login']) ?></strong>
                        <span class="badge badge-<?= $_SESSION['user_role'] ?>">
                            <?= htmlspecialchars($_SESSION['user_role']) ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <div>
                    <a href="/articles" class="btn btn-primary">🏠 Retour à l'accueil</a>
                    <?php if (self::isLoggedIn()): ?>
                        <a href="/auth/logout" class="btn btn-secondary">🚪 Se déconnecter</a>
                    <?php endif; ?>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}
?>