<?php
namespace Backend\Controller;

use Backend\Service\DataService;

class DataController {
    private $dataService;

    public function __construct() {
        $this->dataService = new DataService();
    }

    public function getData() {
        $data = $this->dataService->getData();
        echo json_encode($data);
    }

    public function pushRecord() {
        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->dataService->pushRecord($data);
        echo json_encode($response);
    }

    public function getDeviceData() {
        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->dataService->getDeviceData($data['nazov_zariadenia']);
        echo json_encode($response);
    }
}