<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$sql = 'SELECT * FROM groups';
$data = $db->query($sql)->get();

$subjectsql = 'SELECT gs.id, gr.group_name, su.subject_name
            FROM group_subjects gs
            JOIN groups gr ON gs.group_id = gr.id
            JOIN subjects su ON gs.subject_id = su.id;';
$subjectdata = $db->query($subjectsql)->get();


view('groups/index.php', [
    'data' => $data,
    'group_subjects' => $subjectdata
]);