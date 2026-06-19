<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/UserModel.php';

// Démarrer la session pour le token
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Classe pour le service SOAP
class UserService {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function authenticate($login, $password) {
        try {
            $user = $this->userModel->authenticate($login, $password);
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $_SESSION['token'] = $token;
                return [
                    'success' => true,
                    'token' => $token,
                    'role' => $user['role'],
                    'id' => $user['id']
                ];
            }
            return ['success' => false, 'message' => 'Login ou mot de passe incorrect'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function listUsers($token) {
        try {
            if (!$this->verifyToken($token)) {
                return ['error' => 'Token invalide'];
            }
            return $this->userModel->findAll();
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function addUser($token, $login, $password, $role = 'visiteur') {
        try {
            if (!$this->verifyToken($token)) {
                return ['error' => 'Token invalide'];
            }
            $id = $this->userModel->create($login, $password, $role);
            return ['success' => true, 'id' => $id];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function updateUser($token, $id, $login, $role) {
        try {
            if (!$this->verifyToken($token)) {
                return ['error' => 'Token invalide'];
            }
            $this->userModel->update($id, $login, $role);
            return ['success' => true];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function deleteUser($token, $id) {
        try {
            if (!$this->verifyToken($token)) {
                return ['error' => 'Token invalide'];
            }
            $this->userModel->delete($id);
            return ['success' => true];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function verifyToken($token) {
        return isset($_SESSION['token']) && $_SESSION['token'] === $token;
    }
}

// Le serveur SOAP en mode non-WSDL
try {
    $server = new SoapServer(null, [
        'uri' => 'http://localhost:8000/soap_server.php'
    ]);
    $server->setClass('UserService');
    $server->handle();
} catch (Exception $e) {
    echo "Erreur SOAP : " . $e->getMessage();
}
?>