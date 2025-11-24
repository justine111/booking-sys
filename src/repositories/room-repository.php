<?php
require_once __DIR__ . '/base-repository.php';

class RoomsRepository extends base_repository
{
  public function getAllCategories()
  {
    $sql = "SELECT category_id, name FROM category ORDER BY category_id ASC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function countHotels($searchQuery = null)
  {
    $query = "SELECT COUNT(property_id) as total FROM properties WHERE status IS NOT NULL";
    if (!empty($searchQuery)) {
      $query .= " AND title LIKE :searchQuery
          OR price_per_night LIKE :searchQuery
          OR address LIKE :searchQuery";
    }
    $stmt = $this->db->prepare($query);
    if (!empty($searchQuery)) {
      $searchQuery = "%$searchQuery%";
      $stmt->bindParam(':searchQuery', $searchQuery);
    }
    $stmt->execute();

    return $stmt->fetchColumn();
  }

  public function countHotelsByCategory($categoryId, $searchQuery = null)
  {
    $query = "SELECT COUNT(property_id) as total FROM properties WHERE status IS NOT NULL AND category_id = :categoryId";
    if (!empty($searchQuery)) {
      $query .= " AND (title LIKE :searchQuery
          OR price_per_night LIKE :searchQuery
          OR address LIKE :searchQuery)";
    }
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
    if (!empty($searchQuery)) {
      $searchQuery = "%$searchQuery%";
      $stmt->bindParam(':searchQuery', $searchQuery);
    }
    $stmt->execute();

    return $stmt->fetchColumn();
  }

