<?php

use Core\App;
use Core\Database;

try {
    $db = App::resolve(Database::class);
} catch (\Exception $e) {
    error_log("Neizdevās izveidot datubāzes savienojumu: " . $e->getMessage());
    die("Sistēma šobrīd nav pieejama. Lūdzu, mēģiniet vēlāk.");
}

try {
    // Iegūstam grupas
    $sql = 'SELECT * FROM groups';
    $data = $db->query($sql)->get();
} catch (\Exception $e) {
    error_log("Kļūda ielādējot grupas: " . $e->getMessage());
    $data = [];
}

try {
    // Iegūstam grupu priekšmetus
    $subjectsql = 'SELECT gs.id, gr.group_name, su.subject_name
                   FROM group_subjects gs
                   JOIN groups gr ON gs.group_id = gr.id
                   JOIN subjects su ON gs.subject_id = su.id;';
    $subjectdata = $db->query($subjectsql)->get();
} catch (\Exception $e) {
    error_log("Kļūda ielādējot grupu priekšmetus: " . $e->getMessage());
    $subjectdata = [];
}

try {
    view('groups/index.php', [
        'data' => $data,
        'group_subjects' => $subjectdata
    ]);
} catch (\Exception $e) {
    error_log("Kļūda ielādējot skatu groups/index.php: " . $e->getMessage());
    echo "Diemžēl grupu sarakstu ielādēt neizdevās.";
}