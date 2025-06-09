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

    public function getHotels()
    {
        try {
            $query = "SELECT title, description, price_per_night, address, filename, status
                      FROM properties
                      WHERE status = 'available'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [
                'error' => 'Database query failed: ' . $e->getMessage()
            ]; // Log error or handle gracefully
            return [];
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
}
