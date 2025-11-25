<?php
require_once __DIR__ . '/../../../../controller/room-controller.php';

$updateHotel = new room_controller();
$response = $updateHotel->updateHotel();

header('Content-Type: application/json');
echo json_encode($response);
exit;
