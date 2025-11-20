<?php
require_once __DIR__ . '/base-repository.php';

class ai_repository extends base_repository
{
  public function getHotelsAvailable()
  {
    $sql = "SELECT 
              property_id, 
              title, 
              description, 
              price_per_night, 
              address
            FROM properties
            WHERE status = 5
            ORDER BY property_id DESC LIMIT 5";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
