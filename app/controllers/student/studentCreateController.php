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

try {
    // Iegūstam grupas
    $sql = 'SELECT * FROM groups';
    $data = $db->query($sql)->get();
} catch (\Exception $e) {
    error_log("Kļūda ielādējot grupas: " . $e->getMessage());
    $data = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name_surname'] ?? '';
    $p_code = $_POST['personal_code'] ?? '';
    $group_id = $_POST['group_id'] ?? null;

    if (!$name) {
        $errors['name'] = "Ievadiet vārdu un uzvārdu";
    }
    if (!$group_id) {
        $errors['group_id'] = "Izvēlies grupu";
    }

    try {
        if (!Validator::personalCode($p_code)) {
            $errors['p_code'] = "Nepareizi uzrakstīts personas kods";
        }
    } catch (\Exception $e) {
        error_log("Kļūda pārbaudot personas kodu formātu: " . $e->getMessage());
        $errors['p_code'] = "Neizdevās pārbaudīt personas kodu. Lūdzu, mēģiniet vēlāk.";
    }

    try {
        if (!Validator::uniquePersonalCode($p_code, $db)) {
            $errors['p_code'] = "Šāds personas kods jau eksistē";
        }
    } catch (\Exception $e) {
        error_log("Kļūda pārbaudot personas koda unikālumu: " . $e->getMessage());
        $errors['p_code'] = "Neizdevās pārbaudīt personas koda unikālumu. Lūdzu, mēģiniet vēlāk.";
    }

    if (empty($errors)) {
        try {
            $sql = 'INSERT INTO students (group_id, full_name, personal_code)
                    VALUES (:group_id, :full_name, :personal_code)';

            $db->query($sql, [
                'group_id' => $group_id,
                'full_name' => $name,
                'personal_code' => $p_code
            ]);

            header('Location: /students');
            exit();
        } catch (\Exception $e) {
            error_log("Kļūda pievienojot studentu: " . $e->getMessage());
            $errors['database'] = "Diemžēl studentu pievienot neizdevās. Lūdzu, mēģiniet vēlāk.";
        }
    }
}

try {
    view('students/create.php', [
        'data' => $data,
        'errors' => $errors
    ]);
} catch (\Exception $e) {
    error_log("Kļūda ielādējot skatu students/create.php: " . $e->getMessage());
    echo "Diemžēl formu ielādēt neizdevās.";
}