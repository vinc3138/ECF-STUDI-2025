<!-- PAGE D'INSCRIPTION DE L'UTILISATEUR -->
<!-- ----------------------------------------------------------------------- -->
 
<!-- Appel du menu -->
<?php include('../frontend/Recurrent/navbar.php'); ?>

<!-- Appel de l'entête -->
<?php include('../frontend/Recurrent/header.php'); ?>

<!-- Body -->
<!-- ----------------------------------------------------------------------- -->
<body>
  <div class="container">

    <h2>Inscription</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['error']) ?>
      </div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['success']) ?>
      </div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form method="POST" action="../backend/controllers/register.php">

      <div class="mb-3">
        <label for="pseudo" class="form-label">Pseudo :</label>
        <input type="text" class="form-control" id="pseudo" name="pseudo" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email :</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Mot de passe :</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>

      <button type="submit" class="btn btn-success">S'inscrire</button>

    </form>

  </div>

<!-- Vérification des erreurs -->
<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger">
    <?= htmlspecialchars($_SESSION['error']) ?>
  </div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>


</body>
<!-- ----------------------------------------------------------------------- -->

<!-- Appel du pied de page -->
<?php include('../frontend/Recurrent/footer.php'); ?>
