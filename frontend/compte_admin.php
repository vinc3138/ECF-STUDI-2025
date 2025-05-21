<!-- PAGE DE RECHERCHE DE TRAJET PAR L'UTILISATEUR -->
<!-- ----------------------------------------------------------------------- -->

<!-- Appel du menu -->
<?php include('../frontend/Recurrent/navbar.php'); ?>

<!-- Appel de l'entête -->
<?php include('../frontend/Recurrent/header.php'); ?>

<!-- Body -->
<!-- ----------------------------------------------------------------------- -->
<body>

  <div class="container">

    <!-- Formulaire -->
    <h2>Rechercher un trajet</h2>

    <div class="container">

      <div class="mb-3 column">
        <!-- Départ à renseigner -->
        <label for="departure" class="form-label">Départ :</label>
        <input type="text" name="depart" class="form-control" id="departure" required>
      </div>

      <div class="mb-3 column">
        <!-- Destination à renseigner -->   
        <label for="destination" class="form-label">Destination :</label>
        <input type="text" class="form-control" id="destination" name="destination" required>
      </div>

      <div class="mb-3 column">
        <!-- Date à renseigner -->
        <label for="date" class="form-label">Date :</label>
        <input type="date" class="form-control" id="date" name="date" required>
      </div>

      <!-- Bouton de validation -->
      <button type="submit" class="btn btn-success">Rechercher</button>

    </div>

  </div>

  <div class="container mt-5">

  <h5>Envie de voyager ? Voilà quelques idées de trajet à venir :</h5>
  <ul>
    <?php
      // Connexion à MySQL
      $connexion = new mysqli("localhost", "root", "Toulouse31!", "ecoride");

      if ($connexion->connect_error) {
        die("Erreur de connexion : " . $connexion->connect_error);
      }

      // Requête SQL
      $resultat = $connexion->query("SELECT depart, destination, date FROM trajets LIMIT 5");

      // Affichage des résultats
      if ($resultat->num_rows > 0) {
        while($ligne = $resultat->fetch_assoc()) {
          $date_formatee = date("d/m/Y", strtotime($ligne["date"]));
          echo "<li class='list-group-item'>" . "De " . htmlspecialchars($ligne["depart"]) . " à " . htmlspecialchars($ligne["destination"]) . " le " . htmlspecialchars($date_formatee)."</li>";
        }
      } else {
        echo "<li class='list-group-item'>Aucun trajet n'est actuellement proposé.</li>";
      }

      $connexion->close();
    ?>
  </ul>

  </div>

</body>
<!-- ----------------------------------------------------------------------- -->

<!-- Appel du pied de page -->
<?php include('../frontend/Recurrent/footer.php'); ?>