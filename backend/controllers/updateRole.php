<?php
session_start();
require_once(__DIR__ . '/../models/User.php');

if (!isset($_SESSION['utilisateur_id'])) {
    $_SESSION['error'] = "Vous devez être connecté.";
    header('Location: ../../frontend/login.php'); // ou autre page de connexion
    exit;
}

$userId = $_SESSION['utilisateur_id'];
$newRole = $_POST['role'] ?? null;

if ($newRole) {
    // Tu peux normaliser la casse ici, par ex en majuscule
    $newRole = strtoupper($newRole);

    if (User::updateRole($userId, $newRole)) {
        // Mise à jour de la session
        $_SESSION['role'] = $newRole;
        $_SESSION['success'] = "Rôle mis à jour avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour du rôle.";
    }
} else {
    $_SESSION['error'] = "Rôle non spécifié.";
}

// Redirection vers la page où tu affiches le rôle
header('Location: ../../frontend/preference_user.php');
exit;
?>
