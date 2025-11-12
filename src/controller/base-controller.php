<?php

class base_controller
{

  protected function getUserSession(): array
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    // if (!isset($_SESSION['admin_id'])) {
    //   throw new Exception("User ID is not set in session");
    // }

    return [
      //'memberId'   => $_SESSION['member_id'],
      //'username' => $_SESSION['username'],
      'churchId' => 1,
      //'roleId' => $_SESSION['role_id'],
      //'adminId' => $_SESSION['admin_id'],
      //'lincLeader' => $_SESSION['Linc_leader'],
    ];
  }

  // protected function checkSession()
  // {
  //   if (session_status() === PHP_SESSION_NONE) {
  //     session_start();
  //   }

  //   if (empty($_SESSION['admin_id'])) {
  //     $this->logout();
  //   }

  //   $adminId = $_SESSION['admin_id'];
  //   $sessionRepo = new session_repository();
  //   $isValid = $sessionRepo->validateSession($adminId);


  //   if (!$isValid) {
  //     $this->logout();
  //     exit;
  //   }
  //   return true;
  // }

  protected function response(array $data, int $statusCode = 200)
  {
    http_response_code($statusCode);
    return $data;
  }

  protected function logout()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    session_unset();
    session_destroy();

    $isHtmx = isset($_SERVER['HTTP_HX_REQUEST']) && $_SERVER['HTTP_HX_REQUEST'] === 'true';
    $loginUrl = "/tupa-revamp/src/pages/login/login.php";

    if ($isHtmx) {
      header("HX-Redirect: $loginUrl");
    } else {
      header("Location: $loginUrl");
    }
    exit();
  }

  protected function handleException(Exception $e)
  {
    $statusCode = (is_numeric($e->getCode()) && $e->getCode() > 0) ? (int)$e->getCode() : 500;
    http_response_code($statusCode);
    $errorData = [
      'error'   => true,
      'message' => $e->getMessage()
    ];
    if ($statusCode === 422) {
      $errorData['error_field'] = $e->getPrevious() ? $e->getPrevious()->getMessage() : null;
    }
    return $errorData;
  }
}
