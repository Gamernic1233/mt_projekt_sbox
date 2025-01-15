<?php
namespace Src;

use PDO;

class DeviceController{
    public function addDevice() {
        $data = json_decode(file_get_contents('php://input'), true);
        $nazov_zariadenia = $data['nazov_zariadenia'] ?? '';
        $db = (new Database())->getConnection();
     
     try {
        $stmt = $db->prepare('INSERT INTO zariadenia (nazov_zariadenia) VALUES (:nazov_zariadenia)');
        $stmt->execute(['nazov_zariadenia' => $nazov_zariadenia]);
        echo json_encode(['message' => 'success']);
     } catch (\Throwable $th) {
        http_response_code(401);
        echo json_encode(['message' => 'failed - device already exists']);
     }
    }

    public function getDevices() {
        $db = (new Database())->getConnection();
        $stmt = $db->query('SELECT * FROM zariadenia');
        $data = $stmt->fetchAll();
        $result = [];
        foreach ($data as $key => $value) {
            $record = [
                'nazov_zariadenia' => $value['nazov_zariadenia']
            ];
            array_push($result, $record);
        }

        echo json_encode($result);
    }

    public function getUserDevices() {
        if (isset($_GET['username'])) {
            $username = $_GET['username'];
            $db = (new Database())->getConnection();
        
            $stmt = $db->prepare('SELECT id FROM users WHERE username = :username');
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);  // Bezpečne viaže parameter
            $stmt->execute();
            $user = $stmt->fetch();
        
            if ($user) {
                $user_id = $user['id'];
                // Načítaj zariadenia priradené k používateľovi
                $stmt = $db->prepare('SELECT nazov_zariadenia FROM zariadenia WHERE author_id = :author_id');
                $stmt->bindParam(':author_id', $user_id, PDO::PARAM_INT);  // Bezpečné viazanie ID
                $stmt->execute();
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
                $result = [];
                foreach ($data as $device) {
                    $result[] = $device['nazov_zariadenia'];
                }
        
                echo json_encode($result); 
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'User not found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Username not provided']);
        }
    }
    
}