<?php
require_once __DIR__ . '/../repositories/room-repository.php';

class RoomsController
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
      return [
        'error' => 'Failed to count hotels: ' . $e->getMessage()
      ];
    }
  }

  public function getHotels($searchQuery, $limit, $offset)
  {
    try {
      return $this->repository->getHotels($searchQuery, $limit, $offset);
    } catch (Exception $e) {
      return [
        'error' => 'Failed to retrieve hotels: ' . $e->getMessage()
      ];
    }
  }

  public function getHotelById($roomId)
  {
    try {
      return $this->repository->getHotelById($roomId);
    } catch (Exception $e) {
      return [
        'error' => 'Failed to retrieve hotel: ' . $e->getMessage()
      ];
    }
  }
}
