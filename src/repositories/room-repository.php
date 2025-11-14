<?php
require_once __DIR__ . '/base-repository.php';

class RoomsRepository extends base_repository
{
  public function countHotels($searchQuery = null)
  {
      $query = "SELECT COUNT(property_id) as total FROM properties WHERE status IS NOT NULL";
      if (!empty($searchQuery)) {
        $query .= " AND title LIKE :searchQuery
          OR price_per_night LIKE :searchQuery
          OR address LIKE :searchQuery
          OR status LIKE :searchQuery";
      }
      $stmt = $this->db->prepare($query);
      if (!empty($searchQuery)) {
        $searchQuery = "%$searchQuery%";
        $stmt->bindParam(':searchQuery', $searchQuery);
      }
      $stmt->execute();
      
      return $stmt->fetchColumn();
  }

  public function getHotels($searchQuery = null, $limit, $offset)
  {
    try {
      $searchQuery = trim($searchQuery);

      $query = "SELECT property_id, title, description, price_per_night, address, img1, status
                FROM properties
                WHERE status != 'inactive'";
      if (!empty($searchQuery)) {
        $query .= " AND title LIKE :searchQuery
          OR price_per_night LIKE :searchQuery
          OR address LIKE :searchQuery
          OR status LIKE :searchQuery";
      }
      $query .= " ORDER BY property_id DESC LIMIT :limit OFFSET :offset";
      $stmt = $this->db->prepare($query);
      if (!empty($searchQuery)) {
        $searchQuery = "%$searchQuery%";
        $stmt->bindParam(':searchQuery', $searchQuery);
      }
      $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
      $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [
        'error' => 'Database query failed: ' . $e->getMessage()
      ]; // Log error or handle gracefully
      return [];
    }
  }

  public function getHotelById($roomId)
  {
    try {
      $query = "SELECT 
                  property_id, 
                  title, 
                  description, 
                  price_per_night, 
                  address, 
                  filename, 
                  status
                FROM properties
                WHERE property_id = :id AND status != 'inactive'";
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':id', $roomId, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [
        'error' => 'Database query failed: ' . $e->getMessage()
      ]; // Log error or handle gracefully
    }
  }

  public function addHotel(
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
) {

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/booking-sys/src/repositories/uploads/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$handleFileUpload = function($file) use ($allowed_types, $upload_dir) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("File upload error: Code " . $file['error']);
    }

    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception("Invalid file type: " . $file['type']);
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $unique_name = uniqid('hotel_img_') . '.' . $extension;
    $destination = $upload_dir . $unique_name;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception("Failed to move uploaded file to: " . $destination);
    }

    return $unique_name;
};


    try {
      $filename1 = $handleFileUpload($img1);
      $filename2 = $handleFileUpload($img2);
      $filename3 = $handleFileUpload($img3);
      $filename4 = $handleFileUpload($img4);

      $query = "INSERT INTO properties 
                (title, address, city, price_per_night, host_id, description, amenities, img1, img2, img3, img4, status) 
                VALUES 
                (:title, :address, :city, :price_per_night, :host, :description, :amenities, :image_1_filename, :image_2_filename, :image_3_filename, :image_4_filename, 'available')";
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':title', $hotelName);
      $stmt->bindParam(':address', $address);
      $stmt->bindParam(':city', $city);
      $stmt->bindParam(':price_per_night', $price);
      $stmt->bindParam(':host', $host);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':amenities', $amenities);
      $stmt->bindParam(':image_1_filename', $filename1);
      $stmt->bindParam(':image_2_filename', $filename2);
      $stmt->bindParam(':image_3_filename', $filename3);
      $stmt->bindParam(':image_4_filename', $filename4);

      return $stmt->execute();
    } catch (PDOException $e) {
      return [
        'error' => 'Database insertion failed: ' . $e->getMessage()
      ]; // Log error or handle gracefully
    }
  }
}


