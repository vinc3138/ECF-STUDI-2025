<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION["utilisateur_id"])) {
    $userId = $_SESSION["utilisateur_id"];  // Récupérer l'ID de l'utilisateur connecté
} else {
    // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
    header('Location: ../../frontend/compte_user.php');
    exit();
}

// Import de la configuration de la BDD
require_once "../backend/config/db.php";

if (isset($_POST['submit'])) {

    // Vérification du fichier téléchargé
    $photo = $_FILES["photo"];

    // Vérification si le fichier est bien téléchargé sans erreur
    if ($photo['error'] === 0) {

        // Vérification du type MIME de l'image
        $typeMime = mime_content_type($photo['tmp_name']);
        $typesAutorises = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];

        if (in_array($typeMime, $typesAutorises)) {

            // Vérification de la taille du fichier (max 2 Mo)
            $tailleMax = 2 * 1024 * 1024; // 2 Mo
            if ($photo['size'] <= $tailleMax) {

                // Lire l'image en tant que binaire
                $imageData = file_get_contents($photo['tmp_name']);

                // Connexion à la base de données (ici avec PDO)
 
                    // Requête SQL pour insérer l'image en BLOB
                    $stmt = $pdo->prepare("UPDATE utilisateur SET photo = :photo WHERE utilisateur_id = :id");
                    $stmt->bindParam(':photo', $imageData, PDO::PARAM_LOB);
                    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

                    // Si l'insertion réussit
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Image téléchargée et enregistrée avec succès.</div>";
                        //include('../backend/controllers/affiche_photo.php');
                    } else {
                        echo "Erreur lors de l'enregistrement dans la base de données.";
                    }

            } else {
                echo "Le fichier est trop grand. La taille maximale est de 2 Mo.";
            }
        } else {
            echo "Le fichier n'est pas une image valide (seuls JPEG, PNG et GIF sont autorisés).";
        }
    } else {
        echo "Erreur lors du téléchargement du fichier. Veuillez réessayer.";
    }
}
?>