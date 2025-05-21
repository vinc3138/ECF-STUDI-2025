<?php

session_start();

require_once '../config/Database.php';
require_once('../models/User.php');

if (!isset($_SESSION['utilisateur_id'])) {
    $_SESSION['error'] = "Vous devez être connecté.";
    header('Location: ../../frontend/preference_user.php');
    exit;
}

// Récupération des variables nécessaires, par ex :
$userId = $_SESSION['utilisateur_id'] ?? null;
$covoiturageId = $_SESSION['detail_covoiturage']['trajet']['covoiturage_id'] ?? null;
$prix = $_SESSION['detail_covoiturage']['prix'] ?? null;

if (User::validerParticipation($userId, $covoiturageId, $prix)) {
    
    // Decompte du crédit
    $_SESSION['credit']= $_SESSION['credit'] - $prix;

    echo "Participation validée, crédit débité et place réservée.";
} else {
    echo "Erreur lors de la validation. Crédits insuffisants ou plus de places disponibles.";
}

// Redirection vers la page où tu affiches le rôle
header('Location: ../../frontend/historique_trajet.php?validate=1');
exit;
?>