<?php

use Core\App;
use Core\Database;

try {
    $db = App::resolve(Database::class);
} catch (\Exception $e) {
    error_log("Neizdevās izveidot datubāzes savienojumu: " . $e->getMessage());
    die("Sistēma šobrīd nav pieejama. Lūdzu, mēģiniet vēlāk.");
}

// Get filter parameters from GET request
$filters = [
    'period_id'    => $_GET['period_id'] ?? null,
    'year'         => $_GET['year'] ?? null,
    'group_id'     => $_GET['group_id'] ?? null,
    'student_name' => $_GET['student_name'] ?? null,
];

try {
    // Fetch filter options
    $periods = $db->query("SELECT * FROM stipend_periods ORDER BY year ASC, period ASC")->get();
} catch (\Exception $e) {
    error_log("Kļūda ielādējot stipendiju periodus: " . $e->getMessage());
    $periods = [];
}

try {
    $groups  = $db->query("SELECT * FROM groups ORDER BY group_name ASC")->get();
} catch (\Exception $e) {
    error_log("Kļūda ielādējot grupas: " . $e->getMessage());
    $groups = [];
}

try {
    // Fetch distinct years manually
    $yearsRaw = $db->query("SELECT DISTINCT year FROM stipend_periods ORDER BY year DESC")->get();
    $years = array_map(fn($row) => $row['year'], $yearsRaw);
} catch (\Exception $e) {
    error_log("Kļūda ielādējot stipendiju gadus: " . $e->getMessage());
    $years = [];
}

// Build WHERE conditions dynamically
$where = [];
$params = [];

if ($filters['period_id']) {
    $where[] = 's.period_id = :period_id';
    $params['period_id'] = $filters['period_id'];
}

if ($filters['year']) {
    $where[] = 'sp.year = :year';
    $params['year'] = $filters['year'];
}

if ($filters['group_id']) {
    $where[] = 'g.id = :group_id';
    $params['group_id'] = $filters['group_id'];
}

if ($filters['student_name']) {
    $where[] = 'st.full_name LIKE :student_name';
    $params['student_name'] = '%' . $filters['student_name'] . '%';
}

$whereSql = '';
if (!empty($where)) {
    $whereSql = 'WHERE ' . implode(' AND ', $where);
}

try {
    // Fetch search results
    $sql = "
        SELECT 
            st.full_name AS student_name,
            g.group_name,
            sp.year,
            sp.period,
            s.average_grade,
            s.absences,
            s.failed_subjects_count,
            s.base_stipend,
            s.activity_bonus,
            s.total_stipend
        FROM student_stipend_records s
        JOIN students st ON st.id = s.student_id
        JOIN groups g ON g.id = s.group_id
        JOIN stipend_periods sp ON sp.id = s.period_id
        $whereSql
        ORDER BY g.group_name ASC, st.full_name ASC
    ";

    $records = $db->query($sql, $params)->get();
} catch (\Exception $e) {
    error_log("Kļūda ielādējot stipendiju ierakstus: " . $e->getMessage());
    $records = [];
}

try {
    // Render the search results view
    view('search/results.php', [
        'filters' => $filters,
        'periods' => $periods,
        'groups'  => $groups,
        'years'   => $years,
        'records' => $records
    ]);
} catch (\Exception $e) {
    error_log("Kļūda ielādējot skatu search/results.php: " . $e->getMessage());
    echo "Diemžēl meklēšanas rezultātus ielādēt neizdevās.";
}