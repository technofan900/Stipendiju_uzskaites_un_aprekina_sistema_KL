<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $group = $_POST['group_name'] ?? '';

    $sql = 'INSERT INTO groups (group_name) VALUES (:group_name)';
    $db->query($sql, [
        'group_name' => $group

    ]);
    header('Location: /groups');
    exit();
}

view('groups/create.php');