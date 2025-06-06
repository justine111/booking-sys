<?php

class Connection_class
{
  private $db;

  public function __construct()
  {
    // Include and retrieve the configuration
    $config = require __DIR__ . '/db.config.php';

    try {
      $this->db = new PDO(
        "mysql:host={$config['host_name']};dbname={$config['database']};charset=utf8mb4",
        $config['username'],
        $config['password']
      );

      // Set PDO to throw exceptions for errors
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      error_log("Database connection error: " . $e->getMessage());
      die("A database connection error occurred.");
    }
  }

  public function getConnection()
  {
    return $this->db;
  }
}