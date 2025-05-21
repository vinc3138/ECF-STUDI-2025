<?php
// backend/controllers/updateVoiture.php
session_start();
require_once('../models/Voiture.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = Voiture::updateVoiture($_POST['id_voiture'], $_POST);
    $_SESSION[$result ? 'success' : 'error'] = $result ? "Véhicule modifié." : "Erreur de modification.";
}
header('Location: ../../frontend/maPage.php');
