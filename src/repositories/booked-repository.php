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

  public function countAllBookings($searchQuery)
  {
    $sql = "SELECT 
	            count(a.booking_id)as total
            FROM bookings a
            LEFT JOIN properties b
            ON a.property_id = b.property_id
            LEFT JOIN booking_status c
            ON a.booking_status = c.id";

    if (!empty($searchQuery)) {
      $sql .= " AND b.title LIKE :searchQuery
          OR a.name LIKE :searchQuery";
    }
    $stmt = $this->db->prepare($sql);
    if (!empty($searchQuery)) {
      $searchQuery = "%$searchQuery%";
      $stmt->bindParam(':searchQuery', $searchQuery);
    }
    $stmt->execute();

    return $stmt->fetchColumn();
  }

  public function getAllBookings($searchQuery = null, $limit, $offset)
  {
    $sql = "SELECT 
	            a.booking_id,
              b.title,
	            a.name as client_name,
	            a.contact_no,
 	            a.duration,
	            a.message,
	            c.description as status,
	            a.created_at
            FROM bookings a
            LEFT JOIN properties b
            ON a.property_id = b.property_id
            LEFT JOIN booking_status c
            ON a.booking_status = c.id
            WHERE a.booking_id IS NOT NULL";

    if (!empty($searchQuery)) {
      $sql .= " AND b.title LIKE :searchQuery
        OR a.name LIKE :searchQuery";
    }
    $sql .= " ORDER BY a.booking_id DESC LIMIT :limit OFFSET :offset";
    $stmt = $this->db->prepare($sql);

    if (!empty($searchQuery)) {
      $searchQuery = "%$searchQuery%";
      $stmt->bindParam(':searchQuery', $searchQuery);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
