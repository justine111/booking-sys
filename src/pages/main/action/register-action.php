<?php
require_once __DIR__ . '/../../../controller/auth-controller.php';

$authController = new auth_controller();
$response = $authController->register();

header('Content-Type: application/json');
echo json_encode($response);
exit;
