<?php

use Core\App;
use Core\Database;
use Core\Validator;

$errors = [];

try {
    $db = App::resolve(Database::class);
} catch (\Exception $e) {
    error_log("DB kļūda: " . $e->getMessage());
    die("Sistēma nav pieejama");
}


$id = $_GET['id'] ?? null;

if (!$id) {
    abort();
}


/* ------------------ GET SUBJECT ------------------ */

try {

    $sql = "SELECT * FROM subjects WHERE id = :id";

    $subject = $db->query($sql, [
        'id' => $id
    ])->findOrFail();

} catch (\Exception $e) {

    error_log("Subject load error: " . $e->getMessage());
    die("Priekšmets nav atrasts");
}



/* ------------------ POST ------------------ */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['subject_name'] ?? '';
    $type = $_POST['category_type'] ?? '';


    if (!$name) {
        $errors['subject'] = "Ievadi nosaukumu";
    }

    if (!$type) {
        $errors['type'] = "Izvēlies kategoriju";
    }


    /* unique check */

    try {

        if (
            $name !== $subject['subject_name'] ||
            $type !== $subject['category_type']
        ) {
            if (!Validator::uniqueSubjectName($name, $type, $db)) {
                $errors['subject_name'] = "Šāds priekšmets jau eksistē";
            }
        }

    } catch (\Exception $e) {

        error_log("Validator error: " . $e->getMessage());
        $errors['subject_name'] = "Neizdevās pārbaudīt unikālumu";
    }


    /* update */

    if (empty($errors)) {

        try {

            $sql2 = "
                UPDATE subjects
                SET subject_name = :subject_name,
                    category_type = :category_type
                WHERE id = :id
            ";

            $db->query($sql2, [
                'subject_name' => $name,
                'category_type' => $type,
                'id' => $id
            ]);

            header("Location: /subjects");
            exit();

        } catch (\Exception $e) {

            error_log("Update error: " . $e->getMessage());
            $errors['database'] = "Neizdevās saglabāt";
        }
    }
}


/* ------------------ VIEW ------------------ */

try {

    view('subjects/edit.php', [
        'errors' => $errors,
        'subject' => $subject
    ]);

} catch (\Exception $e) {

    error_log("View error: " . $e->getMessage());
    echo "Neizdevās ielādēt lapu";
}