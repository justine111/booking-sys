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
            ];// Log error or handle gracefully
            return [];
        }
    }
}