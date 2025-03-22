<?php
namespace Frontend\Controller;

use Frontend\Service\AuthService;

class RegistrationController {

    private AuthService $authService;

    public function __construct() {
        $this->authService = new AuthService();
    }

    public function register() : void {
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

        // Ak nie je prihlásený, zobraz stránku pre registráciu
        ob_start();
        include __DIR__ . '/../View/registracia.php';
        echo ob_get_clean();
    }
}
