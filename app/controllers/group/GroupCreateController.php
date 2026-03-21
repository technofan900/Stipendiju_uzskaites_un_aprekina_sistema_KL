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

    $group = $_POST['group_name'] ?? '';

    if (!$group) {
        $errors['group'] = "Ievadiet grupas vārdu";
    }

    try {
        if (!Validator::uniqueGroupName($group, $db)) {
            $errors['group_name'] = "Šāda grupa jau eksistē";
        }
    } catch (\Exception $e) {
        error_log("Kļūda pārbaudot grupas unikālumu: " . $e->getMessage());
        $errors['group_name'] = "Neizdevās pārbaudīt grupas unikālumu. Lūdzu, mēģiniet vēlāk.";
    }

    if (empty($errors)) {
        try {
            $sql = 'INSERT INTO groups (group_name) VALUES (:group_name)';
            $db->query($sql, [
                'group_name' => $group
            ]);

            header('Location: /groups');
            exit();
        } catch (\Exception $e) {
            error_log("Kļūda pievienojot grupu: " . $e->getMessage());
            $errors['database'] = "Diemžēl grupu pievienot neizdevās. Lūdzu, mēģiniet vēlāk.";
        }
    }
}

try {
    view('groups/create.php', [
        'errors' => $errors
    ]);
} catch (\Exception $e) {
    error_log("Kļūda ielādējot skatu groups/create.php: " . $e->getMessage());
    echo "Diemžēl formu ielādēt neizdevās.";
}