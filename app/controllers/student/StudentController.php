<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$sql = 'SELECT st.id, gr.group_name, st.full_name, st.personal_code, st.created_at
    FROM students st
    JOIN groups gr ON gr.id = st.group_id';
$data = $db->query($sql)->get();

view('students/index.php', [
    'data' => $data
]);