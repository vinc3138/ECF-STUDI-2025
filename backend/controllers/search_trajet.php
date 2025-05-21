<?php
require __DIR__ . "/../config/db.php";


// Initialiser les conditions et paramètres
$conditions = [];
$params = [];

// Condition de base : covoiturage non annulé et places disponibles
$conditions[] = "statut_covoiturage != 4 AND nb_place > nb_place_reservee";

// Récupération des paramètres de base (depart, destination, date)
if (isset($_GET["depart"])) {
    $depart = strtoupper(htmlspecialchars($_GET['depart']));
    $conditions[] = "lieu_depart = ?";
    $params[] = $depart;
}

if (isset($_GET["destination"])) {
    $destination = strtoupper(htmlspecialchars($_GET["destination"]));
    $conditions[] = "lieu_arrivee = ?";
    $params[] = $destination;
}

$date = null;
if (isset($_GET["date"]) && $_GET["date"] !== "") {
    $date = htmlspecialchars($_GET["date"]);
    $conditions[] = "date_depart = ?";
    $params[] = $date;
}

// Récupération des filtres (écologique, prix max, durée max, note min)
// condition de filtre sur le type de trajet
if (isset($_GET['ecologique']) && $_GET['ecologique'] !== '') {
    $ecologique = $_GET['ecologique'];

    if($ecologique==='1') {
        $conditions[] = "voiture.energie = ?";
        $params[] = 'ELECTRIQUE';
    } else {
        $conditions[] = "voiture.energie != ?";
        $params[] = 'ELECTRIQUE';
    }  
}

// condition de filtre sur le prix
if (isset($_GET['prix_max']) && $_GET['prix_max'] !== '') {
    $prix_max = $_GET['prix_max'];
    $conditions[] = "covoiturage.prix_personne <= ?";
    $params[] = $prix_max;
}

// condition de filtre sur la durée
if (isset($_GET['duree_max']) && $_GET['duree_max'] !== '' ) {
    $duree_max = floatval($_GET['duree_max']); // au cas où l'utilisateur entre un nombre décimal
    $conditions[] = "TIME_TO_SEC(covoiturage.duree_trajet) <= ?";
    $params[] = $duree_max * 3600; // conversion heures -> secondes
}

// Condition de filtre sur les notes
if (isset($_GET['note_min']) && $_GET['note_min'] !== '') {
    // Remplacer virgule par point AVANT floatval
    $note_min = floatval(str_replace(',', '.', $_GET['note_min']));
    $conditions[] = "utilisateur.note >= ?";
    $params[] = $note_min;
}

// Construction de la requête principale avec les conditions dynamiques
$sql = "SELECT *
        FROM covoiturage 
        JOIN utilisateur ON covoiturage.utilisateur_id = utilisateur.utilisateur_id 
        JOIN voiture ON voiture.voiture_id = covoiturage.voiture_id";

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$resultats = $stmt->fetchAll();

$trajet_avec_place = 0;
$affichageProche = false;

