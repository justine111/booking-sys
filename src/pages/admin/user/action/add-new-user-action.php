<?php
require_once __DIR__ . '/../../../../controller/user-controller.php';

$addUser = new user_controller();
$response = $addUser->addNewUser();

header('Content-Type: application/json');
echo json_encode($response);
exit;
