<?php
// backend/controllers/deleteVoiture.php
session_start();
require_once('../models/Voiture.php');

if (isset($_POST['id_voiture'])) {
    $result = Voiture::deleteVoiture($_POST['id_voiture']);
    $_SESSION[$result ? 'success' : 'error'] = $result ? "Véhicule supprimé." : "Erreur lors de la suppression.";
}
header('Location: ../../frontend/preference_user.php');
