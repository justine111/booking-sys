<?php
require_once __DIR__ . '/../repositories/host-repository.php';
require_once __DIR__ . '/./base-controller.php';

class host_controller extends base_controller
{
  private $repository;

  public function __construct()
  {
    $this->repository = new host_repository();
  }

  public function countAllHosts($searchQuery)
  {
    try {
      return $this->repository->countAllHosts($searchQuery);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function getAllHosts($searchQuery, $limit, $offset)
  {
    try {
      return $this->repository->getAllHosts($searchQuery, $limit, $offset);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function addNewHost()
  {
    try {
      $this->repository->startTransaction();

      $name = $_POST['name'];

      $errors = [];

      if (empty($name)) {
        $errors['name'] = '*Please provide host name';
      }

      if (!empty($errors)) {
        return [
          'error' => true,
          'message' => 'Some fields are required.',
          'fields' => $errors
        ];
      }

      $result = $this->repository->addNewHost($name);
      $this->repository->commitTransaction();

      return $this->response([
        'error' => false,
        'data' => $result,
        'message' => 'New host has been created successfully.'
      ]);
    } catch (Exception $e) {
      $this->repository->rollbackTransaction();
      return $this->handleException($e);
    }
  }
}
