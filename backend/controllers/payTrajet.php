<?php
session_start();

require_once '../config/Database.php';
require_once '../models/User.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: ../../frontend/login.php');
    exit;
}

$pdo = Database::getConnection();

// Vérifie si les bons champs sont envoyés
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payer']) && isset($_POST['action']) && $_POST['action'] === 'payer') {
    
    $_SESSION['message']='';
    $trajetId = intval($_POST['payer']);
    $passagerId = intval($_SESSION['utilisateur_id']);

    // Vérifier que le passager a bien réservé ce trajet et qu’il n’a pas encore payé
    $stmt = $pdo->prepare("
        SELECT c.prix_personne, c.utilisateur_id AS chauffeur_id, r.paiement_realise
        FROM covoiturage c
        JOIN reservation r ON c.covoiturage_id = r.covoiturage_id
        WHERE r.covoiturage_id = :trajet_id AND r.utilisateur_id = :passager_id
    ");
    $stmt->execute([
        'trajet_id' => $trajetId,
        'passager_id' => $passagerId
    ]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$reservation) {
        die("Réservation introuvable.");
    }

//echo var_dump($reservation['paiement_realise']);
//exit;

    if ($reservation['paiement_realise']==="OUI") {
        $_SESSION['message'] = "Ce trajet a déjà été payé.";
        header("Location: ../../frontend/historique_trajet.php");
        exit;
    }

    $prix = $reservation['prix_personne'];
    $chauffeurId = $reservation['chauffeur_id'];

    try {
        $pdo->beginTransaction();

        // 1. Débiter le passager
        $stmt = $pdo->prepare("UPDATE utilisateur SET credit = credit - :prix WHERE utilisateur_id = :passagerId AND credit >= :prix_trajet");
        $stmt->execute([
            'prix' => $prix,
            'passagerId' => $passagerId,
            'prix_trajet' => $prix
        ]);

        if ($stmt->rowCount() === 0) {
            throw new Exception("Solde insuffisant.");
        }

        // 2. Créditer le chauffeur
        $stmt = $pdo->prepare("UPDATE utilisateur SET credit = credit + :prix WHERE utilisateur_id = :chauffeurId");
        $stmt->execute([
            'prix' => $prix,
            'chauffeurId' => $chauffeurId
        ]);

        // 3. Marquer la réservation comme payée
        $stmt = $pdo->prepare("UPDATE reservation SET paiement_realise = 'OUI' WHERE covoiturage_id = :trajet_id AND utilisateur_id = :passager_id");
        $stmt->execute([
            'trajet_id' => $trajetId,
            'passager_id' => $passagerId
        ]);

        $pdo->commit();
        $_SESSION['message'] = "Paiement effectué avec succès.";
        $_SESSION['credit'] =  $_SESSION['credit'] - $prix;

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Erreur lors du paiement : " . $e->getMessage();
    }


    header("Location: ../../frontend/historique_trajet.php");
    exit;
}

header("Location: ../../frontend/historique_trajet.php");
exit;