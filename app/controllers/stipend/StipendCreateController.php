<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // groups
$groups = $db->query(
    "SELECT id, group_name FROM groups"
)->get();


// students
$students = $db->query(
    "SELECT id, name, group_id FROM students"
)->get();


// subjects
$subjects = $db->query(
    "SELECT id, subject_name, group_id FROM subjects"
)->get();



// view('stipend/form/create.php');
}

