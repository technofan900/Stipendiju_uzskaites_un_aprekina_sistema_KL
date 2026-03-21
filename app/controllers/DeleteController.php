<?php

use Core\App;
use Core\Database;

try {
    $db = App::resolve(Database::class);
} catch (\Exception $e) {
    error_log("Neizdevās izveidot datubāzes savienojumu: " . $e->getMessage());
    die("Sistēma šobrīd nav pieejama. Lūdzu, mēģiniet vēlāk.");
}

$id = $_POST['id'] ?? null;
$table = $_POST['table'] ?? null;
$redirect = $_POST['redirect'] ?? "/";

$allowedTables = [
    'subjects',
    'students',
    'groups',
    'student_grades',
    'student_stipend_records',
    'group_subjects',
    'stipend_periods'
];

if ($id && in_array($table, $allowedTables)) {
    try {
        $sql = "DELETE FROM $table WHERE id = :id";
        $db->query($sql, [
            'id' => $id
        ]);
    } catch (\Exception $e) {
        error_log("Kļūda dzēšot ierakstu no tabulas {$table} ar id {$id}: " . $e->getMessage());
    }
}

try {
    header('Location: ' . $redirect);
    exit();
} catch (\Exception $e) {
    error_log("Kļūda pāradresējot uz {$redirect}: " . $e->getMessage());
    echo "Diemžēl pāradresēt neizdevās. Lūdzu, izmantojiet saiti: <a href='{$redirect}'>Atpakaļ</a>";
}