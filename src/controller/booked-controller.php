<?php
require_once __DIR__ . '/../repositories/booked-repository.php';
require_once __DIR__ . '/./base-controller.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../helpers/vendor/autoload.php';

class booking_controller extends base_controller
{
  private $repository;

  public function __construct()
  {
    $this->repository = new booking_repository();
  }

  public function reservation()
  {
    try {
      $this->repository->startTransaction();

      $unitId = $_POST['unit'] ?? '';
      $name = $_POST['name'] ?? '';
      $phoneno = $_POST['phoneno'] ?? '';
      $duration = $_POST['stay-duration'] ?? '';
      $description = $_POST['description'] ?? '';
      $checkInDate = $_POST['check_in_date'] ?? '';
      $checkOutDate = $_POST['check_out_date'] ?? '';
      $emailAcc = $_POST['emailAcc'] ?? '';

      $errors = [];

      if (empty($name)) {
        $errors['name'] = '*Name is required';
      }

      if (empty($phoneno)) {
        $errors['phoneno'] = '*Contanct no. is required';
      }

      if (empty($duration)) {
        $errors['stay-duration'] = '*Stay duration is required';
      }

      if (empty($checkInDate)) {
        $errors['check_in_date'] = '*Check-in date is required';
      }

      if (empty($checkOutDate)) {
        $errors['check_out_date'] = '*Check-out date is required';
      }

      if (empty($emailAcc)) {
        $errors['emailAcc'] = '*Email account is required';
      }

      if (!empty($errors)) {
        return [
          'error' => true,
          'message' => 'Some fields are required.',
          'fields' => $errors
        ];
      }

      $result = $this->repository->reservation($unitId, $name, $phoneno, $duration, $description, $checkInDate, $checkOutDate, $emailAcc);
      $this->repository->commitTransaction();

      return $this->response([
        'error' => false,
        'data' => $result,
        'message' => 'Reservation has been submitted successfully.'
      ]);
    } catch (Exception $e) {
      $this->repository->rollbackTransaction();
      return $this->handleException($e);
    }
  }

  public function countAllBookings($searchQuery, $userRole, $userId)
  {
    try {
      return $this->repository->countAllBookings($searchQuery, $userRole, $userId);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function getAllBookings($searchQuery, $limit, $offset, $userRole, $userId)
  {
    try {
      return $this->repository->getAllBookings($searchQuery, $limit, $offset, $userRole, $userId);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  private function processBooking(array $data, string $action = 'update')
  {
    try {
      $this->repository->startTransaction();

      // Define required fields based on action
      $requiredFields = $action === 'update'
        ? ['status', 'check_in_date', 'check_out_date']
        : ['title', 'client_name', 'status', 'contact_no', 'payment', 'check_in_date', 'check_out_date', 'emailAcc'];

      $errors = [];
      foreach ($requiredFields as $field) {
        if (empty($data[$field] ?? '')) {
          $errors[$field] = "*$field is required";
        }
      }

      if (!empty($errors)) {
        return [
          'error' => true,
          'message' => 'Some fields are required.',
          'fields' => $errors
        ];
      }

      $clientToken   = $data['client_token'] ?? '';
      $propertyId    = $data['property_id'] ?? $data['title'] ?? '';
      $clientName    = $data['client_name'] ?? '';
      $contactNo     = $data['contact_no'] ?? '';
      $duration      = $data['duration'] ?? '';
      $status        = $data['status'] ?? '';
      $payment       = $data['payment'] ?? '';
      $checkInDate   = $data['check_in_date'] ?? '';
      $checkOutDate  = $data['check_out_date'] ?? '';
      $emailAcc      = $data['emailAcc'] ?? '';

      $result = $action === 'update'
        ? $this->repository->updateBooking($clientToken, $propertyId, $clientName, $contactNo, $duration, $status, $payment, $checkInDate, $checkOutDate, $emailAcc)
        : $this->repository->caterBooking($propertyId, $clientName, $status, $contactNo, $payment, $duration, $checkInDate, $checkOutDate, $emailAcc);

      $this->repository->commitTransaction();

      // Send email only if status == 6 and email is not empty
      if ((int)$status === 6 && !empty($emailAcc)) {
        $body = <<<HTML
          <p>Hi $clientName,</p>
          <p>Your booking has been approved!</p>
          <p>BOOKING DETAILS:</p>
          <p>================</p>
          <p>Property: {$propertyId}</p>
          <p>Check-in: {$checkInDate}</p>
          <p>Check-out: {$checkOutDate}</p>
          <p>Total Amount: PHP {$payment}</p>
          <p>Thank you for choosing us!</p>
          <p>This is an automated message. Please do not reply</p>
        HTML;

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'joshernandez1172@gmail.com';
        $mail->Password   = 'vjyr tvwe bpgm ljsd'; // App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->setFrom('joshernandez1172@gmail.com', 'StaySmart');
        $mail->addAddress($emailAcc, $clientName);
        $mail->isHTML(true);
        $mail->Subject = 'Booking Approved';
        $mail->Body    = $body;
        $mail->send();
      }

      return [
        'error' => false,
        'data'  => $result,
        'message' => 'Booking has been ' . ($action === 'update' ? 'updated' : 'created') . ' successfully.'
          . ((int)$status === 6 ? ' Email sent.' : '')
      ];
    } catch (Exception $e) {
      $this->repository->rollbackTransaction();
      return $this->handleException($e);
    }
  }

  public function updateBooking()
  {
    return $this->processBooking($_POST, 'update');
  }

  public function caterBooking()
  {
    return $this->processBooking($_POST, 'create');
  }
}
