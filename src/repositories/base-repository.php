<?php 
require_once __DIR__ . '/../connection/connection.php';

class base_repository 
{
  public $db;

  public function __construct()
  {
    $connection = new Connection_class();
    $this->db = $connection->getConnection();
  }

  public function startTransaction()
  {
    $this->db->beginTransaction();
  }

  public function inTransaction()
  {
    return $this->db->inTransaction();
  }

  public function commitTransaction()
  {
    $this->db->commit();
  }

  public function rollbackTransaction()
  {
    $this->db->rollBack();
  }
}