<?php
namespace Src;

class AuthController {
    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $db = (new Database())->getConnection();
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && $user['password'] === $password) {
            echo json_encode(['message' => 'Login successful']);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid credentials']);
        }
    }

    public function register() {
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $repeat_password = $data['repeat_password'] ?? '';
        $email = $data['email'] ?? '';

        if ($password !== $repeat_password) {
            echo json_encode(['message' => 'Passwords do not match']);
            return;
        }

        try {
            $db = (new Database())->getConnection();
            $stmt = $db->prepare('INSERT INTO users (username, password, email) VALUES (:username, :password, :email)');
            $stmt->execute(['username' => $username, 'password' => $password, 'email' => $email]);
            echo json_encode(['message' => 'success']);
        } catch (\Throwable $th) {
            echo json_encode(['message' => 'failed']);
        }
        

        
    }
}
