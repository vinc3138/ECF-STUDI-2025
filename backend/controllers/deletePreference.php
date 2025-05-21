<?php
// backend/controllers/deleteVoiture.php
session_start();
require_once('../models/Preference.php');

if (isset($_POST['id_preference'])) {
    $result = Voiture::deleteVoiture($_POST['id_preference']);
    $_SESSION[$result ? 'success' : 'error'] = $result ? "Préférence supprimée." : "Erreur lors de la suppression.";
}
header('Location: ../../frontend/preference_user.php');
