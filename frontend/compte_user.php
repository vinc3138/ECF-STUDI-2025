<?php include('../frontend/Recurrent/navbar.php'); ?>
<?php include('../frontend/Recurrent/header.php'); ?>

<body>
<main class="container py-4">

  <div class="row g-4">

    <!-- Colonne 1 -->
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title mb-3">Mon profil</h4>

          <!-- Pseudo -->
          <p class="mb-2"><strong>Pseudo :</strong></p>
          <div class="alert alert-secondary text-center fs-5">
            <?= isset($_SESSION['pseudo']) ? htmlspecialchars($_SESSION['pseudo']) : "Non connecté" ?>
          </div>

          <!-- Privilège -->
          <p class="mt-4 mb-2"><strong>Privilège :</strong></p>
          <div class="alert alert-secondary text-center fs-5">
            <?= isset($_SESSION['privilege']) ? htmlspecialchars($_SESSION['privilege']) : "Non connecté" ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Colonne 2 -->
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <h4 class="card-title mb-4">Ma photo</h4>

          <img class="img-fluid rounded mb-3" src="../backend/controllers/affiche_photo.php" alt="Ma photo" style="max-height: 250px; object-fit: cover;">

          <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <input type="file" class="form-control" name="photo" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-success w-100" name="submit">Envoyer</button>

            <?php
              if (isset($_POST['submit'])) {
                include('../backend/controllers/import_photo.php');     
              }
            ?>
          </form>
        </div>
      </div>
    </div>

    <!-- Colonne 3 -->
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title mb-3">Mes informations</h4>

          <?php include('../backend/controllers/export_information.php'); ?> 

          <form method="POST">
            <div class="mb-3">
              <label for="prenom" class="form-label">Prénom</label>
              <input type="text" name="prenom" class="form-control" value="<?= isset($prenom) ? htmlspecialchars($prenom) : '' ?>">
            </div>

            <div class="mb-3">
              <label for="nom" class="form-label">Nom</label>
              <input type="text" name="nom" class="form-control" value="<?= isset($nom) ? htmlspecialchars($nom) : '' ?>">
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
            </div>

            <div class="mb-3">
              <label for="adresse" class="form-label">Adresse</label>
              <input type="text" name="adresse" class="form-control" value="<?= isset($adresse) ? htmlspecialchars($adresse) : '' ?>">
            </div>

            <div class="mb-3">
              <label for="telephone" class="form-label">Téléphone</label>
              <input type="text" name="telephone" class="form-control" value="<?= isset($telephone) ? htmlspecialchars($telephone) : '' ?>">
            </div>

            <div class="mb-3">
              <label for="date_naissance" class="form-label">Date de naissance</label>
              <input type="date" name="date_naissance" class="form-control" value="<?= isset($date_naissance) ? htmlspecialchars($date_naissance) : '' ?>">
            </div>

            <button type="submit" name="informations_personnelles" class="btn btn-success w-100">Enregistrer</button>

            <?php
              if (isset($_POST['informations_personnelles'])) {
                include('../backend/controllers/import_information.php');
                include('../backend/controllers/export_information.php');
              }
            ?>
          </form>
        </div>
      </div>
    </div>

  </div>

  <!-- Ligne de séparation -->
  <hr class="my-5" />

</main>

<?php include('../frontend/Recurrent/footer.php'); ?>
