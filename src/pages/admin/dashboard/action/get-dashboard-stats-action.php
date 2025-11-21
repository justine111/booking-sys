<?php
require_once __DIR__ . '/../../../../controller/dashboard-controller.php';

$dashboardController = new dashboard_controller();
$response = $dashboardController->getDashboardStats();

header('Content-Type: application/json');
echo json_encode($response);
exit;
