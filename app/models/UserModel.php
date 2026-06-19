<?php
require_once __DIR__ . '/../../config/database.php';

class UserModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT id, login, role, dateCreation FROM Utilisateur ORDER BY id");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT id, login, role, dateCreation FROM Utilisateur WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByLogin($login) {
        $stmt = $this->pdo->prepare("SELECT * FROM Utilisateur WHERE login = ?");
        $stmt->execute([$login]);
        return $stmt->fetch();
    }

    public function create($login, $password, $role = 'visiteur') {
        // Hasher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO Utilisateur (login, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$login, $hashedPassword, $role]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $login, $role) {
        $stmt = $this->pdo->prepare("UPDATE Utilisateur SET login = ?, role = ? WHERE id = ?");
        $stmt->execute([$login, $role, $id]);
    }

    public function updatePassword($id, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE Utilisateur SET password = ? WHERE id = ?");
        $stmt->execute([$hashedPassword, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Utilisateur WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function authenticate($login, $password) {
        $user = $this->findByLogin($login);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function hasRole($userId, $role) {
        $user = $this->findById($userId);
        if ($user['role'] === $role || $user['role'] === 'administrateur') {
            return true;
        }
        return false;
    }
}
?>