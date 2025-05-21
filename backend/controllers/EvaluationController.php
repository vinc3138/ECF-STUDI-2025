<?php
require_once 'models/Evaluation.php';

class EvaluationController {
    private $evaluationModel;

    public function __construct($pdo) {
        $this->evaluationModel = new Evaluation($pdo);
    }

    // Afficher la page d'ajout d'évaluation (formulaire)
    public function formAjouterEvaluation() {
        require 'views/evaluation_form.php';
    }

    // Traiter le formulaire d'ajout d'évaluation
    public function ajouterEvaluation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $covoiturage_id = $_POST['covoiturage_id'];
            $utilisateur_id = $_SESSION['user_id'];  // passager connecté
            $note = $_POST['note'];
            $commentaire = $_POST['commentaire'] ?? null;

            $success = $this->evaluationModel->ajouterEvaluation($covoiturage_id, $utilisateur_id, $note, $commentaire);

            if ($success) {
                header("Location: index.php?action=details_trajet&id=$covoiturage_id&message=Evaluation enregistrée");
                exit;
            } else {
                $error = "Erreur lors de l'enregistrement.";
                require 'views/evaluation_form.php';
            }
        }
    }

    // Afficher la note moyenne et les évaluations d'un conducteur
    public function afficherEvaluationsConducteur($conducteur_id) {
        $noteMoyenne = $this->evaluationModel->getNoteMoyenneConducteur($conducteur_id);
        $evaluations = $this->evaluationModel->getEvaluationsConducteur($conducteur_id);
        require 'views/evaluations_list.php';
    }
}
