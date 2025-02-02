<?php
namespace Backend\Hydrator;

use Backend\Entity\User;

class UserHydrator {
    public static function hydrate(array $data): User {
        $user = new User();
        $user->id = $data['id'];
        $user->username = $data['username'];
        $user->password = $data['password'];
        $user->email = $data['email'];
        return $user;
    }
}