<?php
session_start();
require_once __DIR__ . '/../../../../controller/room-controller.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['error' => true, 'message' => 'Invalid request method']);
  exit;
}

$propertyId = $_POST['property_id'] ?? null;

if (!$propertyId) {
  echo json_encode(['error' => true, 'message' => 'Property ID is required']);
  exit;
}

try {
  $roomController = new room_controller();

  // Update property to rejected status
  // status = 2 (cancelled/rejected), is_active = 1 (not active)
  $result = $roomController->rejectProperty($propertyId);

  echo json_encode([
    'error' => false,
    'message' => 'Property rejected successfully!',
    'data' => $result
  ]);
} catch (Exception $e) {
  echo json_encode([
    'error' => true,
    'message' => 'Error rejecting property: ' . $e->getMessage()
  ]);
}
