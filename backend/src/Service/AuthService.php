<?php
namespace Backend\Service;

use Backend\Database\Database;
use Backend\Entity\User;
use Backend\Exceptions\UnauthorizedException;

class AuthService {
    public function login($username, $password) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && $user['password'] === $password) {
            return ['status' => 'success', 'user' => $user];
        }

        throw new UnauthorizedException();
    }

    public function register($username, $password, $email) {
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
    
        return ['status' => 'success', 'message' => 'User registered successfully'];
    }
    
}
