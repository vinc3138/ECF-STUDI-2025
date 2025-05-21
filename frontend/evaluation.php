<!-- Appel du menu et de l'entête -->
<?php include('../frontend/Recurrent/navbar.php'); ?>
<?php include('../frontend/Recurrent/header.php'); ?>

<main class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-success shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">⭐ Évaluer le trajet</h4>
                </div>
                <div class="card-body">

<?php if (isset($_GET['noter'])): ?>
    <?php
        $covoiturageId = intval($_GET['noter']);
        // Utilisation de $covoiturageId
    ?>
                
                    <form method="POST" action="../backend/controllers/ajoutAvis.php">
                        <input type="hidden" name="covoiturage_id" value="<?= htmlspecialchars($_GET['covoiturage_id'] ?? '') ?>">

                        <div class="mb-3">
                            <label for="note" class="form-label">Note (1 à 5) :</label>
                            <select id="note" name="note" class="form-select" required>
                                <option value="">-- Choisir --</option>
                                <?php for ($i=1; $i<=5; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?> ⭐</option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="commentaire" class="form-label">Commentaire (optionnel) :</label>
                            <textarea id="commentaire" name="commentaire" class="form-control" rows="4" placeholder="Partagez votre expérience..."></textarea>
                        </div>

                        <div class="d-grid">
                            <input type="hidden" name="covoiturage_id" value="<?= $covoiturageId ?>">
                            <button type="submit" class="btn btn-success btn-lg">✅ Envoyer l’évaluation</button>
                        </div>
                    </form>

<?php else: ?>
      <p>Identifiant du trajet non fourni.</p>
<?php endif; ?>  
    
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Pied de page -->
<?php include('../frontend/Recurrent/footer.php'); ?>
