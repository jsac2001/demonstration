<?php

namespace Classes;

class Scrutin {
    private int $id;
    private string $question;
    private array $options;
    private string $description;
    private string $dateDebut;
    private string $dateFin;
    private string $methodeDeVote;

    public function __construct(string $question, array $options, string $description, string $dateDebut, string $dateFin, string $methodeDeVote) {
        $this->question = $question;
        $this->options = $options;
        $this->description = $description;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->methodeDeVote = $methodeDeVote;
    }

    public function create(): bool {
        // Code pour créer le scrutin dans la base de données
        return true;
    }

    public function close(): bool {
        // Code pour fermer le scrutin
        return true;
    }

    public function getResults(): array {
        // Code pour calculer et retourner les résultats
        return [];
    }
}
