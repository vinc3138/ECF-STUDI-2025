<?php
session_start();

require_once '../config/Database.php'; // Connexion PDO stockée dans $pdo

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: ../../frontend/login.php');
    exit;
}
$_SESSION['message']="";
$pdo = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utilisateurId = intval($_SESSION['utilisateur_id']);
    $covoiturageId = intval($_POST['covoiturage_id'] ?? 0);
    $note = intval($_POST['note'] ?? 0);
    $commentaire = trim($_POST['commentaire'] ?? '');

    // Validation basique
    if ($covoiturageId <= 0 || $note < 1 || $note > 5) {
        $_SESSION['error'] = "Données invalides.";
        header('Location: ../../frontend/historique_trajet.php');
        exit;
    }

    // Empêcher plusieurs évaluations du même utilisateur sur le même trajet
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM avis WHERE utilisateur_id = :user AND covoiturage_id = :trajet");
    $stmt->execute(['user' => $utilisateurId, 'trajet' => $covoiturageId]);
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['message'] = "Vous avez déjà évalué ce trajet.";
        header('Location: ../../frontend/historique_trajet.php');
        exit;
    }

    // Insérer l'évaluation
    $stmt = $pdo->prepare("INSERT INTO avis (utilisateur_id, covoiturage_id, note, commentaire, date_creation_commentaire, statut) VALUES (:user, :trajet, :note, :commentaire, NOW(), 1)");
    $stmt->execute([
        'user' => $utilisateurId,
        'trajet' => $covoiturageId,
        'note' => $note,
        'commentaire' => $commentaire
    ]);

    $_SESSION['message'] = "Merci pour votre évaluation !";
    header('Location: ../../frontend/historique_trajet.php');
    exit;
}

// En cas d'accès direct sans POST
header('Location: ../../frontend/historique_trajet.php');
exit;
