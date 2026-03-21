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
    $subjects = $db->query("SELECT * FROM subjects")->get();
} catch (\Exception $e) {
    error_log("Kļūda ielādējot priekšmetus: " . $e->getMessage());
    $subjects = [];
}

try {
    $groups = $db->query("SELECT * FROM groups")->get();
} catch (\Exception $e) {
    error_log("Kļūda ielādējot grupas: " . $e->getMessage());
    $groups = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $subjectId = $_POST['subject_id'] ?? null;
    $selectedGroups = $_POST['groups'] ?? [];

    if (!$subjectId) {
        $errors['subject'] = "Izvēlies priekšmetu";
    }

    if (empty($selectedGroups)) {
        $errors['groups'] = "Izvēlies vismaz vienu grupu";
    }

    // check duplicates
    try {
        foreach ($selectedGroups as $groupId) {
            if (!Validator::subjectAlreadyInGroup($subjectId, $groupId, $db)) {
                $errors['duplicate'] = "Šis priekšmets jau ir pievienots kādai no izvēlētajām grupām";
                break;
            }
        }
    } catch (\Exception $e) {
        error_log("Kļūda pārbaudot vai priekšmets jau pievienots grupai: " . $e->getMessage());
        $errors['duplicate'] = "Neizdevās pārbaudīt priekšmeta pievienojumu grupām. Lūdzu, mēģiniet vēlāk.";
    }

    if (empty($errors)) {
        try {
            foreach ($selectedGroups as $groupId) {
                $db->query(
                    "INSERT INTO group_subjects (group_id, subject_id)
                     VALUES (:group_id, :subject_id)",
                    [
                        'group_id' => $groupId,
                        'subject_id' => $subjectId
                    ]
                );
            }

            header("Location: /groups");
            exit();
        } catch (\Exception $e) {
            error_log("Kļūda pievienojot priekšmetu grupām: " . $e->getMessage());
            $errors['database'] = "Diemžēl priekšmetu pievienot grupām neizdevās. Lūdzu, mēģiniet vēlāk.";
        }
    }
}

try {
    view('groups/bindSubject.php', [
        'subjects' => $subjects,
        'groups' => $groups,
        'errors' => $errors
    ]);
} catch (\Exception $e) {
    error_log("Kļūda ielādējot skatu groups/bindSubject.php: " . $e->getMessage());
    echo "Diemžēl formu ielādēt neizdevās.";
}