<?php

// Import de la configuration de la BDD
require_once(__DIR__ . '/../config/Database.php');

class User {
    public static function create($email, $password, $privilege) {
        $pdo = Database::getConnection(); // Connexion à la base
        $privilege=2;
        $stmt = $pdo->prepare("INSERT INTO utilisateur (email, password, privilege) VALUES (?, ?, ?)");
        $stmt->execute([$email, $password, $privilege]);
    }

    public static function suspend($id) {
        $pdo = Database::getConnection(); // Connexion à la base
        $stmt = $pdo->prepare("UPDATE utilisateur SET privilege_id = 4 WHERE utilisateur_id = ?");
        $stmt->execute([$id]);
    }

    public static function all() {
        $pdo = Database::getConnection(); // Connexion à la base
        $sql = "SELECT utilisateur.utilisateur_id, utilisateur.email, privilege.privilege AS privilege
                FROM utilisateur
                JOIN privilege ON utilisateur.privilege_id = privilege.privilege_id
                WHERE privilege.privilege IN ('EMPLOYE', 'USER')";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function updateRole($userId, $role) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE utilisateur SET role_id = ? WHERE utilisateur_id = ?");
        return $stmt->execute([$role_id, $userId]);
    }

    public static function updatePreferences($userId, $preferences) {
        $pdo = Database::getConnection(); // Connexion à la base
        // Supprime les anciennes préférences
        $pdo->prepare("DELETE FROM utilisateur_preference WHERE utilisateur_id = ?")->execute([$userId]);

        // Insère les nouvelles
        $stmt = $pdo->prepare("INSERT INTO utilisateur_preference (utilisateur_id, preference_id) VALUES (?, ?)");
        foreach ($preferences as $prefId) {
            $stmt->execute([$userId, $prefId]);
        }
    }

    public static function addCustomPreference($userId, $prefName) {
        $pdo = Database::getConnection(); // Connexion à la base

        // Vérifie si la préférence existe déjà
        $stmt = $pdo->prepare("SELECT preference_id FROM preference WHERE preference = ?");
        $stmt->execute([$prefName]);
        $id = $stmt->fetchColumn();

        if (!$id) {
            // Sinon, on l'ajoute
            $stmt = $pdo->prepare("INSERT INTO preference (preference) VALUES (?)");
            $stmt->execute([$prefName]);
            $id = $pdo->lastInsertId();
        }

        // On lie la préférence à l'utilisateur
        $stmt = $pdo->prepare("INSERT INTO utilisateur_preference (utilisateur_id, preference_id) VALUES (?, ?)");
        $stmt->execute([$userId, $id]);
    }

    public static function countVoitures($userId) {
        $pdo = Database::getConnection(); // Connexion à la base
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM voiture WHERE utilisateur_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }


    public static function validerParticipation($userId, $covoiturageId, $prix) {
        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            // 1. Mettre à jour le crédit utilisateur
            $stmt2 = $pdo->prepare("UPDATE utilisateur SET credit = credit - ? WHERE utilisateur_id = ? AND credit >= ?");
            $stmt2->execute([$prix, $userId, $prix]);
            if ($stmt2->rowCount() === 0) {
                // Pas assez de crédits, rollback
                $pdo->rollBack();
                return false;
            }

            // 2. Mettre à jour le nombre de places réservées dans le covoiturage
            $stmt3 = $pdo->prepare("UPDATE covoiturage SET nb_place_reservee = nb_place_reservee + 1 WHERE covoiturage_id = ? AND nb_place_reservee < nb_place");
            $stmt3->execute([$covoiturageId]);
            if ($stmt3->rowCount() === 0) {
                // Plus de place disponible, rollback
                $pdo->rollBack();
                return false;
            }

            $pdo->commit();
            return true;

        } catch (Exception $e) {
            $pdo->rollBack();
            // log erreur si besoin
            return false;
        }
    }

    public static function getHistoriqueCovoiturages($userId) {
        $pdo = Database::getConnection();

        $sql = "
            SELECT covoiturage.*, utilisateur.pseudo, role.libelle, reservation.statut_reservation_id
            FROM covoiturage
            JOIN utilisateur ON covoiturage.utilisateur_id = utilisateur.utilisateur_id
            JOIN role ON utilisateur.role_id = role.role_id
            LEFT JOIN reservation ON covoiturage.covoiturage_id = reservation.covoiturage_id
            WHERE covoiturage.utilisateur_id = :id1
            OR covoiturage.covoiturage_id IN (
                SELECT covoiturage_id FROM reservation WHERE reservation.utilisateur_id = :id2
            )
            ORDER BY covoiturage.date_depart DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id1' => $userId,
            ':id2' => $userId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}