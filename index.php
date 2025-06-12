<?php
session_set_cookie_params(86400);
ob_start();
session_start();
date_default_timezone_set('Asia/Manila');

require_once __DIR__ . '/src/controller/auth-controller.php';
$AunthController = new authController();

try {
    $user_id = "TUC DEV";
    $user_name = "TUC_DEV";
    $user_role = "Admin";
    $department = "IT";

    // Get the requested URI and remove the base path
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $basePath = '/booking-sys';
    $path = str_replace($basePath, '', $requestUri);
    $path = trim($path, '/');

    // Default to 'home' if no path is provided
    $page = empty($path) ? 'dashboard' : $path;

    require_once 'src/route.php';
} catch (Exception $e) {
    $AunthController->logout();
    //var_dump($_SESSION);
    //var_dump($e->getMessage());
}
