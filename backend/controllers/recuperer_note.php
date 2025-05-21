<?php
require __DIR__ . '/../config/db.php'; // Connexion à la base

//$_SESSION['utilisateur_id']=8;
//echo "SESSION : ";
//var_dump($_SESSION);
//exit;

// Vérifie si un ID utilisateur est passé
if (!isset($_SESSION['utilisateur_id']) || !is_numeric($_SESSION['utilisateur_id'])) {
    die("Identifiant utilisateur invalide.");
}

$utilisateur_id = intval($_SESSION['utilisateur_id']);

// Récupération de la note moyenne
$stmt = $pdo->prepare("
    SELECT note 
    FROM utilisateur 
    WHERE utilisateur_id = ?
");
$stmt->execute([$utilisateur_id]);
$noteMoyenne = $stmt->fetchColumn(); // retourne un seul champ : la note moyenne

// Récupération des commentaires
$stmt2 = $pdo->prepare("
    SELECT a.note, a.commentaire, a.date_creation_commentaire, u.pseudo
    FROM avis a
    JOIN utilisateur u ON a.utilisateur_id = u.utilisateur_id  -- l'auteur du commentaire (passager)
    JOIN covoiturage c ON a.covoiturage_id = c.covoiturage_id
    WHERE c.utilisateur_id = ?  -- le conducteur qu'on évalue
    ORDER BY a.date_creation_commentaire DESC
");
$stmt2->execute([$utilisateur_id]);
$evaluations = $stmt2->fetchAll(PDO::FETCH_ASSOC);
