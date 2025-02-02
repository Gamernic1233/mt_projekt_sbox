<?php
namespace Backend\Service;

use Backend\Database\Database;

class DataService {
    public function getData() {
        $db = (new Database())->getConnection();
        $stmt = $db->query('SELECT * FROM merane_data');
        return $stmt->fetchAll();
    }

    public function pushRecord($data) {

        // Overenie, či sú všetky potrebné hodnoty prítomné
        if (
            empty($data['nazov_zariadenia']) ||
            !isset($data['vlhkost_pody']) ||
            !isset($data['tlak_vzduchu']) ||
            !isset($data['teplota_vzduchu']) ||
            !isset($data['vlhkost_vzduchu']) ||
            empty($data['datum_cas'])
        ) {
            http_response_code(400); // Nastav HTTP 400 Bad Request
            return json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        }

        $db = (new Database())->getConnection();
        
        // Upravte dopyt, aby zahŕňal aj datum_cas
        $stmt = $db->prepare('INSERT INTO merane_data (nazov_zariadenia, vlhkost_pody, tlak_vzduchu, teplota_vzduchu, vlhkost_vzduchu, datum_cas) 
                              VALUES (:nazov_zariadenia, :vlhkost_pody, :tlak_vzduchu, :teplota_vzduchu, :vlhkost_vzduchu, :datum_cas)');
        
        // Posielajte aj datum_cas
        $stmt->execute([
            'nazov_zariadenia' => $data['nazov_zariadenia'],
            'vlhkost_pody' => $data['vlhkost_pody'],
            'tlak_vzduchu' => $data['tlak_vzduchu'],
            'teplota_vzduchu' => $data['teplota_vzduchu'],
            'vlhkost_vzduchu' => $data['vlhkost_vzduchu'],
            'datum_cas' => $data['datum_cas']
        ]);
    
        return ['status' => 'success'];
    }

    public function getDeviceData($deviceName) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare('SELECT * FROM merane_data WHERE device_name = :device_name');
        $stmt->execute(['device_name' => $deviceName]);
        return $stmt->fetchAll();
    }
}
