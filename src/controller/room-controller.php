<?php
require_once __DIR__ . '/../repositories/room-repository.php';

class RoomsController
{
    private $repository;

    public function __construct()
    {
        $this->repository = new RoomsRepository();
    }

    public function getHotels()
    {
      try {
        return $this->repository->getHotels();
      } catch (Exception $e) {
          return [
              'error' => 'Failed to retrieve hotels: ' . $e->getMessage()
          ]; // Log error or handle gracefully
      }
    }
}