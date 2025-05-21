<?php
session_start();
require_once('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';

    if (strlen($password) < 8) {
        $_SESSION['error'] = "Le mot de passe doit contenir au moins 8 caractères.";
        header("Location: ../../frontend/reset_form.php?token=" . urlencode($token));
        exit;
    }

    // Vérifier token
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE reset_token = :token AND reset_expire > NOW()");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Lien expiré ou invalide.";
        exit;
    }

    // Hachage et mise à jour
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = :pass, reset_token = NULL, reset_expire = NULL WHERE id = :id");
    $stmt->execute([
        'pass' => $hashedPassword,
        'id' => $user['id']
    ]);

    $_SESSION['success'] = "Mot de passe mis à jour. Vous pouvez vous connecter.";
    header("Location: ../../frontend/login.php");
    exit;
}
