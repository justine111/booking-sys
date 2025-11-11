<?php
require_once 'src/components/header.php';

// Handle routing
switch ($page) {
  case 'dashboard':
    require_once 'src/components/sidebar.php';
    require_once 'src/pages/admin/dashboard/dashboard.php';
    break;

  case 'booking':
    require_once 'src/components/sidebar.php';
    require_once 'src/pages/admin/booking/booking.php';
    break;
  case 'hotel':
    require_once 'src/components/sidebar.php';
    require_once 'src/pages/admin/hotel/hotel.php';
    break;
  case'user':
    require_once 'src/components/sidebar.php';
    require_once 'src/pages/admin/user/user.php';
    break;  
  case 'room':
    require_once 'src/components/sidebar.php';
    require_once 'src/pages/admin/room/room.php';
    break;


  default:
    require_once 'src/pages/404.php';
    break;
}
