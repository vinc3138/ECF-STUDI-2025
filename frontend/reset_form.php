<?php
session_start();
require_once('../config/db.php');

$token = $_GET['token'] ?? '';

if (!$token) {
    echo "Lien invalide.";
    exit;
}

// Vérifie le token
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE reset_token = :token AND reset_expire > NOW()");
$stmt->execute(['token' => $token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Lien expiré ou invalide.";
    exit;
}
?>

<!-- Formulaire pour nouveau mot de passe -->
<form method="POST" action="../backend/controllers/reset_password.php">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    
    <label for="password">Nouveau mot de passe :</label>
    <input type="password" name="password" required>

    <button type="submit">Réinitialiser</button>
</form>
