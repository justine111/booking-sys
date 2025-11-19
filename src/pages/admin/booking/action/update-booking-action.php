<?php
require_once __DIR__ . '/../../../../controller/booked-controller.php';

$updateBooking = new booking_controller();
$response = $updateBooking->updateBooking();

header('Content-Type: application/json');
echo json_encode($response);
exit;
