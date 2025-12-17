<?php
require_once __DIR__ . '/base-repository.php';

class auth_repository extends base_repository
{
  public function login($username, $password)
  {
    $hashedPassword = hash('sha512', $password);
    $sql = "SELECT 
              user_id, 
              name, 
              user_type 
            FROM 
              users
            WHERE 
              email = :username
            AND
              password = :password";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function register($data)
  {
    $hashedPassword = hash('sha512', $data['password']);
    $sql = "INSERT INTO users (name, email, password, user_type, phone_number) 
            VALUES (:name, :email, :password, :user_type, :phone_number)";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':user_type', $data['user_type']);
    $stmt->bindParam(':phone_number', $data['phone_number']);
    
    return $stmt->execute();
  }
}
