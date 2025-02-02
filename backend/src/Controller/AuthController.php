<?php
namespace Backend\Controller;

use Backend\Service\AuthService;
use Backend\Enum\StatusCode;
use Backend\Validator\UserValidator;
use Backend\Exceptions\UnauthorizedException; // Add this line to import the exception

class AuthController {
    private $authService;
    private $userValidator;

    public function __construct() {
        $this->authService = new AuthService();
        $this->userValidator = new UserValidator();
    }

    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        // Validate the input
        if (!$this->userValidator->validateLogin($username, $password)) {
            http_response_code(StatusCode::BAD_REQUEST);
            echo json_encode(['message' => 'Invalid input']);
            return;
        }

        try {
            // Authenticate user
            $response = $this->authService->login($username, $password);

            if ($response['status'] === 'success') {
                echo json_encode(['message' => 'Login successful']);
            } else {
                http_response_code(StatusCode::UNAUTHORIZED);
                echo json_encode(['message' => 'Invalid credentials']);
            }
        } catch (UnauthorizedException $e) {
            // Catch the UnauthorizedException and return 401
            http_response_code(StatusCode::UNAUTHORIZED);
            echo json_encode(['message' => $e->getMessage()]);  // You can also log this error for debugging
        }
    }

    public function register() {
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $repeat_password = $data['repeat_password'] ?? '';
        $email = $data['email'] ?? '';

        // Validate user input
        if (!$this->userValidator->validateRegistration($username, $password, $repeat_password, $email)) {
            http_response_code(StatusCode::BAD_REQUEST);
            echo json_encode(['message' => 'Invalid input']);
            return;
        }

        $response = $this->authService->register($username, $password, $email);
        echo json_encode($response);
    }
}

