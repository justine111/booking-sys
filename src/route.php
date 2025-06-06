<?php
require_once 'src/components/header.php';
//require_once 'src/components/sidebar.php';
//require_once 'src/components/navbar.php';

// Handle routing
switch ($page) {
  case 'main':
    require_once 'src/components/navbar.php';
    require_once 'src/pages/main/main.php';
    break;
  default:
    require_once 'src/pages/404.php';
    break;
}
