<?php
session_start();
require_once '../models/User.php';

if (!empty($_POST['custom_pref'])) {
    $pref_fixe_stmt = trim($_POST['custom_pref']);
    User::addCustomPreferences($_SESSION['utilisateur_id'], $pref);
    
    // Stockage en session pour utilisation dans la vue
    $_SESSION['detail_covoiturage'] = ['preferences_perso' => $preferences_personnalisees, ];
    
    header("Location: ../../frontend/preference_user.php");
    exit;
}
?>
