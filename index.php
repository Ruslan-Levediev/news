<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

session_start();

$basePath = '/project/public';
$requestUri = $_SERVER['REQUEST_URI'];


$uri = parse_url($requestUri, PHP_URL_PATH);


if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
    if ($uri === '') {
        $uri = '/';
    }
}


$router = Router::getInstance();


$router->add('/', [\App\Controllers\HomeController::class, 'index']);
$router->add('/news', [\App\Controllers\NewsController::class, 'list']);
$router->add('/news/{id}', [\App\Controllers\NewsController::class, 'detail']);
$router->add('/news/add', [\App\Controllers\NewsController::class, 'addForm']);
$router->add('/news/add_post', [\App\Controllers\NewsController::class, 'add']); // для POST
$router->add('/user/profile', [\App\Controllers\UserController::class, 'profile']);
$router->add('/user/login', [\App\Controllers\AuthController::class, 'showLoginForm']);
$router->add('/user/login_post', [\App\Controllers\AuthController::class, 'login']); // POST
$router->add('/user/register_post', [\App\Controllers\AuthController::class, 'register']); // POST
$router->add('/user/logout', [\App\Controllers\AuthController::class, 'logout']);
$router->add('/user/profile/clear_notifications', [\App\Controllers\UserController::class, 'clearNotifications']);
$router->add('/user/profile/delete_notification', [\App\Controllers\UserController::class, 'deleteNotification']);
$router->add('/user/profile/mark_notification_read', [\App\Controllers\UserController::class, 'markNotificationRead']);
$router->add('/news/search', [\App\Controllers\NewsController::class, 'search']);
$router->add('/news/delete/{id}', [\App\Controllers\NewsController::class, 'delete']);
$router->add('/news/set_main/{id}', [\App\Controllers\NewsController::class, 'setMain']);
$router->add('/news/unset_main/{id}', [\App\Controllers\NewsController::class, 'unsetMain']);


$router->dispatch($uri);
