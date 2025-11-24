<?php
require_once __DIR__ . '/../repositories/payments-repository.php';
require_once __DIR__ . '/./base-controller.php';

class payments_controller extends base_controller
{
  private $repository;

  public function __construct()
  {
    $this->repository = new payments_repository();
  }

  public function countAllPayments($searchQuery)
  {
    try {
      return $this->repository->countAllPayments($searchQuery);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function getAllPayments($searchQuery, $pageSize, $offset)
  {
    try {
      $userRole = $this->getCurrentUserRole();
      $userId = $this->getCurrentUserId();

      return $this->repository->getAllPayments($searchQuery, $pageSize, $offset, $userRole, $userId);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }
}
