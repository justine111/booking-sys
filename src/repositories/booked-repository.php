<?php
require_once __DIR__ . '/base-repository.php';

class booking_repository extends base_repository
{
  public function reservation($name, $phoneno, $duration, $description)
  {
      $query = "INSERT INTO bookings (name, phoneno, duration, description, status)
                VALUES (:name, :phoneno, :duration, :description, 'pending')";
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':phoneno', $phoneno);
      $stmt->bindParam(':duration', $duration);
      $stmt->bindParam(':description', $description);
      $stmt->execute();

  }
}