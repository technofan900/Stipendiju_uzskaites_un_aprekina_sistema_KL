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

try {

    $sql = "SELECT * FROM groups WHERE id = :id";
    $group = $db->query($sql, ['id' => $id])->findOrFail();

} catch (\Exception $e) {

    error_log("Kļūda ielādējot grupu: " . $e->getMessage());

    die("Grupu neizdevās atrast");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $group_name = $_POST['group_name'] ?? '';

    try {

        if (!Validator::uniqueGroupName($group_name, $db)) {
            $errors['group_name'] = 'Šāda grupa jau eksistē';
        }

    } catch (\Exception $e) {

        error_log("Validator kļūda: " . $e->getMessage());

        $errors['group_name'] = "Neizdevās pārbaudīt grupas nosaukumu";
    }


    if (empty($errors)) {

        try {

            $sql2 = 'UPDATE groups 
                     SET group_name = :group_name 
                     WHERE id = :id';

            $db->query($sql2, [
                'group_name' => $group_name,
                'id' => $id
            ]);

            header('Location: /groups');
            exit;

        } catch (\Exception $e) {

            error_log("Kļūda update groups: " . $e->getMessage());

            $errors['database'] = "Neizdevās saglabāt izmaiņas";
        }
    }
}

try {

    view("groups/edit.php", [
        'group' => $group,
        'errors' => $errors
    ]);

} catch (\Exception $e) {

    error_log("View kļūda groups/edit.php: " . $e->getMessage());

    echo "Neizdevās ielādēt lapu";
}