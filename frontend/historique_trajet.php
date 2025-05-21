<?php
// Démarrage de session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connexion à la base (à adapter si besoin)
require_once('../backend/config/database.php');
$pdo = Database::getConnection(); // Appel explicite requis

// Vérifie si l'utilisateur est chauffeur
$estChauffeur = (isset($_SESSION['utilisateur_id'])) && (strpos($_SESSION['role'], "CHAUFFEUR") !== false);
$chauffeur_id = $_SESSION['utilisateur_id'] ?? null;
?>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche de trajet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Appel du menu et de l'entête -->
<?php include('../frontend/Recurrent/navbar.php'); ?>
<?php include('../frontend/Recurrent/header.php'); ?>

<main class="container mt-4">

    <div class="row">

        <!-- Colonne gauche : Création de trajet -->
        <div class="col-md-5">
            <?php if ($estChauffeur): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        Créer un trajet
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/../backend/controllers/createTrajet.php">
                            <div class="mb-3">
                                <label for="depart" class="form-label">Ville de départ</label>
                                <input type="text" class="form-control" name="depart" required>
                            </div>
                            <div class="mb-3">
                                <label for="adresse_depart" class="form-label">Lieu de départ</label>
                                <input type="text" class="form-control" name="adresse_depart" required>
                            </div>
                            <div class="mb-3">
                                <label for="destination" class="form-label">Destination</label>
                                <input type="text" class="form-control" name="destination" required>
                            </div>
                            <div class="mb-3">
                                <label for="adresse_arrivee" class="form-label">Lieu d'arrivée</label>
                                <input type="text" class="form-control" name="adresse_arrivee" required>
                            </div>                            
                            <div class="mb-3">
                                <label for="date_depart" class="form-label">Date de départ</label>
                                <input type="date" class="form-control" name="date_depart" required>
                            </div>
                            <div class="mb-3">
                                <label for="heure_depart" class="form-label">Heure de départ</label>
                                <input type="time" class="form-control" name="heure_depart" required>
                            </div>
                            <div class="mb-3">
                                <label for="date_arrivee" class="form-label">Date de fin</label>
                                <input type="date" class="form-control" name="date_arrivee" required>
                            </div>

                            <div class="mb-3">
                                <label for="heure_arrivee" class="form-label">Heure d'arrivée prévue</label>
                                <input type="time" class="form-control" name="heure_arrivee" required>
                            </div>
                            <div class="mb-3">
                                <label for="places" class="form-label">Nombre de places</label>
                                <input type="number" class="form-control" name="places" min="1" max="10" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rappel : la plateforme prélève 2 crédits par trajet</label>
                                <label for="prix" class="form-label">Prix par personne</label>
                                <input type="number" class="form-control" name="prix" min="0" max="500" required>
                            </div>
                            <div class="mb-3">
                                <label for="vehicule_id" class="form-label">Véhicule utilisé</label>
                                <select class="form-select" name="vehicule_id" required>
                                    <?php
                                    $stmt = $pdo->prepare("
                                        SELECT v.voiture_id, m.marque, v.modele 
                                        FROM voiture v 
                                        JOIN marque m ON v.marque_id = m.marque_id 
                                        WHERE v.utilisateur_id = ?
                                    ");
                                    $stmt->execute([$chauffeur_id]);
                                    $vehicules = $stmt->fetchAll();

                                    foreach ($vehicules as $v) {
                                        echo '<option value="' . htmlspecialchars($v['voiture_id']) . '">' . htmlspecialchars($v['marque'] . ' ' . $v['modele']) . '</option>';
                                    }
                                    ?>
                                    <option value="new">Ajouter un nouveau véhicule</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Créer le trajet</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    Connectez-vous en tant que chauffeur pour créer un trajet.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success']) && $_GET['success'] === "1"): ?>
                
                <div class="alert alert-success" role="alert">
                <label class="form-label text-success">
                    Le trajet a bien été créé. Vous pouvez désormais le visualiser dans votre historique de trajets.
                </label>
                </div>

            <?php endif; ?>
        </div>


<!-- Colonne milieu : Liste des covoiturages -->
<div class="col-md-7">
    <h3 class="mb-8">Historique de mes covoiturages</h3>

    <?php if (isset($_GET['maj_trajet']) && $_GET['maj_trajet'] === "2"): ?>
        <div class="col-md-8">             
            <div class="alert alert-success" role="alert">
                <label class="form-label text-success">
                    ✅ Le trajet a bien renseigné comme débuté. Bonne route.
                </label>
            </div>
        </div>
    <?php elseif (isset($_GET['maj_trajet']) && $_GET['maj_trajet'] === "3"): ?>
        <div class="col-md-8">             
            <div class="alert alert-success" role="alert">
                <label class="form-label text-success">
                    ✅ Le trajet a bien renseigné comme terminé. Vos covoitureurs peuvent payer et noter le trajet.
                </label>
            </div>
        </div>
    <?php elseif (isset($_GET['maj_trajet']) && $_GET['maj_trajet'] === "4"): ?>
        <div class="col-md-8">             
            <div class="alert alert-success" role="alert">
                <label class="form-label text-success">
                    ✅ Le trajet a bien été annulé. Vos covoitureurs ont été remboursés.
                </label>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['message']) && $_SESSION['message'] !== ""): ?>
        <div class="col-md-8">             
            <div class="alert alert-success" role="alert">
                <?php echo($_SESSION['message']); ?>
                <!--- <label class="form-label text-success">
                    //   ✅ Le trajet a bien été payé.
                    //</label> --->
            </div>
        </div> 
    <?php endif; ?>

    <?php 
    $userId = $_SESSION['utilisateur_id'];
    $role = $_SESSION['role'];
    include('../backend/controllers/historiqueTrajet.php'); 
    ?>


    <div class="col-md-8">
        <?php if (!empty($_SESSION['historique_covoiturages'])): ?>
            <?php foreach ($_SESSION['historique_covoiturages'] as $trajet): ?>
                <?php

                    $isChauffeur = ($trajet['utilisateur_id'] == $userId);
                    switch ($trajet['statut_covoiturage']) {
                        case 1: $statut = "A venir"; break;
                        case 2: $statut = "En cours"; break;
                        case 3: $statut = "Terminé"; break;
                        case 4: $statut = "Annulé"; break;
                        default: $statut = "Inconnu";
                    }
                ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">
                            🚗 <?= htmlspecialchars($trajet['lieu_depart']) ?> → <?= htmlspecialchars($trajet['lieu_arrivee']) ?>
                        </h5>
                        <p class="card-text">
                            Date : <?= date('d/m/Y H:i', strtotime($trajet['date_depart'] . ' ' . $trajet['heure_depart'])) ?><br>
                            Statut : <strong><?= ucfirst(str_replace('_', ' ', $statut)) ?></strong>
                        </p>

                        <?php if ($isChauffeur && $statut === "A venir"): ?>
                            <form method="POST" action="../backend/controllers/majTrajet.php" class="d-inline">
                                <input type="hidden" name="covoiturage_id" value="<?= $trajet['covoiturage_id'] ?>">
                                <button name="action" value="commencer" class="btn btn-success btn-sm">Commencer</button>
                            </form>
                        <?php elseif ($isChauffeur && $statut === "En cours"): ?>
                            <form method="POST" action="../backend/controllers/majTrajet.php" class="d-inline">
                                <input type="hidden" name="covoiturage_id" value="<?= $trajet['covoiturage_id'] ?>">
                                <button name="action" value="terminer" class="btn btn-success btn-sm">Arrivée à destination </button>
                            </form>
                        <?php endif; ?>

                        <?php
                            // Annulation selon rôle
                            if ($statut !== "Terminé" && $statut !== "Annulé"):
                                if ($isChauffeur && ($role === 'CHAUFFEUR' || $role === 'CHAUFFEUR PASSAGER')): 
                        ?>
                                    <form method="POST" action="../backend/controllers/majTrajet.php" class="d-inline" onsubmit="return confirm('Annuler ce trajet (chauffeur) ?');">
                                        <input type="hidden" name="covoiturage_id" value="<?= $trajet['covoiturage_id'] ?>">
                                        <button name="action" value="annuler" class="btn btn-secondary btn-sm">Annuler</button>
                                    </form>
                        <?php elseif (!$isChauffeur && $role === 'PASSAGER'): ?>
                                    <form method="POST" action="../backend/controllers/majTrajet.php" class="d-inline" onsubmit="return confirm('Annuler votre réservation ?');">
                                        <input type="hidden" name="covoiturage_id" value="<?= $trajet['covoiturage_id'] ?>">
                                        <button name="action" value="annuler_passager" class="btn btn-warning btn-sm">Annuler réservation</button>
                                    </form>
                        <?php endif; ?>
                        <?php endif; ?>

                        <!-- Paiement et notation -->
                        <?php if (!$isChauffeur && $statut === "Terminé" && $trajet['paiement_realise'] === "NON"): ?>
                            <form method="POST" action="../backend/controllers/payTrajet.php" class="d-inline">
                                <input type="hidden" name="payer" value="<?= $trajet['covoiturage_id'] ?>">
                                <button name="action" value="payer" class="btn btn-secondary btn-sm">Payer</button>
                            </form>
                        <?php endif; ?>

                        <?php if (!$isChauffeur && $statut === "Terminé" && $trajet['avis_redige'] === "NON") : ?>
                            <form method="GET" action="evaluation.php" class="d-inline">
                                <input type="hidden" name="noter" value="<?= $trajet['covoiturage_id'] ?>">
                                <button name="action" value="noter" class="btn btn-secondary btn-sm">Noter</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun trajet trouvé.</p>
        <?php endif; ?>
    </div> <!-- fermeture col-md-8 -->

