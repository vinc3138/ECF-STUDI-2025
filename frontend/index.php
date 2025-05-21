<!-- PAGE D'ACCUEIl DE L'UTILISATEUR -->
<!-- ----------------------------------------------------------------------- -->
 
<!-- Appel du menu -->
<?php include('../frontend/Recurrent/navbar.php'); ?> 


<!-- Appel de l'entête -->
<?php include('../frontend/Recurrent/header.php'); ?>

<!-- Body -->
<!-- ----------------------------------------------------------------------- -->
<body>

  <div class="bandeau"></div>

  <main class="container">

    <div class="jumbotron text-center">

      <p class="display-8">Grâce à EcoDrive, vous pouvez désormais rechercher vos trajets et réserver rapidement.<br />
      Visualisez les trajets disponibles<br />
      Créer votre compte et connectez vous !<br />
      Réservez votre trajet et voyagez !<br />
      </p>

    </div>

    <hr class="my-8">

    <div class="container">

        <!-- Formulaire de recherche -->
      <h6 class="row align-items-center">Rechercher un trajet</h6>

      <form class="row align-items-center" class="search-form" id="searchForm" method="GET" action="">
        <!-- Départ à renseigner -->
        <label for="depart" class="col text-end">Départ :</label>
        <input class="bg-light text-dark col rounded-pill" type="text" id="depart" name="depart" placeholder="Départ" required>
        <!-- Destination à renseigner -->
        <div for="destination" class="col text-end">Destination :</div>
        <input class="bg-light text-dark col rounded-pill" type="text" id="destination" name="destination" placeholder="Destination" required>
        <!-- Date à renseigner (facultatif) -->
        <div for="date" class="col text-end">Date :</div>
        <input class="bg-light text-dark col rounded-pill" type="date" id="date" name="date" placeholder="Date de départ">

        <div class="col"></div>
        <button class="col btn btn-success btn-md btn-block" name="submit" type="submit">Rechercher</button>

      </form>
  
    <!-- Appel du filtre si le bouton Sumit a été cliqué -->
    <div class="container">
        <?php
        // Si des trajets sont trouvés, afficher le filtre
        if (isset($_GET["submit"])) {
          
            include('../frontend/filtre.php');
            include('../backend/controllers/search_trajet.php'); 
        }
        ?>
    </div>

    </div>



    <hr class="my-8">
    
    <br />

    <div class="row">

      <div class="column">
        <div class="image-wrapper">
          <img class="picture_style_01" src="../frontend/Public/Pictures/PICTURE_SEARCH.jpg" alt="Chercher un trajet">
          <a class="btn btn-success btn-lg btn-block centered-button" href="../frontend/trajet.php" role="button">Trouver un trajet</a>
        </div>
      </div>

      <div class="column">
        <div class="image-wrapper">
          <img class="picture_style_01" src="../frontend/Public/Pictures/PICTURE_CONNECT.jpg" alt="Se connecter">
          <a class="btn btn-success btn-lg btn-block centered-button" href="../frontend/connexion.php" role="button">Se connecter</a>
        </div>
      </div>

      <div class="column">
        <div class="image-wrapper">
          <img class="picture_style_01" src="../frontend/Public/Pictures/PICTURE_CREATE.jpg" alt="Créer un compte">
          <a class="btn btn-success btn-lg btn-block centered-button" href="../frontend/inscription.php" role="button">S'inscrire</a>
        </div>
      </div>

    </div>

  </main>

    <!-- Appel du contrôle de login en PHP -->
    <div class="container">
        <?php

            // Si connecté, affichage du message
            if (isset($_GET['logout']) && $_GET['logout'] == 0) {
                echo "<div class='text-success'>👍 Bienvenue " . htmlspecialchars($_SESSION["pseudo"])."</div>";
            }

        ?>
    </div>

</body>
<!-- ----------------------------------------------------------------------- -->

<!-- Appel du pied de page -->
<?php include('../frontend/Recurrent/footer.php'); ?>