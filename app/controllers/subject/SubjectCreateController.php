<?php

use Core\App;
use Core\Database;
use Core\Validator;

try {
    $db = App::resolve(Database::class);
} catch (\Exception $e) {
    error_log("Neizdevās izveidot datubāzes savienojumu: " . $e->getMessage());
    die("Sistēma šobrīd nav pieejama. Lūdzu, mēģiniet vēlāk.");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $subject = $_POST['subject_name'] ?? '';
    $type = $_POST['category_type'] ?? '';

    if (!$subject) {
        $errors["subject"] = "Ievadiet priekšmeta nosaukumu";
    }

    if (!$type) {
        $errors['type'] = 'Izvēlies priekšmeta kategoriju';
    }

    try {
        if (!Validator::uniqueSubjectName($subject, $type, $db)) {
            $errors['subject_name'] = "Šāds priekšmets jau eksistē";
        }
    } catch (\Exception $e) {
        error_log("Kļūda pārbaudot unikālo priekšmeta nosaukumu: " . $e->getMessage());
        $errors['subject_name'] = "Neizdevās pārbaudīt priekšmeta unikālumu. Lūdzu, mēģiniet vēlāk.";
    }

    if (empty($errors)) {
        try {
            $sql = 'INSERT INTO subjects (subject_name, category_type)
                    VALUES (:subject_name, :category_type)';

            $db->query($sql, [
                'subject_name' => $subject,
                'category_type' => $type
            ]);

            header('Location: /subjects');
            exit();
        } catch (\Exception $e) {
            error_log("Kļūda pievienojot priekšmetu: " . $e->getMessage());
            $errors['database'] = "Diemžēl priekšmetu pievienot neizdevās. Lūdzu, mēģiniet vēlāk.";
        }
    }
}

try {
    view('subjects/create.php', [
        'errors' => $errors
    ]);
} catch (\Exception $e) {
    error_log("Kļūda ielādējot skatu subjects/create.php: " . $e->getMessage());
    echo "Diemžēl formu ielādēt neizdevās.";
}