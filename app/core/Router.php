<?php
namespace App\Core;
use App\Controllers\CommentController;
class Router {
    private static ?Router $instance = null;
    private array $routes = [];

    
    private function __construct() {}


    public static function getInstance(): Router {
        if (self::$instance === null) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    
    public function add(string $path, $handler): void {
        $this->routes[$path] = $handler;
    }

    
    public function dispatch(string $uri): void {
        
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as $route => $handler) {
    $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '(\d+)', $route);
    $pattern = "#^" . $pattern . "$#";
    
    if (preg_match($pattern, $uri, $matches)) {
        
                array_shift($matches); 

                if (is_array($handler)) {
                    [$class, $method] = $handler;
                    $controller = new $class();

                    
                    $params = [];
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $params[] = $_POST;
                    }

                    
                    $params = array_merge($matches, $params);

                    call_user_func_array([$controller, $method], $params);
                    return;
                } elseif (is_callable($handler)) {
                    call_user_func_array($handler, $matches);
                    return;
                }
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
