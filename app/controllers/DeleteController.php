<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$id = $_POST['id'] ?? null;
$table = $_POST['table'] ?? null;
$redirect = $_POST['redirect'] ?? "/";

$allowedTables = [
    'subjects',
    'students',
    'groups',
    'student_grades',
    'student_stipend_records',
    'group_subjects'
];

if ($id && in_array($table, $allowedTables)) {

    $sql = "DELETE FROM $table WHERE id = :id";

    $db->query($sql, [
        'id' => $id
    ]);
}

header('Location: ' . $redirect);
exit();