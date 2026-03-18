<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

// groups
$groups = $db->query(
    "SELECT id, group_name FROM groups"
)->get();

$groupId = $_GET['group_id'] ?? null;

$students = [];
$group_subjects = [];
$periods = [];

if ($groupId) {

    // students
    $students = $db->query(
        "SELECT id, full_name FROM students WHERE group_id = :group_id",
        ['group_id' => $groupId]
    )->get();

    // subjects
    $group_subjects = $db->query(
        "SELECT su.id, su.subject_name, su.category_type
         FROM group_subjects gs
         JOIN subjects su ON su.id = gs.subject_id
         WHERE gs.group_id = :group_id",
        ['group_id' => $groupId]
    )->get();

    // periods
    $periods = $db->query(
        "SELECT id, period FROM stipend_periods"
    )->get();
}

view('stipend/form.php', [
    'groups' => $groups,
    'students' => $students,
    'group_subjects' => $group_subjects,
    'periods' => $periods,
    'groupId' => $groupId
]);