<?php
session_start();
require_once __DIR__ . '/../../../controller/review-controller.php';

$reviewController = new ReviewController();
$response = $reviewController->submitReview();

header('Content-Type: application/json');
echo json_encode($response);
