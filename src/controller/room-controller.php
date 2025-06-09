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
        ]; // Log error or handle gracefully
      }
    }

    public function getHotels($searchQuery)
    {
      try {
        return $this->repository->getHotels($searchQuery);
      } catch (Exception $e) {
          return [
              'error' => 'Failed to retrieve hotels: ' . $e->getMessage()
          ]; // Log error or handle gracefully
      }
    }
}