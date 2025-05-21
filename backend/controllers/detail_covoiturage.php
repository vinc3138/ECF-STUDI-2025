<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Import de la configuration de la BDD
require_once "../backend/config/db.php";

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Covoiturage non trouvé.";
    header("Location: ../../frontend/trajet.php");
    exit;
}

$covoiturage_id = $_GET['id'];

// Trajet + conducteur
$stmt = $pdo->prepare("
    SELECT c.*, u.pseudo, u.nom, u.prenom
    FROM covoiturage c
    JOIN utilisateur u ON c.utilisateur_id = u.utilisateur_id
    WHERE c.covoiturage_id = :id
");
$stmt->execute(['id' => $covoiturage_id]);
$covoiturage = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$covoiturage) {
    $_SESSION['error'] = "Ce covoiturage n'existe pas.";
    header("Location: ../../frontend/trajet.php");
    exit;
}

// --- Données de trajet
$date = $covoiturage['date_depart'];
$heure = date('H\h00', strtotime($covoiturage['heure_depart']));
$prix = $covoiturage['prix_personne'];
$place = $covoiturage['nb_place'];

// --- Avis du conducteur
$avis_stmt = $pdo->prepare("
    SELECT a.note, a.commentaire, u.pseudo
    FROM avis a
    JOIN covoiturage ON a.covoiturage_id = covoiturage.covoiturage_id
    JOIN utilisateur u ON covoiturage.utilisateur_id = u.utilisateur_id
    WHERE u.utilisateur_id = :id
");
$avis_stmt->execute(['id' => $covoiturage['utilisateur_id']]);
$avis = $avis_stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Moyenne des notes du conducteur
$moyenne_stmt = $pdo->prepare("
    SELECT AVG(avis.note) AS moyenne
    FROM avis
    JOIN covoiturage ON avis.covoiturage_id = covoiturage.covoiturage_id
    JOIN utilisateur ON covoiturage.utilisateur_id = utilisateur.utilisateur_id
    WHERE utilisateur.utilisateur_id = :id
");
$moyenne_stmt->execute(['id' => $covoiturage['utilisateur_id']]);
$moyenne_result = $moyenne_stmt->fetch(PDO::FETCH_ASSOC);

if ($moyenne_result && $moyenne_result['moyenne'] !== null) {
    $moyenne_note = round($moyenne_result['moyenne'], 1); // Arrondi à 0,1
} else {
    $moyenne_note = null; // Aucun avis
}

// --- Véhicule du conducteur
$vehicule_stmt = $pdo->prepare("
    SELECT marque, modele, energie
    FROM voiture
    JOIN marque ON voiture.marque_id = marque.marque_id
    WHERE utilisateur_id = :id
");
$vehicule_stmt->execute(['id' => $covoiturage['utilisateur_id']]);
$vehicule = $vehicule_stmt->fetch(PDO::FETCH_ASSOC);


// --- Préférences chauffeur FIXES
$pref_fixe_stmt = $pdo->prepare("
    SELECT preference.preference
    FROM utilisateur_preference
    JOIN preference ON utilisateur_preference.preference_id = preference.preference_id
    WHERE utilisateur_preference.utilisateur_id = :id
    AND preference.statut_preference = 'FIXE'
");
$pref_fixe_stmt->execute(['id' => $covoiturage['utilisateur_id']]);

// Préférences passées en minuscules
$preferences_raw = $pref_fixe_stmt->fetchAll(PDO::FETCH_COLUMN);
$preferences_fixes = array_map('strtolower', $preferences_raw);

// Initialise avec "false", puis passe à true si présent
$preferences = [
    'musique' => in_array('musique', $preferences_fixes),
    'discussion' => in_array('conversation', $preferences_fixes),
    'animal' => in_array('animal', $preferences_fixes),
    'fumeur' => in_array('fumeur', $preferences_fixes),
];


// --- Récupération des préférences PERSONNALISÉES
$pref_perso_stmt = $pdo->prepare("
    SELECT preference.preference
    FROM utilisateur_preference
    JOIN preference ON utilisateur_preference.preference_id = preference.preference_id
    WHERE utilisateur_preference.utilisateur_id = :id
    AND preference.statut_preference = 'USER'
");
$pref_perso_stmt->execute(['id' => $covoiturage['utilisateur_id']]);
$preferences_personnalisees = $pref_perso_stmt->fetchAll(PDO::FETCH_COLUMN);

// Stockage en session pour utilisation dans la vue
$_SESSION['detail_covoiturage'] = [
    'trajet' => $covoiturage,
    'date' => $date,
    'heure' => $heure,
    'prix' => $prix,
    'place' => $place,
    'vehicule' => $vehicule,
    'avis' => $avis,
    'moyenne_note' => $moyenne_note,
    'preferences' => $preferences,
    'preferences_perso' => $preferences_personnalisees,
];

// Ajout au tableau final
$preferences['autres'] = $preferences_personnalisees;

//header("Location: ../../frontend/detail_trajet.php");
