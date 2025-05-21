<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Supprime toutes les variables de session
session_unset();

// Détruit la session
session_destroy();

// Redirige vers la page de connexion avec logout=1 pour indiquer que la déconnexion a eu lieu
header("Location: ../../frontend/connexion.php?logout=1");
exit;