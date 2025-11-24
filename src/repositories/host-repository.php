<?php
require_once __DIR__ . '/base-repository.php';

class host_repository extends base_repository
{
  public function countAllHosts($searchQuery)
  {
    $sql = "SELECT
	            count(host_id) as total
            FROM `hosts`
            WHERE host_id IS NOT NULL";

    if (!empty($searchQuery)) {
      $sql .= " AND name LIKE :searchQuery";
    }
    $stmt = $this->db->prepare($sql);
    if (!empty($searchQuery)) {
      $searchQuery = "%$searchQuery%";
      $stmt->bindParam(':searchQuery', $searchQuery);
    }
    $stmt->execute();

    return $stmt->fetchColumn();
  }

  public function getAllHosts($searchQuery = null, $limit, $offset)
  {
    $sql = "SELECT
	            host_id,
	            name,
	            date_created
            FROM `hosts`
            WHERE host_id IS NOT NULL";

    if (!empty($searchQuery)) {
      $sql .= " AND name LIKE :searchQuery";
    }
    $sql .= " ORDER BY host_id DESC LIMIT :limit OFFSET :offset";
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

  public function addNewHost($name)
  {
    $sql = "INSERT INTO hosts (name) VALUES (:name)";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->execute();

    return $stmt->rowCount();
  }
}
