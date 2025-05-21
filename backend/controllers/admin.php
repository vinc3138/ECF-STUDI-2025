class Admin {
    public static function getStats() {
        global $pdo;

        // Covoiturages par jour
        $stmt = $pdo->query("SELECT date_trajet, COUNT(*) AS total FROM trajets GROUP BY date_trajet");
        $covoiturages = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $covoiturages[$row['date_trajet']] = (int)$row['total'];
        }

        // Crédits par jour
        $stmt2 = $pdo->query("SELECT date_trajet, SUM(credits) AS credits FROM trajets GROUP BY date_trajet");
        $credits = [];
        $totalCredits = 0;
        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            $credits[$row['date_trajet']] = (int)$row['credits'];
            $totalCredits += (int)$row['credits'];
        }

        return [
            'covoiturages_par_jour' => $covoiturages,
            'credits_par_jour' => $credits,
            'total_credits' => $totalCredits
        ];
    }
}
