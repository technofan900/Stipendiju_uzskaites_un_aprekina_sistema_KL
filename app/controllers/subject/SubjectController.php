<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$sql = 'SELECT * FROM subjects';
$data = $db->query($sql)->get();

$sql2 = 'SELECT sg.id, st.full_name AS student_name, su.subject_name, sg.grade
FROM student_grades sg
JOIN student_stipend_records sr ON sg.stipend_record_id = sr.id
JOIN students st ON sr.student_id = st.id
JOIN subjects su ON sg.subject_id = su.id';
$grades = $db->query($sql2)->get();

view('subjects/index.php', [
    'data' => $data,
    'grades' => $grades
]);