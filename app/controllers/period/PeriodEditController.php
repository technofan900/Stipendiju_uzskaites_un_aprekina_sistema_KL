<?php

use Core\App;
use Core\Database;
use Core\Validator;

$db = App::resolve(Database::class);

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    die('Nederīgs ID');
}

$period = $db->query('SELECT * FROM stipend_periods WHERE id = :id', [
    'id' => $id
])->findOrFail();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $year = $_POST['year'] ?? '';
    $periodInput = trim($_POST['period'] ?? '');
    $p_group = trim($_POST['period_group'] ?? '');

    if ($periodInput === '') {
        $errors['period'] = 'Ievadiet periodu';
    }

    if ($p_group === '') {
        $errors['period_group'] = 'Ievadiet perioda grupu';
    }

    if (empty($errors)) {
        $db->query(
            'UPDATE stipend_periods 
             SET year = :year, period = :period, period_group = :period_group 
             WHERE id = :id',
            [
                'year' => $year,
                'period' => $periodInput,
                'period_group' => $p_group,
                'id' => $id
            ]
        );

        header('Location: /period');
        exit;
    }
}

view('stipend_period/edit.php', [
    'period' => $period,
    'errors' => $errors
]);