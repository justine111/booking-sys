<?php
require_once __DIR__ . '/../repositories/user-repository.php';
require_once __DIR__ . '/./base-controller.php';

class user_controller extends base_controller
{
  private $repository;

  public function __construct()
  {
    $this->repository = new user_repository();
  }

  public function countAllUsers($searchQuery)
  {
    try {
      return $this->repository->countAllUsers($searchQuery);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function getAllUsers($searchQuery, $limit, $offset)
  {
    try {
      return $this->repository->getAllUsers($searchQuery, $limit, $offset);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function addNewUser()
  {
    try {
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }
}
