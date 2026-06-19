<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/UserModel.php';

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login() {
        // Si déjà connecté, rediriger vers l'accueil
        if (isset($_SESSION['user_id'])) {
            header('Location: /articles');
            exit;
        }
        $this->render('auth/login', []);
    }

    public function authenticate() {
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($login) || empty($password)) {
            $this->render('auth/login', ['error' => 'Login et mot de passe requis']);
            return;
        }

        $user = $this->userModel->authenticate($login, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_login'] = $user['login'];
            $_SESSION['user_role'] = $user['role'];
            header('Location: /articles');
            exit;
        } else {
            $this->render('auth/login', ['error' => 'Login ou mot de passe incorrect']);
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /articles');
        exit;
    }

    public function register() {
        $this->render('auth/register', []);
    }

    public function store() {
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($login) || empty($password)) {
            $this->render('auth/register', ['error' => 'Tous les champs sont obligatoires']);
            return;
        }

        if ($password !== $confirmPassword) {
            $this->render('auth/register', ['error' => 'Les mots de passe ne correspondent pas']);
            return;
        }

        // Vérifier si le login existe déjà
        $existing = $this->userModel->findByLogin($login);
        if ($existing) {
            $this->render('auth/register', ['error' => 'Ce login est déjà utilisé']);
            return;
        }

        $this->userModel->create($login, $password, 'visiteur');
        $this->render('auth/login', ['success' => 'Compte créé avec succès. Connectez-vous.']);
    }
}
?>