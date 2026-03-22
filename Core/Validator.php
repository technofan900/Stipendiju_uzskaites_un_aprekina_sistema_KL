<?php
namespace Core;

class Validator
{
    public static function string($value, $min = 1, $max = INF)
    {
        $value = trim($value);
        return strlen($value) >= $min && strlen($value) <= $max;
    }

    public static function email ($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function personalCode($code)
    {
        return preg_match('/^\d{6}-\d{5}$/', $code);
    }

    public static function uniquePersonalCode($code, Database $db, $ignoreId = null)
    {
        $sql = "SELECT COUNT(*) as count 
                FROM students 
                WHERE personal_code = :code";

        $params = [
            'code' => $code
        ];

        if ($ignoreId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $ignoreId;
        }

        $result = $db->query($sql, $params)->find();

        return $result['count'] == 0;
    }

    public static function uniqueSubjectName($string, $category, Database $db) {
        $sql = "SELECT COUNT(*) as count FROM subjects WHERE subject_name = :subject_name AND category_type = :category_type";

        $result = $db->query($sql, [
            'subject_name' => $string,
            'category_type' => $category
        ])->find();

        return $result['count'] == 0;
    }

    public static function uniqueGroupName($string, Database $db) {
        $sql = "SELECT COUNT(*) as count FROM groups WHERE group_name = :group_name";

        $result = $db->query($sql, [
            'group_name' => $string
        ])->find();

        return $result["count"] == 0;
    }

    public static function subjectAlreadyInGroup($subjectId, $groupId, Database $db)
    {
        $sql = "SELECT 1
                FROM group_subjects
                WHERE subject_id = :subject_id
                AND group_id = :group_id
                LIMIT 1";

        $result = $db->query($sql, [
            'subject_id' => $subjectId,
            'group_id' => $groupId
        ])->find();

        return !$result; // true if NOT exists
    }

    public static function uniqueStudentName($string, Database $db) {
        $sql = "SELECT COUNT(*) as count FROM students WHERE full_name = :full_name";

        $result = $db->query($sql, [
            'full_name' => $string
        ])->find();

        return $result["count"] == 0;        
    }

    public static function validateStipend(array $students, $period): array
    {
        $errors = [];

        // period check
        if (!isset($period) || $period === '') {
            $errors['period'] = "Izvēlies periodu";
        }

        // students check
        if (empty($students)) {
            $errors['students'] = "Nav studentu";
            return $errors;
        }

        foreach ($students as $sid => $student) {

            // absences
            if (!isset($student['absences']) || !is_numeric($student['absences'])) {
                $errors["absences_$sid"] = "Nepareizi kavējumi";
            } elseif ($student['absences'] < 0) {
                $errors["absences_$sid"] = "Kavējumi nevar būt negatīvi";
            }

            // extra
            if (!isset($student['extra_amount']) || !is_numeric($student['extra_amount'])) {
                $errors["extra_$sid"] = "Nepareiza summa";
            } elseif ($student['extra_amount'] < 0) {
                $errors["extra_$sid"] = "Summa nevar būt negatīva";
            }

            // grades
            if (!empty($student['grades'])) {

                foreach ($student['grades'] as $subjectId => $gradeData) {

                    if (!isset($gradeData['grade']) || !is_numeric($gradeData['grade'])) {
                        $errors["grade_{$sid}_{$subjectId}"] = "Nepareiza atzīme";
                        continue;
                    }

                    $grade = (float)$gradeData['grade'];

                    if ($grade < 0 || $grade > 10) {
                        $errors["grade_{$sid}_{$subjectId}"] = "Atzīmei jābūt 0–10";
                    }

                    if (!isset($gradeData['category'])) {
                        $errors["category_{$sid}_{$subjectId}"] = "Nav kategorijas";
                    }

                }

            }

        }

        return $errors;
    }
}
