<?php

require_once '../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    User::suspend($userId);
    header('Location: ../../frontend/dashboard_admin.php');
    exit;
}
