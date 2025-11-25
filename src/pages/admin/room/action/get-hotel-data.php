<?php
require_once __DIR__ . '/../../../../controller/room-controller.php';

$propertyId = $_GET['id'] ?? null;

if (empty($propertyId)) {
  echo json_encode(['error' => true, 'message' => 'Property ID is required']);
  exit;
}

$roomController = new room_controller();
$response = $roomController->getHotelById($propertyId);

header('Content-Type: application/json');
echo json_encode($response);
exit;
