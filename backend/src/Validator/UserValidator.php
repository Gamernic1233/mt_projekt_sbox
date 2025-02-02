<?php
namespace Backend\Validator;

class UserValidator {
    /**
     * Validate login data.
     * 
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function validateLogin($username, $password) {
        // Add your validation logic here.
        // For example, check that both fields are non-empty:
        if(empty($username) || empty($password)) {
            return false;
        }
        return true;
    }

    /**
     * Validate registration data.
     *
     * @param string $username
     * @param string $password
     * @param string $repeatPassword
     * @param string $email
     * @return bool
     */
    public function validateRegistration($username, $password, $repeatPassword, $email) {
        // Basic example validation:
        if(empty($username) || empty($password) || empty($repeatPassword) || empty($email)) {
            return false;
        }
        if ($password !== $repeatPassword) {
            return false;
        }
        // Optionally: add email format validation here.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }
}
