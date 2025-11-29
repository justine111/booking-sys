<?php
require_once __DIR__ . '/../repositories/booked-repository.php';
require_once __DIR__ . '/./base-controller.php';

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

      if (!empty($errors)) {
        return [
          'error' => true,
          'message' => 'Some fields are required.',
          'fields' => $errors
        ];
      }

      $result = $this->repository->reservation($unitId, $name, $phoneno, $duration, $description, $checkInDate, $checkOutDate);
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

  public function countAllBookings($searchQuery)
  {
    try {
      return $this->repository->countAllBookings($searchQuery);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function getAllBookings($searchQuery, $limit, $offset)
  {
    try {
      $userRole = $this->getCurrentUserRole();
      $userId = $this->getCurrentUserId();

      return $this->repository->getAllBookings($searchQuery, $limit, $offset, $userRole, $userId);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function updateBooking()
  {
    try {
      $this->repository->startTransaction();

      $clientToken = $_POST['client_token'] ?? '';
      $propertyId = $_POST['property_id'] ?? '';
      $clientName = $_POST['client_name'] ?? '';
      $contactNo = $_POST['contact_no'] ?? '';
      $duration = $_POST['duration'] ?? '';
      $status = $_POST['status'] ?? '';
      $payment = $_POST['payment'] ?? '';
      $checkInDate = $_POST['check_in_date'] ?? '';
      $checkOutDate = $_POST['check_out_date'] ?? '';

      $errors = [];

      if (empty($status)) {
        $errors['status'] = '*Booking status is required';
      }

      if (empty($checkInDate)) {
        $errors['check_in_date'] = '*Check-in date is required';
      }

      if (empty($checkOutDate)) {
        $errors['check_out_date'] = '*Check-out date is required';
      }

      if (!empty($errors)) {
        return [
          'error' => true,
          'message' => 'Some fields are required.',
          'fields' => $errors
        ];
      }
      $result = $this->repository->updateBooking($clientToken, $propertyId, $clientName, $contactNo, $duration, $status, $payment, $checkInDate, $checkOutDate);
      $this->repository->commitTransaction();

      return $this->response([
        'error' => false,
        'data' => $result,
        'message' => 'Booking has been updated successfully.'
      ]);
    } catch (Exception $e) {
      $this->repository->rollbackTransaction();
      return $this->handleException($e);
    }
  }

  public function caterBooking()
  {
    try {
      $this->repository->startTransaction();

      $propertyId = $_POST['title'] ?? '';
      $clientName = $_POST['client_name'] ?? '';
      $status = $_POST['status'] ?? '';
      $contactNo = $_POST['contact_no'] ?? '';
      $payment = $_POST['payment'] ?? '';
      $duration = $_POST['duration'] ?? '';
      $checkInDate = $_POST['check_in_date'] ?? '';
      $checkOutDate = $_POST['check_out_date'] ?? '';

      $errors = [];

      if (empty($propertyId)) {
        $errors['title'] = '*Unit name is required';
      }

      if (empty($clientName)) {
        $errors['client_name'] = '*Client name is required';
      }

      if (empty($status)) {
        $errors['status'] = '*Booking status is required';
      }

      if (empty($contactNo)) {
        $errors['contact_no'] = '*Contact no. is required';
      }

      if (empty($payment)) {
        $errors['payment'] = '*Payment is required';
      }

      if (empty($checkInDate)) {
        $errors['check_in_date'] = '*Check-in date is required';
      }

      if (empty($checkOutDate)) {
        $errors['check_out_date'] = '*Check-out date is required';
      }

      if (!empty($errors)) {
        return [
          'error' => true,
          'message' => 'Some fields are required.',
          'fields' => $errors
        ];
      }
      $result = $this->repository->caterBooking($propertyId, $clientName, $status, $contactNo, $payment, $duration, $checkInDate, $checkOutDate);
      $this->repository->commitTransaction();

      return $this->response([
        'error' => false,
        'data' => $result,
        'message' => 'Booking has been created successfully.'
      ]);
    } catch (Exception $e) {
      $this->repository->rollbackTransaction();
      return $this->handleException($e);
    }
  }
}
