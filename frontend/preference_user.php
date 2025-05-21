<?php include('../frontend/Recurrent/navbar.php'); ?>
<?php include('../frontend/Recurrent/header.php'); ?>

<body>
<main class="container my-5">

  <!-- Messages de succès ou d'erreur -->
  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <div class="row g-4">

    <!-- Colonne 1 : Rôle utilisateur -->
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title">Mon rôle actuel</h4>
          <?php if (isset($_SESSION['role'])): ?>
            <p class="alert alert-secondary text-center fs-4">
              <?= htmlspecialchars($_SESSION['role']) ?>
            </p>
          <?php endif; ?>

          <form method="POST" action="../../backend/controllers/updateRole.php">
            <label for="rôle_utilisateur" class="form-label">Modifier mon rôle :</label>
            <select class="form-select" name="role" id="rôle_utilisateur" required>
              <option value="">--Choisir une option--</option>
              <option value="chauffeur">Chauffeur</option>
              <option value="passager">Passager</option>
              <option value="chauffeur_passager">Chauffeur et passager</option>
            </select>
            <button type="submit" class="btn btn-success mt-2">Valider</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Colonne 2 : Crédits -->
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title">Mes crédits</h4>
          <?php if (isset($_SESSION['credit'])): ?>
            <p class="alert alert-success text-center fs-4">
              <?= htmlspecialchars($_SESSION['credit']) ?> crédits
            </p>

          <?php if (isset($_GET['success']) && $_GET['success'] === "1"): ?>
              <div class="col px-0">
                  <div class="alert alert-success w-100 mb-0" role="alert">
                      <label class="form-label text-success mb-0">
                          ✅ Votre compte a bien été crédité de 100 crédits.
                      </label>
                  </div>
              </div>
          <?php elseif (isset($_GET['success'])  && $_GET['success'] === "0"): ?>
              <div class="col px-0">             
                  <div class="alert alert-success" role="alert">
                      <label class="form-label text-success">
                          🚫 Votre compte n'a pas pu être crédité, veuillez réessayer.
                      </label>
                  </div>
              </div>
          <?php endif; ?>

            <br/><br/><br/>
          <?php endif; ?>
            <div class="d-grid">
                <form action="../backend/controllers/addCredit.php" method="POST" onsubmit="return confirm('Confirmer le rechargement de 100 crédits ?');">
                    <button type="submit" class="btn btn-success w-100">
                        🔋 Recharger de 100 crédits
                    </button>
                </form>
            </div>
        </div>
      </div>
    </div>

    <!-- Colonne 3 : Préférences -->
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title">Mes préférences</h4>

          <form method="POST" action="../../backend/controllers/updatePreferences.php">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="preferences[]" value="1" id="musique">
              <label class="form-check-label" for="musique">Musique 🎶</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="preferences[]" value="2" id="animal">
              <label class="form-check-label" for="animal">Animal🐕</label>
            </div>
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" name="preferences[]" value="3" id="fumeur">
              <label class="form-check-label" for="fumeur">Fumeur🚬</label>
            </div>
            <button type="submit" class="btn btn-success">Sauvegarder</button>
          </form>

          <hr>

          <h5>Ajouter une préférence personnalisée</h5>
          <form method="POST" action="../../backend/controllers/addCustomPreference.php" class="d-flex gap-2">
            <input type="text" name="custom_pref" class="form-control" placeholder="Nouvelle préférence" required>
            <button type="submit" class="btn btn-outline-success">Ajouter</button>
          </form>

          <h6 class="mt-3">Mes préférences personnalisées :</h6>
          <ul class="list-group">
            <?php if (!empty($_SESSION['detail_covoiturage']['preferences_perso'])): ?>
              <?php foreach ($_SESSION['detail_covoiturage']['preferences_perso'] as $pref): ?>
                <li class="list-group-item"><?= ucfirst(htmlspecialchars($pref)) ?></li>
              <?php endforeach; ?>
            <?php else: ?>
              <li class="list-group-item text-muted">Aucune préférence personnalisée enregistrée.</li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>

    <!-- Colonne 4 : Véhicules -->
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title">Mes véhicules</h4>

          <?php
          require_once '../backend/models/User.php';
          $nbVoitures = User::countVoitures($_SESSION['utilisateur_id']);
          ?>
          <p>Nombre de véhicules : <strong><?= $nbVoitures ?></strong></p>

          <?php $role = strtoupper($_SESSION['role'] ?? ''); ?>
          <?php if (stripos($role, 'CHAUFFEUR') === false): ?>
            <div class="alert alert-warning">Vous n'êtes pas chauffeur. Aucun véhicule affiché.</div>
          <?php else : ?>
          <h6>Ajouter un véhicule :</h6>
          <form method="POST" action="../../backend/controllers/addVoiture.php" class="row g-2">
            <div class="col-md-6"><input type="text" class="form-control" name="immatriculation" placeholder="Immatriculation"></div>
            <div class="col-md-6"><input type="text" class="form-control" name="marque" placeholder="Marque"></div>
            <div class="col-md-6"><input type="text" class="form-control" name="modele" placeholder="Modèle"></div>
            <div class="col-md-6"><input type="date" class="form-control" name="date_immatriculation"></div>
            <div class="col-md-6"><input type="number" class="form-control" name="nb_places_voiture" placeholder="Places dispo"></div>
            <div class="col-md-6"><input type="text" class="form-control" name="energie" placeholder="Énergie"></div>
            <div class="col-12"><button type="submit" class="btn btn-success w-100">Ajouter</button></div>
          </form>

          <hr>

          <?php
          require_once '../backend/models/Voiture.php';
          $voitures = Voiture::getVoituresByUser($_SESSION['utilisateur_id']);
          if (!empty($voitures)):
          ?>
            <ul class="list-group mt-3">
              <?php foreach ($voitures as $v): ?>
                <li class="list-group-item">
                  <?= "🚗  ".htmlspecialchars($v['marque_id']) ?> <?= htmlspecialchars($v['modele']) ?> - <?= htmlspecialchars($v['immatriculation']) ?>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p class="text-muted">Aucun véhicule enregistré.</p>
          <?php endif; ?>


          <?php endif; ?>





        </div>
      </div>
    </div>

  </div>

</main>
<?php include('../frontend/Recurrent/footer.php'); ?>
