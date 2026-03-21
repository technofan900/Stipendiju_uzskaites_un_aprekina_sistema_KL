<?php

use Core\App;
use Core\Database;
use Core\Validator;

$db = App::resolve(Database::class);

$errors = [];

$id = $_GET['id'] ?? null;

$sql = 'SELECT * FROM stipend_periods WHERE id = :id';
$period = $db->query($sql, [
    'id' => $id
])->findOrFail();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $year = $_POST['year'] ?? '';
    $period = $_POST['period'] ?? '';
    $p_group = $_POST['period_group'] ?? '';

    $sql = 'UPDATE stipend_periods SET year = :year, period = :period, period_group = :period_group WHERE id = :id';
    $db->query($sql, [
        'year' => $year,
        'period' => $period,
        'period_group' => $p_group,
        'id' => $id
    ]);
    header('Location: /period');
    exit;
}


view('stipend_period/edit.php', [
    'period' => $period
]);