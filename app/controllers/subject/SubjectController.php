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
    // Iegūstam priekšmetus
    $sql = 'SELECT * FROM subjects';
    $data = $db->query($sql)->get();
} catch (\Exception $e) {
    error_log("Kļūda ielādējot priekšmetus: " . $e->getMessage());
    $data = [];
}

try {
    // Iegūstam studentu atzīmes
    $sql2 = 'SELECT sg.id, st.full_name AS student_name, su.subject_name, sg.grade
             FROM student_grades sg
             JOIN student_stipend_records sr ON sg.stipend_record_id = sr.id
             JOIN students st ON sr.student_id = st.id
             JOIN subjects su ON sg.subject_id = su.id';
    $grades = $db->query($sql2)->get();
} catch (\Exception $e) {
    error_log("Kļūda ielādējot studentu atzīmes: " . $e->getMessage());
    $grades = [];
}

try {
    view('subjects/index.php', [
        'data' => $data,
        'grades' => $grades
    ]);
} catch (\Exception $e) {
    error_log("Kļūda ielādējot skatu subjects/index.php: " . $e->getMessage());
    echo "Diemžēl priekšmetu un atzīmju skatu ielādēt neizdevās.";
}