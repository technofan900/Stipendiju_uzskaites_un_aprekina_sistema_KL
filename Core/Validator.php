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

    // public static function stipendForm(array $data, array $groupSubjects = []): array
    // {
    //     $errors = [];

    //     // Required select inputs
    //     if (empty($data['group_id'])) {
    //         $errors['group_id'] = "Izvēlies grupu";
    //     }

    //     if (empty($data['student_id'])) {
    //         $errors['student_id'] = "Izvēlies skolēnu";
    //     }

    //     if (empty($data['period'])) {
    //         $errors['period'] = "Izvēlies stipendijas periodu";
    //     }

    //     // Validate grades
    //     foreach ($groupSubjects as $subject) {
    //         $subjectId = $subject['id'];
    //         if (!isset($data['grades'][$subjectId]['grade']) || $data['grades'][$subjectId]['grade'] === '') {
    //             $errors['grades'][$subjectId] = "Ievadi atzīmi priekšmetam: {$subject['subject_name']}";
    //         }
    //     }

    //     return $errors;
    // }
}
