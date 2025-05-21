<!-- PAGE DE CONNEXION DE L'UTILISATEUR -->
<!-- ----------------------------------------------------------------------- -->
 
<!-- Appel du menu -->
<?php include('../frontend/Recurrent/navbar.php'); ?>

<!-- Appel de l'entête -->
<?php include('../frontend/Recurrent/header.php'); ?>

<!-- Body -->
<!-- ----------------------------------------------------------------------- -->
 <body>

    <?php if (isset($_GET['action_avis']) && $_GET['action_avis'] === "0"): ?>
        <div class="col-md-8">             
            <div class="alert alert-success" role="alert">
                <label class="form-label text-success">
                    ⏹️ Action non réalisée.
                </label>
            </div>
        </div>
    <?php elseif (isset($_GET['maj_trajet']) && $_GET['maj_trajet'] === "1"): ?>
        <div class="col-md-8">             
            <div class="alert alert-success" role="alert">
                <label class="form-label text-success">
                    ✅ Action de validation prise en compte.
                </label>
            </div>
        </div>
    <?php elseif (isset($_GET['maj_trajet']) && $_GET['maj_trajet'] === "2"): ?>
        <div class="col-md-8">             
            <div class="alert alert-success" role="alert">
                <label class="form-label text-success">
                    ❌ Action de refus prise en compte.
                </label>
            </div>
        </div>
    <?php endif; ?>  

   <div class="container">

    <h1>Espace Employé</h1>

    <h2>➤ Covoiturages problématique</h2>
    <h6>Affiche les commentaires ayant une note inférieure ou égale à 2</h6>
    <?php include('../backend/controllers/recuperer_avis_negatif.php'); ?>

    <?php if (empty($problemes)): ?>
        <p>Aucun avis en attente.</p>
    <?php else: ?>
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>Date du commentaire</th>
                    <th>ID</th>
                    <th>Note</th>
                    <th>Message</th>
                    <th>Actions</th>
                    <th>N° covoiturage</th>
                    <th>Pseudo chauffeur</th>
                    <th>Email chauffeur</th>
                    <th>Pseudo passager</th>
                    <th>Email passager</th>
                    <th>Date de départ</th>  
                    <th>Date d'arrivée</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($problemes as $p): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($p['date_creation_commentaire'])) ?></td>
                        <td><?= htmlspecialchars($p['avis_id']) ?></td>
                        <td><?= htmlspecialchars($p['note']) ?>⭐/5</td>
                        <td><?= htmlspecialchars($p['commentaire']) ?></td>
                        <td>
                            <form method="POST" action="../backend/controllers/validationAvis.php" class="d-flex justify-content-center gap-2">
                                <input type="hidden" name="avis_id" value="<?= $p['avis_id'] ?>">
                                <button name="action" value="valider" class="btn btn-success btn-sm">Valider</button>
                                <button name="action" value="refuser" class="btn btn-danger btn-sm">Refuser</button>
                            </form>
                        </td>
                        <td><?= htmlspecialchars($p['avis_id']) ?></td>
                        <td><?= htmlspecialchars($p['conducteur_pseudo']) ?></td> 
                        <td><?= htmlspecialchars($p['conducteur_email']) ?></td>
                        <td><?= htmlspecialchars($p['passager_pseudo']) ?></td>                       
                        <td><?= htmlspecialchars($p['passager_email']) ?></td>
                        <td><?= htmlspecialchars($p['date_depart']) ?></td>
                        <td><?= htmlspecialchars($p['date_arrivee']) ?></td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <hr>

    <h2>➤ Avis en attente de validation</h2>

    <?php include('../backend/controllers/recuperer_avis_a_valider.php'); ?>

    <?php if (empty($avis)): ?>
        <p>Aucun avis en attente.</p>
    <?php else: ?>
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>Date du commentaire</th>
                    <th>ID</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($avis as $a): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($a['date_creation_commentaire'])) ?></td>
                        <td><?= htmlspecialchars($a['avis_id']) ?></td>
                        <td><?= htmlspecialchars($a['commentaire']) ?></td>
                        <td>
                            <form method="POST" action="../backend/controllers/validationAvis.php" class="d-flex justify-content-center gap-2">
                                <input type="hidden" name="avis_id" value="<?= $a['avis_id'] ?>">
                                <button name="action" value="valider" class="btn btn-success btn-sm">Valider</button>
                                <button name="action" value="refuser" class="btn btn-danger btn-sm">Refuser</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

   </div>

</body>
<!-- ----------------------------------------------------------------------- -->

<!-- Appel du pied de page -->
<?php include('../frontend/Recurrent/footer.php'); ?>