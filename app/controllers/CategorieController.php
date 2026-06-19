<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/CategorieModel.php';
require_once __DIR__ . '/../../core/Auth.php';  // ⬅️ AJOUTÉ

class CategorieController extends Controller {
    private $categorieModel;

    public function __construct() {
        $this->categorieModel = new CategorieModel();
    }

    // ✅ PUBLIC
    public function index() {
        $categories = $this->categorieModel->findAll();
        $this->render('categories/index', ['categories' => $categories]);
    }

    // 🔒 PROTÉGÉ - Éditeur ou Admin
    public function create() {
        Auth::requireEditeur();  // ⬅️ AJOUTÉ
        $this->render('categories/create', []);
    }

    // 🔒 PROTÉGÉ - Éditeur ou Admin
    public function store() {
        Auth::requireEditeur();  // ⬅️ AJOUTÉ
        $libelle = $_POST['libelle'] ?? '';

        if (empty($libelle)) {
            $error = "Le libellé est obligatoire.";
            $this->render('categories/create', ['error' => $error]);
            return;
        }

        $this->categorieModel->create($libelle);
        header('Location: /categories');
        exit;
    }

    // 🔒 PROTÉGÉ - Éditeur ou Admin
    public function edit($id) {
        Auth::requireEditeur();  // ⬅️ AJOUTÉ
        $categorie = $this->categorieModel->findById($id);
        $this->render('categories/edit', ['categorie' => $categorie]);
    }

    // 🔒 PROTÉGÉ - Éditeur ou Admin
    public function update($id) {
        Auth::requireEditeur();  // ⬅️ AJOUTÉ
        $libelle = $_POST['libelle'] ?? '';
        $this->categorieModel->update($id, $libelle);
        header('Location: /categories');
        exit;
    }

    // 🔒 PROTÉGÉ - Éditeur ou Admin
    public function delete($id) {
        Auth::requireEditeur();  // ⬅️ AJOUTÉ
        $this->categorieModel->delete($id);
        header('Location: /categories');
        exit;
    }
}
?>