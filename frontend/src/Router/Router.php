<?php
namespace Frontend\Router;

class Router {
    private $routes = [];

    public function addRoute($method, $uri, $callback) {
        $this->routes[] = compact('method', 'uri', 'callback');
    }

    public function dispatch() {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];
    
        // Debugging output
        error_log("Request URI: " . $requestUri);
        error_log("Request Method: " . $requestMethod);
    
        $routeMatched = false;
    
        foreach ($this->routes as $route) {
            if (strpos($requestUri, $route['uri']) === 0 && $route['method'] === $requestMethod) {
                // Call the appropriate callback for the route
                call_user_func($route['callback']);
                $routeMatched = true;
                break;
            }
        }
    
        if (!$routeMatched) {
            // Route not found, return a JSON error response
            http_response_code(404);
            echo json_encode(['message' => 'Endpoint not found']);
        }
    }
    
}
