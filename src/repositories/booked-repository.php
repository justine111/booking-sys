<?php
require_once __DIR__ . '/base-repository.php';
//require_once __DIR__ . '/../helpers/email-helper.php';

class booking_repository extends base_repository
{
  // private $emailHelper;

  // public function __construct()
  // {
  //   parent::__construct();
  //   $this->emailHelper = new EmailHelper();
  // }
  public function reservation($unitId, $name, $phoneno, $duration, $description, $checkInDate, $checkOutDate, $emailAcc)
  {
    // Generate unique client token using PHP's built-in functions
    $clientToken = bin2hex(random_bytes(16));

    $query = "INSERT INTO bookings (property_id, client_token, email, name, contact_no, duration, message, check_in_date, check_out_date, booking_status)
              VALUES (:unitid, :clientToken, :email, :name, :phoneno, :duration, :description, :checkInDate, :checkOutDate, 1)";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':unitid', $unitId);
    $stmt->bindParam(':clientToken', $clientToken);
    $stmt->bindParam(':email', $emailAcc);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':phoneno', $phoneno);
    $stmt->bindParam(':duration', $duration);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':checkInDate', $checkInDate);
    $stmt->bindParam(':checkOutDate', $checkOutDate);
    return $stmt->execute();
  }

  public function countAllBookings($searchQuery, $userRole = null, $userId = null)
  {
    $sql = "SELECT COUNT(*) as total
            FROM (
              SELECT 
                a.booking_id, 
                a.client_token, 
                a.booking_status,
                b.title,
                a.name,
                b.host_id,
                ROW_NUMBER() OVER(
                  PARTITION BY a.client_token 
                  ORDER BY a.created_at DESC
                ) as rn
              FROM bookings a
              LEFT JOIN properties b ON a.property_id = b.property_id
              LEFT JOIN booking_status c ON a.booking_status = c.id
              WHERE a.booking_id IS NOT NULL
            ) as latest_bookings
            WHERE latest_bookings.rn = 1
            AND latest_bookings.booking_status IN (1, 6)";

    if ($userRole == 3) {
      $sql .= " AND latest_bookings.host_id = :user_id";
    }

    if (!empty($searchQuery)) {
      $sql .= " AND (latest_bookings.title LIKE :searchQuery
          OR latest_bookings.name LIKE :searchQuery)";
    }
    $stmt = $this->db->prepare($sql);

    if ($userRole == 3) {
      $stmt->bindParam(':user_id', $userId);
    }

    if (!empty($searchQuery)) {
      $searchQuery = "%$searchQuery%";
      $stmt->bindParam(':searchQuery', $searchQuery);
    }
    $stmt->execute();

    return $stmt->fetchColumn();
  }

  public function getAllBookings($searchQuery = null, $limit, $offset, $userRole = null, $userId = null)
  {
    $sql = "SELECT 
              latest.booking_id,
              latest.property_id,
              latest.title,
              latest.client_name,
              latest.email,
              latest.contact_no,
              latest.duration,
              latest.message,
              latest.status,
              latest.check_in_date,
              latest.check_out_date,
              latest.total_amount,
              latest.created_at,
              latest.client_token,
              latest.user_id,
              latest.host_id
            FROM (
              SELECT 
                a.booking_id,
                a.property_id,
                b.title,
                a.name as client_name,
                a.email,
                a.contact_no,
                a.duration,
                a.message,
                c.description as status,
                a.check_in_date,
                a.check_out_date,
                a.total_amount,
                a.created_at,
                a.client_token,
                a.booking_status,
                b.user_id,
                b.host_id,
                ROW_NUMBER() OVER(
                  PARTITION BY a.client_token 
                  ORDER BY a.created_at DESC
                ) as rn
              FROM bookings a
              LEFT JOIN properties b ON a.property_id = b.property_id
              LEFT JOIN booking_status c ON a.booking_status = c.id
              WHERE a.booking_id IS NOT NULL
            ) as latest
            WHERE latest.rn = 1
            AND latest.booking_status IN (1, 6)";

    // Filter for hosts - only show bookings for their properties
    if ($userRole == 3) {
      $sql .= " AND latest.host_id = :user_id";
    }

    if (!empty($searchQuery)) {
      $sql .= " AND (latest.title LIKE :searchQuery
        OR latest.client_name LIKE :searchQuery)";
    }
    $sql .= " ORDER BY latest.created_at DESC 
              LIMIT :limit OFFSET :offset";
    $stmt = $this->db->prepare($sql);

    if ($userRole == 3) {
      $stmt->bindParam(':user_id', $userId);
    }

    if (!empty($searchQuery)) {
      $searchQuery = "%$searchQuery%";
      $stmt->bindParam(':searchQuery', $searchQuery);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function updateBooking($clientToken, $propertyId, $clientName, $contactNo, $duration, $status, $payment, $checkInDate, $checkOutDate, $emailAcc)
  {
    // Update the latest booking for this client_token
    $sql = "UPDATE bookings 
            SET booking_status = :status,
                total_amount = :payment,
                check_in_date = :checkInDate,
                check_out_date = :checkOutDate
            WHERE client_token = :clientToken
            ORDER BY created_at DESC
            LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':clientToken', $clientToken);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':payment', $payment);
    $stmt->bindParam(':checkInDate', $checkInDate);
    $stmt->bindParam(':checkOutDate', $checkOutDate);
    $stmt->execute();

    // Insert a new payment record for this update
    $paymentMethod = 'Cash';
    $sql = "INSERT INTO payments (property_id, client_token, payment_method, amount_paid, status)
            VALUES (:propertyId, :clientToken, :paymentMethod, :amountPaid, :paymentStatus)";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':propertyId', $propertyId);
    $stmt->bindParam(':clientToken', $clientToken);
    $stmt->bindParam(':paymentMethod', $paymentMethod);
    $stmt->bindParam(':amountPaid', $payment);
    $stmt->bindParam(':paymentStatus', $status);
    $stmt->execute();

    // Update property status
    $sql = "UPDATE properties SET status = :status WHERE property_id = :propertyId";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':propertyId', $propertyId);
    $stmt->bindParam(':status', $status);
    $stmt->execute();

    // Send SMS notification if booking is approved (status = 2 = confirmed)
    //   if ($status == 2 || $status == 6) {
    //     try {
    //       // Get property details for SMS
    //       $sql = "SELECT title FROM properties WHERE property_id = :propertyId";
    //       $stmt = $this->db->prepare($sql);
    //       $stmt->bindParam(':propertyId', $propertyId);
    //       $stmt->execute();
    //       $property = $stmt->fetch(PDO::FETCH_ASSOC);

    //       if ($property && !empty($contactNo)) {
    //         $propertyTitle = $property['title'];


    //         $emailResult = $this->emailHelper->sendBookingApprovalEmail(
    //           $emailAcc,
    //           $clientName,
    //           $propertyTitle,
    //           $checkInDate,
    //           $checkOutDate,
    //           $payment
    //         );

    //         // Log SMS result (optional)
    //         if ($emailResult['success']) {
    //           error_log("Email sent successfully to $emailAcc for booking approval");
    //         } else {
    //           error_log("Failed to send Email to $emailAcc: " . $emailResult['message']);
    //         }
    //       }
    //     } catch (Exception $e) {
    //       // Don't fail the booking update if SMS fail        error_log("Error sending Email notification: " . $e->getMessage());
    //     }
    //   }
  }

  public function caterBooking($propertyId, $clientName, $status, $contactNo, $payment, $duration, $checkInDate, $checkOutDate, $emailAcc)
  {
    // Generate unique client token using PHP's built-in functions
    $paymentMethod = 'Cash';
    $clientToken = bin2hex(random_bytes(16));

    $sql = "INSERT INTO bookings (property_id, client_token, email, name, contact_no, total_amount, duration, check_in_date, check_out_date, booking_status)
            VALUES (:propertyId, :clientToken, :emailAcc, :clientName, :contact_no, :payment, :duration, :checkInDate, :checkOutDate, :status)";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':propertyId', $propertyId);
    $stmt->bindParam(':clientToken', $clientToken);
    $stmt->bindParam(':emailAcc', $emailAcc);
    $stmt->bindParam(':clientName', $clientName);
    $stmt->bindParam(':contact_no', $contactNo);
    $stmt->bindParam(':payment', $payment);
    $stmt->bindParam(':duration', $duration);
    $stmt->bindParam(':checkInDate', $checkInDate);
    $stmt->bindParam(':checkOutDate', $checkOutDate);
    $stmt->bindParam(':status', $status);
    $stmt->execute();

    $sql = "INSERT INTO payments (property_id, client_token, payment_method, amount_paid, status)
            VALUES (:propertyId, :clientToken, :paymentMethod, :amountPaid, :paymentStatus)";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':propertyId', $propertyId);
    $stmt->bindParam(':clientToken', $clientToken);
    $stmt->bindParam(':paymentMethod', $paymentMethod);
    $stmt->bindParam(':amountPaid', $payment);
    $stmt->bindParam(':paymentStatus', $status);
    $stmt->execute();

    $sql = "UPDATE properties SET status = :status WHERE property_id = :propertyId";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':propertyId', $propertyId);
    $stmt->bindParam(':status', $status);
    $stmt->execute();

    // Send SMS notification if booking is approved (status = 2 = confirmed or status = 6 = booked)
    // if ($status == 2 || $status == 6) {
    //   try {
    //     // Get property details for SMS
    //     $sql = "SELECT title FROM properties WHERE property_id = :propertyId";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bindParam(':propertyId', $propertyId);
    //     $stmt->execute();
    //     $property = $stmt->fetch(PDO::FETCH_ASSOC);

    //     if ($property && !empty($contactNo)) {
    //       $propertyTitle = $property['title'];

    //       $emailResult = $this->emailHelper->sendBookingApprovalEmail(
    //         $emailAcc,
    //         $clientName,
    //         $propertyTitle,
    //         $checkInDate,
    //         $checkOutDate,
    //         $payment
    //       );

    //       // Log SMS result (optional)
    //       if ($emailResult['success']) {
    //         error_log("Email sent successfully to $emailAcc for new booking");
    //       } else {
    //         error_log("Failed to send Email to $emailAcc: " . $emailResult['message']);
    //       }
    //     }
    //   } catch (Exception $e) {
    //     error_log("Error sending Email notification: " . $e->getMessage());
    //   }
    // }
  }
}
