<?php
require_once __DIR__ . '/../../../controllers/members-controller.php';

$addMilestone = new member_controller();
$response = $addMilestone->addMilestone();

header('Content-Type: application/json');
echo json_encode($response);
exit;
