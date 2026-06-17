<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/ArticleModel.php';
require_once __DIR__ . '/../models/CategorieModel.php';

class ArticleController extends Controller {
    private $articleModel;
    private $categorieModel;

    public function __construct() {
        $this->articleModel  = new ArticleModel();
        $this->categorieModel = new CategorieModel();
    }

    public function index() {
        $articles = $this->articleModel->findAll();
        $this->render('articles/index', ['articles' => $articles]);
    }

    public function show($id) {
        $article = $this->articleModel->findById($id);
        if (!$article) {
            http_response_code(404);
            echo "<h1>Article non trouvé</h1>";
            return;
        }
        $this->render('articles/show', ['article' => $article]);
    }

    public function create() {
        $categories = $this->categorieModel->findAll();
        $this->render('articles/create', ['categories' => $categories]);
    }

    public function store() {
        $titre     = $_POST['titre'] ?? '';
        $contenu   = $_POST['contenu'] ?? '';
        $categorie = $_POST['categorie'] ?? '';

        if (empty($titre) || empty($contenu) || empty($categorie)) {
            $categories = $this->categorieModel->findAll();
            $error = "Tous les champs sont obligatoires.";
            $this->render('articles/create', ['categories' => $categories, 'error' => $error]);
            return;
        }

        $this->articleModel->create($titre, $contenu, $categorie);
        header('Location: /articles');
        exit;
    }

    public function edit($id) {
        $article    = $this->articleModel->findById($id);
        $categories = $this->categorieModel->findAll();
        $this->render('articles/edit', ['article' => $article, 'categories' => $categories]);
    }

    public function update($id) {
        $titre     = $_POST['titre'] ?? '';
        $contenu   = $_POST['contenu'] ?? '';
        $categorie = $_POST['categorie'] ?? '';

        $this->articleModel->update($id, $titre, $contenu, $categorie);
        header('Location: /articles');
        exit;
    }

    public function delete($id) {
        $this->articleModel->delete($id);
        header('Location: /articles');
        exit;
    }

    // ⬇️⬇️⬇️ NOUVELLE MÉTHODE POUR FILTRER PAR CATÉGORIE ⬇️⬇️⬇️
    public function byCategorie($id) {
        $articles = $this->articleModel->findByCategorie($id);
        
        // Récupérer le nom de la catégorie pour l'afficher
        $categorie = $this->categorieModel->findById($id);
        $titre_categorie = $categorie ? $categorie['libelle'] : 'Catégorie';
        
        $this->render('articles/index', [
            'articles' => $articles,
            'titre_categorie' => $titre_categorie
        ]);
    }
}
?>