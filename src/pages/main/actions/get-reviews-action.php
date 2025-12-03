<?php
session_start();
require_once __DIR__ . '/../../../controller/review-controller.php';

$reviewController = new ReviewController();
$propertyId = $_GET['property_id'] ?? null;
$limit = $_GET['limit'] ?? 10;
$offset = $_GET['offset'] ?? 0;

if (!$propertyId) {
  echo json_encode([
    'error' => true,
    'message' => 'Property ID is required'
  ]);
  exit;
}

$response = $reviewController->getReviews($propertyId, $limit, $offset);

header('Content-Type: application/json');
echo json_encode($response);
