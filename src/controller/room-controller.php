<?php
require_once __DIR__ . '/../repositories/room-repository.php';
require_once __DIR__ . '/./base-controller.php';

class room_controller extends base_controller
{
  private $repository;

  public function __construct()
  {
    $this->repository = new RoomsRepository();
  }

  public function countHotels($searchQuery)
  {
    try {
      return $this->repository->countHotels($searchQuery);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function getHotels($searchQuery, $limit, $offset)
  {
    try {
      return $this->repository->getHotels($searchQuery, $limit, $offset);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function getHotelById($roomId)
  {
    try {
      return $this->repository->getHotelById($roomId);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function getAllCategories()
  {
    try {
      return $this->repository->getAllCategories();
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function countHotelsByCategory($categoryId, $searchQuery)
  {
    try {
      return $this->repository->countHotelsByCategory($categoryId, $searchQuery);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function getHotelsByCategory($categoryId, $searchQuery, $limit, $offset)
  {
    try {
      return $this->repository->getHotelsByCategory($categoryId, $searchQuery, $limit, $offset);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function getHotelListAvailable()
  {
    try {
      return $this->repository->getHotelListAvailable();
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function addHotel()
  {
    try {
      $this->repository->startTransaction();

      $hotelName = $_POST['hotel-name'];
      $address = $_POST['address'];
      $city = $_POST['city'];
      $price = $_POST['price'];
      $host = $_POST['host'];
      $description = $_POST['description'];
      $amenities = $_POST['amenities'];

      $img1 = $_FILES['image_1'];
      $img2 = $_FILES['image_2'];
      $img3 = $_FILES['image_3'];
      $img4 = $_FILES['image_4'];

      $errors = [];

      if (empty($hotelName)) {
        $errors['hotel-name'] = '*Please provide unit name';
      }

      if (empty($address)) {
        $errors['address'] = '*Address is required';
      }

      if (empty($city)) {
        $errors['city'] = '*City is required';
      }

      if (empty($price)) {
        $errors['price'] = '*Price rate is required';
      }

      if (empty($host)) {
        $errors['host'] = '*Unit host is required';
      }

      if (!empty($errors)) {
        return [
          'error' => true,
          'message' => 'Some fields are required.',
          'fields' => $errors
        ];
      }

      // Get current user role and ID
      $userRole = $this->getCurrentUserRole();
      $userId = $this->getCurrentUserId();
      $initialStatus = AuthorizationHelper::getInitialPropertyStatus($userRole);

      $result = $this->repository->addHotel(
        $hotelName,
        $address,
        $city,
        $price,
        $host,
        $description,
        $amenities,
        $img1,
        $img2,
        $img3,
        $img4,
        $userId,
        $initialStatus
      );
      $this->repository->commitTransaction();

      $message = $initialStatus === 0
        ? 'New Hotel room has been submitted for approval.'
        : 'New Hotel room has been created successfully.';

      return $this->response([
        'error' => false,
        'data' => $result,
        'message' => $message
      ]);
    } catch (Exception $e) {
      $this->repository->rollbackTransaction();
      return $this->handleException($e);
    }
  }

  public function approveProperty()
  {
    try {
      // Only admin and moderators can approve
      $this->requireRole([AuthorizationHelper::ROLE_ADMIN, AuthorizationHelper::ROLE_MODERATOR]);

      $propertyId = $_POST['property_id'] ?? null;

      if (empty($propertyId)) {
        throw new Exception('Property ID is required', 400);
      }

      $result = $this->repository->updatePropertyStatus($propertyId, 5); // 5 = available

      return $this->response([
        'error' => false,
        'data' => $result,
        'message' => 'Property has been approved successfully.'
      ]);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function rejectProperty()
  {
    try {
      // Only admin and moderators can reject
      $this->requireRole([AuthorizationHelper::ROLE_ADMIN, AuthorizationHelper::ROLE_MODERATOR]);

      $propertyId = $_POST['property_id'] ?? null;

      if (empty($propertyId)) {
        throw new Exception('Property ID is required', 400);
      }

      $result = $this->repository->updatePropertyStatus($propertyId, 1); // 1 = rejected

      return $this->response([
        'error' => false,
        'data' => $result,
        'message' => 'Property has been rejected.'
      ]);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function getCountOfHotels($searchQuery)
  {
    try {
      return $this->repository->getCountOfHotels($searchQuery);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function getListOfHotels($searchQuery, $limit, $offset)
  {
    try {
      $userRole = $this->getCurrentUserRole();
      $userId = $this->getCurrentUserId();

      return $this->repository->getListOfHotels($searchQuery, $limit, $offset, $userRole, $userId);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }
}
