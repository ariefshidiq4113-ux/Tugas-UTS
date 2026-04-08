<?php
// public/index.php  —  Entry Point

session_start();
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/core/Database.php';
require_once BASE_PATH . '/core/Model.php';
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Router.php';

// Load all models
require_once BASE_PATH . '/app/models/Models.php';
require_once BASE_PATH . '/app/models/Article.php';

$router = new Router();

// =====================================
//  PUBLIC ROUTES
// =====================================
$router->get('/',                                    ['HomeController',    'index']);
$router->get('/search',                              ['HomeController',    'search']);
$router->get('/artikel/{slug}',                      ['ArticleController', 'show']);
$router->post('/artikel/{slug}/komentar',            ['ArticleController', 'postComment']);
$router->get('/kategori/{slug}',                     ['ArticleController', 'category']);

// =====================================
//  AUTH ROUTES
// =====================================
$router->get('/auth/login',                          ['AuthController',    'loginForm']);
$router->post('/auth/login',                         ['AuthController',    'login']);
$router->get('/auth/register',                       ['AuthController',    'registerForm']);
$router->post('/auth/register',                      ['AuthController',    'register']);
$router->get('/auth/logout',                         ['AuthController',    'logout']);
$router->get('/profile',                             ['AuthController',    'profile']);
$router->post('/profile/update',                     ['AuthController',    'updateProfile']);

// =====================================
//  ADMIN ROUTES
// =====================================
$router->get('/admin/dashboard',                     ['AdminController',   'dashboard']);

// Articles
$router->get('/admin/articles',                      ['AdminController',   'articles']);
$router->get('/admin/articles/create',               ['AdminController',   'createArticle']);
$router->post('/admin/articles/store',               ['AdminController',   'storeArticle']);
$router->get('/admin/articles/{id}/edit',            ['AdminController',   'editArticle']);
$router->post('/admin/articles/{id}/update',         ['AdminController',   'updateArticle']);
$router->get('/admin/articles/{id}/delete',          ['AdminController',   'deleteArticle']);

// Categories
$router->get('/admin/categories',                    ['AdminController',   'categories']);
$router->post('/admin/categories/store',             ['AdminController',   'storeCategory']);
$router->post('/admin/categories/{id}/update',       ['AdminController',   'updateCategory']);
$router->get('/admin/categories/{id}/delete',        ['AdminController',   'deleteCategory']);

// Users
$router->get('/admin/users',                         ['AdminController',   'users']);
$router->post('/admin/users/{id}/update',            ['AdminController',   'updateUser']);
$router->get('/admin/users/{id}/delete',             ['AdminController',   'deleteUser']);

// Comments
$router->get('/admin/comments',                      ['AdminController',   'comments']);
$router->get('/admin/comments/{id}/approve',         ['AdminController',   'approveComment']);
$router->get('/admin/comments/{id}/delete',          ['AdminController',   'deleteComment']);

// Settings
$router->get('/admin/settings',                      ['AdminController',   'settings']);
$router->post('/admin/settings/save',                ['AdminController',   'saveSettings']);

// =====================================
//  DISPATCH
// =====================================
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri    = $_SERVER['REQUEST_URI'];

$router->dispatch($requestMethod, $requestUri);
