<?php
session_start();
require_once('../config/db.php'); // connexion BDD

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Adresse email invalide.";
        header("Location: ../../frontend/reset_password.php");
        exit;
    }

    // Vérifier si l'utilisateur existe
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION['error'] = "Aucun compte associé à cet email.";
        header("Location: ../../frontend/reset_password.php");
        exit;
    }

    // Générer un token sécurisé
    $token = bin2hex(random_bytes(32));
    $expire = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Stocker le token et l'expiration
    $stmt = $pdo->prepare("UPDATE utilisateurs SET reset_token = :token, reset_expire = :expire WHERE email = :email");
    $stmt->execute([
        'token' => $token,
        'expire' => $expire,
        'email' => $email
    ]);

    // Lien de réinitialisation
    $resetLink = "http://tondomaine.com/frontend/reset_form.php?token=$token";

    // Simuler un envoi de mail (à remplacer par mail() ou PHPMailer)
    // mail($email, "Réinitialisation de mot de passe", "Cliquez ici : $resetLink");

    $_SESSION['success'] = "Un email de réinitialisation a été envoyé (simulation) : <a href='$resetLink'>Réinitialiser</a>";
    header("Location: ../../frontend/reset_password.php");
    exit;
}
