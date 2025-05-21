<?php

// Import de la configuration de la BDD
require_once(__DIR__ . '/../config/Database.php');

class Covoiturage {
    public static function getCovoituragesParJour() {
        global $pdo;
        $stmt = $pdo->query("SELECT DATE(date_départ) as jour, COUNT(*) as total FROM covoiturage GROUP BY jour");
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[$row['jour']] = $row['total'];
        }
        return $results;
    }

    public static function getCreditsParJour() {
        global $pdo;
        $stmt = $pdo->query("SELECT DATE(date_départ) as jour, SUM(prix_place) as total FROM covoiturage GROUP BY jour");
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[$row['jour']] = $row['total'];
        }
        return $results;
    }

    public static function getTotalCredits() {
        global $pdo;
        $stmt = $pdo->query("SELECT SUM(prix_place) as total FROM covoiturage");
        $row = $stmt->fetch();
        return $row['total'] ?? 0;
    }
}
