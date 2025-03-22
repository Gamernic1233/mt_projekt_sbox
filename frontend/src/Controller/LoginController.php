<?php
namespace Frontend\Controller;

use Frontend\Service\AuthService;

class LoginController {

    private AuthService $authService;

    public function __construct() {
        $this->authService = new AuthService();
    }

    public function login() : void {
        // Skontroluj, či existuje platný token
        if (isset($_COOKIE['token'])) {
            $JWT = $_COOKIE['token'];
            try {
                $data = $this->authService->validateJWT($JWT);
                // Ak je platný token, zobraz stránku pre prihláseného užívateľa
                ob_start();
                include __DIR__ . '/../View/prihlaseny.php';
                echo ob_get_clean();
                return;
            } catch (\Exception $e) {
                // Ak token nie je platný, pokračuj ako keby nebol prihlásený
            }
        }

        // Ak nie je prihlásený, zobraz stránku na prihlásenie
        ob_start();
        include __DIR__ . '/../View/login.php';
        echo ob_get_clean();
    }
}
