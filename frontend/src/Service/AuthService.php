<?php
namespace Frontend\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService {

    private string $keyPath;

    public function __construct() {
        $this->keyPath = __DIR__.'/../../data/';
    }



    public function validateJWT(string $token) : array {
        if (!file_exists($this->keyPath.'/public_key.pem')) {
            throw new \Exception('Key not found.');
        }

        $publicKey = file_get_contents($this->keyPath.'/public_key.pem');
        try {
            $payLoad = JWT::decode($token, new Key($publicKey, 'RS256'));
            return (array) $payLoad;
        } catch(Exception $e) {
            throw new \Exception('Key invalid. '.$e->getMessage());
        }

    }
    
}
