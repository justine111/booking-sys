<?php
require_once __DIR__ . '/../../controller/room-controller.php';

$roomController = new room_controller();
$categoryId = $_GET['category'] ?? null;
$searchQuery = $_GET['search'] ?? null;
$pageSize = isset($_GET['pageSize']) && is_numeric($_GET['pageSize']) ? (int)$_GET['pageSize'] : 12;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $pageSize;

// Get count and hotels based on whether category filter is applied
if ($categoryId) {
    $count = $roomController->countHotelsByCategory($categoryId, $searchQuery);
    $rooms = $roomController->getHotelsByCategory($categoryId, $searchQuery, $pageSize, $offset);
} else {
    $count = $roomController->countHotels($searchQuery);
    $rooms = $roomController->getHotels($searchQuery, $pageSize, $offset);
}

$totalPages = ceil($count / $pageSize);

// Return JSON response with hotel data
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'hotels' => $rooms,
    'count' => $count,
    'page' => $page,
    'totalPages' => $totalPages,
    'pageSize' => $pageSize
]);
