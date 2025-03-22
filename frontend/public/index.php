<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Frontend\Router\Router;
use Frontend\Controller\LoginController;
use Frontend\Controller\RegistrationController;

// Handle OPTIONS (preflight) request first
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();  // No need to do anything further for OPTIONS request
}

$router = new Router();

// Define your routes here
$router->addRoute('GET', '/login', [new LoginController(), 'login']); //login
$router->addRoute('GET', '/register', [new RegistrationController(), 'register']); //registracia

$router->addRoute('GET', '/', [new LoginController(), 'login']); // prihlaseny

$router->dispatch();
