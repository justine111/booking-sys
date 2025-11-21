<?php
require_once __DIR__ . '/../repositories/dashboard-repository.php';
require_once __DIR__ . '/./base-controller.php';

class dashboard_controller extends base_controller
{
  private $repository;

  public function __construct()
  {
    $this->repository = new dashboard_repository();
  }

  public function getDashboardStats()
  {
    try {
      $totalBookings = $this->repository->getTotalBookings();
      $totalHotels = $this->repository->getTotalHotels();
      $totalUsers = $this->repository->getTotalUsers();
      $occupancyRate = $this->repository->getOccupancyRate();
      $revenueData = $this->repository->getRevenueData();
      $bookingsTrend = $this->repository->getBookingsTrend();
      $recentBookings = $this->repository->getRecentBookings();
      $topHotels = $this->repository->getTopHotels();

      return $this->response([
        'error' => false,
        'data' => [
          'stats' => [
            'totalBookings' => $totalBookings,
            'totalHotels' => $totalHotels,
            'totalUsers' => $totalUsers,
            'occupancyRate' => $occupancyRate
          ],
          'revenue' => $revenueData,
          'bookingsTrend' => $bookingsTrend,
          'recentBookings' => $recentBookings,
          'topHotels' => $topHotels
        ]
      ]);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function getMonthlyBookings()
  {
    try {
      $year = $_GET['year'] ?? date('Y');
      $bookingsData = $this->repository->getMonthlyBookings($year);

      return $this->response([
        'error' => false,
        'data' => $bookingsData
      ]);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  public function getRevenueOverview()
  {
    try {
      $year = $_GET['year'] ?? date('Y');
      $revenueData = $this->repository->getRevenueOverview($year);

      return $this->response([
        'error' => false,
        'data' => $revenueData
      ]);
    } catch (Exception $e) {
      return $this->handleException($e);
    }
  }
}
