<?php
session_start();
require_once '../config/db.php';

// Vérifie que la requête est bien POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Méthode non autorisée.");
}

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    die("Accès non autorisé.");
}

$user_id = $_SESSION['utilisateur_id'];

try {
    // Recharge de 100 crédits
    $stmt = $pdo->prepare("UPDATE utilisateur SET credit = credit + 100 WHERE utilisateur_id = ?");
    $stmt->execute([$user_id]);

    // Récupérer les infos de l'utilisateur pour affichage ou mail
    $stmt = $pdo->prepare("SELECT email, pseudo FROM utilisateur WHERE utilisateur_id = ?");
    $stmt->execute([$user_id]);
    $utilisateur = $stmt->fetch();

    if ($utilisateur) {
        // Envoi mail (optionnel)
        $to = $utilisateur['email'];
        $subject = "Crédit rechargé avec succès";
        $message = "Bonjour " . htmlspecialchars($utilisateur['pseudo']) . ",\n\n".
                   "Votre compte a été rechargé de 100 crédits.\n".
                   "Merci de votre confiance.\n\nL'équipe";
        $headers = "From: no-reply@tonsite.com\r\n";

        mail($to, $subject, $message, $headers);
    }

    $_SESSION['credit'] = $_SESSION['credit'] + 100;
    
    // Redirection
    header("Location: ../../frontend/preference_user.php?success=1&msg=credit_recharge");
    exit;

} catch (Exception $e) {
    // En cas d'erreur
    header("Location: ../../frontend/preference_user.php?success=0&error=" . urlencode($e->getMessage()));
    exit;
}
