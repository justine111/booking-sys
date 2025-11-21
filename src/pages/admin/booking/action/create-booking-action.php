<?php
require_once __DIR__ . '/../../../../controller/booked-controller.php';

$createBooking = new booking_controller();
$response = $createBooking->caterBooking();

header('Content-Type: application/json');
echo json_encode($response);
exit;
