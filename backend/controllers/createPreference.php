<?php

session_start();

require_once('../config/db.php');

require_once '../models/User.php';
require_once '../models/Voiture.php';
require_once '../models/Date.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    User::addPreference($_SESSION['utilisateur_id'], $_POST);
    header("Location: ../../frontend/preference_user.php");
    exit;
}
?>