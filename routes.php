<?php
//home
$router->get('/', 'HomeController.php');

//subjects
$router->get('/subjects', 'subject/SubjectController.php');
$router->get('/subjects/create', 'subject/SubjectCreateController.php');
$router->post('/subjects/create', 'subject/SubjectCreateController.php');

//groups
$router->get('/groups', 'group/GroupController.php');
$router->get('/groups/create', 'group/GroupCreateController.php');
$router->post('/groups/create', 'group/GroupCreateController.php');

//student
$router->get('/students', 'student/studentController.php');
$router->get('/students/create', 'student/studentCreateController.php');
$router->post('/students/create', 'student/studentCreateController.php');

//stipend
$router->get('/stipend/form', 'stipend/StipendController.php');
$router->post('/stipend/form/create', 'stipend/StipendCreateController.php');