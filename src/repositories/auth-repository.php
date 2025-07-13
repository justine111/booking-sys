<?php
require_once __DIR__ . '/base-repository.php';

class auth_repository extends base_repository
{
  public function login($username, $password)
  {
    // $hashedPassword = hash('sha512', $password);
    // $sql = "query for login admin or user";

    // $stmt = $this->db->prepare($sql);
    // $stmt->bindParam(':username', $username);
    // $stmt->bindParam(':password', $hashedPassword);
    // $stmt->execute();
    // return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
