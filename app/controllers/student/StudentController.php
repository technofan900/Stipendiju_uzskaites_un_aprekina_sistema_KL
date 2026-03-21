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
    // Iegūstam studentus ar grupām
    $sql = 'SELECT st.id, gr.group_name, st.full_name, st.personal_code, st.created_at
            FROM students st
            JOIN groups gr ON gr.id = st.group_id';
    $data = $db->query($sql)->get();
} catch (\Exception $e) {
    error_log("Kļūda ielādējot studentus: " . $e->getMessage());
    $data = [];
}

try {
    view('students/index.php', [
        'data' => $data
    ]);
} catch (\Exception $e) {
    error_log("Kļūda ielādējot skatu students/index.php: " . $e->getMessage());
    echo "Diemžēl studentu sarakstu ielādēt neizdevās.";
}