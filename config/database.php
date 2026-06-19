<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Chemin ABSOLU vers la base (même fichier pour les deux versions)
        $dbPath = 'C:/esp-news/mglsi_news.db';
        $this->pdo = new PDO('sqlite:' . $dbPath);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->initDatabase();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getPdo() {
        return $this->pdo;
    }

    private function initDatabase() {
        // Création des tables
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS Categorie (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                libelle VARCHAR(20)
            );

            CREATE TABLE IF NOT EXISTS Article (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                titre VARCHAR(255),
                contenu TEXT,
                dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP,
                dateModification DATETIME DEFAULT CURRENT_TIMESTAMP,
                categorie INTEGER,
                utilisateur_id INTEGER,
                FOREIGN KEY (categorie) REFERENCES Categorie(id),
                FOREIGN KEY (utilisateur_id) REFERENCES Utilisateur(id)
            );

            -- NOUVEAU : Table Utilisateur
            CREATE TABLE IF NOT EXISTS Utilisateur (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                login VARCHAR(50) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(20) DEFAULT 'visiteur',
                dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Insérer les catégories et articles par défaut
        $count = $this->pdo->query("SELECT COUNT(*) FROM Categorie")->fetchColumn();
        if ($count == 0) {
            $this->pdo->exec("
                INSERT INTO Categorie (libelle) VALUES 
                    ('Sport'), 
                    ('Santé'), 
                    ('Education'), 
                    ('Politique');
                
                INSERT INTO Article (titre, contenu, categorie) VALUES
                    ('Première victoire du Sénégal', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.', 1),
                    ('Election en Mauritanie', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.', 4),
                    ('Début de la CAN', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.', 1),
                    ('Pétrole au Sénégal', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.', 4),
                    ('Inauguration d un ENO à l UVS', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.', 3);
            ");
        }

        // NOUVEAU : Insérer les utilisateurs par défaut si la table est vide
        $countUsers = $this->pdo->query("SELECT COUNT(*) FROM Utilisateur")->fetchColumn();
        if ($countUsers == 0) {
            // Les mots de passe sont hachés avec password_hash()
            // admin123, edit123, visit123
            $this->pdo->exec("
                INSERT INTO Utilisateur (login, password, role) VALUES 
                    ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'administrateur'),
                    ('editeur1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'editeur'),
                    ('visiteur1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'visiteur')
            ");
        }
    }
}
?>