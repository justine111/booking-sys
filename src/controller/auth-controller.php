<?php
require_once __DIR__ . '/../repositories/auth-repository.php';
require_once __DIR__ . '/./base-controller.php';

class auth_controller extends base_controller
{
  private $authRepository;

  public function __construct()
  {
    $this->authRepository = new auth_repository();
  }

  public function login()
  {
    try {
      $username = 'james@gmail.com'; //trim($_POST['username'])
      $password = 'password'; //trim($_POST['password'])

      if (empty($username) && empty($password)) {
        return $this->response([
          'error' => true,
          'message' => 'Username and Password Is Required'
        ]);
      } else if (empty($username)) {
        return $this->response([
          'error' => true,
          'message' => 'Username Is Required'
        ]);
      } else if (empty($password)) {
        return $this->response([
          'error' => true,
          'message' => 'Password Is Required'
        ]);
      }
      $response = $this->authRepository->login($username, $password);

      if (!$response) {
        return $this->response([
          'error' => true,
          'message' => 'Invalid username or password.'
        ]);
      } else {
        if (session_status() === PHP_SESSION_NONE) {
          session_start();
        }
        $_SESSION['user_id'] = $response['user_id'];
        $_SESSION['name'] = $response['name'];
        $_SESSION['user_type'] = $response['user_type'];

        $baseUrlmain = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        $baseUrl = '/booking-sys/';
        return [
          'error' => false,
          'message' => $baseUrl
        ];
      }
    } catch (Exception $e) {
      return [
        'error' => true,
        'message' => $e->getMessage()
      ];
    }
  }

  public function logout($navigate = true)
  {
    session_unset();
    session_destroy();

    if ($navigate) {
      $baseUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
      header("Location: $baseUrl/booking-sys/src/pages/main/main.php");
    }
    exit();
  }
}
