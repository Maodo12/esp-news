<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController extends Controller {
    private $userModel;

    public function __construct() {
        // Vérifier que l'utilisateur est administrateur
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrateur') {
            header('Location: /articles');
            exit;
        }
        $this->userModel = new UserModel();
    }

    public function index() {
        $users = $this->userModel->findAll();
        $this->render('users/index', ['users' => $users]);
    }

    public function create() {
        $this->render('users/create', []);
    }

    public function store() {
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'visiteur';

        if (empty($login) || empty($password)) {
            $this->render('users/create', ['error' => 'Tous les champs sont obligatoires']);
            return;
        }

        // Vérifier si le login existe déjà
        $existing = $this->userModel->findByLogin($login);
        if ($existing) {
            $this->render('users/create', ['error' => 'Ce login existe déjà']);
            return;
        }

        $this->userModel->create($login, $password, $role);
        header('Location: /users');
        exit;
    }

    public function edit($id) {
        $user = $this->userModel->findById($id);
        if (!$user) {
            http_response_code(404);
            echo "<h1>Utilisateur non trouvé</h1>";
            return;
        }
        $this->render('users/edit', ['user' => $user]);
    }

    public function update($id) {
        $login = $_POST['login'] ?? '';
        $role = $_POST['role'] ?? 'visiteur';

        if (empty($login)) {
            $user = $this->userModel->findById($id);
            $this->render('users/edit', ['user' => $user, 'error' => 'Le login est obligatoire']);
            return;
        }

        $this->userModel->update($id, $login, $role);
        header('Location: /users');
        exit;
    }

    public function delete($id) {
        // Empêcher la suppression de son propre compte
        if ($id == $_SESSION['user_id']) {
            header('Location: /users?error=Impossible de supprimer son propre compte');
            exit;
        }
        
        $this->userModel->delete($id);
        header('Location: /users');
        exit;
    }
}
?>