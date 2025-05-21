<?php
// Connexion à la base de données
require_once('../backend/config/db.php'); // ajuste le chemin si nécessaire

// Vérifie que l'utilisateur est connecté
if (isset($_SESSION['pseudo'])) {
    $pseudo = $_SESSION['pseudo'];

    // Prépare la requête pour récupérer les infos
    $stmt = $pdo->prepare("SELECT prenom, nom, email, adresse, telephone, date_naissance FROM utilisateur WHERE pseudo = :pseudo");
    $stmt->execute(['pseudo' => $pseudo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si des données sont trouvées, on les rend disponibles
    if ($user) {
        $prenom = ucfirst($user['prenom']);
        $nom = strtoupper($user['nom']);
        $email = $user['email'];
        $adresse = $user['adresse'];
        $telephone = $user['telephone'];
        $date_naissance = $user['date_naissance'];
    }

}
?>
