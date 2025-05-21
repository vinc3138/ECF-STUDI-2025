<?php

require_once '../config/Database.php';
$pdo = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['avis_id'], $_POST['action'])) {
        exit('Requête invalide.');
    }

    $avis_id = (int) $_POST['avis_id'];
    $action = $_POST['action'];

    if (!in_array($action, ['valider', 'refuser'])) {
        exit('Action non autorisée.');
    }

        if ($action === 'refuser') {
            $stmt = $pdo->prepare("UPDATE avis SET statut = 4 WHERE avis_id = :avis_id");
            $stmt->execute(['avis_id' => $avis_id]);

            header('Location: ../../frontend/dashboard_employe.php?action_avis=2');
            exit();

        } elseif ($action === 'valider') {
            $pdo->beginTransaction();

            // 1. Récupérer la note de l'avis et l'utilisateur concerné
            $stmt = $pdo->prepare("SELECT utilisateur_id, note FROM avis WHERE avis_id = :avis_id");
            $stmt->execute(['avis_id' => $avis_id]);
            $avis = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$avis) throw new Exception("Avis introuvable.");

            $note_avis = (float) $avis['note'];
            $utilisateur_id = (int) $avis['utilisateur_id'];

            // 2. Récupérer la note actuelle
            $stmt = $pdo->prepare("SELECT note FROM utilisateur WHERE utilisateur_id = :id");
            $stmt->execute(['id' => $utilisateur_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) throw new Exception("Utilisateur introuvable.");

            $note_actuelle = isset($user['note']) ? (float) $user['note'] : 0;

            // 3. Compter le nombre d'avis déjà validés
            $stmt = $pdo->prepare("
                SELECT COUNT(*) 
                FROM avis 
                WHERE utilisateur_id = :utilisateur_id 
                AND statut = 5
            ");
            $stmt->execute(['utilisateur_id' => $utilisateur_id]);
            $nb_avis_calcule = (int) $stmt->fetchColumn();

            // 4. Calcul de la nouvelle moyenne pondérée
            $nouvelle_moyenne = round((($note_actuelle * $nb_avis_calcule) + $note_avis) / ($nb_avis_calcule + 1), 2);

            // 5. Mettre à jour l'utilisateur
            $stmt = $pdo->prepare("UPDATE utilisateur SET note = :note WHERE utilisateur_id = :id");
            $stmt->execute([
                'note' => $nouvelle_moyenne,
                'id' => $utilisateur_id
            ]);

            // 6. Valider l'avis
            $stmt = $pdo->prepare("UPDATE avis SET statut = 5 WHERE avis_id = :avis_id");
            $stmt->execute(['avis_id' => $avis_id]);

            $pdo->commit();

            header('Location: ../../frontend/dashboard_employe.php?action_avis=1');
            exit();
        }

    header('Location: ../../frontend/dashboard_employe.php?action_avis=0');
    exit();
}
