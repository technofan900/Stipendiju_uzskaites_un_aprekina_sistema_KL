<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

function avg_grade(array $grades): float {
    $grade_count = count($grades);
    $grade_sum = 0;
    foreach($grades as $grade) {
        $grade_sum += (int) $grade['grade'];
    }
    return round((float) $grade_sum / (float) $grade_count,2);
}
    
function failing_grade(array $grades): int {
    $fail = 0;
    foreach($grades as $grade) {
        if ($grade['category'] === 'P') {
            if((int) $grade['grade'] < 5) {
                $fail++;
            }
        } else {
            if ($grade['category'] === 'V') {
                if((int) $grade['grade'] < 4) {
                    $fail++;
                }
            }
        }
    }
    return $fail;
}

function base_stipend(float $avg_grade, int $failed_grades, int $absences): float {
    $base = 0;
    if ($absences >= 2 and $absences <= 8) {
        $base = 15;
    } elseif ($absences > 8) {
        $base = 0;
    } elseif ($failed_grades >= 2) {
        $base = 0;
    }

    if ($avg_grade >= 0 and $avg_grade < 4.0) {
        $base = 0;
    } elseif ($avg_grade >= 4.0 and $avg_grade < 6.0) {
        $base = 16;
    } elseif ($avg_grade >= 6.0 and $avg_grade < 8.0) {
        $base = 41;
    } elseif ($avg_grade >= 8.0 and $avg_grade <= 10) {
        $base = 81;
    }
    return $base;
}

function extra_stipend(string $extra): float {
    return round((float) $extra / 6, 2) ;
}

function total_stipend(float $base, float $extra): float {
    return round($base + $extra, 2);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $student = $_POST['student_id'] ?? '';
    $groupId = $_POST['group_id'] ?? null;
    $period = $_POST['period'] ?? '';

    //used for other values
    $grades = $_POST['grades'] ?? '';

    $avg_grade = avg_grade($grades) ?? '';
    $fail_grades = failing_grade($grades) ?? '';
    $absences = $_POST['absences'] ?? '';
    $base_stipend = base_stipend($avg_grade, $fail_grades, $absences);
    $extra_stipend = extra_stipend($_POST['extra_amount']) ?? '';
    $total_stipend =  total_stipend($base_stipend, $extra_stipend) ?? '';

    // echo '<pre>';
    // var_dump($total_stipend);
    // var_dump($avg_grade);
    // var_dump(base_stipend($avg_grade, $fail_grades, $absences));
    // echo '</pre>';


    $sql = "INSERT INTO student_stipend_records 
    (student_id, group_id, period_id, average_grade, failed_subjects_count, absences, base_stipend, activity_bonus, total_stipend) 
    VALUES (:student_id, :group_id, :period_id, :average_grade, :failed_subjects_count, :absences, :base_stipend, :activity_bonus, :total_stipend)";
    $db->query($sql, [
        'student_id' => $student,
        'group_id' => $groupId,
        'period_id' => $period,
        'average_grade' => $avg_grade,
        'failed_subjects_count' => $fail_grades,
        'absences' => $absences,
        'base_stipend' => $base_stipend,
        'activity_bonus' => $extra_stipend,
        'total_stipend' => $total_stipend
    ]);
    header('Location: /');
}

view('stipend/form.php');