<?php
require_once __DIR__ . '/../connection/connection.php';

class RoomsRepository
{
  private $db;

  public function __construct()
  {
    $connection = new Connection_class();
    $this->db = $connection->getConnection();
  }

  public function countHotels($searchQuery = null)
  {
    try {
      $searchQuery = trim($searchQuery);

      $query = "SELECT COUNT(property_id) as total FROM properties WHERE status IS NOT NULL";
      if (!empty($searchQuery)) {
        $query .= " AND title LIKE :searchQuery
          OR price_per_night LIKE :searchQuery
          OR address LIKE :searchQuery
          OR status LIKE :searchQuery";
      }
      $stmt = $this->db->prepare($query);
      if (!empty($searchQuery)) {
        $searchQuery = "%$searchQuery%";
        $stmt->bindParam(':searchQuery', $searchQuery);
      }
      $stmt->execute();
      return $stmt->fetchColumn();
    } catch (PDOException $e) {
      return [
        'error' => 'Database query failed: ' . $e->getMessage()
      ]; // Log error or handle gracefully
    }
  }

  public function getHotels($searchQuery = null, $limit, $offset)
  {
    try {
      $searchQuery = trim($searchQuery);

      $query = "SELECT property_id, title, description, price_per_night, address, filename, status
                FROM properties
                WHERE status != 'inactive'";
      if (!empty($searchQuery)) {
        $query .= " AND title LIKE :searchQuery
          OR price_per_night LIKE :searchQuery
          OR address LIKE :searchQuery
          OR status LIKE :searchQuery";
      }
      $query .= " ORDER BY property_id DESC LIMIT :limit OFFSET :offset";
      $stmt = $this->db->prepare($query);
      if (!empty($searchQuery)) {
        $searchQuery = "%$searchQuery%";
        $stmt->bindParam(':searchQuery', $searchQuery);
      }
      $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
      $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [
        'error' => 'Database query failed: ' . $e->getMessage()
      ]; // Log error or handle gracefully
      return [];
    }
  }

  public function getHotelById($roomId)
  {
    try {
      $query = "SELECT 
                  property_id, 
                  title, 
                  description, 
                  price_per_night, 
                  address, 
                  filename, 
                  status
                FROM properties
                WHERE property_id = :id AND status != 'inactive'";
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':id', $roomId, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [
        'error' => 'Database query failed: ' . $e->getMessage()
      ]; // Log error or handle gracefully
    }
  }
}


  // public function countStudent($searchQuery = null)
  // {
  //     $searchQuery = trim($searchQuery);
  //     $sql = "SELECT COUNT(a.student_id) as total 
  //           FROM students a
  //           WHERE a.inactive = 0 IN (0, 1)";
  //     if (!empty($searchQuery)) {
  //         $sql .= " AND a.last_name LIKE :searchQuery
  //             OR a.first_name LIKE :searchQuery
  //             OR a.middle_name LIKE :searchQuery
  //             OR a.student_code LIKE :searchQuery
  //             OR a.address LIKE :searchQuery";
  //     }
  //     $stmt = $this->db->prepare($sql);
  //     if (!empty($searchQuery)) {
  //         $searchQuery = "%$searchQuery%";
  //         $stmt->bindParam(':searchQuery', $searchQuery);
  //     }
  //     $stmt->execute();
  //     return $stmt->fetchColumn();
  // }

  // public function getStudent($searchQuery = null, $limit, $offset, $sort, $order)
  // {
  //     $searchQuery = trim($searchQuery);

  //     $sql = "SELECT 
  //             a.student_id,
  //             a.student_code,
  //             concat(a.last_name, ', ', a.first_name) as name,
  //             a.birthdate,
  //             a.address,
  //             a.civil_status,
  //             a.gender,
  //             a.email,
  //             b.church_name,
  //             a.is_enrolled,
  //             a.avatar_path,
  //             a.inactive
  //           FROM students a
  //           LEFT JOIN admin_churches b ON a.church_id = b.church_id
  //           WHERE a.inactive IN (0, 1)";

  //     if (!empty($searchQuery)) {
  //         $sql .= " AND a.last_name LIKE :searchQuery
  //             OR a.first_name LIKE :searchQuery
  //             OR a.middle_name LIKE :searchQuery
  //             OR a.student_code LIKE :searchQuery
  //             OR a.address LIKE :searchQuery
  //             OR b.church_name LIKE :searchQuery";
  //     }

  //     $sql .= " ORDER BY " . $sort . " " . $order . " LIMIT :limit OFFSET :offset";
  //     $stmt = $this->db->prepare($sql);

  //     if (!empty($searchQuery)) {
  //         $searchParam = "%$searchQuery%";
  //         $stmt->bindParam(':searchQuery', $searchParam, PDO::PARAM_STR);
  //     }

  //     $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
  //     $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

  //     $stmt->execute();
  //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
  // }

