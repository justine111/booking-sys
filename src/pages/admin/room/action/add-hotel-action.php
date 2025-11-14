<?php
require_once __DIR__ . '/../../../../controller/room-controller.php';

$addHotel = new room_controller();
$response = $addHotel->addHotel();

header('Content-Type: application/json');
echo json_encode($response);
exit;
