<?php

namespace Auth;

class SessionManager {

    public static function startSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function isLoggedIn(): bool {
        return isset($_SESSION['user']);
    }

    public static function logout(): void {
        session_destroy();
    }
}
