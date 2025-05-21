<?php
require_once 'models/User.php';
require_once 'models/Covoiturage.php';

class AdminController {
    
    public function dashboard() {
        require 'views/admin/dashboard.php';
    }

    public function createEmploye() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            User::create($email, $password, 'employee');
            header('Location: /admin/dashboard');
        }
    }

    public function suspendUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'];
            User::suspend($userId);
            header('Location: /admin/dashboard');
        }
    }

    public function stats() {
        $covoiturages = Covoiturage::getCovoituragesParJour();
        $credits = Covoiturage::getCreditsParJour();
        $total = Covoiturage::getTotalCredits();

        echo json_encode([
            'covoiturages_par_jour' => $covoiturages,
            'credits_par_jour' => $credits,
            'total_credits' => $total
        ]);
    }

	public function stats() {
		header('Content-Type: application/json');

		$data = Admin::getStats(); // méthode dans le modèle
		echo json_encode($data);
	}
    
}