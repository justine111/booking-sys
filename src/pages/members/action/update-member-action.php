<?php
require_once __DIR__ . '/../../../controllers/members-controller.php';

$updateMember = new member_controller();
$response = $updateMember->updateMember();

header('Content-Type: application/json');
echo json_encode($response);
exit;
