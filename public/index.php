<?php
require_once __DIR__ . '/../core/Router.php';

$router = new Router();

// Routes Articles
$router->add('GET',  '/',                'ArticleController', 'index');
$router->add('GET',  '/articles',        'ArticleController', 'index');
$router->add('GET',  '/articles/create', 'ArticleController', 'create');
$router->add('POST', '/articles/store',  'ArticleController', 'store');
$router->add('GET',  '/articles/{id}',   'ArticleController', 'show');
$router->add('GET',  '/articles/{id}/edit',   'ArticleController', 'edit');
$router->add('POST', '/articles/{id}/update', 'ArticleController', 'update');
$router->add('POST', '/articles/{id}/delete', 'ArticleController', 'delete');
$router->add('GET',  '/articles/categorie/{id}', 'ArticleController', 'byCategorie'); // ⬅️ AJOUTEZ CETTE LIGNE

// Routes Catégories
$router->add('GET',  '/categories',        'CategorieController', 'index');
$router->add('GET',  '/categories/create', 'CategorieController', 'create');
$router->add('POST', '/categories/store',  'CategorieController', 'store');
$router->add('GET',  '/categories/{id}/edit',   'CategorieController', 'edit');
$router->add('POST', '/categories/{id}/update', 'CategorieController', 'update');
$router->add('POST', '/categories/{id}/delete', 'CategorieController', 'delete');

$method = $_SERVER['REQUEST_METHOD'];
$uri    = $_SERVER['REQUEST_URI'];

$router->dispatch($method, $uri);
?>