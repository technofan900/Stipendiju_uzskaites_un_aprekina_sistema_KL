<?php

use Core\App;
use Core\Database;

try {
    // Mēģinām iegūt datubāzes savienojumu
    $db = App::resolve(Database::class);

    try {
        // Sagatavojam SQL vaicājumu
        $sql = 'SELECT ssr.id, st.full_name, gr.group_name, sp.period_group, 
                       ssr.average_grade, ssr.failed_subjects_count, 
                       ssr.absences, ssr.base_stipend, ssr.activity_bonus, 
                       ssr.total_stipend, ssr.created_at
                FROM student_stipend_records ssr
                JOIN students st ON st.id = ssr.student_id
                JOIN groups gr ON gr.id = st.group_id
                JOIN stipend_periods sp ON sp.id = ssr.period_id;';

        // Izpildām vaicājumu un iegūstam rezultātu
        $data = $db->query($sql)->get();
    } catch (\Exception $e) {
        // SQL vaicājuma kļūdas apstrāde
        error_log("Datubāzes vaicājuma kļūda: " . $e->getMessage());
        $data = []; // Tukšs masīvs, lai sistēma nepārtrauktos
        $errorMessage = "Diemžēl notika kļūda, mēģinot iegūt stipendiju datus.";
    }

    // Skata ielāde ar datiem vai kļūdas paziņojumu
    view('home/index.php', [
        'data' => $data,
        'errorMessage' => $errorMessage ?? null
    ]);

} catch (\Exception $e) {
    // Vispārēja kļūda, piemēram, savienojuma ar datubāzi neizdevās izveidot
    error_log("Sistēmas kļūda: " . $e->getMessage());
    
    // Lietotājam saprotams paziņojums
    view('home/index.php', [
        'data' => [],
        'errorMessage' => "Sistēma šobrīd nav pieejama. Lūdzu, mēģiniet vēlāk."
    ]);
}