  public function getHotels($searchQuery = null, $limit, $offset)
  {
    $sql = "SELECT 
	            a.property_id, 
              a.title, 
              a.description, 
              a.address,
              a.price_per_night, 
              a.address, 
              a.img1, 
              a.status
            FROM properties a
            LEFT JOIN booking_status b
            ON a.status = b.id
            WHERE a.property_id IS NOT NULL";

    if (!empty($searchQuery)) {
      $sql .= " AND a.title LIKE :searchQuery
        OR a.price_per_night LIKE :searchQuery
        OR a.address LIKE :searchQuery";
    }
    $sql .= " ORDER BY a.property_id DESC LIMIT :limit OFFSET :offset";
    $stmt = $this->db->prepare($sql);

    if (!empty($searchQuery)) {
      $searchQuery = "%$searchQuery%";
      $stmt->bindParam(':searchQuery', $searchQuery);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getHotelsByCategory($categoryId, $searchQuery = null, $limit, $offset)
  {
    $sql = "SELECT 
	            a.property_id, 
              a.title, 
              a.description, 
              a.address,
              a.price_per_night, 
              a.address, 
              a.img1, 
              a.status
            FROM properties a
            LEFT JOIN booking_status b
            ON a.status = b.id
            WHERE a.property_id IS NOT NULL AND a.category_id = :categoryId";

    if (!empty($searchQuery)) {
      $sql .= " AND (a.title LIKE :searchQuery
        OR a.price_per_night LIKE :searchQuery
        OR a.address LIKE :searchQuery)";
    }
    $sql .= " ORDER BY a.property_id DESC LIMIT :limit OFFSET :offset";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);

    if (!empty($searchQuery)) {
      $searchQuery = "%$searchQuery%";
      $stmt->bindParam(':searchQuery', $searchQuery);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getHotelById($roomId)
  {
    $sql = "SELECT 
              property_id, 
              title, 
              description, 
              price_per_night, 
              amenities,
              address, 
              img1, 
              img2, 
              img3,
              img4,
              status
            FROM properties
            WHERE property_id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getHotelListAvailable()
  {
    $sql = "SELECT property_id, title FROM `properties` WHERE status = 5";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function addHotel($hotelName, $address, $city, $price, $host, $description, $amenities, $img1, $img2, $img3, $img4, $userId, $status = 5)
  {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/booking-sys/src/repositories/uploads/';

    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0777, true);
    }

    $handleFileUpload = function ($file) use ($allowed_types, $upload_dir) {
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

    $filename1 = $handleFileUpload($img1);
    $filename2 = $handleFileUpload($img2);
    $filename3 = $handleFileUpload($img3);
    $filename4 = $handleFileUpload($img4);

    $sql = "INSERT INTO properties 
            (
              title, 
              address, 
              city, 
              price_per_night, 
              host_id, 
              user_id,
              description, 
              amenities, 
              img1, 
              img2, 
              img3, 
              img4, 
              status
            ) 
            VALUES 
            (
              :title, 
              :address, 
              :city, 
              :price_per_night, 
              :host, 
              :user_id,
              :description, 
              :amenities, 
              :image_1_filename, 
              :image_2_filename, 
              :image_3_filename, 
              :image_4_filename, 
              :status
            )";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':title', $hotelName);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':price_per_night', $price);
    $stmt->bindParam(':host', $host);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':amenities', $amenities);
    $stmt->bindParam(':image_1_filename', $filename1);
    $stmt->bindParam(':image_2_filename', $filename2);
    $stmt->bindParam(':image_3_filename', $filename3);
    $stmt->bindParam(':image_4_filename', $filename4);
    $stmt->bindParam(':status', $status);

    return $stmt->execute();
  }

  public function updatePropertyStatus($propertyId, $status)
  {
    $sql = "UPDATE properties SET status = :status WHERE property_id = :property_id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':property_id', $propertyId);
    return $stmt->execute();
  }

  public function getCountOfHotels($searchQuery = null)
  {
    $searchQuery = trim($searchQuery);

    $sql = "SELECT count(a.property_id)as total
            FROM properties a
            LEFT JOIN hosts b
            ON a.host_id = b.host_id
            WHERE a.status IS NOT NULL";

    if (!empty($searchQuery)) {
      $sql .= " AND a.title LIKE :searchQuery
          OR a.price_per_night LIKE :searchQuery
          OR a.address LIKE :searchQuery
          OR a.status LIKE :searchQuery
          OR b.name LIKE :searchQuery";
    }
    $stmt = $this->db->prepare($sql);
    if (!empty($searchQuery)) {
      $searchQuery = "%$searchQuery%";
      $stmt->bindParam(':searchQuery', $searchQuery);
    }
    $stmt->execute();

    return $stmt->fetchColumn();
  }

  public function getListOfHotels($searchQuery = null, $limit, $offset, $userRole = null, $userId = null)
  {
    require_once __DIR__ . '/../helpers/authorization-helper.php';
    $searchQuery = trim($searchQuery);

    $sql = "SELECT
              a.property_id,
              a.title,
              a.address,
              a.city,
              a.description,
              a.amenities,
              a.price_per_night,
              a.img1,
              a.created_at,
              a.user_id,
              c.description as status,
              b.name  
            FROM properties a
            LEFT JOIN hosts b
            ON a.host_id = b.host_id
            LEFT JOIN booking_status c
            ON a.status = c.id
            WHERE a.status IS NOT NULL";

    // Filter based on user role
    if ($userRole === AuthorizationHelper::ROLE_HOST) {
      // Hosts only see their own properties
      $sql .= " AND a.host_id = :user_id";
    } elseif ($userRole === AuthorizationHelper::ROLE_MODERATOR) {
      // Moderators see all properties or pending approvals
      // No additional filter needed
    } elseif ($userRole === AuthorizationHelper::ROLE_ADMIN) {
      // Admins see everything  
      // No additional filter needed
    }

    if (!empty($searchQuery)) {
      $sql .= " AND (a.title LIKE :searchQuery
          OR a.price_per_night LIKE :searchQuery
          OR a.address LIKE :searchQuery
          OR a.status LIKE :searchQuery
          OR b.name LIKE :searchQuery)";
    }
    $sql .= " ORDER BY a.property_id DESC LIMIT :limit OFFSET :offset";
    $stmt = $this->db->prepare($sql);

    if ($userRole === AuthorizationHelper::ROLE_HOST) {
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
}
