<?php
require_once 'src/components/header.php';
require_once 'src/components/navbar.php';
require_once 'src/components/footer.php';

// Handle routing
switch ($page) {
  case 'main':
    require_once 'src/pages/ai/index.php';
    require_once 'src/pages/main/main.php';
    break;
  // case 'main':
  //   require_once 'src/pages/main/main.php';
  //   break;
  default:
    require_once 'src/pages/404.php';
    break;
}
