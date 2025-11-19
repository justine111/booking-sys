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

      if (!empty($errors)) {
        return [
          'error' => true,
          'message' => 'Some fields are required.',
          'fields' => $errors
        ];
      }

      $result = $this->repository->reservation($unitId, $name, $phoneno, $duration, $description);
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
      return $this->repository->getAllBookings($searchQuery, $limit, $offset);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function updateBooking()
  {
    try {
      $this->repository->startTransaction();

      $bookingId = $_POST['booking_id'] ?? '';
      $propertyId = $_POST['property_id'] ?? '';
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
      $result = $this->repository->updateBooking($bookingId, $propertyId, $status, $payment, $checkInDate, $checkOutDate);
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
}
