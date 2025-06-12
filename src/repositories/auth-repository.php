<?php
require_once __DIR__ . '/../connection/connection.php';

class authRepository
{
  private $db;

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

  public function login($username, $password)
  {
    $hashedPassword = hash('sha512', $password);
    $sql = "SELECT 
                a.username,
                a.role_id,
                a.church_id,
                a.member_id,
                a.admin_id,
                b.Linc_leader
                FROM tbl_admin a
                JOIN member_infos b ON a.inactive = b.inactive
                WHERE a.username = :username AND a.password = :password AND (a.role_id = 1 OR a.role_id = 2 OR a.role_id = 0)";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
