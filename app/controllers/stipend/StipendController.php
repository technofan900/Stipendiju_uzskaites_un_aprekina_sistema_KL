<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

// groups
$groups = $db->query(
    "SELECT id, group_name FROM groups"
)->get();


// students
$students = $db->query(
    "SELECT id, full_name, group_id FROM students"
)->get();

// period
$periods = $db->query(
    "SELECT id, period FROM stipend_periods"
)->get();


// subjects
$subjects = $db->query(
    "SELECT id, subject_name FROM subjects"
)->get();

view('stipend/form.php', [
    'groups' => $groups,
    'students' => $students,
    'periods' => $periods,
    'subjects' => $subjects
]);