<?php

// Import de la configuration de la BDD
require_once(__DIR__ . '/../config/Database.php');

class Voiture {

    public static function addVoiture($userId, $data) {
        $pdo = Database::getConnection();

        // Étape 1 : Obtenir l'ID de la marque
        $marque_id = self::getMarqueId($data['marque']);

        // Étape 2 : Insérer le véhicule avec l'ID de la marque
        $stmt = $pdo->prepare("INSERT INTO voiture
            (utilisateur_id, marque_id, immatriculation, modele, date_immatriculation, nb_places_voiture, energie) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
            
        $stmt->execute([
            $userId,
            $marque_id,
            $data['immatriculation'],
            $data['modele'],
            ddmmyyyy_to_yyyymmdd($data['date_immatriculation']),
            $data['nb_places_voiture'],
            $data['energie'],
        ]);
    }
    
    public static function countVoitures($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM voiture WHERE utilisateur_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public static function getVoituresByUser($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM voiture WHERE utilisateur_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function updateVoiture($voitureId, $data) {
        $pdo = Database::getConnection();
        unset($data['id_voiture']); // éviter mise à jour accidentelle
        $set = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
        $stmt = $pdo->prepare("UPDATE voiture SET $set WHERE utilisateur_id = ?");
        return $stmt->execute([...array_values($data), $voitureId]);
    }

    public static function deleteVoiture($id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM voiture WHERE utilisateur_id = ?");
        return $stmt->execute([$id]);
    }

    public static function getMarqueId($nom_marque) {
    $pdo = Database::getConnection();

    // Rechercher si la marque existe déjà
    $stmt = $pdo->prepare("SELECT marque FROM marque WHERE marque = ?");
    $stmt->execute([$nom_marque]);
    $result = $stmt->fetch();

    if ($result) {
        return $result['marque'];
    } else {
        // Si elle n'existe pas, on peut la créer (facultatif selon ton besoin)
        $stmt = $pdo->prepare("INSERT INTO marque (marque.marque) VALUES (?)");
        $stmt->execute([$nom_marque]);
        return $pdo->lastInsertId();
    }
    }


}