</div> <!-- fermeture col-md-7 -->

<hr class="my-8">

<div class="col-md-row">

    <?php include('../backend/controllers/recuperer_note.php'); ?>

    <h2>
        Note moyenne du conducteur : 
        <?= isset($noteMoyenne) ? '<strong>' . number_format($noteMoyenne, 1, ',', '') . ' ⭐</strong>' : 'Pas encore de note' ?>
    </h2>
    <br/>
    <h3>Commentaires des passagers :</h3>
    <?php if (!empty($evaluations)): ?>
        <?php foreach ($evaluations as $eval): ?>
            <div class="evaluation border rounded p-3 mb-3 bg-light">
                <p>
                    <strong><?= htmlspecialchars($eval['pseudo']) ?></strong> a noté 
                    <strong><?= number_format($eval['note'], 1, ',', '') ?>⭐ /5</strong>
                </p>
                <p><?= nl2br(htmlspecialchars($eval['commentaire'])) ?></p>
                <p><small class="text-muted">Le <?= date('d/m/Y', strtotime($eval['date_creation_commentaire'])) ?></small></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune évaluation pour ce conducteur.</p>
    <?php endif; ?>
</div>

</main>

<!-- Pied de page -->
<?php include('../frontend/Recurrent/footer.php'); ?>

</body>
</html>
