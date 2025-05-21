<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="container">
    <h3>Affiner la recherche</h3>
    <form method="GET" action="">
        <div class="row">
            <!-- Aspect écologique -->
            <div class="mb-3 col-auto">
                <label for="ecologique" class="form-label">Aspect écologique :</label>
                <select name="ecologique" class="form-control" id="ecologique">
                    <option value="">Tous</option>
                    <option value="1" <?php echo (isset($_GET['ecologique']) && $_GET['ecologique'] == '1') ? 'selected' : ''; ?>>Oui</option>
                    <option value="0" <?php echo (isset($_GET['ecologique']) && $_GET['ecologique'] == '0') ? 'selected' : ''; ?>>Non</option>
                </select>
            </div>

            <!-- Prix maximum -->
            <div class="mb-3 col-auto">
                <label for="prix_max" class="form-label">Prix maximum :</label>
                <input type="number" class="form-control" id="prix_max" name="prix_max" value="<?php echo isset($_GET['prix_max']) ? $_GET['prix_max'] : ''; ?>" min="0">
            </div>

            <!-- Durée maximale -->
            <div class="mb-3 col-auto">
                <label for="duree_max" class="form-label">Durée maximale :</label>
                <input type="number" class="form-control" id="duree_max" name="duree_max" value="<?php echo isset($_GET['duree_max']) ? $_GET['duree_max'] : ''; ?>" min="0">
            </div>

            <!-- Note minimale -->
            <div class="mb-3 col-auto">
                <label for="note_min" class="form-label">Note minimale du chauffeur :</label>
                <input type="number" class="form-control" id="note_min" name="note_min" step="0.1" min="0" max="5" value="<?php echo isset($_GET['note_min']) ? $_GET['note_min'] : ''; ?>">
            </div>

            <!-- Champs cachés -->
            <input type="hidden" name="depart" value="<?php echo isset($_GET['depart']) ? htmlspecialchars($_GET['depart']) : ''; ?>">
            <input type="hidden" name="destination" value="<?php echo isset($_GET['destination']) ? htmlspecialchars($_GET['destination']) : ''; ?>">
            <input type="hidden" name="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">

            <!-- Bouton -->
            <div class="mb-3 col-auto align-self-end">
                <button type="submit" name="submit_filtre" class="btn btn-success">Appliquer les filtres</button>
            </div>
        </div>
    </form>
</div>
