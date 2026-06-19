<?php
require_once __DIR__ . '/../../config/database.php';

class ArticleModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function findAll() {
        $stmt = $this->pdo->query("
            SELECT a.*, c.libelle as categorie_libelle
            FROM Article a
            LEFT JOIN Categorie c ON a.categorie = c.id
            ORDER BY a.dateCreation DESC
        ");
        return $stmt->fetchAll();
    }

    // ⬇️⬇️⬇️ NOUVEAU : Pagination ⬇️⬇️⬇️
    public function findAllPaginated($page = 1, $perPage = 5) {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare("
            SELECT a.*, c.libelle as categorie_libelle
            FROM Article a
            LEFT JOIN Categorie c ON a.categorie = c.id
            ORDER BY a.dateCreation DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$perPage, $offset]);
        return $stmt->fetchAll();
    }

    // ⬇️⬇️⬇️ NOUVEAU : Compter les articles ⬇️⬇️⬇️
    public function countAll() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM Article");
        return $stmt->fetchColumn();
    }

    // ⬇️⬇️⬇️ NOUVEAU : Pagination par catégorie ⬇️⬇️⬇️
    public function findByCategoriePaginated($categorieId, $page = 1, $perPage = 5) {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare("
            SELECT a.*, c.libelle as categorie_libelle
            FROM Article a
            LEFT JOIN Categorie c ON a.categorie = c.id
            WHERE a.categorie = ?
            ORDER BY a.dateCreation DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$categorieId, $perPage, $offset]);
        return $stmt->fetchAll();
    }

    // ⬇️⬇️⬇️ NOUVEAU : Compter les articles d'une catégorie ⬇️⬇️⬇️
    public function countByCategorie($categorieId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM Article WHERE categorie = ?");
        $stmt->execute([$categorieId]);
        return $stmt->fetchColumn();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("
            SELECT a.*, c.libelle as categorie_libelle
            FROM Article a
            LEFT JOIN Categorie c ON a.categorie = c.id
            WHERE a.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByCategorie($categorieId) {
        $stmt = $this->pdo->prepare("
            SELECT a.*, c.libelle as categorie_libelle
            FROM Article a
            LEFT JOIN Categorie c ON a.categorie = c.id
            WHERE a.categorie = ?
            ORDER BY a.dateCreation DESC
        ");
        $stmt->execute([$categorieId]);
        return $stmt->fetchAll();
    }

    public function create($titre, $contenu, $categorie) {
        $stmt = $this->pdo->prepare("
            INSERT INTO Article (titre, contenu, categorie)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$titre, $contenu, $categorie]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $titre, $contenu, $categorie) {
        $stmt = $this->pdo->prepare("
            UPDATE Article
            SET titre = ?, contenu = ?, categorie = ?, dateModification = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        $stmt->execute([$titre, $contenu, $categorie, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Article WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>