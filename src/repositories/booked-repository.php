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
            ON a.booking_status = c.id
            WHERE a.booking_id IS NOT NULL
            AND a.booking_status IN (1, 6)";

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
              a.property_id,
              b.title,
	            a.name as client_name,
	            a.contact_no,
 	            a.duration,
	            a.message,
	            c.description as status,
              a.check_in_date,
              a.check_out_date,
              a.total_amount,
	            a.created_at
            FROM bookings a
            LEFT JOIN properties b
            ON a.property_id = b.property_id
            LEFT JOIN booking_status c
            ON a.booking_status = c.id
            WHERE a.booking_id IS NOT NULL
            AND a.booking_status IN (1, 6)";

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

  public function updateBooking($bookingId, $propertyId, $status, $payment, $checkInDate, $checkOutDate)
  {
    $sql = "UPDATE bookings SET booking_status = :status, total_amount = :payment, check_in_date = :checkInDate, check_out_date = :checkOutDate WHERE booking_id = :bookingId";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':bookingId', $bookingId);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':payment', $payment);
    $stmt->bindParam(':checkInDate', $checkInDate);
    $stmt->bindParam(':checkOutDate', $checkOutDate);
    $stmt->execute();

    $sql = "UPDATE properties SET status = :status WHERE property_id = :propertyId";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':propertyId', $propertyId);
    $stmt->bindParam(':status', $status);
    $stmt->execute();
  }

  public function caterBooking($propertyId, $clientName, $status, $contactNo, $payment, $duration, $checkInDate, $checkOutDate, $paymentMethod = 'Cash')
  {
    $sql = "INSERT INTO bookings (property_id, name, contact_no, total_amount, duration, check_in_date, check_out_date, booking_status)
            VALUES (:propertyId, :clientName, :contact_no, :payment, :duration, :checkInDate, :checkOutDate, :status)";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':propertyId', $propertyId);
    $stmt->bindParam(':clientName', $clientName);
    $stmt->bindParam(':contact_no', $contactNo);
    $stmt->bindParam(':payment', $payment);
    $stmt->bindParam(':duration', $duration);
    $stmt->bindParam(':checkInDate', $checkInDate);
    $stmt->bindParam(':checkOutDate', $checkOutDate);
    $stmt->bindParam(':status', $status);
    $stmt->execute();

    $bookingId = $this->db->lastInsertId();

    $sql = "INSERT INTO payments (booking_id, payment_method, amount_paid, status)
            VALUES (:bookingId, :paymentMethod, :amountPaid, :paymentStatus)";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
    $stmt->bindParam(':paymentMethod', $paymentMethod);
    $stmt->bindParam(':amountPaid', $payment);
    $stmt->bindParam(':paymentStatus', $status);
    $stmt->execute();
  }
}
