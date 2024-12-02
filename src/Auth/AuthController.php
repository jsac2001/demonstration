<?php

namespace Auth;

use Classes\User;

class AuthController {

    public function login(string $email, string $password): bool {
        // Code pour vérifier les informations d'identification de l'utilisateur
        return true;
    }

    public function logout(): void {
        SessionManager::logout();
    }
}
