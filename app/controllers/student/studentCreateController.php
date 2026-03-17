<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$sql = 'SELECT * FROM GROUPS';
$data = $db->query($sql)->get();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name_surname'] ?? '';
    $p_code = $_POST['personal_code'] ?? '';
    $group_id = $_POST['group_id'] ?? null;

    $sql = 'INSERT INTO students (group_id, full_name, personal_code)
            VALUES (:group_id, :full_name, :personal_code)';

    $db->query($sql, [
        'group_id' => $group_id,
        'full_name' => $name,
        'personal_code' => $p_code
    ]);

    header('Location: /students');
    exit();
}


view('students/create.php', [
    'data' => $data
]);