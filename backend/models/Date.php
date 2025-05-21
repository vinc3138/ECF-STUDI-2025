<?php

// Import de la configuration de la BDD
require_once(__DIR__ . '/../config/Database.php');

function ddmmyyyy_to_yyyymmdd(string $date): ?string {
    $dateTime = DateTime::createFromFormat('d/m/Y', $date);
    if ($dateTime && $dateTime->format('d/m/Y') === $date) {
        return $dateTime->format('Y-m-d');
    }
    return null; // Format invalide
}

function yyyymmdd_to_ddmmyyyy(string $date): ?string {
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);
    if ($dateTime && $dateTime->format('Y-m-d') === $date) {
        return $dateTime->format('d/m/Y');
    }
    return null; // Format invalide
}
