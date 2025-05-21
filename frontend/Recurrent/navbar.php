<!-- Projet EcoRide - Navbar -->

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

  <header class="navbar navbar-expand-lg navbar-light bg-light">

  

    <div class="container">

      <img class="logo_style" src="../frontend/Public/Pictures/LOGO_ECORIDE.png" alt="Logo EcoRide" width="100" height="100"/>

      <a class="navbar-brand" href="#">EcoRide</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">

        <span class="navbar-toggler-icon"></span>

      </button>

      <div class="collapse navbar-collapse" id="navbarNav">

        <ul class="navbar-nav ms-auto">



          <li class="nav-item"><a class="nav-link" href="../frontend/index.php">Accueil</a></li>

          <li class="nav-item"><a class="nav-link" href="../frontend/trajet.php">Rechercher un trajet</a></li>

          <!-- Si profil non existant dans la variable SESSION : afficher les onglets "Connexion" et "Inscription" -->
          <?php if (!isset($_SESSION['pseudo'])): ?>

              <li class="nav-item"><a class="nav-link" href="../frontend/connexion.php">Connexion</a></li>

              <li class="nav-item"><a class="nav-link" href="../frontend/inscription.php">Inscription</a></li>

          <?php endif; ?>

          <li class="nav-item"><a class="nav-link" href="../frontend/apropos.php">À propos / Contact</a></li>

          <!-- Si profil existant dans la variable SESSION : afficher les onglets "Compte", "Préférences" et "Déconnexion" -->
          <?php if (isset($_SESSION['pseudo'])&&$_SESSION['privilege'] !== 'SUSPENDU'): ?>

              <li class="nav-item" style="cursor: not-allowed;"><div class="nav-link">👤 <strong><?= htmlspecialchars($_SESSION['pseudo']) ?></strong></div></li>
              
              <li class="nav-item"><a class="nav-link" href="../frontend/compte_user.php"><strong>Compte</strong></a></li>

              <li class="nav-item"><a class="nav-link" href="../frontend/preference_user.php"><strong>Préférences</strong></a></li>

          <?php if (isset($_SESSION['utilisateur_id'])): ?>

                  <?php if ($_SESSION['privilege'] === 'ADMIN'): ?>

                    <li class="nav-item"><a class="nav-link" href="../frontend/dashboard_admin.php"><strong>Espace Admin</strong></a></li>

                  <?php elseif ($_SESSION['privilege'] === 'EMPLOYE'): ?>

                    <li class="nav-item"><a class="nav-link" href="../frontend/dashboard_employe.php"><strong>Espace Employé</strong></a></li>

                  <?php elseif ($_SESSION['privilege'] === 'USER'): ?>

                    <li class="nav-item"><a class="nav-link" href="../frontend/historique_trajet.php"><strong>Trajets</strong></a></li>

                  <?php endif; ?>

          <?php endif; ?>

              <li class="nav-item"><a class="nav-link" href="../backend/controllers/logout.php"><strong>Déconnexion</strong></a></li>

          <?php endif; ?>

        </ul>

      </div>

    </div>

  </header>