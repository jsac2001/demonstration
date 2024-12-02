<?php

namespace Classes;

class User {
    private int $id;
    private string $pseudo;
    private string $email;
    private string $motDePasse;
    private string $role;

    public function __construct(string $pseudo, string $email, string $motDePasse, string $role = 'Participant') {
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->motDePasse = password_hash($motDePasse, PASSWORD_DEFAULT);
        $this->role = $role;
    }

    public function register(): bool {
        // Code pour enregistrer l'utilisateur dans la base de données
        return true;
    }

    public function login(string $email, string $password): bool {
        // Code pour vérifier l'authentification de l'utilisateur
        return true;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function setRole(string $role): void {
        $this->role = $role;
    }
}
