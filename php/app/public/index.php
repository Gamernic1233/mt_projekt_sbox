<?php
require_once __DIR__ . '/../../vendor/autoload.php';  // Prípadne uprav cestu k autoload.php

use Src\AuthController;
use Src\DataController;
use Src\DeviceController;

// Získaj cestu endpointu
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Povolenie pre všetky originy
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Povolené HTTP metódy
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Povolené hlavičky

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Content-Length: 0");
    header("Content-Type: text/plain");
    exit(0); // Ukonči OPTIONS požiadavku
}

// Endpointy getZariadeniaData()
if ($requestUri === '/login' && $requestMethod === 'POST') {
    (new AuthController())->login();
} elseif ($requestUri === '/data' && $requestMethod === 'GET') {
    (new DataController())->getData();
} elseif ($requestUri === '/' && $requestMethod === 'GET') {
    echo json_encode(['message' => 'Welcome to the API']);
} elseif ($requestUri === '/register' && $requestMethod === 'POST') {
    (new AuthController())->register();
} elseif ($requestUri === '/push' && $requestMethod === 'POST') {
    (new DataController())->pushRecord();
} elseif ($requestUri === '/deviceData' && $requestMethod === 'POST') {
    (new DataController())->getDeviceData();
} elseif ($requestUri === '/addDevice' && $requestMethod === 'POST') {
    (new DeviceController())->addDevice();
} elseif ($requestUri === '/getDevices' && $requestMethod === 'GET') {
    (new DeviceController())->getDevices();
}elseif ($requestUri === '/getUserDevices' && $requestMethod === 'GET') {
    (new DeviceController())->getUserDevices(); 
} elseif ($requestUri === '/getUsers' && $requestMethod === 'POST') {
    (new DataController())->getUsers();
}elseif ($requestUri === '/getZariadeniaData' && $requestMethod === 'POST') {
    (new DataController())->getZariadeniaData();
}else {
    http_response_code(404);
    echo json_encode(['message' => 'Endpoint not found']);
}
