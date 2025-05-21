<?php

if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_POST['informations_personnelles'])) {
    
    // Connexion à la BDD
    require_once '../backend/config/db.php';

    // Sécurité : nettoyage des données
    $prenom = htmlspecialchars($_POST['prenom']);
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $birthday = $_POST['date_naissance']; // format DD/MM/YYYY

echo("ok");

    // Convertir la date SQL en format YYYY-MM-DD attendu
    $dateNaissance = '';
    if (!empty($birthday)) {
        $dateNaissance = date('Y/m/d', strtotime($birthday));
    }

    $utilisateur_id = $_SESSION['utilisateur_id'];

    // Requête de mise à jour
    $stmt = $pdo->prepare("
        UPDATE utilisateur
        SET prenom = :prenom,
            nom = :nom,
            email = :email,
            adresse = :adresse,
            telephone = :telephone,
            date_naissance = :birthday
        WHERE utilisateur_id = :id
    ");

    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':adresse', $adresse);
    $stmt->bindParam(':telephone', $telephone);
    $stmt->bindParam(':birthday', $dateNaissance);
    $stmt->bindParam(':id', $utilisateur_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Informations mises à jour !</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de la mise à jour.</div>";
    }
}
?>
