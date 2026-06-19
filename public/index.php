<?php
// Démarrer la session pour l'authentification
session_start();

require_once __DIR__ . '/../core/Router.php';

$router = new Router();

// ============================================
// ROUTES EXISTANTES
// ============================================

// Routes Articles
$router->add('GET', '/', 'ArticleController', 'index');
$router->add('GET', '/articles', 'ArticleController', 'index');
$router->add('GET', '/articles/create', 'ArticleController', 'create');
$router->add('POST', '/articles/store', 'ArticleController', 'store');
$router->add('GET', '/articles/{id}', 'ArticleController', 'show');
$router->add('GET', '/articles/{id}/edit', 'ArticleController', 'edit');
$router->add('POST', '/articles/{id}/update', 'ArticleController', 'update');
$router->add('POST', '/articles/{id}/delete', 'ArticleController', 'delete');
$router->add('GET', '/articles/categorie/{id}', 'ArticleController', 'byCategorie');

// Routes Catégories
$router->add('GET', '/categories', 'CategorieController', 'index');
$router->add('GET', '/categories/create', 'CategorieController', 'create');
$router->add('POST', '/categories/store', 'CategorieController', 'store');
$router->add('GET', '/categories/{id}/edit', 'CategorieController', 'edit');
$router->add('POST', '/categories/{id}/update', 'CategorieController', 'update');
$router->add('POST', '/categories/{id}/delete', 'CategorieController', 'delete');

// ============================================
// NOUVELLES ROUTES À AJOUTER
// ============================================

// Routes Authentification
$router->add('GET', '/auth/login', 'AuthController', 'login');
$router->add('POST', '/auth/authenticate', 'AuthController', 'authenticate');
$router->add('GET', '/auth/logout', 'AuthController', 'logout');
$router->add('GET', '/auth/register', 'AuthController', 'register');
$router->add('POST', '/auth/store', 'AuthController', 'store');

// Routes Utilisateurs (admin uniquement)
$router->add('GET', '/users', 'UserController', 'index');
$router->add('GET', '/users/create', 'UserController', 'create');
$router->add('POST', '/users/store', 'UserController', 'store');
$router->add('GET', '/users/{id}/edit', 'UserController', 'edit');
$router->add('POST', '/users/{id}/update', 'UserController', 'update');
$router->add('POST', '/users/{id}/delete', 'UserController', 'delete');

// ============================================
// FIN DES ROUTES
// ============================================

$method = $_SERVER['REQUEST_METHOD'];
$uri    = $_SERVER['REQUEST_URI'];

$router->dispatch($method, $uri);
?>