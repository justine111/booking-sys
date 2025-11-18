<?php
require_once __DIR__ . '/../../../connection/connection.php';

class ReservationAPI
{
  private $db;

  public function __construct()
  {
    $connection = new Connection_class();
    $this->db = $connection->getConnection();
  }

  public function processReservation()
  {
    header('Content-Type: application/json');

    $unitId = $_POST['unit'] ?? '';
    $name = $_POST['name'] ?? '';
    $phoneno = $_POST['phoneno'] ?? '';
    $duration = $_POST['stay-duration'] ?? '';
    $description = $_POST['description'] ?? '';

    $errors = [];

    if (empty($name)) $errors['name'] = '*Name is required';
    if (empty($phoneno)) $errors['phoneno'] = '*Contact no. is required';
    if (empty($duration)) $errors['stay-duration'] = '*Stay duration is required';

    if (!empty($errors)) {
      http_response_code(400);
      echo json_encode([
        'error' => true,
        'message' => 'Some fields are required.',
        'fields' => $errors
      ]);
      return;
    }

    try {
      $query = "INSERT INTO bookings (property_id, name, contact_no, duration, message, booking_status)
                      VALUES (:unitid, :name, :phoneno, :duration, :description, 1)";
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':unitid', $unitId);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':phoneno', $phoneno);
      $stmt->bindParam(':duration', $duration);
      $stmt->bindParam(':description', $description);
      $stmt->execute();

      echo json_encode([
        'error' => false,
        'message' => 'Reservation has been submitted successfully.',
        'booking_id' => $this->db->lastInsertId()
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'error' => true,
        'message' => 'Database error: ' . $e->getMessage()
      ]);
    }
  }
}

$api = new ReservationAPI();
$api->processReservation();
