<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/User.php';

if (!isset($_SESSION['utilisateur_id'])) {
    $_SESSION['error'] = "Vous devez être connecté.";
    header('Location: ../../frontend/preference_user.php');
    exit;
}

$userId = $_SESSION['utilisateur_id'];
$historique = User::getHistoriqueCovoiturages($userId);

// On stocke pour la vue
$_SESSION['historique_covoiturages'] = $historique;
// Redirection vers la page où tu affiches le rôle
//header('Location: ../../frontend/historique_trajet.php');
//exit;
?>