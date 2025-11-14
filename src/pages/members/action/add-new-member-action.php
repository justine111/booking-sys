<?php
require_once __DIR__ . '/../../../controllers/members-controller.php';

$addNewMember = new member_controller();
$response = $addNewMember->addNewMember();

header('Content-Type: application/json');
echo json_encode($response);
exit;
