<?php

namespace Classes;

class Vote {
    private int $id;
    private int $idUtilisateur;
    private int $idScrutin;
    private array $choix;

    public function __construct(int $idUtilisateur, int $idScrutin, array $choix) {
        $this->idUtilisateur = $idUtilisateur;
        $this->idScrutin = $idScrutin;
        $this->choix = $choix;
    }

    public function submitVote(): bool {
        // Code pour soumettre un vote dans la base de données
        return true;
    }

    public function validateVote(): bool {
        // Code pour valider le format du vote
        return true;
    }
}
