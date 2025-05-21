<?php
session_start();  // Démarre la session pour pouvoir utiliser $_SESSION

// Inclure la configuration de la base de données
require_once '../config/db.php';

// Initialiser les variables d'erreur
$emailErr = $passwordErr = "";
$email = $password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Email invalide.";
    }

    // Validation du mot de passe
    if (strlen($password) < 8) {
        $passwordErr = "Le mot de passe doit contenir au moins 8 caractères.";
    }
    
    // Si aucune erreur, procéder à l'enregistrement
    if (empty($emailErr) && empty($passwordErr)) {
        // Hachage du mot de passe pour la sécurité
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Préparer la requête d'insertion dans la base de données
            $stmt = $pdo->prepare("INSERT INTO users (email, password, privilege) VALUES (?, ?, ?)");
            $stmt->execute([$email, $hashedPassword, 'EMPLOYE']);  // Le rôle par défaut est 'EMPLOYE'

            // Si l'insertion réussie, stocker un message de succès dans la session
            $_SESSION['success_message'] = "L'employé a bien été créé !";

            // Rediriger vers le dashboard_admin.php
            header('Location: /frontend/dashboard_admin.php');
            exit();
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Erreur lors de la création de l'utilisateur : " . $e->getMessage();
        }
    } else {
        // Si des erreurs, stocker ces erreurs dans la session
        $_SESSION['emailErr'] = $emailErr;
        $_SESSION['passwordErr'] = $passwordErr;
    }
}
?>
