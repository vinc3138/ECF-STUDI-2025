<?php
// Démarrage de session si besoin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connexion à la base (adapte les infos de connexion)
require_once('../backend/config/database.php');
$pdo = Database::getConnection(); // Méthode de connexion PDO

try {
    // Prépare et exécute la requête pour récupérer les avis en attente avec note <= 2
$sql = "
SELECT 
    a.avis_id,
    a.commentaire,
    a.note,
    a.date_creation_commentaire,
    sc.statut_commentaire,
    c.covoiturage_id,
    
    -- Infos conducteur
    uc.pseudo AS conducteur_pseudo,
    uc.email AS conducteur_email,
    
    -- Infos passager
    up.pseudo AS passager_pseudo,
    up.email AS passager_email,
    c.lieu_depart,
    c.date_depart,
    c.lieu_arrivee,
    c.date_arrivee

    FROM avis a
    JOIN statut_commentaire sc ON a.statut = sc.id_statut_commentaire
    JOIN covoiturage c ON a.covoiturage_id = c.covoiturage_id

    -- Jointure pour passager
    JOIN utilisateur up ON a.utilisateur_id = up.utilisateur_id

    -- Jointure pour conducteur
    JOIN utilisateur uc ON c.utilisateur_id = uc.utilisateur_id

    WHERE sc.statut_commentaire = 'A VALIDER' OR sc.statut_commentaire = 'STANDBY'
    AND a.note <= 2
    ORDER BY a.date_creation_commentaire DESC;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $problemes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // En cas d'erreur, tu peux gérer ici (log, affichage, etc.)
    error_log("Erreur lors de la récupération des avis : " . $e->getMessage());
    $problemes = []; // Pas d'avis en cas d'erreur
}
