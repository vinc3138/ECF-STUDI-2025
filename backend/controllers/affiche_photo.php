<?php

session_start();

// Vérification basique
if (!isset($_SESSION['utilisateur_id'])) {
    http_response_code(403);
    exit("Non autorisé");
}

// Connexion à la base
require __DIR__ . '/../../backend/config/db.php';

$stmt = $pdo->prepare("SELECT photo FROM utilisateur WHERE utilisateur_id = :id");
$stmt->bindParam(':id', $_SESSION['utilisateur_id'], PDO::PARAM_INT);
$stmt->execute();

$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Nettoyage de tout tampon éventuel
if (ob_get_length()) ob_clean();

if ($data && !empty($data['photo'])) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->buffer($data['photo']);
    header("Content-Type: $mime");
    echo $data['photo'];
    exit;
} else {
    header("Content-Type: image/png");
    readfile(__DIR__ . '/../../frontend/Private/Users_pictures/PICTURE_USER_00.png');
    exit;
}