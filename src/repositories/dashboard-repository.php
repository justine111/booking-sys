<?php
require_once __DIR__ . '/base-repository.php';

class dashboard_repository extends base_repository
{
  public function getTotalBookings()
  {
    $query = "SELECT COUNT(*) as total FROM bookings WHERE booking_status IN (6) AND DATE(created_at) = CURDATE()";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
  }

  public function getTotalHotels()
  {
    $query = "SELECT COUNT(*) as total FROM properties";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
  }

  public function getTotalUsers()
  {
    $query = "SELECT COUNT(*) as total FROM users";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
  }

  public function getOccupancyRate()
  {
    $query = "SELECT 
                ROUND(
                  (COUNT(CASE WHEN booking_status IN (6) THEN 1 END) * 100.0 / 
                  COUNT(*)), 
                2) as occupancy_rate 
              FROM bookings 
              WHERE booking_status IN (6)";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['occupancy_rate'] ?? 0;
  }

  public function getRevenueData()
  {
    $query = "SELECT 
                COALESCE(SUM(latest.total_amount), 0) as total_revenue,
                COALESCE(SUM(CASE WHEN MONTH(latest.created_at) = MONTH(CURRENT_DATE()) THEN latest.total_amount ELSE 0 END), 0) as monthly_revenue,
                COALESCE(SUM(CASE WHEN YEAR(latest.created_at) = YEAR(CURRENT_DATE()) THEN latest.total_amount ELSE 0 END), 0) as yearly_revenue
              FROM (
                SELECT 
                  b.total_amount,
                  b.created_at,
                  b.client_token,
                  b.booking_status,
                  ROW_NUMBER() OVER(
                    PARTITION BY b.client_token 
                    ORDER BY b.created_at DESC
                  ) as rn
                FROM bookings b
              ) as latest
              WHERE latest.rn = 1
              AND latest.booking_status IN (2, 6)";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getBookingsTrend()
  {
    $query = "SELECT 
                MONTHNAME(created_at) as month,
                COUNT(*) as bookings_count
              FROM bookings 
              WHERE YEAR(created_at) = YEAR(CURRENT_DATE())
                AND booking_status IN (1, 2, 6)
              GROUP BY MONTH(created_at), MONTHNAME(created_at)
              ORDER BY MONTH(created_at)";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getRecentBookings($limit = 5)
  {
    $query = "SELECT 
                latest.booking_id,
                latest.hotel_name,
                latest.client_name,
                latest.contact_no,
                latest.status,
                latest.total_amount,
                latest.check_in_date,
                latest.check_out_date,
                latest.created_at,
                latest.client_token
              FROM (
                SELECT 
                  b.booking_id,
                  p.title as hotel_name,
                  b.name as client_name,
                  b.contact_no,
                  bs.description as status,
                  b.total_amount,
                  b.check_in_date,
                  b.check_out_date,
                  b.created_at,
                  b.client_token,
                  b.booking_status,
                  ROW_NUMBER() OVER(
                    PARTITION BY b.client_token 
                    ORDER BY b.created_at DESC
                  ) as rn
                FROM bookings b
                LEFT JOIN properties p ON b.property_id = p.property_id
                LEFT JOIN booking_status bs ON b.booking_status = bs.id
              ) as latest
              WHERE latest.rn = 1
              AND latest.booking_status IN (1, 2, 6)
              ORDER BY latest.created_at DESC
              LIMIT :limit";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getTopHotels($limit = 4)
  {
    $query = "SELECT 
                p.property_id,
                p.title as hotel_name,
                COUNT(b.booking_id) as bookings_count,
                COALESCE(SUM(b.total_amount), 0) as total_revenue
              FROM properties p
              LEFT JOIN bookings b ON p.property_id = b.property_id 
                AND b.booking_status IN (2, 6)
                AND YEAR(b.created_at) = YEAR(CURRENT_DATE())
              GROUP BY p.property_id, p.title
              ORDER BY bookings_count DESC, total_revenue DESC
              LIMIT :limit";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getMonthlyBookings($year)
  {
    $query = "SELECT 
                MONTHNAME(created_at) as month,
                COUNT(*) as bookings_count
              FROM bookings 
              WHERE YEAR(created_at) = :year
                AND booking_status IN (1, 2, 6)
              GROUP BY MONTH(created_at), MONTHNAME(created_at)
              ORDER BY MONTH(created_at)";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':year', $year);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getRevenueOverview($year)
  {
    $query = "SELECT 
                MONTHNAME(created_at) as month,
                COALESCE(SUM(total_amount), 0) as revenue
              FROM bookings 
              WHERE YEAR(created_at) = :year
                AND booking_status IN (2, 6)
              GROUP BY MONTH(created_at), MONTHNAME(created_at)
              ORDER BY MONTH(created_at)";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':year', $year);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
