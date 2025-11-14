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

  public function addHotel()
  {
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

    return $this->repository->addHotel(
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
  }

  public function getCountOfHotels()
  {
    try {
      return $this->repository->getCountOfHotels();
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }
  
  public function getListOfHotels()
  {
    try {
      return $this->repository->getListOfHotels();
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }
}
