<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$sql = 'SELECT ssr.id, st.full_name, gr.group_name, sp.period_group, ssr.average_grade, ssr.failed_subjects_count, 
        ssr.absences, ssr.base_stipend, ssr.activity_bonus, ssr.total_stipend, ssr.created_at
        FROM student_stipend_records ssr
        JOIN students st ON st.id = ssr.student_id
        JOIN groups gr ON gr.id = st.group_id
        JOIN stipend_periods sp ON sp.id = ssr.period_id;';
$data = $db->query($sql)->get();

view('home/index.php',[
    'data' => $data
]);