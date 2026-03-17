<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$sql = 'SELECT * FROM subjects';
$data = $db->query($sql)->get();

view('subjects/index.php', [
    'data' => $data
]);