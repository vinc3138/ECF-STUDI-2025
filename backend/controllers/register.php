<?php
session_start();
require_once('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = trim($_POST['pseudo']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 1. Champs vides ?
    if (empty($pseudo) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
        header("Location: ../../frontend/inscription.php");
        exit;
    }

    // 2. Email valide ?
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "L'adresse email n'est pas valide.";
        header("Location: ../../frontend/inscription.php");
        exit;
    }

    // 3. Mot de passe sécurisé ?
    $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';
    if (!preg_match($passwordRegex, $password)) {
        $_SESSION['error'] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.";
        header("Location: ../../frontend/inscription.php");
        exit;
    }

    // 4. Pseudo ou email déjà utilisé ?
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE pseudo = :pseudo OR email = :email");
    $stmt->execute(['pseudo' => $pseudo, 'email' => $email]);
    $exists = $stmt->fetchColumn();

    if ($exists > 0) {
        $_SESSION['error'] = "Le pseudo ou l'email est déjà utilisé.";
        header("Location: ../../frontend/inscription.php");
        exit;
    }

    // 5. Hachage du mot de passe et insertion
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO utilisateur (pseudo, email, password) VALUES (:pseudo, :email, :mot_de_passe)");
    $stmt->execute([
        'pseudo' => $pseudo,
        'email' => $email,
        'mot_de_passe' => $hashedPassword
    ]);

    $_SESSION['success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    header("Location: ../../frontend/inscription.php");
    exit;
}