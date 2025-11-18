<?php
require_once __DIR__ . '/base-repository.php';

class booking_repository extends base_repository
{
  public function reservation($unitId, $name, $phoneno, $duration, $description)
  {
    $query = "INSERT INTO bookings (property_id, name, contact_no, duration, message, booking_status)
                VALUES (:unitid, :name, :phoneno, :duration, :description, 1)";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':unitid', $unitId);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':phoneno', $phoneno);
    $stmt->bindParam(':duration', $duration);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
  }

  public function getAllBookings()
  {
    $sql = "SELECT 
	              a.booking_id,
	              a.name,
	              a.contact_no,
	              a.duration,
                a.created_at,
	              c.description as status,
	              b.title,
	              b.property_id,
	              b.price_per_night,
	              d.name as host_name
              FROM bookings a
              LEFT JOIN properties b
              ON a.property_id = b.property_id
              LEFT JOIN booking_status c
              ON a.booking_status = c.id
              LEFT JOIN hosts d
              ON b.host_id = d.host_id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
