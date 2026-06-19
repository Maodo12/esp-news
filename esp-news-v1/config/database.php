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
                FOREIGN KEY (categorie) REFERENCES Categorie(id)
            );
        ");

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
    }
}

// Fonction helper pour récupérer la connexion
function getDb() {
    return Database::getInstance()->getPdo();
}
?>