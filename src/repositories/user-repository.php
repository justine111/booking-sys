<?php
require_once __DIR__ . '/base-repository.php';

class user_repository extends base_repository
{
  public function countAllUsers($searchQuery)
  {
    $sql = "SELECT
	            count(a.user_id) as total
            FROM `users` a
            LEFT JOIN user_roles b
            ON a.user_type = b.user_role_id
            WHERE a.user_id IS NOT NULL";

    if (!empty($searchQuery)) {
      $sql .= " AND a.email LIKE :searchQuery
        OR a.name LIKE :searchQuery
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

  public function getAllUsers($searchQuery = null, $limit, $offset)
  {
    $sql = "SELECT
	            a.user_id,
	            a.name,
	            a.email,
	            a.phone_number,
	            a.created_at,
	            b.name as role 
            FROM `users` a
            LEFT JOIN user_roles b
            ON a.user_type = b.user_role_id
            WHERE a.user_id IS NOT NULL";

    if (!empty($searchQuery)) {
      $sql .= " AND a.email LIKE :searchQuery
        OR a.name LIKE :searchQuery
        OR b.name LIKE :searchQuery";
    }
    $sql .= " ORDER BY a.user_id DESC LIMIT :limit OFFSET :offset";
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
