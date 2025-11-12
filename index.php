<?php
session_set_cookie_params(86400);
ob_start();
session_start();
date_default_timezone_set('Asia/Manila');

require_once __DIR__ . '/src/controller/auth-controller.php';
$AunthController = new auth_controller();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = '/booking-sys';
$path = trim(str_replace($basePath, '', $requestUri), '/');
$page = $_GET['page'] ?? (empty($path) ? 'dashboard' : $path);

try {
  if (!isset($_SESSION['id'])) {
    throw new Exception('Authentication failed. Please login');
  }
  $userId = $_SESSION['user_id'];
  $name = $_SESSION['name'];
  $userRole = $_SESSION['user_type'];

  require_once 'src/route.php';
} catch (Exception $e) {
  $AunthController->logout();
  //var_dump($_SESSION);
  //var_dump($e->getMessage());
}
