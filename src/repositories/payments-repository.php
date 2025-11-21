<?php
require_once __DIR__ . '/base-repository.php';

class payments_repository extends base_repository
{
  public function countAllPayments($searchQuery)
  {
    $sql = "SELECT 
	            count(a.payment_id) as total
	          FROM payments a
	          LEFT JOIN bookings b
	          ON a.booking_id = b.booking_id
	          LEFT JOIN properties c
	          ON b.property_id = c.property_id
            WHERE a.payment_id IS NOT NULL";

    if (!empty($searchQuery)) {
      $sql .= " AND c.title LIKE :searchQuery
          OR b.name LIKE :searchQuery";
    }
    $stmt = $this->db->prepare($sql);
    if (!empty($searchQuery)) {
      $searchQuery = "%$searchQuery%";
      $stmt->bindParam(':searchQuery', $searchQuery);
    }
    $stmt->execute();

    return $stmt->fetchColumn();
  }

  public function getAllPayments($searchQuery = null, $limit, $offset)
  {
    $sql = "SELECT 
	            a.payment_id,
              c.title,
	            b.name as client_name,
	            a.amount_paid,
	            a.payment_date,
	            b.check_in_date,
	            b.check_out_date
	          FROM payments a
	          LEFT JOIN bookings b
	          ON a.booking_id = b.booking_id
	          LEFT JOIN properties c
	          ON b.property_id = c.property_id
            WHERE a.payment_id IS NOT NULL";

    if (!empty($searchQuery)) {
      $sql .= " AND c.title LIKE :searchQuery
        OR b.name LIKE :searchQuery";
    }
    $sql .= " ORDER BY a.payment_id DESC LIMIT :limit OFFSET :offset";
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
