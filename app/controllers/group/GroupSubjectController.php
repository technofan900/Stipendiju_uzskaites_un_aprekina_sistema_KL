<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);


// get subjects
$subjects = $db->query(
    "SELECT id, subject_name FROM subjects"
)->get();


// get groups
$groups = $db->query(
    "SELECT id, group_name FROM groups"
)->get();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $subjectId = $_POST['subject_id'] ?? null;
    $groupIds = $_POST['groups'] ?? [];

    if ($subjectId && !empty($groupIds)) {

        foreach ($groupIds as $groupId) {

            $db->query(
                "INSERT INTO group_subjects (group_id, subject_id)
                 VALUES (:group_id, :subject_id)", [
                    'group_id' => $groupId,
                    'subject_id' => $subjectId
                ]);
        }
    }
    header('Location: /groups');
    exit();
}


view('groups/bindSubject.php', [
    'subjects' => $subjects,
    'groups' => $groups
]);