<?php
session_start();
require_once '../config/Database.php'; // adapte le chemin selon ton projet

if (!isset($_SESSION['utilisateur_id'])) {
    $_SESSION['error'] = "Vous devez être connecté.";
    header('Location: ../../frontend/preference_user.php');
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$pdo = Database::getConnection();

try {
    // Suppression des préférences fixes actuelles pour l'utilisateur
    $stmtDel = $pdo->prepare("
        DELETE utilisateur_preference FROM utilisateur_preference
        JOIN preference ON utilisateur_preference.preference_id = preference.preference_id
        WHERE utilisateur_preference.utilisateur_id = :uid
        AND preference.statut_preference = 'FIXE'
    ");
    $stmtDel->execute(['uid' => $utilisateur_id]);

    // Insertion des préférences cochées (id dans $_POST['preferences'])
    if (!empty($_POST['preferences']) && is_array($_POST['preferences'])) {
        $stmtIns = $pdo->prepare("
            INSERT INTO utilisateur_preference (utilisateur_id, preference_id) VALUES (:uid, :pid)
        ");
        foreach ($_POST['preferences'] as $pref_id) {
            $stmtIns->execute([
                'uid' => $utilisateur_id,
                'pid' => (int)$pref_id
            ]);
        }
    }

    $_SESSION['success'] = "Préférences mises à jour avec succès.";
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur lors de la mise à jour des préférences.";
}

// Redirection vers la page du formulaire
header('Location: ../../frontend/preference_user.php');
exit;