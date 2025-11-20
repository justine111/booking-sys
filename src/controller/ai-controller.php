<?php
require_once __DIR__ . '/../repositories/ai-repository.php';
require_once __DIR__ . '/./base-controller.php';

class ai_controller extends base_controller
{
  private $repository;

  public function __construct()
  {
    $this->repository = new ai_repository();
  }

  public function getHotelsAvailable()
  {
    try {
      return $this->repository->getHotelsAvailable();
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }
}

header('Content-Type: application/json');

$core = new ai_controller();
$hotels = $core->getHotelsAvailable();

echo json_encode(['hotels' => $hotels]);
