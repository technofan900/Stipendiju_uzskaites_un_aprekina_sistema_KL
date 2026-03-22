<?php
//home
$router->get('/', 'HomeController.php');

// For deleting
$router->delete('/delete', 'DeleteController.php');

//subjects
$router->get('/subjects', 'subject/SubjectController.php');
$router->get('/subjects/create', 'subject/SubjectCreateController.php');
$router->post('/subjects/create', 'subject/SubjectCreateController.php');
$router->get('/subject/edit', 'subject/SubjectEditController.php');
$router->post('/subject/edit', 'subject/SubjectEditController.php');


//groups
$router->get('/groups', 'group/GroupController.php');
$router->get('/groups/create', 'group/GroupCreateController.php');
$router->post('/groups/create', 'group/GroupCreateController.php');
$router->get('/group/edit', 'group/GroupEditController.php');
$router->post('/group/edit', 'group/GroupEditController.php');

$router->get('/groups/bindSubject', 'group/GroupSubjectController.php');
$router->post('/groups/bindSubject', 'group/GroupSubjectController.php');

//student
$router->get('/students', 'student/studentController.php');
$router->get('/students/create', 'student/studentCreateController.php');
$router->post('/students/create', 'student/studentCreateController.php');
$router->get('/student/edit', 'student/StudentEditController.php');
$router->post('/student/edit', 'student/StudentEditController.php');

//stipend
$router->get('/stipend/form', 'stipend/StipendController.php');
$router->post('/stipend/form/create', 'stipend/StipendCreateController.php');

//stipend period
$router->get('/period', 'period/StipendPeriodController.php');
$router->get('/period/create', 'period/SPeriodCreateController.php');
$router->post('/period/create', 'period/SPeriodCreateController.php');
$router->get('/period/edit', 'period/PeriodEditController.php');
$router->post('/period/edit', 'period/PeriodEditController.php');

//search
$router->get('/search', 'SearchController.php');