<?php
// backend/helpers/auth.php

// Assure-toi que la session est démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifie si un utilisateur est connecté avec le rôle "USER"
function estUtilisateurConnecte(): bool {
    return isset($_SESSION['utilisateur_id']) && isset($_SESSION['privilege']) && $_SESSION['privilege'] === 'USER';
}

// Vérifie si l'utilisateur est un "ADMIN"
function estAdministrateur(): bool {
    return isset($_SESSION['utilisateur_id']) && isset($_SESSION['privilege']) && $_SESSION['privilege'] === 'ADMIN';
}

// Vérifie si quelqu'un est connecté (n'importe quel rôle)
function estConnecte(): bool {
    return isset($_SESSION['utilisateur_id']);
}

// Récupère l'ID de l'utilisateur connecté
function getUtilisateurId(): ?int {
    return $_SESSION['utilisateur_id'] ?? null;
}

// Récupère le rôle de l'utilisateur
function getRoleUtilisateur(): ?string {
    return $_SESSION['privilege'] ?? null;
}
