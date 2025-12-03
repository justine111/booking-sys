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

  // Update property to approved status
  // status = 5 (available), is_active = 0 (approved/active)
  $result = $roomController->approveProperty($propertyId);

  echo json_encode([
    'error' => false,
    'message' => 'Property approved successfully!',
    'data' => $result
  ]);
} catch (Exception $e) {
  echo json_encode([
    'error' => true,
    'message' => 'Error approving property: ' . $e->getMessage()
  ]);
}
