<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $subject = $_POST['subject_name'] ?? '';
    $type = $_POST['category_type'] ?? '';

    $sql = 'INSERT INTO subjects (subject_name, category_type)
            VALUES (:subject_name, :category_type)';

    $db->query($sql, [
        'subject_name' => $subject,
        'category_type' => $type
    ]);

    header('Location: /subjects');
    exit();
}


view('subjects/create.php');