<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use App\Controllers\UserController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

$homeController = new HomeController();
$userController = new UserController();

if($uri === '' || $uri === '/' && $_SERVER['REQUEST_METHOD'] === 'GET'){
    $homeController->index();
}elseif ($uri === '/users' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $userController->index();
} elseif ($uri === '/users/create' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $userController->create();
} elseif ($uri === '/users/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController->store();
} elseif (preg_match('/^\/users\/edit\/(\d+)$/', $uri, $matches) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $userController->edit((int)$matches[1]);
} elseif (preg_match('/^\/users\/update\/(\d+)$/', $uri, $matches) && $_SERVER['REQUEST_METHOD'] === 'PATCH') {
    $userController->update((int)$matches[1]);
} elseif (preg_match('/^\/users\/delete\/(\d+)$/', $uri, $matches) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $userController->delete((int)$matches[1]);
} else {
    http_response_code(404);
    echo "Página não encontrada";
}