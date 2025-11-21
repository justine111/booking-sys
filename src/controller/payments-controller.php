<?php
require_once __DIR__ . '/../repositories/payment-repository.php';
require_once __DIR__ . '/./base-controller.php';

class payments_controller extends base_controller
{
  private $repository;

  public function __construct()
  {
    $this->repository = new payments_controller();
  }

  public function countAllPayments()
  {
    try {
      return $this->repository->countAllPayments();
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function getAllPayments()
  {
    try {
      return $this->repository->getAllPayments();
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }
}
