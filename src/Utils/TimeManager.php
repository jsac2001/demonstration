<?php

namespace Utils;

class TimeManager {

    public static function isScrutinOpen(string $dateDebut, string $dateFin): bool {
        $currentDate = date('Y-m-d H:i:s');
        return ($currentDate >= $dateDebut && $currentDate <= $dateFin);
    }

    public static function getRemainingTime(string $dateFin): string {
        $now = new \DateTime();
        $endDate = new \DateTime($dateFin);
        $interval = $now->diff($endDate);
        return $interval->format('%d jours, %h heures, %i minutes restantes');
    }
}
