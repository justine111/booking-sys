<?php
require_once 'src/components/header.php';
require_once 'src/components/sidebar.php';
require_once __DIR__ . '/helpers/authorization-helper.php';

// Get current user role from session
$userRole = $_SESSION['user_type'] ?? null;

// Check if user can access the requested module
if (!AuthorizationHelper::canViewModule($userRole, $page)) {
  require_once 'src/pages/403.php';
  exit;
}

// Handle routing
switch ($page) {
  case 'dashboard':
    require_once 'src/pages/admin/dashboard/dashboard.php';
    break;
  case 'booking':
    require_once 'src/pages/admin/booking/booking.php';
    break;
  case 'payments':
    require_once 'src/pages/admin/payments/payments.php';
    break;
  case 'host':
    require_once 'src/pages/admin/host/host.php';
    break;
  case 'user':
    require_once 'src/pages/admin/user/user.php';
    break;
  case 'room':
    require_once 'src/pages/admin/room/room.php';
    break;

  default:
    require_once 'src/pages/404.php';
    break;
}
