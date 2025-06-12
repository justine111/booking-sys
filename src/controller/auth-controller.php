<?php
require_once __DIR__ . '/../repositories/auth-repository.php';

class AuthController
{
  private $authRepository;
  private $db;

  public function __construct()
  {
    $this->authRepository = new authRepository();

    $connection = new Connection_class();
    $this->db = $connection->getConnection();
  }

  public function login()
  {
    try {
      $username = trim($_POST['username']);
      $password = trim($_POST['password']);

      if (empty($username)) {
        throw new Exception('Username is required.');
      }
      if (empty($password)) {
        throw new Exception('Password is required.');
      }

      $response = $this->authRepository->login($username, $password);
      if (!$response) {
        throw new Exception('Invalid username or password.');
      } else {
        if (session_status() === PHP_SESSION_NONE) {
          session_start();
        }
        $_SESSION['username'] = $response['username'];
        $_SESSION['role_id'] = $response['role_id'];
        $_SESSION['church_id'] = $response['church_id'];
        $_SESSION['member_id'] = $response['member_id'];
        $_SESSION['admin_id'] = $response['admin_id'];
        $_SESSION['Linc_leader'] = $response['Linc_leader'];

        if ($response['role_id'] == 1) {
          $baseUrlmain = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
          $baseUrl = $baseUrlmain . '/tupa-revamp/src/pages/church-selection.php';
          return [
            'error' => false,
            'message' => $baseUrl
          ];
        } else if ($response['role_id'] == 0 && $response['Linc_leader'] == 1) {
          $baseUrl = '/tupa-revamp/linc-group';
          return [
            'error' => false,
            'message' => $baseUrl
          ];
        } else {
          $baseUrl = '/tupa-revamp/';
          return [
            'error' => false,
            'message' => $baseUrl
          ];
        }
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
