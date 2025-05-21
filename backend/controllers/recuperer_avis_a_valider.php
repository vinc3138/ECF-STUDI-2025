<?php
// Démarrage de session si besoin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connexion à la base (adapte les infos de connexion)
require_once('../backend/config/database.php');
$pdo = Database::getConnection(); // Méthode de connexion PDO

try {
    // Prépare et exécute la requête pour récupérer les avis en attente de valiation
$sql = "SELECT a.avis_id, a.commentaire, a.note, sc.statut_commentaire, a.date_creation_commentaire
        FROM avis a
        JOIN statut_commentaire sc ON a.statut = sc.id_statut_commentaire
        WHERE sc.statut_commentaire = 'A VALIDER'OR sc.statut_commentaire = 'STANDBY';
        ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $avis = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // En cas d'erreur, tu peux gérer ici (log, affichage, etc.)
    error_log("Erreur lors de la récupération des avis : " . $e->getMessage());
    $avis = []; // Pas d'avis en cas d'erreur
}
