<?php

// Import de la configuration de la BDD
require_once(__DIR__ . '/../config/Database.php');

class Preference {
        
    public static function getPreferences($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM preference WHERE utilisateur_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getCustomPreferences($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM utilisateur_preferences WHERE utilisateur_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addCustomPreferences($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT preference.preference
        FROM utilisateur_preference
        JOIN preference ON utilisateur_preference.preference_id = preference.preference_id
        WHERE utilisateur_preference.utilisateur_id = :id
        AND preference.statut_preference = 'USER'
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

}