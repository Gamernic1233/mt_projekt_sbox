<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Router/Router.php';  // Adjust path to point to the correct location
require_once __DIR__ . '/../src/Controller/AuthController.php';
require_once __DIR__ . '/../src/Controller/DataController.php';
require_once __DIR__ . '/../src/Controller/DeviceController.php';


use Backend\Router\Router;
use Backend\Controller\AuthController;
use Backend\Controller\DataController;
use Backend\Controller\DeviceController;

// Add CORS headers for every request
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // If you're using Authorization header too

// Handle OPTIONS (preflight) request first
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();  // No need to do anything further for OPTIONS request
}

$router = new Router();

// Define your routes here
$router->addRoute('POST', '/login', [new AuthController(), 'login']); //login
$router->addRoute('POST', '/register', [new AuthController(), 'register']); //registracia
$router->addRoute('GET', '/getuserdevices', [new DeviceController(), 'getUserDevices']); //ziska zariadenia pre konkretneho usera
$router->addRoute('GET', '/getuserdevice', [new DeviceController(), 'getUserDevice']); //ziska data z konkretneho zariadenia
$router->addRoute('GET', '/getdata', [new DataController(), 'getData']); //ziska data
$router->addRoute('POST', '/pushrecord', [new DataController(), 'pushRecord']); //prida polozku
$router->addRoute('GET', '/devices', [new DeviceController(), 'getDevices']); // ziska vsetky zariadenia
$router->addRoute('POST', '/adddevice', [new DeviceController(), 'addDevice']); // prida zariadenie na konkretneho usera (auth id)

$router->dispatch();
