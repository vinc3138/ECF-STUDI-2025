<?php


// Inclusion de l'entête, du menu et du pied de page
include('../frontend/Recurrent/navbar.php');
include('../frontend/Recurrent/header.php');
include('../backend/controllers/detail_covoiturage.php');    
include('../backend/models/Date.php');

if (!isset($_SESSION['detail_covoiturage'])) {
    echo "<div class='container'><div class='alert alert-danger'>Aucune donnée de covoiturage disponible.</div></div>";
    exit;
}

$detail = $_SESSION['detail_covoiturage'];
$trajet = $detail['trajet'];
?>

<body>

<div class="container mt-4">
  <div class="row">
    <div class="col-12">
      <button onclick="history.back()" class="btn btn-success w-100">◀️ Retour vers les résultats</button>
    </div>
  </div>
</div>

<div class="container mt-4">

    <h2>Détail du covoiturage</h2>
    <hr>

    <!-- Informations trajet -->
    <h4>Trajet</h4>
    <p><strong>Départ :</strong> <?= htmlspecialchars($trajet['lieu_depart']) ?></p>
    <p><strong>Destination :</strong> <?= htmlspecialchars($trajet['lieu_arrivee']) ?></p>
    <p><strong>Date :</strong> <?= htmlspecialchars(yyyymmdd_to_ddmmyyyy($detail['date'])) ?></p>
    <p><strong>Heure de départ :</strong> <?= htmlspecialchars($detail['heure']) ?></p>
    <p><strong>Prix :</strong> <?= htmlspecialchars($detail['prix']) ?> crédits</p>
    <p><strong>Places restantes :</strong> <?= htmlspecialchars($detail['place']) ?></p>

    <hr>

    <!-- Conducteur -->
    <h4>Conducteur</h4>
    <p><strong>Pseudo :</strong> <?= htmlspecialchars($trajet['pseudo']) ?></p>
    <p><strong>Nom :</strong> <?= htmlspecialchars(ucfirst($trajet['prenom'])) ?> <?= htmlspecialchars(strtoupper($trajet['nom'])) ?></p>
    <?php if (!empty($detail['moyenne_note'])): 
        $note = $detail['moyenne_note'];
        $fullStars = floor($note);
        $halfStar = ($note - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
    ?>
    
        <p><strong>Note moyenne :</strong> 
            <?php for ($i = 0; $i < $fullStars; $i++) echo '<i class="bi bi-star-fill text-warning" style="text-shadow: 0 0 0 black;"></i>'; ?>
            <?php if ($halfStar) echo '<i class="bi bi-star-half text-warning" style="text-shadow: 0 0 0 black;"></i>'; ?>
            <?php for ($i = 0; $i < $emptyStars; $i++) echo '☆'; ?>
            (<?= number_format($note, 1) ?>/5)
            
        </p>
    <?php else: ?>
        <p><strong>Note moyenne :</strong> Aucun avis pour le moment</p>
    <?php endif; ?>

    <hr>

    <!-- Véhicule -->
    <h4>Véhicule</h4>
    <?php if ($detail['vehicule']): ?>
        <p><strong>Marque :</strong> <?= htmlspecialchars($detail['vehicule']['marque']) ?></p>      
        <p><strong>Modèle :</strong> <?= htmlspecialchars($detail['vehicule']['modele']) ?></p>
        <p><strong>Énergie :</strong> <?= htmlspecialchars($detail['vehicule']['energie']) ?></p>
    <?php else: ?>
        <p>Informations véhicule non renseignées.</p>
    <?php endif; ?>

    <hr>

    <?php
    $prefs = $_SESSION['preferences'] ?? [];
    ?>
    <!-- Préférences -->
    <h4>Préférences du conducteur</h4>
    <?php if ($detail['preferences']): ?>
        <br>

            <li><strong>🎶 Musique :</strong> <?= $detail['preferences']['musique'] ? "Oui" : "Non" ?></li>
            <br/>
            <li><strong>🐶 Animaux acceptés :</strong> <?= $detail['preferences']['animal'] ? "Oui" : "Non" ?></li>
            <br/>
            <li><strong>🚬 Fumeur :</strong> <?= $detail['preferences']['fumeur'] ? "Oui" : "Non" ?></li>
            <br/>
            <li><strong>🗨️ Discussion :</strong> <?= $detail['preferences']['discussion'] ? "Oui" : "Non" ?></li>

            <?php if (!empty($prefs['autres']) && is_array($prefs['autres'])): ?>
                <?php foreach ($prefs['autres'] as $autre): ?>
                    <li><strong>Préférence personnalisée :</strong> <?= htmlspecialchars($autre) ?></li>
                <?php endforeach; ?>
            <?php endif; ?>

            </br/>

            <h5>Préférences personnalisées :</h5>
            <br/>
            <?php if (!empty($detail['preferences_perso'])): ?>
                <ul>
                    <?php foreach ($detail['preferences_perso'] as $pref): ?>
                        <li><?= ucfirst(htmlspecialchars($pref)) ?></li><br/>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucune préférence personnalisée renseignée.</p>
            <?php endif; ?>

        </ul>

    <?php else: ?>
        <p>Préférences non renseignées.</p>
    <?php endif; ?>

    <hr>

    <!-- Avis -->
    <h4>Avis</h4>
    <?php if (!empty($detail['avis'])): ?>
        <?php foreach ($detail['avis'] as $avis): ?>
            <div class="border p-2 mb-2">
                <strong><?= htmlspecialchars($avis['pseudo']) ?></strong> a mis <strong><?= htmlspecialchars($avis['note']) ?>/5</strong><br>
                <em><?= htmlspecialchars($avis['commentaire']) ?></em>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun avis pour ce conducteur.</p>
    <?php endif; ?>

    <hr>

    <!-- Bouton Participer -->
    <div class="mt-4">
        <?php
        $places_restantes = $detail['place'];
        $prix_trajet = $detail['prix'];
        $utilisateur_connecte = isset($_SESSION['utilisateur_id']) && $_SESSION['privilege'] === 'USER';
        $credit_utilisateur = $_SESSION['credit'] ?? 0;
        ?>

        <?php if ($_SESSION['privilege'] !== 'USER'): ?>
            <div class="alert alert-info">
                Vous devez être un User pour réserver un trajet (et non un Admin ou un Employe)</a>.
            </div>
        <?php elseif (!$utilisateur_connecte): ?>
            <div class="alert alert-info">
                Pour participer, veuillez <a href="connexion.php">vous connecter</a> ou <a href="inscription.php">créer un compte</a>.
            </div>
        <?php elseif ($places_restantes <= 0): ?>
            <div class="alert alert-warning">Aucune place restante pour ce trajet.</div>
        <?php elseif ($credit_utilisateur < $prix_trajet): ?>
            <div class="alert alert-danger">
                Crédit insuffisant (<?= $credit_utilisateur ?> crédit<?= $credit_utilisateur > 1 ? 's' : '' ?>, coût : <?= $prix_trajet ?> crédits) .
            </div>
        <?php else: ?>
            <form method="POST" action="../backend/controllers/validateTrajet.php" onsubmit="return confirm('Confirmez-vous votre participation ? Le trajet coûte <?= $prix_trajet ?> crédits.')">
                <input type="hidden" name="covoiturage_id" value="<?= $trajet['covoiturage_id'] ?>">
                <button type="submit" name="participer" class="btn btn-success">✅ Participer</button>
            </form>
        <?php endif; ?>
    </div>

</div>

<?php include('../frontend/Recurrent/footer.php'); ?>
