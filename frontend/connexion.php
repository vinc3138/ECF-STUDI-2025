<!-- PAGE DE CONNEXION DE L'UTILISATEUR -->
<!-- ----------------------------------------------------------------------- -->
 
<!-- Appel du menu -->
<?php include('../frontend/Recurrent/navbar.php'); ?>

<!-- Appel de l'entête -->
<?php include('../frontend/Recurrent/header.php'); ?>

<!-- Body -->
<!-- ----------------------------------------------------------------------- -->
<body>
  <div class="container">
    <h2>Connexion</h2>
    <form method="POST" action="">
      <div class="mb-3">
        <label for="email" class="form-label">Email :</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Mot de passe :</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <button type="submit" name='connexion' class="btn btn-success" onclick="validateMDP()">Se connecter</button>
    </form>
    <div class="btn btn-link btn-sm">
      <a href="reset_password.php">Mot de passe oublié ?</a>
    </div>
  </div>

<!-- ----------------------------------------------------------------------- -->

    <!-- Appel du contrôle de login / logout en PHP -->
    <div class="container">
        <?php
            // Appel du fichie login.php
            if (isset($_POST['email']) && isset($_POST['password'])) {
                include('../backend/controllers/login.php');    
            }
            // Si déconnecté, affichage du message
            if (isset($_GET['logout']) && $_GET['logout'] == 1 && !isset($_POST['connexion'])) {
                echo "<div class='text-success'>✅ Vous avez bien été déconnecté. Au revoir!</div>";
            }

        ?>
    </div>


</body>
<!-- ----------------------------------------------------------------------- -->

<!-- Appel du pied de page -->
<?php include('../frontend/Recurrent/footer.php'); ?>