// Si aucun résultat mais une date est renseignée, tenter la recherche du trajet le plus proche
if (!$resultats && $date !== null) {
    $stmtProche = $pdo->prepare("
        SELECT *, ABS(DATEDIFF(date_arrivee, ?)) AS diff
        FROM covoiturage 
        JOIN utilisateur ON covoiturage.utilisateur_id = utilisateur.utilisateur_id 
        JOIN voiture ON voiture.voiture_id = covoiturage.voiture_id 
        WHERE lieu_depart = ? 
        AND lieu_arrivee = ? 
        AND statut_covoiturage != 4 
        AND nb_place > nb_place_reservee
        ORDER BY diff ASC
        LIMIT 1
    ");

    $stmtProche->execute([$date, $depart, $destination]);
    $resultats = $stmtProche->fetchAll();

    if ($resultats) {
        $affichageProche = true;
    } else {
        echo "<p>Aucun trajet trouvé pour la date demandée, ni à une date proche.</p>";
        return;  // Juste retourner au lieu d'exit pour éviter de casser le reste
    }
}

if ($resultats) {
    // Compter les trajets avec des places disponibles
    foreach ($resultats as $trajet) {
        if ($trajet['nb_place'] > 0) {
            $trajet_avec_place++;
        }
    }

    if ($trajet_avec_place > 0) {
        if ($affichageProche) {
            echo "<p>Aucun trajet trouvé à la date exacte. Voici <strong>$trajet_avec_place trajet(s) disponible(s)</strong> à une date proche :</p>";
        } else {
            echo "<p><strong>$trajet_avec_place trajet(s) disponible(s) trouvé(s) :</strong></p>";
        }
    } else {
        echo "<p>Aucun trajet avec des places disponibles n’a été trouvé.</p>";
    }

    // Affichage des trajets disponibles
    foreach ($resultats as $trajet) {
        if ($trajet['nb_place'] > 0) {

            list($heures, $minutes, $secondes) = explode(':', $trajet['duree_trajet']);
            $duree_formatee = intval($heures) . 'h' . str_pad($minutes, 2, '0', STR_PAD_LEFT);

            $voyage_ecologique = ($trajet['energie'] === "ELECTRIQUE") ? "OUI" : "NON";
            
            //  Mise en couleur de l'affichage OUI/NON du trajet ecologique
            $couleurEcologique = ($voyage_ecologique === "OUI") ? "text-success" : "text-danger";

            $heure_formatee = date('H\h00', strtotime($trajet['heure_depart']));
            $note1 = $trajet['note'];
            $note2 = floatval(str_replace('.', ',', $trajet['note']));
            $note_min_formatee = number_format(floatval(str_replace('.', ',', $trajet['note'])), 1, ',', '');
            
            // Conversion photo
            $photoData = $trajet['photo'];
            $photoBase64 = base64_encode($photoData);
            $photoMimeType = 'image/jpeg'; // ou image/png selon ton cas
            $photoSrc = "data:$photoMimeType;base64,$photoBase64";


if (!empty($photoData)) {
    // Conversion photo
    $photoBase64 = base64_encode($photoData);
    $photoMimeType = 'image/jpeg'; // ou 'image/png'
    $photoSrc = "data:$photoMimeType;base64,$photoBase64";
    $imageHtml = "
            <div class='rounded-circle shadow-sm overflow-hidden' style='width: 220px; height: 220px;'>
                <img src='" . $photoSrc . "' alt='Photo du chauffeur' class='img-fluid h-100 w-100' style='object-fit: cover;'>
            </div>";

} else {
    // Pas d'image, on met un div vide qui prend zéro place (ou tu peux adapter)
    $imageHtml = "";
}


echo "

<div class='card mb-4 shadow-lg border-0 rounded' style='background-color: #e6f4ea'>
    <div class='row g-0'>
        <!-- Colonne image -->
        <div class='col-md-3 col-12 d-flex align-items-center justify-content-center p-3'>
                $imageHtml
        </div>

        <!-- Colonne infos -->
        <div class='col-md-9 col-12'>
            <div class='card-body py-4 px-4'>

                <!-- Titre -->
                <h5 class='card-title fw-bold text-success mb-3'>
                    🚗 De <strong>{$trajet['lieu_depart']}</strong> à <strong>{$trajet['lieu_arrivee']}</strong>
                </h5>

                <!-- Infos en grille -->
                <div class='row gy-2'>
                    <div class='col-sm-6'>📅 <strong>Date :</strong> {$trajet['date_depart']}</div>
                    <div class='col-sm-6'>🕐 <strong>Départ :</strong> {$heure_formatee}</div>
                    <div class='col-sm-6'>💺 <strong>Places :</strong> {$trajet['nb_place']}</div>
                    <div class='col-sm-6'>💶 <strong>Prix :</strong> {$trajet['prix_personne']} crédits</div>
                    <div class='col-sm-6'>⏳ <strong>Durée :</strong> {$duree_formatee}</div>
                    <div class='col-sm-6 {$couleurEcologique}'>🌿 <strong>Écologique :</strong> {$voyage_ecologique}</div>
                    <div class='col-sm-6'>⭐ <strong>Note :</strong> {$trajet['note']} / 5</div>
                    <div class='col-sm-6'>👤 <strong>Chauffeur :</strong> {$trajet['pseudo']}</div>
                </div>

                <!-- Bouton -->
                <div class='mt-4'>
                    <a href='../frontend/detail_trajet.php?id={$trajet['covoiturage_id']}' class='btn btn-success'>
                        🔎 Voir le détail du trajet
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>";



        }
    }
} else {
    echo "<p>Aucun trajet trouvé pour la date demandée, ni à une date proche.</p>";
}
?>
