<?php
namespace Backend\Service;

use Backend\Database\Database;
use Backend\Entity\User;
use Backend\Exceptions\UnauthorizedException;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;



class AuthService {

    private string $keyPath;

    public function __construct() {
        $this->keyPath = __DIR__.'/../../data/';
    }

    public function login(string $username, string $password) : array {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && $user['password'] === $password)  {
            $JWT = $this->generateJWT([
                'username' => $user['username'],
                'email' => $user['email'],
            ]);
            return [
                'status' => 'success', 
                'user' => $user['username'],
                'token' => $JWT,
            ];
        }

        throw new UnauthorizedException();
    }

    public function register(string $username, string $password, string $email) : array {
        $db = (new Database())->getConnection();
    
        // Skontroluj, či užívateľ s daným username už existuje
        $stmt = $db->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $userExists = $stmt->fetchColumn();
    
        if ($userExists) {
            // Vráť chybu, ak už existuje používateľ s rovnakým menom
            http_response_code(409); // Conflict
            return ['status' => 'error', 'message' => 'Username already exists'];
        }
    
        // Vlož nového používateľa, ak username nie je obsadené
        $stmt = $db->prepare('INSERT INTO users (username, password, email) VALUES (:username, :password, :email)');
        $stmt->execute(['username' => $username, 'password' => $password, 'email' => $email]);
    
        // Generovanie JWT tokenu pre nového používateľa
        $JWT = $this->generateJWT([
            'username' => $username,
            'email' => $email,
        ]);
    
        return [
            'status' => 'success', 
            'message' => 'User registered successfully',
            'token' => $JWT  // Posielame token späť klientovi
        ];
    }
    

    public function validateJWT(string $token) : array {
        if (!file_exists($this->keyPath.'/public_key.pem')) {
            throw new \Exception('Key not found.');
        }

        $publicKey = file_get_contents($this->keyPath.'/public_key.pem');
        try {
            $payLoad = JWT::decode($token, new Key($publicKey, 'RS256'));
            return (array) $payLoad;
        } catch(\Exception $e) {
            throw new \Exception('Key invalid. ');
        } catch(\UnexpectedValueException $e) {
            throw new \Exception('Key invalid. '.$e->getMessage());
        }

    }

    private function generateJWT(array $userData) : string {
        if (!file_exists($this->keyPath.'/private_key.pem')) {
            throw new \Exception('Key not found.');
        }

        $privateKey = file_get_contents($this->keyPath.'/private_key.pem');
        $timeIssued = time();
        $timeExpire = time() + 3600;

        if(!$userData) {
            throw new \Exception('User data not found.');
        }

        $payLoad = array_merge($userData, [
            'iat' => $timeIssued, 
            'exp' => $timeExpire
        ]);

        return JWT::encode($payLoad, $privateKey, 'RS256');
    }

}
