<?php
require_once __DIR__ . '/../../controller/auth-controller.php';

$LoginController = new auth_controller();
$LoginController->logout();
