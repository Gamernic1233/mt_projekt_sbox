<?php
namespace Backend\Controller;

use Backend\Service\DeviceService;
use Backend\Service\AuthService;

class DeviceController {
    private $deviceService;
    private AuthService $authService;

    public function __construct() {
        $this->deviceService = new DeviceService();
        $this->authService = new AuthService();
    }

    public function addDevice() {
        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->deviceService->addDevice($data);
        echo json_encode($response);
    }

    public function getDevices() {
        $data = $this->deviceService->getDevices();
        echo json_encode($data);
    }

    public function getUserDevices() {
        $headers = getallheaders();
        $JWT = null;

        if (isset($headers['Authorization'])) {
            $matches = [];
            
            if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
                $JWT = $matches[1]; // Return the token part
            }
        }

        if($JWT) {
            try {
                $data = $this->authService->validateJWT($JWT);
            } catch(\Exception $e) {
                
            } 
        }

        if(isset($data) && $data['username']) {
            $data = $this->deviceService->getUserDevices($data['username']); 
            echo json_encode($data); 
            return;
        }

        http_response_code(401);  // Ak neexistuje username, vráti chybu
        echo json_encode(['error' => 'Unauthorized']);


        // if (isset($_GET['username'])) {
        //     $username = $_GET['username'];  // Získa parametre username
    
        //     // Zavolaj metódu, ktorá vracia všetky zariadenia používateľa
        //     $data = $this->deviceService->getUserDevices($username); 
    
        //     echo json_encode($data);  // Pošle zariadenia späť ako JSON
        // } else {
        //     http_response_code(400);  // Ak neexistuje username, vráti chybu
        //     echo json_encode(['error' => 'Username is required']);
        // }
    }

    public function getUserDevice() {
        $username = $_GET['username'] ?? null;
        $deviceName = $_GET['device_name'] ?? null;
    
        if (!$username || !$deviceName) {
            http_response_code(400);
            echo json_encode(['error' => 'Both username and device_name are required']);
            return;
        }
    
        // Zavolaj metódu na získanie dát o zariadení
        $data = $this->deviceService->getUserDeviceData($username, $deviceName);
        echo json_encode($data);
    }
    
}