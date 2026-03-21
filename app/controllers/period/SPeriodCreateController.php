<?php

use Core\App;
use Core\Database;

try {
    $db = App::resolve(Database::class);
} catch (\Exception $e) {
    error_log("DB savienojuma kļūda: " . $e->getMessage());
    die("Sistēma šobrīd nav pieejama.");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $year = $_POST['year'] ?? '';
    $period = $_POST['period'] ?? '';
    $group = $_POST['period_group'] ?? '';

    // validation

    if (!$year) {
        $errors['year'] = "Ievadiet gadu";
    }

    if (!$period) {
        $errors['period'] = "Ievadiet periodu";
    }

    if (empty($errors)) {

        try {

            $sql = "INSERT INTO stipend_periods (year, period, period_group)
                    VALUES (:year, :period, :period_group)";

            $db->query($sql, [
                'year' => $year,
                'period' => $period,
                'period_group' => $group
            ]);

            header('Location: /period');
            exit();

        } catch (\Exception $e) {

            error_log("Kļūda pievienojot periodu: " . $e->getMessage());

            $errors['database'] = "Neizdevās saglabāt periodu. Mēģiniet vēlreiz.";
        }
    }
}

try {

    view('stipend_period/create.php', [
        'errors' => $errors
    ]);

} catch (\Exception $e) {

    error_log("View kļūda stipend_period/create.php: " . $e->getMessage());

    echo "Neizdevās ielādēt formu.";
}