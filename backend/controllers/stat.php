<?php

// Vérifie si l'utilisateur est connecté et admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
    http_response_code(403);
    echo json_encode(['error' => 'Accès non autorisé']);
    exit;
}

// Connexion PDO attendue dans $pdo
header('Content-Type: application/json');

// --- Covoiturages par jour ---
$sql1 = "SELECT DATE(date_depart) as jour, COUNT(*) as total
         FROM covoiturages
         GROUP BY DATE(date_depart)
         ORDER BY jour ASC";
$stmt1 = $pdo->query($sql1);
$covoiturages = [];
while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
    $covoiturages[$row['jour']] = (int)$row['total'];
}

// --- Crédits par jour ---
$sql2 = "SELECT DATE(date_paiement) as jour, SUM(montant) as total
         FROM paiements
         GROUP BY DATE(date_paiement)
         ORDER BY jour ASC";
$stmt2 = $pdo->query($sql2);
$credits = [];
$totalCredits = 0;
while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $credits[$row['jour']] = (float)$row['total'];
    $totalCredits += (float)$row['total'];
}

// --- Réponse JSON ---
echo json_encode([
    'covoiturages_par_jour' => $covoiturages,
    'credits_par_jour' => $credits,
    'total_credits' => $totalCredits
]);
