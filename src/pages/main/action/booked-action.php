<?php
require_once __DIR__ . '/../../../controller/booked-controller.php';

$booking = new booking_controller();
$response = $booking->reservation();

header('Content-Type: application/json');
echo json_encode($response);
exit;
