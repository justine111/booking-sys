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
        $img4
      );
      $this->repository->commitTransaction();

      return $this->response([
        'error' => false,
        'data' => $result,
        'message' => 'New Hotel room has been created successfully.'
      ]);
    } catch (Exception $e) {
      $this->repository->rollbackTransaction();
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
      return $this->repository->getListOfHotels($searchQuery, $limit, $offset);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }
}
