<?php
include('../frontend/Recurrent/navbar.php');
include('../frontend/Recurrent/header.php');
?>

<body>
<div class="container">
    <h2>Rechercher un trajet</h2>
    <form method="GET" action="">
        <div class="mb-3 column">
            <label for="depart" class="form-label">Départ :</label>
            <input type="text" name="depart" class="form-control" id="depart" required
                value="<?php echo isset($_GET['depart']) ? htmlspecialchars($_GET['depart']) : ''; ?>">
        </div>

        <div class="mb-3 column">
            <label for="destination" class="form-label">Destination :</label>
            <input type="text" class="form-control" id="destination" name="destination" required
                value="<?php echo isset($_GET['destination']) ? htmlspecialchars($_GET['destination']) : ''; ?>">
        </div>

        <div class="mb-3 column">
            <label for="date" class="form-label">Date (facultatif) :</label>
            <input type="date" class="form-control" id="date" name="date"
                value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
        </div>

        <button type="submit" name="submit" class="btn btn-success">Rechercher</button>
    </form>
</div>

<!-- Affichage du filtre -->
<div class="container">
    <?php
    if (isset($_GET["submit"]) || isset($_GET["submit_filtre"])) {
        include('filtre.php');
    }
    ?>
</div>

<!-- Affichage des résultats -->
<div class="container mt-4">
    <?php
    if (isset($_GET["submit"]) || isset($_GET["submit_filtre"])) {
        include('../backend/controllers/search_trajet.php');
    }
    ?>
</div>
</body>

<?php include('../frontend/Recurrent/footer.php'); ?>
