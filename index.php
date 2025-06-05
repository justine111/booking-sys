<?php
// Start the session
session_start();

// Set the default timezone
date_default_timezone_set('Asia/Manila');

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
$page = empty($path) ? 'main' : $path;

require_once 'src/route.php';
