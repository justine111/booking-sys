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
      $username = trim($_POST['username']);
      $password = trim($_POST['password']);

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

  public function register()
  {
    try {
      $name = trim($_POST['name'] ?? '');
      $email = trim($_POST['email'] ?? '');
      $password = trim($_POST['password'] ?? '');
      $phone = trim($_POST['phone'] ?? '');
      $user_type = trim($_POST['user_type'] ?? '3'); // Default to Host (3) if not specified, or maybe 1? Let's assume 3 for this task.

      if (empty($name) || empty($email) || empty($password)) {
        return $this->response([
          'error' => true,
          'message' => 'Name, Email and Password are required.'
        ]);
      }

      $data = [
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'phone_number' => $phone,
        'user_type' => $user_type
      ];

      $result = $this->authRepository->register($data);

      if ($result) {
        return [
          'error' => false,
          'message' => 'Registration successful! Please login.'
        ];
      } else {
        return [
          'error' => true,
          'message' => 'Registration failed. Email might already be in use.'
        ];
      }
    } catch (Exception $e) {
      return [
        'error' => true,
        'message' => 'Registration error: ' . $e->getMessage()
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
