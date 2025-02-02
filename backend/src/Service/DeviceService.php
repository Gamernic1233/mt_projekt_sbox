<?php
namespace Backend\Service;

use Backend\Database\Database;

class DeviceService {
    public function addDevice($data) {
        $db = (new Database())->getConnection();
        
        // Overíme, či zariadenie už existuje
        $stmt = $db->prepare('SELECT COUNT(*) FROM zariadenia WHERE nazov_zariadenia = :nazov_zariadenia');
        $stmt->execute(['nazov_zariadenia' => $data['nazov_zariadenia']]);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            // Ak zariadenie už existuje, vrátime chybovú odpoveď
            return ['status' => 'failed', 'message' => 'Device already exists'];
        }
    
        // Ak zariadenie neexistuje, pokračujeme s vložením
        try {
            $stmt = $db->prepare('INSERT INTO zariadenia (nazov_zariadenia, author_id) VALUES (:nazov_zariadenia, :author_id)');
            $stmt->execute([
                'nazov_zariadenia' => $data['nazov_zariadenia'],
                'author_id' => $data['author_id']
            ]);
            
            // Ak je všetko v poriadku, vrátime úspešnú odpoveď
            return ['status' => 'success', 'message' => 'Device added successfully'];
        } catch (\Throwable $th) {
            // Ak nastane chyba pri vkladaní, vrátime chybovú odpoveď
            return ['status' => 'failed', 'message' => 'Failed to add device'];
        }
    }
    
    public function getDevices() {
        $db = (new Database())->getConnection();
        $stmt = $db->query('SELECT * FROM zariadenia');
        return $stmt->fetchAll();
    }

    public function getUserDevices($username) {
        if (empty($username)) {
            return ['error' => 'Username is required'];  // Ak nie je username, vráti chybu
        }
    
        $db = (new Database())->getConnection();
        $stmt = $db->prepare('SELECT * FROM zariadenia WHERE author_id = (SELECT id FROM users WHERE username = :username)');
        $stmt->execute(['username' => $username]);
    
        return $stmt->fetchAll();  // Vráti zoznam zariadení používateľa
    }
    
    public function getUserDeviceData($username, $deviceName) {
        if (empty($username) || empty($deviceName)) {
            return ['error' => 'Username and device_name are required'];
        }
    
        $db = (new Database())->getConnection();
        // Oprava názvu tabuľky z 'data' na 'merane_data'
        $stmt = $db->prepare('SELECT * FROM zariadenia INNER JOIN merane_data ON zariadenia.nazov_zariadenia = merane_data.nazov_zariadenia WHERE zariadenia.author_id = (SELECT id FROM users WHERE username = :username) AND zariadenia.nazov_zariadenia = :device_name');
        $stmt->execute(['username' => $username, 'device_name' => $deviceName]);
        
        return $stmt->fetchAll();
    }
    
}
