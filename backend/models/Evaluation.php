<?php
class Evaluation {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Enregistrer une nouvelle évaluation
    public function ajouterEvaluation($covoiturage_id, $utilisateur_id, $note, $commentaire = null) {
        $sql = "INSERT INTO evaluations (covoiturage_id, utilisateur_id, note, commentaire) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$covoiturage_id, $utilisateur_id, $note, $commentaire]);
    }

    // Récupérer la note moyenne d'un conducteur
    public function getNoteMoyenneConducteur($conducteur_id) {
        $sql = "SELECT AVG(e.note) as note_moyenne
                FROM evaluations e
                JOIN covoiturage c ON e.covoiturage_id = c.covoiturage_id
                WHERE c.utilisateur_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$conducteur_id]);
        $result = $stmt->fetch();
        return $result ? round($result['note_moyenne'], 2) : null;
    }

    // Récupérer toutes les évaluations pour un conducteur
    public function getEvaluationsConducteur($conducteur_id) {
        $sql = "SELECT e.*, u.pseudo
                FROM evaluations e
                JOIN covoiturage c ON e.covoiturage_id = c.covoiturage_id
                JOIN utilisateur u ON e.utilisateur_id = u.utilisateur_id
                WHERE c.utilisateur_id = ?
                ORDER BY e.date_evaluation DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$conducteur_id]);
        return $stmt->fetchAll();
    }
}
