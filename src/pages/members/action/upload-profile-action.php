<?php
require_once __DIR__ . '/../../../controllers/members-controller.php';

$uploadProfile = new member_controller();
$response = $uploadProfile->uploadProfile();

header('Content-Type: application/json');
echo json_encode($response);
exit;
