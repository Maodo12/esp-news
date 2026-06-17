<?php
require_once __DIR__ . '/../../config/database.php';

class CategorieModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM Categorie ORDER BY libelle");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM Categorie WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($libelle) {
        $stmt = $this->pdo->prepare("INSERT INTO Categorie (libelle) VALUES (?)");
        $stmt->execute([$libelle]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $libelle) {
        $stmt = $this->pdo->prepare("UPDATE Categorie SET libelle = ? WHERE id = ?");
        $stmt->execute([$libelle, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Categorie WHERE id = ?");
        $stmt->execute([$id]);
    }
}