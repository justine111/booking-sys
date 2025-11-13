<?php
require_once __DIR__ . '/../../../controller/auth-controller.php';

$LoginController = new auth_controller();
$response = $LoginController->login();

header('Content-Type: application/json');
echo json_encode($response);
exit;
