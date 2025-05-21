<?php

// Démarrage de session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*if (!isset($_SESSION['utilisateur_id'])) {
    // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
    header('Location: ../../frontend/historique_trajet.php');
    exit();
}
*/

require_once('../config/db.php');

require_once '../models/User.php';
require_once '../models/Date.php';

$depart = strtoupper($_POST['depart']);
$destination = strtoupper($_POST['destination']);
$date_depart = $_POST['date_depart'];
$heure_depart = $_POST['heure_depart'];
$date_arrivee = $_POST['date_arrivee'];
$heure_arrivee = $_POST['heure_arrivee'];
$places = $_POST['places'];
$prix = $_POST['prix'];
$vehicule_id = strtoupper($_POST['vehicule_id']);
$chauffeur_id = $_SESSION['utilisateur_id'];
$adresse_depart = $_POST['adresse_depart'];
$adresse_arrivee = $_POST['adresse_arrivee'];

/* Vérifier si nouveau véhicule
if ($vehicule_id === 'new') {
    $marque = $_POST['nouvelle_marque'];
    $modele = $_POST['nouveau_modele'];
    $immat = $_POST['nouvelle_immatriculation'];

    $stmt = $pdo->prepare("INSERT INTO vehicules (utilisateur_id, marque, modele, immatriculation) VALUES (?, ?, ?, ?)");
    $stmt->execute([$chauffeur_id, $marque, $modele, $immat]);
    $vehicule_id = $pdo->lastInsertId();
}*/

// Déduire les 2 crédits de la plateforme
//$credit_reel = $prix - 2;

// Insertion du trajet
$stmt = $pdo->prepare("INSERT INTO covoiturage (utilisateur_id, lieu_depart, lieu_arrivee, date_depart, heure_depart, date_arrivee, heure_arrivee, nb_place, nb_place_reservee, prix_personne, voiture_id, statut_covoiturage, adresse_depart, adresse_arrivee) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?, 1, ?, ?)");
$result = $stmt->execute([$chauffeur_id, $depart, $destination, $date_depart, $heure_depart, $date_arrivee, $heure_arrivee, $places, $prix, $vehicule_id, $adresse_depart, $adresse_arrivee]);


if ($result) {
    header('Location: ../../frontend/historique_trajet.php?success=1');
} else {
    header('Location: ../../frontend/historique_trajet.php?success=0');
}