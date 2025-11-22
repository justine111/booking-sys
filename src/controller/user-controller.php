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
      $this->repository->startTransaction();

      $name = $_POST['name'];
      $role = $_POST['role'];
      $email = $_POST['email'];
      $contact_no = $_POST['contact_no'];
      $password = $_POST['password'];

      $errors = [];

      if (empty($name)) {
        $errors['name'] = '*Please provide name';
      }

      if (empty($role)) {
        $errors['role'] = '*Role is required';
      }

      if (empty($email)) {
        $errors['email'] = '*Email is required';
      }

      if (empty($password)) {
        $errors['password'] = '*Password rate is required';
      }

      if (!empty($errors)) {
        return [
          'error' => true,
          'message' => 'Some fields are required.',
          'fields' => $errors
        ];
      }

      $result = $this->repository->addNewUser($name, $role, $email, $contact_no, $password);
      $this->repository->commitTransaction();

      return $this->response([
        'error' => false,
        'data' => $result,
        'message' => 'New user has been created successfully.'
      ]);
    } catch (Exception $e) {
      $this->repository->rollbackTransaction();
      return $this->handleException($e);
    }
  }
}
