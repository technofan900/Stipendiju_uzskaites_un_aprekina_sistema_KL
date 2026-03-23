<?php

use Core\App;
use Core\Database;
use Core\Validator;

$errors = [];

try {
    $db = App::resolve(Database::class);
} catch (\Exception $e) {
    error_log("DB savienojuma kļūda: " . $e->getMessage());
    die("Sistēma šobrīd nav pieejama");
}

$id = $_GET['id'] ?? null;

if (!$id) {
    abort();
}


// Fetch existing student
try {

    $sql = "SELECT * FROM students WHERE id = :id";
    $student = $db->query($sql, ['id' => $id])->findOrFail();

} catch (\Exception $e) {

    error_log("Kļūda ielādējot studentu: " . $e->getMessage());
    die("Studentu neizdevās atrast");
}


// Fetch groups
try {

    $groups = $db->query("SELECT * FROM groups")->get();

} catch (\Exception $e) {

    error_log("Kļūda ielādējot grupas: " . $e->getMessage());
    $groups = [];
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $group_id = $_POST['group_id'] ?? '';
    $student_name = trim($_POST['name_surname'] ?? '');
    $p_code = trim($_POST['personal_code'] ?? '');


    // unique check
    try {

        if ($p_code !== $student['personal_code']) {
            if (!Validator::uniquePersonalCode($p_code, $db, $id)) {
                $errors['p_code'] = 'Šāds personas kods jau eksistē';
            }
        }

    } catch (\Exception $e) {

        error_log("Validator kļūda personal_code: " . $e->getMessage());
        $errors['p_code'] = "Neizdevās pārbaudīt personas kodu";
    }


    // required fields

    if (empty($group_id)) {
        $errors['group_id'] = 'Lūdzu izvēlies grupu';
    }

    if (empty($student_name)) {
        $errors['name'] = 'Lūdzu ievadi vārdu un uzvārdu';
    }

    if (empty($p_code)) {
        $errors['p_code'] = 'Lūdzu ievadi personas kodu';
    }


    // update

    if (empty($errors)) {

        try {

            $sql2 = '
                UPDATE students 
                SET group_id = :group_id,
                    full_name = :full_name,
                    personal_code = :personal_code
                WHERE id = :id
            ';

            $db->query($sql2, [
                'group_id' => $group_id,
                'full_name' => $student_name,
                'personal_code' => $p_code,
                'id' => $id
            ]);

            header('Location: /students');
            exit;

        } catch (\Exception $e) {

            error_log("Kļūda update students: " . $e->getMessage());
            $errors['database'] = "Neizdevās saglabāt izmaiņas";
        }
    }
}
try {

    view('students/edit.php', [
        'student' => $student,
        'groups' => $groups,
        'errors' => $errors
    ]);

} catch (\Exception $e) {

    error_log("View kļūda students/edit.php: " . $e->getMessage());
    echo "Neizdevās ielādēt lapu";
}