<?php
require_once __DIR__ . '/base-repository.php';

class payments_repository extends base_repository
{
  public function countAllPayments($searchQuery)
  {
    $sql = "SELECT
              COUNT(DISTINCT latest.payment_id) as total
            FROM (
              SELECT
                a.payment_id,
                a.client_token
              FROM payments a
              LEFT JOIN bookings b ON a.client_token = b.client_token
              LEFT JOIN properties c ON b.property_id = c.property_id
              WHERE a.payment_id IS NOT NULL
            ) AS latest
            WHERE 1=1";

    if (!empty($searchQuery)) {
      $sql .= " AND (c.title LIKE :searchQuery
          OR b.name LIKE :searchQuery)";
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
              latest.payment_id,
              latest.title,
              latest.client_name,
              latest.amount_paid,
              latest.payment_date,
              latest.check_in_date,
              latest.check_out_date,
              latest.client_token
            FROM (
              SELECT 
                a.payment_id,
                c.title,
                b.name as client_name,
                a.amount_paid,
                a.payment_date,
                b.check_in_date,
                b.check_out_date,
                a.client_token,
                ROW_NUMBER() OVER(
                  PARTITION BY a.client_token 
                  ORDER BY a.payment_date DESC
                ) as rn
              FROM payments a
              LEFT JOIN bookings b ON a.client_token = b.client_token
              LEFT JOIN properties c ON b.property_id = c.property_id
              WHERE a.payment_id IS NOT NULL
            ) as latest
            WHERE latest.rn = 1";

    if (!empty($searchQuery)) {
      $sql .= " AND (latest.title LIKE :searchQuery
        OR latest.client_name LIKE :searchQuery)";
    }
    $sql .= " ORDER BY latest.payment_date DESC 
              LIMIT :limit OFFSET :offset";
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
