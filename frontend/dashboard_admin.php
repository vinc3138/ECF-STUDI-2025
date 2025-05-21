<!-- PAGE DE CONNEXION DE L'UTILISATEUR -->
<!-- ----------------------------------------------------------------------- -->

<!-- Appel du menu -->
<?php include('../frontend/Recurrent/navbar.php'); ?>

<!-- Appel de l'entête -->
<?php include('../frontend/Recurrent/header.php'); ?>

<?php require_once '../backend/models/User.php'; ?>
<?php require_once '../backend/models/Covoiturage.php'; ?>

<!-- Body -->
<!-- ----------------------------------------------------------------------- -->

<body>


  <div class="row justify-content-center">
    <div class="col col-lg-8">

      <div class="card shadow rounded-4 p-4">
        <h1 class="text-center mb-4">⚙️ Espace Admin</h1>
        <h5 class="text-secondary mb-3">Créer un nouveau compte employé</h5>

        <form method="POST" action="/backend/controllers/createUser.php">
          <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="email@exemple.com" required>
          </div>

          <div class="mb-3">
            <label for="pseudo" class="form-label">Pseudo</label>
            <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Nom d'utilisateur" required>
          </div>

          <div class="mb-4">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">
            ➕ Créer l’employé
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Graphiques -->
  <div class="mt-5">
    <?php require_once 'index.html'; ?>
  </div>
</div>

</body>

<?php include('../frontend/Recurrent/footer.php'); ?>
