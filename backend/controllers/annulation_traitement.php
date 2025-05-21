<?php
session_start();
require '../config/db.php'; // Connexion PDO stockée dans $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['covoiturage_id'])) {
    $trajetId = $_POST['covoiturage_id'];

    // 1. Récupérer les participants (emails)
    $stmt = $pdo->prepare("SELECT u.email FROM reservation r
                           JOIN utilisateur u ON r.utilisateur_id = u.utilisateur_id
                           WHERE r.covoiturage_id = ?");
    $stmt->execute([$trajetId]);
    $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 2. Supprimer ou annuler le trajet
    $delete = $pdo->prepare("DELETE FROM covoiturage WHERE covoiturage_id = ?");
    $delete->execute([$trajetId]);

    // 3. Envoyer un mail à chaque participant
    foreach ($emails as $email) {
        mail(
            $email,
            "Annulation de votre trajet",
            "Bonjour, le trajet que vous aviez réservé a été annulé par le chauffeur."
        );
    }

    // 4. Rediriger avec message
    $_SESSION['message'] = "Trajet annulé et participants notifiés.";
    header('Location: ../mes_trajets.php');
    exit();
} else {
    http_response_code(400);
    echo "Requête invalide.";
}
?>
