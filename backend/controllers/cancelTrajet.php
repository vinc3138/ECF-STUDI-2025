<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Méthode non autorisée.");
}

if (!isset($_SESSION['utilisateur_id'], $_SESSION['role'])) {
    die("Non autorisé.");
}

$user_id = $_SESSION['utilisateur_id'];
$role = $_SESSION['role'];
$covoiturage_id = $_POST['covoiturage_id'] ?? null;

if (!$covoiturage_id || !is_numeric($covoiturage_id)) {
    die("ID covoiturage invalide.");
}

try {
    $pdo->beginTransaction();

    if ($role === 'CHAUFFEUR' || $role === 'CHAUFFEUR PASSAGER') {
        // Vérifier si c’est bien le chauffeur du trajet
        $stmt = $pdo->prepare("SELECT nb_place, nb_place_reservee FROM covoiturage WHERE covoiturage_id = ? AND utilisateur_id = ?");
        $stmt->execute([$covoiturage_id, $user_id]);
        $trajet = $stmt->fetch();

        if ($trajet) {
            // 1. Mettre à jour le statut du trajet en annulé
            $stmt = $pdo->prepare("
                UPDATE covoiturage
                SET statut_covoiturage = 'annule', nb_place = nb_place + nb_place_reservee, nb_place_reservee = 0
                WHERE covoiturage_id = ?
            ");
            $stmt->execute([$covoiturage_id]);

            // 2. Rembourser tous les passagers et envoyer mail
            $stmt = $pdo->prepare("
                SELECT r.utilisateur_id, u.email, u.pseudo, r.nb_places_reservees
                FROM reservation r
                JOIN utilisateur u ON r.utilisateur_id = u.utilisateur_id
                WHERE r.covoiturage_id = ?
            ");
            $stmt->execute([$covoiturage_id]);
            $passagers = $stmt->fetchAll();

            foreach ($passagers as $passager) {
                // Exemple remboursement : crédit du passager augmenté
                $stmt = $pdo->prepare("UPDATE utilisateur SET credit = credit + ? WHERE utilisateur_id = ?");
                $stmt->execute([$passager['nb_places_reservees'], $passager['utilisateur_id']]);

                // Envoi mail d’annulation
                $to = $passager['email'];
                $subject = "Annulation de votre covoiturage";
                $message = "Bonjour " . htmlspecialchars($passager['pseudo']) . ",\n\n".
                           "Le covoiturage que vous aviez réservé a été annulé par le chauffeur. ".
                           "Votre réservation a été remboursée.\n\nCordialement,\nL'équipe";
                $headers = "From: no-reply@tonsite.com\r\n";

                mail($to, $subject, $message, $headers);
            }

            // 3. (Optionnel) Déduire crédit du chauffeur, si besoin
            // $stmt = $pdo->prepare("UPDATE utilisateur SET credit = credit - ? WHERE utilisateur_id = ?");
            // $stmt->execute([10, $user_id]);

        } else {
            throw new Exception("Vous n'êtes pas le chauffeur de ce trajet.");
        }
    } else {
        // C’est un passager qui annule sa réservation

        // Vérifier que la réservation existe
        $stmt = $pdo->prepare("SELECT nb_places_reservees FROM reservation WHERE covoiturage_id = ? AND utilisateur_id = ?");
        $stmt->execute([$covoiturage_id, $user_id]);
        $reservation = $stmt->fetch();

        if (!$reservation) {
            throw new Exception("Réservation non trouvée.");
        }

        $places_reservees = $reservation['nb_places_reservees'];

        // 1. Supprimer la réservation
        $stmt = $pdo->prepare("DELETE FROM reservation WHERE covoiturage_id = ? AND utilisateur_id = ?");
        $stmt->execute([$covoiturage_id, $user_id]);

        // 2. Ajouter les places libérées dans covoiturage
        $stmt = $pdo->prepare("UPDATE covoiturage SET nb_place = nb_place + ?, nb_place_reservee = nb_place_reservee - ? WHERE covoiturage_id = ?");
        $stmt->execute([$places_reservees, $places_reservees, $covoiturage_id]);

        // 3. Rembourser le passager (exemple ici 1 crédit par place)
        $stmt = $pdo->prepare("UPDATE utilisateur SET credit = credit + ? WHERE utilisateur_id = ?");
        $stmt->execute([$places_reservees, $user_id]);

        // (Optionnel) Envoi mail de confirmation au passager
        $stmt = $pdo->prepare("SELECT email, pseudo FROM utilisateur WHERE utilisateur_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        $to = $user['email'];
        $subject = "Annulation de votre réservation";
        $message = "Bonjour " . htmlspecialchars($user['pseudo']) . ",\n\n".
                   "Votre réservation pour le covoiturage a été annulée et votre crédit remboursé.\n\nCordialement,\nL'équipe";
        $headers = "From: no-reply@tonsite.com\r\n";

        mail($to, $subject, $message, $headers);
    }

    $pdo->commit();

    header("Location: ../../frontend/historique_trajet.php?success=1&msg=annulation_reussie");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    header("Location: ../../frontend/historique_trajet.php?success=0&error=" . urlencode($e->getMessage()));
    exit;
}
