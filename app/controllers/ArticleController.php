<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/ArticleModel.php';
require_once __DIR__ . '/../models/CategorieModel.php';
require_once __DIR__ . '/../../core/Auth.php';  // ⬅️ AJOUTÉ

class ArticleController extends Controller {
    private $articleModel;
    private $categorieModel;

    public function __construct() {
        $this->articleModel  = new ArticleModel();
        $this->categorieModel = new CategorieModel();
    }

    // ✅ PUBLIC
    public function index() {
        $page = $_GET['page'] ?? 1;
        $perPage = 5;
        $totalArticles = $this->articleModel->countAll();
        $totalPages = ceil($totalArticles / $perPage);
        
        $articles = $this->articleModel->findAllPaginated($page, $perPage);
        $this->render('articles/index', [
            'articles' => $articles,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalArticles' => $totalArticles
        ]);
    }

    // ✅ PUBLIC
    public function show($id) {
        $article = $this->articleModel->findById($id);
        if (!$article) {
            http_response_code(404);
            echo "<h1>Article non trouvé</h1>";
            return;
        }
        $this->render('articles/show', ['article' => $article]);
    }

    // 🔒 PROTÉGÉ - Éditeur ou Admin
    public function create() {
        Auth::requireEditeur();  // ⬅️ AJOUTÉ
        $categories = $this->categorieModel->findAll();
        $this->render('articles/create', ['categories' => $categories]);
    }

    // 🔒 PROTÉGÉ - Éditeur ou Admin
    public function store() {
        Auth::requireEditeur();  // ⬅️ AJOUTÉ
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

    // 🔒 PROTÉGÉ - Éditeur ou Admin
    public function edit($id) {
        Auth::requireEditeur();  // ⬅️ AJOUTÉ
        $article    = $this->articleModel->findById($id);
        $categories = $this->categorieModel->findAll();
        $this->render('articles/edit', ['article' => $article, 'categories' => $categories]);
    }

    // 🔒 PROTÉGÉ - Éditeur ou Admin
    public function update($id) {
        Auth::requireEditeur();  // ⬅️ AJOUTÉ
        $titre     = $_POST['titre'] ?? '';
        $contenu   = $_POST['contenu'] ?? '';
        $categorie = $_POST['categorie'] ?? '';

        $this->articleModel->update($id, $titre, $contenu, $categorie);
        header('Location: /articles');
        exit;
    }

    // 🔒 PROTÉGÉ - Éditeur ou Admin
    public function delete($id) {
        Auth::requireEditeur();  // ⬅️ AJOUTÉ
        $this->articleModel->delete($id);
        header('Location: /articles');
        exit;
    }

    // ✅ PUBLIC
    public function byCategorie($id) {
        $page = $_GET['page'] ?? 1;
        $perPage = 5;
        $totalArticles = $this->articleModel->countByCategorie($id);
        $totalPages = ceil($totalArticles / $perPage);
        
        $articles = $this->articleModel->findByCategoriePaginated($id, $page, $perPage);
        
        $categorie = $this->categorieModel->findById($id);
        $titre_categorie = $categorie ? $categorie['libelle'] : 'Catégorie';
        
        $this->render('articles/index', [
            'articles' => $articles,
            'titre_categorie' => $titre_categorie,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalArticles' => $totalArticles
        ]);
    }
}
?>
