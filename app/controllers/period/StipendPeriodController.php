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

    $sql = "SELECT * FROM stipend_periods ORDER BY year DESC, id DESC";

    $periods = $db->query($sql)->get();

} catch (\Exception $e) {

    error_log("Kļūda ielādējot stipend_periods: " . $e->getMessage());

    $periods = [];
}

try {

    view('stipend_period/index.php', [
        'periods' => $periods
    ]);

} catch (\Exception $e) {

    error_log("Kļūda ielādējot skatu stipend_period/index.php: " . $e->getMessage());

    echo "Diemžēl periodu sarakstu ielādēt neizdevās.";

}