<?php
namespace Src;

class DataController {
    public function getUsers() {
        $db = (new Database())->getConnection();
        $stmt = $db->query('SELECT * FROM users');
        $data = $stmt->fetchAll();
        $result = [];
        foreach ($data as $key => $value) {
            $record = [
                'id' => $value['id'],
                'username' => $value['username'],
                'password' => $value['password'],
                'email' => $value['email']
            ];
            array_push($result, $record);
        }
        echo json_encode($result); 
    }

    public function getZariadeniaData() {
        $db = (new Database())->getConnection();
        $stmt = $db->query('SELECT * FROM zariadenia');
        $data = $stmt->fetchAll();
        $result = [];
        foreach ($data as $key => $value) {
            $record = [
                'id' => $value['id'],
                'author_id' => $value['author_id'],
                'nazov_zariadenia' => $value['nazov_zariadenia'],
            ];
            array_push($result, $record);
        echo json_encode($result); 
        }
    }
    
    public function getData() {
        $db = (new Database())->getConnection();
        $stmt = $db->query('SELECT * FROM merane_data');
        $data = $stmt->fetchAll();
        $result = [];
        foreach ($data as $key => $value) {
            $record = [
                'nazov_zariadenia' => $value['nazov_zariadenia'],
                'vlhkost_pody' => $value['vlhkost_pody'],
                'tlak_vzduchu' => $value['tlak_vzduchu'],
                'teplota_vzduchu' => $value['teplota_vzduchu'],
                'vlhkost_vzduchu' => $value['vlhkost_vzduchu'],
                'datum_cas' => $value['datum_cas']
            ];
            array_push($result, $record);
        }

        echo json_encode($result);
    }

    public function pushRecord() {
        $data = json_decode(file_get_contents('php://input'), true);

        $db = (new Database())->getConnection();
        $stmt = $db->prepare('SELECT * FROM zariadenia WHERE nazov_zariadenia = :nazov_zariadenia');
        $stmt->execute(['nazov_zariadenia' => $data['nazov_zariadenia']]);
        $device = $stmt->fetch();

        if (!$device) {
            echo json_encode(['message' => 'Device not found']);
            return;
        }

        $nazov_zariadenia = $data['nazov_zariadenia'] ?? '';
        $vlhkost_pody = floatval($data['vlhkost_pody'] ?? '');
        $tlak_vzduchu = floatval($data['tlak_vzduchu'] ?? '');
        $teplota_vzduchu = floatval($data['teplota_vzduchu'] ?? '');
        $vlhkost_vzduchu = floatval($data['vlhkost_vzduchu'] ?? '');
        $datum_cas = $data['datum_cas'] ?? '';
        $datum_cas = date('Y-m-d H:i:s.u', strtotime($datum_cas));

        $db = (new Database())->getConnection();
        $stmt = $db->prepare('INSERT INTO merane_data (vlhkost_pody, tlak_vzduchu, teplota_vzduchu, vlhkost_vzduchu, datum_cas, nazov_zariadenia) VALUES (:vlhkost_pody, :tlak_vzduchu, :teplota_vzduchu, :vlhkost_vzduchu, :datum_cas, :nazov_zariadenia)');
        $stmt->execute(['nazov_zariadenia' => $nazov_zariadenia, 'vlhkost_pody' => $vlhkost_pody, 'tlak_vzduchu' => $tlak_vzduchu, 'teplota_vzduchu' => $teplota_vzduchu, 'vlhkost_vzduchu' => $vlhkost_vzduchu, 'datum_cas' => $datum_cas, 'nazov_zariadenia' => $nazov_zariadenia]);

        echo json_encode(['message' => 'sucess']);
    }

    public function getDeviceData() {
        $db = (new Database())->getConnection();
        $data = json_decode(file_get_contents('php://input'), true);
        $nazov_zariadenia = $data['nazov_zariadenia'] ?? '';

        $response = [];

        $stmt = $db->prepare('SELECT * FROM merane_data where nazov_zariadenia = :nazov_zariadenia');
        $stmt->execute(['nazov_zariadenia' => $nazov_zariadenia]);

        $result = $stmt->fetchAll();

        foreach ($result as $key => $value) {
            $record = [
                'nazov_zariadenia' => $value['nazov_zariadenia'],
                'vlhkost_pody' => $value['vlhkost_pody'],
                'tlak_vzduchu' => $value['tlak_vzduchu'],
                'teplota_vzduchu' => $value['teplota_vzduchu'],
                'vlhkost_vzduchu' => $value['vlhkost_vzduchu'],
                'datum_cas' => $value['datum_cas']
            ];
            array_push($response, $record);
        }

        echo json_encode($response);
    }
}
