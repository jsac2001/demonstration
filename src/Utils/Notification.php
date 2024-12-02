<?php

namespace Utils;

class Notification {
    private int $id;
    private string $message;
    private string $type;
    private string $date;

    public function __construct(string $message, string $type, string $date) {
        $this->message = $message;
        $this->type = $type;
        $this->date = $date;
    }

    public function addNotification(): bool {
        // Code pour ajouter une notification dans la base de données
        return true;
    }

    public function getNotifications(): array {
        // Code pour récupérer les notifications
        return [];
    }
}
