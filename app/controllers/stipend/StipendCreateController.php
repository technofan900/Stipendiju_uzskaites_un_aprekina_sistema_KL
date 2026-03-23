<?php

use Core\App;
use Core\Database;
use Core\Validator;

try {
    $db = App::resolve(Database::class);
} catch (\Exception $e) {
    error_log("Neizdevās izveidot datubāzes savienojumu: " . $e->getMessage());
    die("Sistēma šobrīd nav pieejama.");
}

$errors = [];

function avg_grade(array $grades): float {
    try {
        $grade_count = count($grades);
        $grade_sum = 0;

        foreach ($grades as $grade) {
            $grade_sum += (int)$grade['grade'];
        }

        return round($grade_sum / max(1, $grade_count), 2);

    } catch (\Exception $e) {
        error_log($e->getMessage());
        return 0;
    }
}

function failing_grade(array $grades): int {

    $fail = 0;

    foreach ($grades as $grade) {

        if ($grade['category'] === 'P' && (int)$grade['grade'] < 5) {
            $fail++;
        }

        if ($grade['category'] === 'V' && (int)$grade['grade'] < 4) {
            $fail++;
        }

    }

    return $fail;
}

function base_stipend(float $avg, int $fail, int $abs): float {

    if ($abs >= 9 || $fail >= 2) return 0;

    if ($abs >= 2 && $abs <= 8) return 15;

    if ($avg >= 8) return 81;
    if ($avg >= 6) return 41;
    if ($avg >= 4) return 16;

    return 0;
}

function extra_stipend(float $extra): float {
    return round($extra / 6, 2);
}

function total_stipend(float $base, float $extra): float {
    return $base > 0 ? round($base + $extra, 2) : 0;
}


// =========================
// POST
// =========================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $period = $_POST['period'] ?? '';
    $students = $_POST['students'] ?? [];

    $errors = Validator::validateStipend($students, $period);

    if (!empty($errors)) {

        $groupId = $_POST['students'][array_key_first($_POST['students'])]['group_id'] ?? null;

        $groups = $db->query("SELECT * FROM groups")->get();

        $periods = $db->query("SELECT * FROM stipend_periods")->get();

        $studentsList = [];
        $subjects = [];

        if ($groupId) {

            $studentsList = $db->query(
                "SELECT * FROM students WHERE group_id = :id",
                ['id' => $groupId]
            )->get();

            $subjects = $db->query(
                "SELECT s.id, s.subject_name, s.category_type
                FROM group_subjects gs
                JOIN subjects s ON s.id = gs.subject_id
                WHERE gs.group_id = :id",
                ['id' => $groupId]
            )->get();
        }

        view('stipend/form.php', [

            'errors' => $errors,
            'groupId' => $groupId,
            'groups' => $groups,
            'periods' => $periods,
            'students' => $studentsList,
            'group_subjects' => $subjects,
            'old' => $_POST['students'],
            'old_period' => $_POST['period'] ?? null

        ]);

        return;
    }

    foreach ($students as $studentData) {

        try {

            $studentId = $studentData['student_id'];
            $groupId = $studentData['group_id'];
            $absences = (int)$studentData['absences'];
            $grades = $studentData['grades'];
            $extra = (float)$studentData['extra_amount'];

            $avg = avg_grade($grades);
            $fail = failing_grade($grades);
            $base = base_stipend($avg, $fail, $absences);
            $extraCalc = extra_stipend($extra);
            $total = total_stipend($base, $extraCalc);


            $sql = "INSERT INTO student_stipend_records
            (student_id, group_id, period_id, average_grade,
             failed_subjects_count, absences,
             base_stipend, activity_bonus, total_stipend)

            VALUES
            (:student_id, :group_id, :period_id,
             :average_grade, :failed_subjects_count,
             :absences, :base_stipend,
             :activity_bonus, :total_stipend)";


            $db->query($sql, [

                'student_id' => $studentId,
                'group_id' => $groupId,
                'period_id' => $period,

                'average_grade' => $avg,
                'failed_subjects_count' => $fail,
                'absences' => $absences,

                'base_stipend' => $base,
                'activity_bonus' => $extraCalc,
                'total_stipend' => $total

            ]);


            $recordId = $db->lastInsertId();


            foreach ($grades as $subjectId => $data) {

                $db->query(
                    "INSERT INTO student_grades
                    (stipend_record_id, subject_id, grade)

                    VALUES
                    (:record, :subject, :grade)",

                    [
                        'record' => $recordId,
                        'subject' => $subjectId,
                        'grade' => (int)$data['grade']
                    ]
                );
            }


        } catch (\Exception $e) {

            error_log(
                "Kļūda studentam {$studentId}: " .
                $e->getMessage()
            );

        }

    }

    header('Location: /');
    exit();
}

view('stipend/form.php', [
    'errors' => $errors
]);