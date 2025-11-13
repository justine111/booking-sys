<?php
require_once __DIR__ . '/../../controllers/auth-controller.php';

$LoginController = new auth_controller();
$response = $LoginController->loginAuth();

header('Content-Type: application/json');
echo json_encode($response);
exit;
