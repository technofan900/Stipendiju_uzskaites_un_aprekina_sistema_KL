<?php

use Core\App;
use Core\Database;

try {
    $db = App::resolve(Database::class);
} catch (\Exception $e) {
    error_log("Neizdevās izveidot datubāzes savienojumu: " . $e->getMessage());
    die("Sistēma šobrīd nav pieejama. Lūdzu, mēģiniet vēlāk.");
}

// Functions for stipend calculation with try/catch inside if needed
function avg_grade(array $grades): float {
    try {
        $grade_count = count($grades);
        $grade_sum = 0;
        foreach ($grades as $grade) {
            $grade_sum += (int) $grade['grade'];
        }
        return round((float) $grade_sum / max(1, (float) $grade_count), 2);
    } catch (\Exception $e) {
        error_log("Kļūda aprēķinot vidējo atzīmi: " . $e->getMessage());
        return 0;
    }
}

function failing_grade(array $grades): int {
    try {
        $fail = 0;
        foreach ($grades as $grade) {
            if ($grade['category'] === 'P' && (int)$grade['grade'] < 5) {
                $fail++;
            } elseif ($grade['category'] === 'V' && (int)$grade['grade'] < 4) {
                $fail++;
            }
        }
        return $fail;
    } catch (\Exception $e) {
        error_log("Kļūda aprēķinot neveiksmīgās atzīmes: " . $e->getMessage());
        return 0;
    }
}

function base_stipend(float $avg_grade, int $failed_grades, int $absences): float {
    try {
        if ($absences >= 9 || $failed_grades >= 2) return 0;
        if ($absences >= 2 && $absences <= 8) return 15;
        if ($avg_grade >= 8.0 && $avg_grade <= 10.0) return 81;
        if ($avg_grade >= 6.0) return 41;
        if ($avg_grade >= 4.0) return 16;
        return 0;
    } catch (\Exception $e) {
        error_log("Kļūda aprēķinot bāzes stipendiju: " . $e->getMessage());
        return 0;
    }
}

function extra_stipend(float $extra): float {
    try {
        return round($extra / 6, 2);
    } catch (\Exception $e) {
        error_log("Kļūda aprēķinot papildu stipendiju: " . $e->getMessage());
        return 0;
    }
}

function total_stipend(float $base, float $extra): float {
    try {
        return $base > 0 ? round($base + $extra, 2) : 0;
    } catch (\Exception $e) {
        error_log("Kļūda aprēķinot kopējo stipendiju: " . $e->getMessage());
        return 0;
    }
}

// Handle batch POST for multiple students
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['students'])) {

    $period = $_POST['period'] ?? '';

    foreach ($_POST['students'] as $studentData) {
        try {
            $studentId = $studentData['student_id'];
            $groupId = $studentData['group_id'];
            $absences = (int) ($studentData['absences'] ?? 0);
            $grades = $studentData['grades'] ?? [];
            $extra = (float) ($studentData['extra_amount'] ?? 0);

            // Calculations
            $avg = avg_grade($grades);
            $fail = failing_grade($grades);
            $base = base_stipend($avg, $fail, $absences);
            $extraCalc = extra_stipend($extra);
            $total = total_stipend($base, $extraCalc);

            // Insert stipend record
            $sql = "INSERT INTO student_stipend_records
                    (student_id, group_id, period_id, average_grade, failed_subjects_count, absences, base_stipend, activity_bonus, total_stipend)
                    VALUES (:student_id, :group_id, :period_id, :average_grade, :failed_subjects_count, :absences, :base_stipend, :activity_bonus, :total_stipend)";

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

            // Insert grades for each subject
            foreach ($grades as $subjectId => $data) {
                try {
                    $db->query("INSERT INTO student_grades (stipend_record_id, subject_id, grade)
                                VALUES (:stipend_record_id, :subject_id, :grade)", [
                        'stipend_record_id' => $recordId,
                        'subject_id' => $subjectId,
                        'grade' => (int) $data['grade']
                    ]);
                } catch (\Exception $e) {
                    error_log("Kļūda saglabājot atzīmi priekš student_id {$studentId}: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            error_log("Kļūda apstrādājot student_id {$studentId}: " . $e->getMessage());
        }
    }

    header('Location: /');
    exit();
}

// Load view
try {
    view('stipend/form.php');
} catch (\Exception $e) {
    error_log("Kļūda ielādējot stipendiju formu: " . $e->getMessage());
    echo "Diemžēl formu ielādēt neizdevās.";
}