<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/CategorieModel.php';

class CategorieController extends Controller {
    private $categorieModel;

    public function __construct() {
        $this->categorieModel = new CategorieModel();
    }

    public function index() {
        $categories = $this->categorieModel->findAll();
        $this->render('categories/index', ['categories' => $categories]);
    }

    public function create() {
        $this->render('categories/create', []);
    }

    public function store() {
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

    public function edit($id) {
        $categorie = $this->categorieModel->findById($id);
        $this->render('categories/edit', ['categorie' => $categorie]);
    }

    public function update($id) {
        $libelle = $_POST['libelle'] ?? '';
        $this->categorieModel->update($id, $libelle);
        header('Location: /categories');
        exit;
    }

    public function delete($id) {
        $this->categorieModel->delete($id);
        header('Location: /categories');
        exit;
    }
}