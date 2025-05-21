<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Import de la configuration de la BDD
require "../backend/config/db.php";

$email = $_POST["email"];
$password = $_POST["password"];

$stmt = $pdo->prepare("
    SELECT *
    FROM utilisateur
    JOIN privilege ON utilisateur.privilege_id = privilege.privilege_id
    JOIN role ON utilisateur.role_id = role.role_id
    WHERE email = ?
    ");

$stmt->execute([$email]);
$user = $stmt->fetch();


if ($user && $password) {

    if (password_verify($password, $user["password"])) {
        
        // Renseignement de la super-variable SESSION
        $_SESSION["utilisateur_id"] = $user["utilisateur_id"];
        $_SESSION["pseudo"] = $user["pseudo"];
        $_SESSION["credit"] = $user["credit"];
        $_SESSION["note"] = $user["note"];
        $_SESSION["role"] = strval($user["libelle"]);
        $_SESSION["privilege"] = strval($user["privilege"]);
        $_SESSION["date_connexion"] = date("Y-m-d H:i:s");
        header('Location: ../frontend/index.php?logout=0');
        exit();

    } else {
        echo "<div class='text-danger'>⚠️ Identifiants incorrects. Veuillez réessayer de vous connecter.</div>";
    }

} else {
    echo "<div class='text-danger'>⚠️ Veuillez fournir un nom d'utilisateur et un mot de passe.</div>";
}

?>
