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
    // groups
    $groups = $db->query(
        "SELECT id, group_name FROM groups"
    )->get();
} catch (\Exception $e) {
    error_log("Kļūda ielādējot grupas: " . $e->getMessage());
    $groups = [];
}

$groupId = $_GET['group_id'] ?? null;

$students = [];
$group_subjects = [];
$periods = [];

if ($groupId) {
    try {
        // students
        $students = $db->query(
            "SELECT id, full_name FROM students WHERE group_id = :group_id",
            ['group_id' => $groupId]
        )->get();
    } catch (\Exception $e) {
        error_log("Kļūda ielādējot studentus priekš group_id {$groupId}: " . $e->getMessage());
        $students = [];
    }

    try {
        // subjects
        $group_subjects = $db->query(
            "SELECT su.id, su.subject_name, su.category_type
             FROM group_subjects gs
             JOIN subjects su ON su.id = gs.subject_id
             WHERE gs.group_id = :group_id",
            ['group_id' => $groupId]
        )->get();
    } catch (\Exception $e) {
        error_log("Kļūda ielādējot priekšmetus priekš group_id {$groupId}: " . $e->getMessage());
        $group_subjects = [];
    }

    try {
        // periods
        $periods = $db->query(
            "SELECT id, period FROM stipend_periods"
        )->get();
    } catch (\Exception $e) {
        error_log("Kļūda ielādējot stipendiju periodus: " . $e->getMessage());
        $periods = [];
    }
}

try {
    view('stipend/form.php', [
        'groups' => $groups,
        'students' => $students,
        'group_subjects' => $group_subjects,
        'periods' => $periods,
        'groupId' => $groupId
    ]);
} catch (\Exception $e) {
    error_log("Kļūda ielādējot stipendiju formu: " . $e->getMessage());
    echo "Diemžēl formu ielādēt neizdevās.";
}