<?php

session_start();

require_once '../config/Database.php';
require_once('../models/User.php');

if (!isset($_SESSION['utilisateur_id'])) {
    $_SESSION['error'] = "Vous devez être connecté.";
    header('Location: ../../frontend/preference_user.php');
    exit;
}

require_once '../config/db.php';

if (!empty($_POST['covoiturage_id']) && !empty($_POST['action'])) {
    $pdo = Database::getConnection();
    $covoiturageId = $_POST['covoiturage_id'];
    $action = $_POST['action'];

    // Récupérer le statut actuel du covoiturage
    $stmt = $pdo->prepare("SELECT statut_covoiturage FROM covoiturage WHERE covoiturage_id = :id");
    $stmt->execute(['id' => $covoiturageId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);



    if (!$result) {
        die("Trajet introuvable.");
    }

    $statutActuel = (int)$result['statut_covoiturage'];
    $nouveauStatut = null;

    // Transition selon le statut et l'action demandée
    switch ($action) {
        case 'commencer':
            if ($statutActuel === 1) { // à venir
                $nouveauStatut = 2; // en cours
            }
            break;
        case 'terminer':
            if ($statutActuel === 2) { // en cours
                $nouveauStatut = 3; // terminé
            }
            break;
        case 'annuler':
            if (in_array($statutActuel, [1, 2])) {
                $nouveauStatut = 4; // annulé
            }
            break;
        default:
            die("Action invalide.");
    }



    if ($nouveauStatut !== null) {
        $updateStmt = $pdo->prepare(
        "UPDATE covoiturage
        SET statut_covoiturage = :statut 
        WHERE covoiturage_id = :id
        ");
        $updateStmt->execute([
            'statut' => $nouveauStatut,
            'id' => $covoiturageId
        ]);

        // Redirige vers la page des historique trajets avec le nouveau statut
        header("Location: ../../frontend/historique_trajet.php?maj_trajet=".$nouveauStatut);
        exit;

    } else {
        echo "Changement de statut non autorisé pour ce trajet.";
    }

// Redirige vers la page des historique trajets avec canceled=0 pour indiquer que l'annulation n'a pas eu lieu
header("Location: ../../frontend/historique_trajet.php?maj_trajet=0");
}