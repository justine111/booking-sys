<?php
require_once __DIR__ . '/../../../../controller/host-controller.php';

$addHost = new host_controller();
$response = $addHost->addNewHost();

header('Content-Type: application/json');
echo json_encode($response);
exit;
